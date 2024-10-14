import AppLogo from "@/components/app-logo";
import { Link } from "@inertiajs/react";
import { PropsWithChildren } from "react";

export default function Guest({ children }: PropsWithChildren) {
    return (
        <div className="flex flex-col items-center justify-center min-h-screen bg-slate-200">
            <div className="flex flex-col w-11/12 max-w-md p-6 space-y-6 overflow-hidden rounded-lg shadow-md bg-primary">
                <Link href="/" className="w-20 mx-auto">
                    <AppLogo className="w-20 h-20 text-gray-500 fill-current" />
                </Link>

                {children}
            </div>
        </div>
    );
}
