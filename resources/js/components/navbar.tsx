import { usePage } from "@inertiajs/react";
import { NAVIGATION_ROUTES } from "../routes";
import { useScreenSize } from "@/hooks/use-screen-size";
import { Sidebar } from "./sidebar";
import { Profile } from "./profile";

export const Navbar = () => {
    const { url } = usePage();

    const { width } = useScreenSize();

    const formatedNav = NAVIGATION_ROUTES.map((nav) => ({
        ...nav,
        current: url.includes(nav.path),
    }));

    return (
        <nav className="sticky top-0 z-50 w-full max-w-6xl mx-auto transition-all ease-in-out bg-opacity-0 bg-primary">
            <div className="max-w-full px-4 mx-auto md:px-8 lg:px-16">
                <div className="flex items-center justify-between w-full py-4 md:py-5 lg:justify-between lg:space-x-10 lg:py-6">
                    <div>Logo</div>

                    {width >= 768 ? (
                        <>
                            {/* NAVIGATION */}
                            <div>
                                <ul className="hidden md:flex md:items-center md:gap-8">
                                    {formatedNav.map((navigation, index) => (
                                        <li
                                            key={index}
                                            className="font-medium text-white"
                                        >
                                            <div
                                                className={`relative before:absolute before:h-0.5 before:bottom-0 before:w-0 before:content-[''] before:bg-white before:hover:w-1/2 before:transition-all before:duration-300 ${
                                                    navigation.current
                                                        ? "before:w-1/2"
                                                        : ""
                                                }`}
                                            >
                                                <a
                                                    href={navigation.path}
                                                    className=""
                                                >
                                                    {navigation.label}
                                                </a>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {/* PROFILE */}
                            <Profile />
                        </>
                    ) : (
                        <Sidebar />
                    )}
                </div>
            </div>
        </nav>
    );
};
