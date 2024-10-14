import { jsx } from "react/jsx-runtime";
import ReactDOMServer from "react-dom/server";
import { createInertiaApp } from "@inertiajs/react";
import createServer from "@inertiajs/react/server";
import { parse, stringify } from "qs";
async function resolvePageComponent(path, pages) {
  for (const p of Array.isArray(path) ? path : [path]) {
    const page = pages[p];
    if (typeof page === "undefined") {
      continue;
    }
    return typeof page === "function" ? page() : page;
  }
  throw new Error(`Page not found: ${path}`);
}
class Route {
  /**
   * @param {String} name - Route name.
   * @param {Object} definition - Route definition.
   * @param {Object} config - Ziggy configuration.
   */
  constructor(name, definition, config) {
    this.name = name;
    this.definition = definition;
    this.bindings = definition.bindings ?? {};
    this.wheres = definition.wheres ?? {};
    this.config = config;
  }
  /**
   * Get a 'template' of the complete URL for this route.
   *
   * @example
   * https://{team}.ziggy.dev/user/{user}
   *
   * @return {String} Route template.
   */
  get template() {
    const template = `${this.origin}/${this.definition.uri}`.replace(/\/+$/, "");
    return template === "" ? "/" : template;
  }
  /**
   * Get a template of the origin for this route.
   *
   * @example
   * https://{team}.ziggy.dev/
   *
   * @return {String} Route origin template.
   */
  get origin() {
    return !this.config.absolute ? "" : this.definition.domain ? `${this.config.url.match(/^\w+:\/\//)[0]}${this.definition.domain}${this.config.port ? `:${this.config.port}` : ""}` : this.config.url;
  }
  /**
   * Get an array of objects representing the parameters that this route accepts.
   *
   * @example
   * [{ name: 'team', required: true }, { name: 'user', required: false }]
   *
   * @return {Array} Parameter segments.
   */
  get parameterSegments() {
    var _a;
    return ((_a = this.template.match(/{[^}?]+\??}/g)) == null ? void 0 : _a.map((segment) => ({
      name: segment.replace(/{|\??}/g, ""),
      required: !/\?}$/.test(segment)
    }))) ?? [];
  }
  /**
   * Get whether this route's template matches the given URL.
   *
   * @param {String} url - URL to check.
   * @return {Object|false} - If this route matches, returns the matched parameters.
   */
  matchesUrl(url) {
    if (!this.definition.methods.includes("GET")) return false;
    const pattern = this.template.replace(/(\/?){([^}?]*)(\??)}/g, (_, slash, segment, optional) => {
      var _a;
      const regex = `(?<${segment}>${((_a = this.wheres[segment]) == null ? void 0 : _a.replace(/(^\^)|(\$$)/g, "")) || "[^/?]+"})`;
      return optional ? `(${slash}${regex})?` : `${slash}${regex}`;
    }).replace(/^\w+:\/\//, "");
    const [location, query] = url.replace(/^\w+:\/\//, "").split("?");
    const matches = new RegExp(`^${pattern}/?$`).exec(decodeURI(location));
    if (matches) {
      for (const k in matches.groups) {
        matches.groups[k] = typeof matches.groups[k] === "string" ? decodeURIComponent(matches.groups[k]) : matches.groups[k];
      }
      return { params: matches.groups, query: parse(query) };
    }
    return false;
  }
  /**
   * Hydrate and return a complete URL for this route with the given parameters.
   *
   * @param {Object} params
   * @return {String}
   */
  compile(params) {
    const segments = this.parameterSegments;
    if (!segments.length) return this.template;
    return this.template.replace(/{([^}?]+)(\??)}/g, (_, segment, optional) => {
      if (!optional && [null, void 0].includes(params[segment])) {
        throw new Error(
          `Ziggy error: '${segment}' parameter is required for route '${this.name}'.`
        );
      }
      if (this.wheres[segment]) {
        if (!new RegExp(
          `^${optional ? `(${this.wheres[segment]})?` : this.wheres[segment]}$`
        ).test(params[segment] ?? "")) {
          throw new Error(
            `Ziggy error: '${segment}' parameter '${params[segment]}' does not match required format '${this.wheres[segment]}' for route '${this.name}'.`
          );
        }
      }
      return encodeURI(params[segment] ?? "").replace(/%7C/g, "|").replace(/%25/g, "%").replace(/\$/g, "%24");
    }).replace(this.config.absolute ? /(\.[^/]+?)(\/\/)/ : /(^)(\/\/)/, "$1/").replace(/\/+$/, "");
  }
}
class Router extends String {
  /**
   * @param {String} [name] - Route name.
   * @param {(String|Number|Array|Object)} [params] - Route parameters.
   * @param {Boolean} [absolute] - Whether to include the URL origin.
   * @param {Object} [config] - Ziggy configuration.
   */
  constructor(name, params, absolute = true, config) {
    super();
    this._config = config ?? (typeof Ziggy !== "undefined" ? Ziggy : globalThis == null ? void 0 : globalThis.Ziggy);
    this._config = { ...this._config, absolute };
    if (name) {
      if (!this._config.routes[name]) {
        throw new Error(`Ziggy error: route '${name}' is not in the route list.`);
      }
      this._route = new Route(name, this._config.routes[name], this._config);
      this._params = this._parse(params);
    }
  }
  /**
   * Get the compiled URL string for the current route and parameters.
   *
   * @example
   * // with 'posts.show' route 'posts/{post}'
   * (new Router('posts.show', 1)).toString(); // 'https://ziggy.dev/posts/1'
   *
   * @return {String}
   */
  toString() {
    const unhandled = Object.keys(this._params).filter((key) => !this._route.parameterSegments.some(({ name }) => name === key)).filter((key) => key !== "_query").reduce((result, current) => ({ ...result, [current]: this._params[current] }), {});
    return this._route.compile(this._params) + stringify(
      { ...unhandled, ...this._params["_query"] },
      {
        addQueryPrefix: true,
        arrayFormat: "indices",
        encodeValuesOnly: true,
        skipNulls: true,
        encoder: (value, encoder) => typeof value === "boolean" ? Number(value) : encoder(value)
      }
    );
  }
  /**
   * Get the parameters, values, and metadata from the given URL.
   *
   * @param {String} [url] - The URL to inspect, defaults to the current window URL.
   * @return {{ name: string, params: Object, query: Object, route: Route }}
   */
  _unresolve(url) {
    if (!url) {
      url = this._currentUrl();
    } else if (this._config.absolute && url.startsWith("/")) {
      url = this._location().host + url;
    }
    let matchedParams = {};
    const [name, route2] = Object.entries(this._config.routes).find(
      ([name2, route3]) => matchedParams = new Route(name2, route3, this._config).matchesUrl(url)
    ) || [void 0, void 0];
    return { name, ...matchedParams, route: route2 };
  }
  _currentUrl() {
    const { host, pathname, search } = this._location();
    return (this._config.absolute ? host + pathname : pathname.replace(this._config.url.replace(/^\w*:\/\/[^/]+/, ""), "").replace(/^\/+/, "/")) + search;
  }
  /**
   * Get the name of the route matching the current window URL, or, given a route name
   * and parameters, check if the current window URL and parameters match that route.
   *
   * @example
   * // at URL https://ziggy.dev/posts/4 with 'posts.show' route 'posts/{post}'
   * route().current(); // 'posts.show'
   * route().current('posts.index'); // false
   * route().current('posts.show'); // true
   * route().current('posts.show', { post: 1 }); // false
   * route().current('posts.show', { post: 4 }); // true
   *
   * @param {String} [name] - Route name to check.
   * @param {(String|Number|Array|Object)} [params] - Route parameters.
   * @return {(Boolean|String|undefined)}
   */
  current(name, params) {
    const { name: current, params: currentParams, query, route: route2 } = this._unresolve();
    if (!name) return current;
    const match = new RegExp(`^${name.replace(/\./g, "\\.").replace(/\*/g, ".*")}$`).test(
      current
    );
    if ([null, void 0].includes(params) || !match) return match;
    const routeObject = new Route(current, route2, this._config);
    params = this._parse(params, routeObject);
    const routeParams = { ...currentParams, ...query };
    if (Object.values(params).every((p) => !p) && !Object.values(routeParams).some((v) => v !== void 0))
      return true;
    const isSubset = (subset, full) => {
      return Object.entries(subset).every(([key, value]) => {
        if (Array.isArray(value) && Array.isArray(full[key])) {
          return value.every((v) => full[key].includes(v));
        }
        if (typeof value === "object" && typeof full[key] === "object" && value !== null && full[key] !== null) {
          return isSubset(value, full[key]);
        }
        return full[key] == value;
      });
    };
    return isSubset(params, routeParams);
  }
  /**
   * Get an object representing the current location (by default this will be
   * the JavaScript `window` global if it's available).
   *
   * @return {Object}
   */
  _location() {
    var _a, _b, _c;
    const {
      host = "",
      pathname = "",
      search = ""
    } = typeof window !== "undefined" ? window.location : {};
    return {
      host: ((_a = this._config.location) == null ? void 0 : _a.host) ?? host,
      pathname: ((_b = this._config.location) == null ? void 0 : _b.pathname) ?? pathname,
      search: ((_c = this._config.location) == null ? void 0 : _c.search) ?? search
    };
  }
  /**
   * Get all parameter values from the current window URL.
   *
   * @example
   * // at URL https://tighten.ziggy.dev/posts/4?lang=en with 'posts.show' route 'posts/{post}' and domain '{team}.ziggy.dev'
   * route().params; // { team: 'tighten', post: 4, lang: 'en' }
   *
   * @return {Object}
   */
  get params() {
    const { params, query } = this._unresolve();
    return { ...params, ...query };
  }
  get routeParams() {
    return this._unresolve().params;
  }
  get queryParams() {
    return this._unresolve().query;
  }
  /**
   * Check whether the given route exists.
   *
   * @param {String} name
   * @return {Boolean}
   */
  has(name) {
    return Object.keys(this._config.routes).includes(name);
  }
  /**
   * Parse Laravel-style route parameters of any type into a normalized object.
   *
   * @example
   * // with route parameter names 'event' and 'venue'
   * _parse(1); // { event: 1 }
   * _parse({ event: 2, venue: 3 }); // { event: 2, venue: 3 }
   * _parse(['Taylor', 'Matt']); // { event: 'Taylor', venue: 'Matt' }
   * _parse([4, { uuid: 56789, name: 'Grand Canyon' }]); // { event: 4, venue: 56789 }
   *
   * @param {(String|Number|Array|Object)} params - Route parameters.
   * @param {Route} route - Route instance.
   * @return {Object} Normalized complete route parameters.
   */
  _parse(params = {}, route2 = this._route) {
    params ?? (params = {});
    params = ["string", "number"].includes(typeof params) ? [params] : params;
    const segments = route2.parameterSegments.filter(({ name }) => !this._config.defaults[name]);
    if (Array.isArray(params)) {
      params = params.reduce(
        (result, current, i) => segments[i] ? { ...result, [segments[i].name]: current } : typeof current === "object" ? { ...result, ...current } : { ...result, [current]: "" },
        {}
      );
    } else if (segments.length === 1 && !params[segments[0].name] && (params.hasOwnProperty(Object.values(route2.bindings)[0]) || params.hasOwnProperty("id"))) {
      params = { [segments[0].name]: params };
    }
    return {
      ...this._defaults(route2),
      ...this._substituteBindings(params, route2)
    };
  }
  /**
   * Populate default parameters for the given route.
   *
   * @example
   * // with default parameters { locale: 'en', country: 'US' } and 'posts.show' route '{locale}/posts/{post}'
   * defaults(...); // { locale: 'en' }
   *
   * @param {Route} route
   * @return {Object} Default route parameters.
   */
  _defaults(route2) {
    return route2.parameterSegments.filter(({ name }) => this._config.defaults[name]).reduce(
      (result, { name }, i) => ({ ...result, [name]: this._config.defaults[name] }),
      {}
    );
  }
  /**
   * Substitute Laravel route model bindings in the given parameters.
   *
   * @example
   * _substituteBindings({ post: { id: 4, slug: 'hello-world', title: 'Hello, world!' } }, { bindings: { post: 'slug' } }); // { post: 'hello-world' }
   *
   * @param {Object} params - Route parameters.
   * @param {Object} route - Route definition.
   * @return {Object} Normalized route parameters.
   */
  _substituteBindings(params, { bindings, parameterSegments }) {
    return Object.entries(params).reduce((result, [key, value]) => {
      if (!value || typeof value !== "object" || Array.isArray(value) || !parameterSegments.some(({ name }) => name === key)) {
        return { ...result, [key]: value };
      }
      if (!value.hasOwnProperty(bindings[key])) {
        if (value.hasOwnProperty("id")) {
          bindings[key] = "id";
        } else {
          throw new Error(
            `Ziggy error: object passed as '${key}' parameter is missing route model binding key '${bindings[key]}'.`
          );
        }
      }
      return { ...result, [key]: value[bindings[key]] };
    }, {});
  }
  valueOf() {
    return this.toString();
  }
}
function route(name, params, absolute, config) {
  const router = new Router(name, params, absolute, config);
  return name ? router.toString() : router;
}
const Ziggy$1 = { "url": "http://localhost:8000", "port": 8e3, "defaults": {}, "routes": { "debugbar.openhandler": { "uri": "_debugbar/open", "methods": ["GET", "HEAD"] }, "debugbar.clockwork": { "uri": "_debugbar/clockwork/{id}", "methods": ["GET", "HEAD"], "parameters": ["id"] }, "debugbar.assets.css": { "uri": "_debugbar/assets/stylesheets", "methods": ["GET", "HEAD"] }, "debugbar.assets.js": { "uri": "_debugbar/assets/javascript", "methods": ["GET", "HEAD"] }, "debugbar.cache.delete": { "uri": "_debugbar/cache/{key}/{tags?}", "methods": ["DELETE"], "parameters": ["key", "tags"] }, "debugbar.queries.explain": { "uri": "_debugbar/queries/explain", "methods": ["POST"] }, "filament.exports.download": { "uri": "filament/exports/{export}/download", "methods": ["GET", "HEAD"], "parameters": ["export"], "bindings": { "export": "id" } }, "filament.imports.failed-rows.download": { "uri": "filament/imports/{import}/failed-rows/download", "methods": ["GET", "HEAD"], "parameters": ["import"], "bindings": { "import": "id" } }, "filament.dashboard.auth.login": { "uri": "dashboard/login", "methods": ["GET", "HEAD"] }, "filament.dashboard.auth.logout": { "uri": "dashboard/logout", "methods": ["POST"] }, "filament.dashboard.auth.profile": { "uri": "dashboard/profile", "methods": ["GET", "HEAD"] }, "filament.dashboard.pages.dashboard": { "uri": "dashboard", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.banom-masters.index": { "uri": "dashboard/banom-masters", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.banom-masters.create": { "uri": "dashboard/banom-masters/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.banom-masters.edit": { "uri": "dashboard/banom-masters/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.warga.index": { "uri": "dashboard/warga", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.warga.edit": { "uri": "dashboard/warga/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.warga.detail": { "uri": "dashboard/warga/{record}", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.kaders.index": { "uri": "dashboard/kaders", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.kaders.create": { "uri": "dashboard/kaders/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.kaders.edit": { "uri": "dashboard/kaders/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.lembaga-masters.index": { "uri": "dashboard/lembaga-masters", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.lembaga-masters.create": { "uri": "dashboard/lembaga-masters/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.lembaga-masters.edit": { "uri": "dashboard/lembaga-masters/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.index": { "uri": "dashboard/data-kecamatan", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.data-kecamatan.create": { "uri": "dashboard/data-kecamatan/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.data-kecamatan.edit": { "uri": "dashboard/data-kecamatan/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.detail": { "uri": "dashboard/data-kecamatan/{record}", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.surat-keputusan": { "uri": "dashboard/data-kecamatan/{record}?state=surat-keputusan", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.warga": { "uri": "dashboard/data-kecamatan/{record}?state=warga", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.pengurus": { "uri": "dashboard/data-kecamatan/{record}?state=kepengurusan", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.data-kecamatan.buat-warga": { "uri": "dashboard/data-kecamatan/{record}/buat-warga", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.shield.roles.index": { "uri": "dashboard/shield/roles", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.shield.roles.create": { "uri": "dashboard/shield/roles/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.shield.roles.view": { "uri": "dashboard/shield/roles/{record}", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.shield.roles.edit": { "uri": "dashboard/shield/roles/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "filament.dashboard.resources.users.index": { "uri": "dashboard/users", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.users.create": { "uri": "dashboard/users/create", "methods": ["GET", "HEAD"] }, "filament.dashboard.resources.users.edit": { "uri": "dashboard/users/{record}/edit", "methods": ["GET", "HEAD"], "parameters": ["record"] }, "sanctum.csrf-cookie": { "uri": "sanctum/csrf-cookie", "methods": ["GET", "HEAD"] }, "livewire.update": { "uri": "livewire/update", "methods": ["POST"] }, "livewire.upload-file": { "uri": "livewire/upload-file", "methods": ["POST"] }, "livewire.preview-file": { "uri": "livewire/preview-file/{filename}", "methods": ["GET", "HEAD"], "parameters": ["filename"] }, "ignition.healthCheck": { "uri": "_ignition/health-check", "methods": ["GET", "HEAD"] }, "ignition.executeSolution": { "uri": "_ignition/execute-solution", "methods": ["POST"] }, "ignition.updateConfig": { "uri": "_ignition/update-config", "methods": ["POST"] }, "home": { "uri": "/", "methods": ["GET", "HEAD"] }, "form-response": { "uri": "form/{code}", "methods": ["GET", "HEAD"], "parameters": ["code"] }, "surat-tugas": { "uri": "surat_tugas/{filename}", "methods": ["GET", "HEAD"], "parameters": ["filename"] }, "login": { "uri": "login", "methods": ["POST"] }, "logout": { "uri": "logout", "methods": ["POST"] } } };
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(Ziggy$1.routes, window.Ziggy.routes);
}
const appName = "Laravel";
createServer(
  (page) => createInertiaApp({
    page,
    render: ReactDOMServer.renderToString,
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(
      `./pages/${name}.jsx`,
      /* @__PURE__ */ Object.assign({ "./pages/home/index.jsx": () => import("./assets/index-CZPdh0cb.js") })
    ),
    setup: ({ App, props }) => {
      global.route = (name, params, absolute, config = Ziggy$1) => route(name, params, absolute, config);
      return /* @__PURE__ */ jsx(App, { ...props });
    }
  })
);
