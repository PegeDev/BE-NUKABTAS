import { ChevronDown20Filled } from "@fluentui/react-icons";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from "@headlessui/react";
import clsx from "clsx";

export const FAQSection = () => {
    const data = [
        {
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
        {
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
            description:
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, voluptate?",
        },
    ];
    return (
        <section id="faq" className="">
            <div className="container pt-32">
                <div className="mb-8 space-y-4 text-center">
                    <div className="text-center">
                        <span className="text-lg font-semibold text-primary">
                            FAQ
                        </span>
                        <h2 className="text-[32px] leading-tight md:text-[42px] font-bold">
                            Ada Pertanyaan ?
                        </h2>
                    </div>
                    <p className="md:text-lg text-background">
                        Beberapa pertanyaan yang sering diajukan
                    </p>
                </div>
                <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
                    {data.map((item, index) => (
                        <Disclosure key={index}>
                            {({ open }) => (
                                <div
                                    className={
                                        "bg-white rounded-lg p-4 drop-shadow h-fit space-y-4 overflow-hidden hover:drop-shadow-lg transition-all duration-300 ease-in-out"
                                    }
                                >
                                    <DisclosureButton className="flex items-center w-full gap-4 font-semibold text-left">
                                        <div className="flex items-center justify-center w-10 h-10 p-2 rounded-lg bg-slate-200 text-primary">
                                            <ChevronDown20Filled
                                                className={clsx(
                                                    "transition-all duration-300 ease-in-out",
                                                    {
                                                        "rotate-180": open,
                                                    }
                                                )}
                                            />
                                        </div>
                                        {item.title}
                                    </DisclosureButton>
                                    <DisclosurePanel
                                        transition
                                        className={clsx(
                                            " pl-8 transition-all duration-200 ease-out max-h-40 data-[closed]:opacity-0 data-[closed]:max-h-0"
                                        )}
                                    >
                                        {item.description}
                                    </DisclosurePanel>
                                </div>
                            )}
                        </Disclosure>
                        // <li
                        //     key={index}
                        //     className="transition-all duration-300 ease-in-out bg-white rounded-lg hover:drop-shadow-lg drop-shadow h-fit"
                        // >
                        //     {/* Collapsible Toggle Header */}
                        //     <input
                        //         type="checkbox"
                        //         id={`collapse-${index}`}
                        //         name={`collapse-${index}`}
                        //         className="hidden peer"
                        //     />

                        //     {/* Header Section with Title */}
                        //     <label
                        //         htmlFor={`collapse-${index}`}
                        //         className="flex items-center justify-between gap-4 p-4 cursor-pointer"
                        //     >
                        //         <div className="flex items-center gap-4">
                        //             <div className="flex items-center justify-center w-10 h-10 p-2 rounded-lg bg-slate-200 text-primary">
                        //                 <ChevronDown20Filled className="transition-all duration-300 ease-in-out rotate-0 peer-has-[:checked]:rotate-180" />
                        //             </div>
                        //             <h3 className="font-semibold">
                        //                 {item.title}
                        //             </h3>
                        //         </div>
                        //     </label>

                        //     {/* Collapsible Content with Animation */}
                        //     <div className="px-4 overflow-hidden text-gray-600 transition-all duration-500 ease-in-out max-h-0 peer-checked:max-h-40 ">
                        //         <p className="py-4">{item.description}</p>
                        //     </div>
                        // </li>
                    ))}
                </div>
            </div>
        </section>
    );
};
