import { Diamond24Regular } from "@fluentui/react-icons";

interface IconFrameProps {
    icon: React.ReactNode;
}

export const IconFrame: React.FC<IconFrameProps> = ({ icon }) => {
    return (
        <div className="mb-[40px] relative before:absolute bg-primary w-[70px] h-[70px] flex items-center justify-center text-white text-2xl rounded-[14px] before:rotate-[23deg] before:bg-primary before:opacity-20 before:w-[70px] before:h-[70px] before:rounded-[14px] group-hover:before:rotate-45 before:transition-all before:duration-500 before:ease-in-out">
            {icon}
        </div>
    );
};
