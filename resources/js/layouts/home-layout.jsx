import { Navbar } from "@/components/navbar";

export default function HomeLayout({ children }) {
    return (
        <>
            <div className="relative overflow-hidden bg-primary md:h-screen">
                <Navbar />
                <main className="relative h-full">{children}</main>
            </div>
        </>
    );
}
