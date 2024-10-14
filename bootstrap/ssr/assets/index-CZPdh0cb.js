import { jsxs, jsx, Fragment } from "react/jsx-runtime";
import { usePage, Link } from "@inertiajs/react";
import { useState } from "react";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/react";
const NAVIGATION_ROUTES = [
  { label: "Beranda", path: "/", current: false },
  {
    label: "Tentang",
    path: "/tentang-kami",
    current: false
  },
  { label: "Kontak", path: "/kontak", current: false }
];
function maskEmail(email) {
  const [localPart, domain] = email.split("@");
  if (localPart.length <= 2) {
    return email;
  }
  const firstChar = localPart[0];
  const maskedLocalPart = `${firstChar}${"*".repeat(8)}`;
  return `${maskedLocalPart}@${domain}`;
}
const Profile = () => {
  useState(false);
  const { auth: user } = usePage().props;
  console.log({ usePage: usePage().props });
  return user ? /* @__PURE__ */ jsxs(Popover, { className: "relative", children: [
    /* @__PURE__ */ jsx(PopoverButton, { className: "block text-sm/6 font-semibold text-white/80 focus:outline-none  data-[hover]:text-white", children: /* @__PURE__ */ jsxs("div", { className: "flex items-center space-x-2", children: [
      /* @__PURE__ */ jsxs("div", { className: "flex flex-col text-right max-w-28", children: [
        /* @__PURE__ */ jsx("p", { className: "truncate", children: user == null ? void 0 : user.name }),
        /* @__PURE__ */ jsx("p", { className: "text-xs truncate", children: maskEmail(user == null ? void 0 : user.email) })
      ] }),
      /* @__PURE__ */ jsx("div", { className: "relative", children: /* @__PURE__ */ jsx(
        "img",
        {
          src: user == null ? void 0 : user.profile_picture,
          className: "w-10 h-10 rounded-full"
        }
      ) })
    ] }) }),
    /* @__PURE__ */ jsxs(
      PopoverPanel,
      {
        transition: true,
        anchor: "bottom end",
        className: "w-64 mt-4 rounded-xl bg-white text-sm/6 transition duration-200 ease-in-out [--anchor-gap:var(--spacing-5)] data-[closed]:-translate-y-1 data-[closed]:opacity-0",
        children: [
          /* @__PURE__ */ jsx("div", { className: "px-4 py-2 border-b border-black/10", children: /* @__PURE__ */ jsx("p", { className: "font-semibold truncate text-nowrap", children: user == null ? void 0 : user.name }) }),
          /* @__PURE__ */ jsx("div", { className: "p-2", children: /* @__PURE__ */ jsx(
            "a",
            {
              className: "block px-3 py-1 transition rounded-lg hover:bg-black/15",
              href: "/dashboard",
              children: /* @__PURE__ */ jsx("p", { className: "text-sm text-black", children: "Dashboard" })
            }
          ) }),
          /* @__PURE__ */ jsx("div", { className: "p-2", children: /* @__PURE__ */ jsx(
            "a",
            {
              className: "block px-3 py-1 transition rounded-lg hover:bg-black/15",
              href: "/dashboard/profile",
              children: /* @__PURE__ */ jsx("p", { className: "text-sm text-black", children: "Profil Saya" })
            }
          ) }),
          /* @__PURE__ */ jsx("div", { className: "p-2 border-t border-black/10", children: /* @__PURE__ */ jsx(
            Link,
            {
              className: "block w-full px-3 py-1 text-left transition rounded-lg hover:bg-red-500/20",
              href: route("logout"),
              children: /* @__PURE__ */ jsx("p", { className: "text-sm text-red-500", children: "Keluar" })
            }
          ) })
        ]
      }
    )
  ] }) : /* @__PURE__ */ jsxs(
    "a",
    {
      className: "h-10 items-center justify-center gap-2.5 rounded-md px-4 py-3 transition-all ease-in-out hover:opacity-75 lg:flex bg-white text-black",
      children: [
        /* @__PURE__ */ jsx("img", { src: "iconShield.png", className: "w-5 h-5" }),
        /* @__PURE__ */ jsx("p", { className: "font-medium", children: "Login" })
      ]
    }
  );
};
const Navbar = ({ user }) => {
  const { url } = usePage();
  const formatedNav = NAVIGATION_ROUTES.map((nav) => ({
    ...nav,
    current: url.includes(nav.path)
  }));
  return /* @__PURE__ */ jsx("nav", { className: "top-0 z-[999] w-full transition-all ease-in-out bg-primary sticky bg-opacity-0 max-w-6xl mx-auto", children: /* @__PURE__ */ jsx("div", { className: "max-w-full px-4 mx-auto md:px-8 lg:px-16", children: /* @__PURE__ */ jsxs("div", { className: "flex items-center justify-between w-full py-3 md:py-5 lg:justify-between lg:space-x-10 lg:py-6", children: [
    /* @__PURE__ */ jsx("div", { children: "Logo" }),
    /* @__PURE__ */ jsx("div", { children: /* @__PURE__ */ jsx("ul", { className: "flex items-center gap-8", children: formatedNav.map((navigation, index) => /* @__PURE__ */ jsx(
      "li",
      {
        className: "font-medium text-white",
        children: /* @__PURE__ */ jsx(
          "div",
          {
            className: `relative before:absolute before:h-0.5 before:bottom-0 before:w-0 before:content-[''] before:bg-white before:hover:w-1/2 before:transition-all before:duration-300 ${navigation.current ? "before:w-1/2" : ""}`,
            children: /* @__PURE__ */ jsx("a", { href: navigation.path, className: "", children: navigation.label })
          }
        )
      },
      index
    )) }) }),
    /* @__PURE__ */ jsx(Profile, { user })
  ] }) }) });
};
function HomeLayout({ children, user }) {
  return /* @__PURE__ */ jsx(Fragment, { children: /* @__PURE__ */ jsxs("div", { className: "relative overflow-hidden bg-primary md:h-screen", children: [
    /* @__PURE__ */ jsx(Navbar, { user }),
    /* @__PURE__ */ jsx("main", { className: "relative h-full", children })
  ] }) });
}
function HomeIndex({ auth }) {
  return /* @__PURE__ */ jsx(HomeLayout, { children: "HOME" });
}
export {
  HomeIndex as default
};
