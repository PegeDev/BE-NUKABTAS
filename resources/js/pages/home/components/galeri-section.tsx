import { GaleryCarousel } from "@/components/galery-carousel";

export const GalerySection = () => {
    return (
        <section
            id="galeri"
            className="flex flex-col items-center justify-center pt-32 bg-white"
        >
            <div className="container md:space-y-10">
                <div className="flex flex-col items-center mb-8 space-y-4">
                    <div className="flex flex-col items-center space-y-4">
                        <span className="text-lg font-semibold text-center text-primary">
                            Galeri
                        </span>
                        <h2 className="text-center leading-tight text-[32px] md:text-[42px] font-bold">
                            Bertemu dengan Tim
                        </h2>
                    </div>
                    <p className="font-medium text-center md:text-lg text-background">
                        Kegiatan yang dilakukan oleh PCNU Kab. Tasikmalaya
                    </p>
                </div>
                <div id="galery">
                    <GaleryCarousel
                        data={[
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                            "https://placehold.co/600x400",
                        ]}
                    />
                </div>
            </div>
        </section>
    );
};
