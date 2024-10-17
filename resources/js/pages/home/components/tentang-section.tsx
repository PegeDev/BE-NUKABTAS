export const TentangSection = () => {
    return (
        <section id="tentang-kami" className="min-h-screen m-auto ">
            <div className="container py-32">
                <div className="grid h-full bg-white rounded-md md:grid-cols-2 drop-shadow-lg">
                    <div className="p-8 space-y-8 md:p-16">
                        <span className="px-4 py-2 text-sm font-semibold text-white rounded-md bg-primary">
                            Tentang Kami
                        </span>
                        <h2 className="leading-tight font-bold text-[32px] md:text-[42px]">
                            PCNU Yang Terdepan Dalam Segi Manajemen Data
                        </h2>
                        <p className="font-medium text-background">
                            Manajemen data yang baik adalah pondasi penting
                            dalam mengelola informasi anggota, pengurus, dan
                            lembaga dengan efektif dan aman. Dengan menggunakan
                            sistem manajemen data yang canggih, PCNU Kabupaten
                            Tasikmalaya dapat mengumpulkan, menyimpan, dan
                            mengakses data dengan mudah untuk kepentingan
                            organisasi.
                        </p>
                        <p className="font-medium text-background">
                            Dengan manajemen data yang baik, PCNU Kabupaten
                            Tasikmalaya dapat meningkatkan efisiensi
                            operasional, mengambil keputusan berdasarkan fakta
                            yang akurat, dan menjalin komunikasi yang lebih baik
                            dengan anggotanya.
                        </p>
                    </div>
                    <div className="flex justify-center h-full p-16">
                        <img src="/tentang-kami.svg" alt="Tentang Kami" />
                    </div>
                </div>
            </div>
        </section>
    );
};
