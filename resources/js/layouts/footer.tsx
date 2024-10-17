export const Footer = () => {
    return (
        <footer className="h-40 bg-gradient-to-tr from-primary to-black/50 bg-primary">
            <div className="container px-4 py-12 overflow-hidden">
                <div className="flex flex-col items-center h-full md:justify-between md:flex-row">
                    <div className="md:w-1/2">
                        <p className="text-white">
                            Copyright © 2024. All rights reserved.
                        </p>
                    </div>
                    <div>
                        <p className="text-white">
                            Developed with{" "}
                            <span className="text-red-500 animate-pulse">
                                ❤
                            </span>{" "}
                            by{" "}
                            <a
                                href="https://pege.eu.org"
                                target="_blank"
                                className="font-semibold underline"
                            >
                                Pege Dev
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    );
};
