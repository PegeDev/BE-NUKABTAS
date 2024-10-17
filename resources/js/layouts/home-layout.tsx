import { Navbar } from "@/components/navbar";
import { Head } from "@inertiajs/react";
import React from "react";
import { Footer } from "./footer";

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
            <div className="w-full min-h-screen">
                <Navbar />
                <main>{children}</main>
            </div>
            <Footer />
        </>
    );
}
