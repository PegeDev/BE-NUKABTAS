import { Navbar } from "@/components/navbar";
import { Head } from "@inertiajs/react";
import React from "react";

interface HomeLayoutProps {
    children: React.ReactNode;
    title: string;
    description: string;
}

export default function HomeLayout({
    children,
    title,
    description,
}: HomeLayoutProps): JSX.Element {
    return (
        <>
            <Head title={title}>
                <meta name="description" content={description} />
            </Head>
            <div className="relative overflow-hidden bg-primary md:h-screen">
                <Navbar />
                <main className="relative h-full">{children}</main>
            </div>
        </>
    );
}
