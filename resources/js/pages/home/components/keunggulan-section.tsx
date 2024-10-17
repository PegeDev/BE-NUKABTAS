import { IconFrame } from "@/components/icon-frame";
import { BranchFork20Regular } from "@fluentui/react-icons";
import clsx from "clsx";

export const KeunggulanSection = () => {
    const data = [
        {
            count: "22222",
            desc: "MWC yang Terdaftar dan Aktif",
            icon: <BranchFork20Regular className="w-8 h-8" />,
        },
        {
            count: "22222",
            desc: "Ranting yang terdaftar dan aktif",
            icon: <BranchFork20Regular className="w-8 h-8" />,
        },
        {
            count: "22222",
            desc: "Anak Ranting yang terdaftar dan aktif",
            icon: <BranchFork20Regular className="w-8 h-8" />,
        },
        {
            count: "22222",
            desc: "Banom yang terdaftar dan aktif",
            icon: <BranchFork20Regular className="w-8 h-8" />,
        },
        {
            count: "22222",
            desc: "Lembaga yang terdaftar dan aktif",
            icon: <BranchFork20Regular className="w-8 h-8" />,
        },
    ];
    return (
        <section
            id="keunggulan"
            className="flex flex-col items-center justify-center bg-white"
        >
            <div className="container pt-32 pb-24">
                <div className="space-y-16">
                    <div className="space-y-4">
                        <div>
                            <span className="text-lg font-semibold text-primary">
                                Keunggulan
                            </span>
                            <h2 className="text-[32px] md:text-[42px] font-bold leading-tight">
                                Keunggulan PCNU Kab. Tasikmalaya
                            </h2>
                        </div>
                        <p className="md:text-lg text-background">
                            Terdapat banyak keunggulan dalam data PCNU Kab.
                            Tasikmalaya
                        </p>
                    </div>
                    <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {data?.map((val, index) => (
                            <div
                                key={index}
                                className={clsx(
                                    "flex items-start gap-5 group w-full",
                                    {
                                        "lg:col-start-3":
                                            index === data.length - 1,
                                    }
                                )}
                            >
                                <div>
                                    <IconFrame icon={val.icon} />
                                </div>
                                <div>
                                    <span className="text-[46px] font-bold leading-[28px] mb-4">
                                        {val?.count}
                                    </span>
                                    <p className="font-medium text-background text-wrap">
                                        {val?.desc}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
};
