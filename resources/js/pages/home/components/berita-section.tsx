import { BeritaList } from "@/components/berita-list";

export const BeritaSection = () => {
    const data = [
        {
            title: "Berita 1",
            slug: "berita-1",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Berita 2",
            slug: "berita-2",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Berita 3",
            slug: "berita-3",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Berita 4",
            slug: "berita-4",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Berita 5",
            slug: "berita-5",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Berita 6",
            slug: "berita-6",
            thumb: "https://placehold.co/600x400",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
    ];
    return (
        <section id="berita" className="bg-white">
            <div className="container pt-32 pb-24">
                <div className="flex flex-col items-center mb-8 space-y-4">
                    <span className="text-lg font-semibold text-center text-primary">
                        Berita
                    </span>
                    <h2 className="text-center leading-tight text-[32px] md:text-[42px] font-bold">
                        Berita Terkini
                    </h2>
                </div>
                <div>
                    <BeritaList data={data} />
                </div>
            </div>
        </section>
    );
};
