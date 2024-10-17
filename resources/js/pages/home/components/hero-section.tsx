import { TypedHome } from "@/components/typed-home";

export const HeroSection = () => {
    return (
        <section id="hero" className="min-h-screen pt-24 bg-primary ">
            <div className="relative z-0 mx-auto flex container w-full grid-cols-2 flex-col items-start justify-end gap-x-10 py-0 md:grid md:h-[80vh]  md:py-10 lg:items-center ">
                <div className="z-10 order-1 w-full mt-10 h-fit md:mt-24 md:w-auto lg:mt-0 ">
                    <p
                        className="mb-4 text-center text-3xl font-bold tracking-wide text-white md:text-left lg:text-5xl lg:leading-[60px] animate-fade-in-left"
                        style={{ animationDuration: "1s" }}
                    >
                        SIM NU
                    </p>
                    <div
                        className="mb-4 text-center min-h-20 md:text-left animate-fade-in-left"
                        style={{ animationDuration: ".7s" }}
                    >
                        <TypedHome />
                    </div>
                </div>
                <div className="text-white text-sm order-3 col-span-2 mx-auto mt-20 mb-12 w-fit rounded-full bg-[#085749] py-3 px-6 md:mx-0 md:mt-0 md:mr-auto md:mb-0">
                    <p>Copyright © 2024. All rights reserved.</p>
                </div>
                <div className="w-full h-full mt-10 justify-self-center md:order-2 md:mt-0 md:justify-self-end">
                    <div className="flex justify-center w-full animate-fade-in-right">
                        <img
                            alt="Hero SIMNU"
                            src="/hero.webp"
                            className="animate-bounce-slow relative top-3 z-10 w-72 md:w-[497px]"
                        />
                    </div>
                </div>
                <img
                    className="absolute right-0 -top-24"
                    src={"/hero-gradient.png"}
                    width={800}
                    height={800}
                />
                <img
                    className="absolute right-0 md:w-[55%] h-full"
                    src={"/dot_map.svg"}
                    width={500}
                    height={500}
                />
            </div>
        </section>
    );
};
