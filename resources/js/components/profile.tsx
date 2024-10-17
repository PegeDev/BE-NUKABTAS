import { useState } from "react";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/react";
import { Link, router, usePage } from "@inertiajs/react";
import clsx from "clsx";

interface ProfileProps {
    isPrimary?: boolean;
}

export const Profile: React.FC<ProfileProps> = ({ isPrimary = false }) => {
    const [open, setOpen] = useState(false);
    const { auth: user }: any = usePage().props;

    return user ? (
        <Popover className="relative z-50">
            <PopoverButton
                className={clsx(
                    "block text-sm/6 font-semibold  focus:outline-none",
                    {
                        "text-black": isPrimary,
                        "text-white": !isPrimary,
                    }
                )}
            >
                <div className="flex items-center space-x-2">
                    <div className="flex flex-col text-right max-w-28">
                        <p className="text-base font-semibold truncate ">
                            {user?.name}
                        </p>
                        <p className="text-sm truncate">{user?.email}</p>
                    </div>
                    <div className="relative">
                        <img
                            src={user?.profile_picture}
                            className={clsx(
                                "w-10 h-10 ring-2 p-[2px] rounded-full transition-all",
                                {
                                    "ring-primary": isPrimary,
                                    "ring-white": !isPrimary,
                                }
                            )}
                        />
                    </div>
                </div>
            </PopoverButton>
            <PopoverPanel
                transition
                anchor="bottom end"
                className="w-64 mt-2 z-50 rounded-xl bg-white text-sm/6 transition duration-200 ease-in-out [--anchor-gap:var(--spacing-5)] data-[closed]:-translate-y-1 data-[closed]:opacity-0"
            >
                <div className="px-4 py-2 border-b border-black/10">
                    <p className="font-semibold truncate text-nowrap">
                        {user?.name}
                    </p>
                </div>
                <div className="p-2">
                    <a
                        className="block px-3 py-1 transition rounded-lg hover:bg-black/15"
                        href="/dashboard"
                    >
                        <p className="text-sm text-black">Dashboard</p>
                    </a>
                </div>
                <div className="p-2">
                    <a
                        className="block px-3 py-1 transition rounded-lg hover:bg-black/15"
                        href="/dashboard/profile"
                    >
                        <p className="text-sm text-black">Profil Saya</p>
                    </a>
                </div>
                <div className="p-2 border-t border-black/10">
                    <Link
                        className="block w-full px-3 py-1 text-left transition rounded-lg hover:bg-red-500/20"
                        href={route("logout")}
                        method="post"
                    >
                        <p className="text-sm text-red-500">Keluar</p>
                    </Link>
                </div>
            </PopoverPanel>
        </Popover>
    ) : (
        <a
            href={"/dashboard/login"}
            className={clsx(
                "h-10 items-center justify-center gap-2.5 rounded-md px-4 py-3 transition-all ease-in-out hover:bg-current/55 lg:flex drop-shadow-lg",
                {
                    "bg-primary text-white": isPrimary,
                    "bg-white text-black": !isPrimary,
                }
            )}
        >
            <img src={"iconShield.png"} className="w-5 h-5" />
            <p className="font-medium">Login</p>
        </a>
    );
};
