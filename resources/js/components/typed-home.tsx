import { ReactTyped } from "react-typed";

export const TypedHome = () => {
    return (
        <ReactTyped
            className="h-16 font-medium text-white md:text-lg"
            typeSpeed={40}
            strings={[
                "Merupakan Sistem Informasi Menejemen NU yang Memuat Data PCNU, MWCNU, Ranting NU, Anak Ranting NU, Lembaga NU dan Badan Otonom NU yang berada di bawah lingkungan NU Kabupaten Tasikmalaya.",
            ]}
        />
    );
};
