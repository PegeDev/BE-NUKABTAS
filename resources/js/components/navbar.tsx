import { NAVIGATION_ROUTES } from "../routes";
import { Sidebar } from "./sidebar";
import { Profile } from "./profile";
import { useWindowSize } from "@react-hooks-library/core";

import useScroll from "@/hooks/use-scroll";
import clsx from "clsx";

export const Navbar = () => {
    const { width } = useWindowSize();

    const isScrolled = useScroll();

    const formatedNav = NAVIGATION_ROUTES.map((nav) => ({
        ...nav,
        current: false,
    }));

    return (
        <nav
            className={clsx(
                "z-50 w-full fixed  top-0 transition-all duration-500",
                {
                    "bg-transparent": !isScrolled,
                    "bg-white/70 backdrop-blur-sm drop-shadow-lg": isScrolled,
                }
            )}
        >
            <div className="container relative">
                <div className="flex items-center justify-between w-full py-4 md:py-5 lg:justify-between lg:space-x-10 lg:py-6">
                    <div>Logo</div>
                    {width >= 768 ? (
                        <>
                            {/* NAVIGATION */}
                            <div className="flex items-center justify-center">
                                <ul className="hidden md:flex md:items-center md:gap-8">
                                    {formatedNav.map((nav, index) => (
                                        <NavLink
                                            key={index}
                                            name={nav.label}
                                            selector={nav.path}
                                            isActive={nav.current}
                                        />
                                    ))}
                                </ul>
                            </div>

                            {/* PROFILE */}
                            <Profile isPrimary={isScrolled} />
                        </>
                    ) : (
                        <Sidebar isPrimary={isScrolled} />
                    )}
                </div>
            </div>
        </nav>
    );
};

function NavLink({
    isActive,
    name,
    selector,
}: {
    isActive: boolean;
    name: string;
    selector?: string;
}) {
    const el = document.querySelector(`section` + selector);

    const isScrolled = useScroll();

    return (
        <li
            onClick={() => el?.scrollIntoView({ behavior: "smooth" })}
            className={clsx(
                "transition-all font-medium cursor-pointer select-none",
                {
                    "text-foreground": isScrolled,
                    "text-white": !isScrolled,
                }
            )}
        >
            <div
                className={clsx(
                    `relative before:absolute before:h-0.5 before:bottom-0 before:w-0 before:content-['']  before:hover:w-1/2 before:transition-all before:duration-300 `,
                    {
                        "before:w-1/2": isActive,
                        "before:bg-foreground": isScrolled,
                        "before:bg-white": !isScrolled,
                    }
                )}
            >
                {name}
            </div>
        </li>
    );
}
