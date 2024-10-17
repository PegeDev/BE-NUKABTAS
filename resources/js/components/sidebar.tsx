import { Button } from "@headlessui/react";
import { Dismiss24Filled, Grid28Filled } from "@fluentui/react-icons";
import { NAVIGATION_ROUTES } from "@/routes";
import { Link, usePage } from "@inertiajs/react";
import { useSidebar } from "@/hooks/use-sidebar";
import clsx from "clsx";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
import useScroll from "@/hooks/use-scroll";

interface SidebarProps {
    isPrimary?: boolean;
}

export const Sidebar: React.FC<SidebarProps> = ({ isPrimary = false }) => {
    const { url } = usePage();
    const { auth: user }: any = usePage().props;

    const formatedNav = NAVIGATION_ROUTES.map((nav) => ({
        ...nav,
        current: url.includes(nav.path),
    }));
    const { isOpen, onToggle } = useSidebar();
    return (
        <>
            <Button
                onClick={onToggle}
                className={clsx("p-2 text-xl", {
                    "text-primary": isPrimary,
                    "text-white": !isPrimary,
                })}
            >
                <Grid28Filled />
            </Button>

            <aside
                className={clsx(
                    "fixed top-0 right-0 w-2/3 h-screen bg-white transition ease-in-out",
                    {
                        "translate-x-full": !isOpen,
                    }
                )}
                aria-label="Sidebar"
            >
                <div className="p-4 ">
                    <div className="flex items-center justify-between mb-8">
                        <div>LOGO</div>
                        <Button
                            onClick={onToggle}
                            className={"text-primary px-2 py-1"}
                        >
                            <Dismiss24Filled />
                        </Button>
                    </div>
                    <div>
                        <ul className="flex flex-col gap-4 mb-4">
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
                    <div className="border-t border-primary">
                        {!user ? (
                            <a
                                href={"/dashboard/login"}
                                className={
                                    "mt-4 items-center w-full justify-center gap-2.5 rounded-md px-4 py-3 transition-all ease-in-out hover:opacity-75 flex bg-primary/20 text-primary"
                                }
                            >
                                <img
                                    src={"iconShield.png"}
                                    className="w-5 h-5"
                                />
                                <p className="font-medium">Login</p>
                            </a>
                        ) : (
                            <div className="flex flex-col gap-2 mt-4 space-y-4">
                                <div className="flex items-center justify-end gap-4">
                                    <div className="flex flex-col text-right ">
                                        <p className="font-semibold truncate text-primary">
                                            {user?.name}
                                        </p>
                                        <p className="text-xs truncate text-primary">
                                            {user?.email}
                                        </p>
                                    </div>
                                    <div className="relative">
                                        <img
                                            src={user?.profile_picture}
                                            className="border-2 rounded-full w-14 h-14 border-primary"
                                        />
                                    </div>
                                </div>
                                <Link
                                    href={route("logout")}
                                    method="post"
                                    as="button"
                                    type="button"
                                    className={
                                        "w-full bg-red-500/20 px-4 py-2 rounded-md text-red-500 text-sm"
                                    }
                                >
                                    Keluar
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </aside>
        </>
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

    const { onClose } = useSidebar();

    const handleScroll = () => {
        onClose();
        el?.scrollIntoView({ behavior: "smooth" });
    };

    return (
        <li
            onClick={handleScroll}
            className={clsx(
                "transition-all font-medium cursor-pointer select-none text-primary"
            )}
        >
            <div
                className={clsx(
                    `relative before:absolute before:h-0.5 before:bottom-0 before:w-0 before:content-['']  before:hover:w-1/2 before:transition-all before:duration-300 `,
                    {
                        "before:w-1/2": isActive,
                        "before:bg-primary": isScrolled,
                    }
                )}
            >
                {name}
            </div>
        </li>
    );
}
