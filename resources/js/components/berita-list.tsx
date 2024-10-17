import { BeritaSchema } from "@/types/berita";

interface BeritaListProps {
    data: BeritaSchema[];
}

export const BeritaList: React.FC<BeritaListProps> = ({ data }) => {
    return (
        <div className="flex py-4 space-x-4 overflow-x-scroll scrollbar-hide">
            {data.map((item, index) => (
                <div
                    key={index}
                    className="flex-shrink-0 overflow-hidden bg-white rounded-lg shadow-lg w-80"
                >
                    {/* Image Section */}
                    <div className="w-full h-48">
                        <img
                            src={item.thumb}
                            alt={item.title}
                            className="object-cover w-full h-full"
                        />
                    </div>
                    {/* Content Section */}
                    <div className="p-4">
                        <h2 className="text-xl font-bold text-gray-800">
                            {item.title}
                        </h2>
                        <p className="mt-2 text-sm text-gray-600">
                            {item.description}
                        </p>
                        <a
                            href={item.slug}
                            className="inline-block mt-4 text-primary hover:text-primary/80"
                        >
                            Read more
                        </a>
                    </div>
                </div>
            ))}
        </div>
    );
};
