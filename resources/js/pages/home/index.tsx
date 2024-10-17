import HomeLayout from "@/layouts/home-layout";
import { HeroSection } from "./components/hero-section";
import { TentangSection } from "./components/tentang-section";
import { KeunggulanSection } from "./components/keunggulan-section";
import { GalerySection } from "./components/galeri-section";
import { BeritaSection } from "./components/berita-section";
import { FAQSection } from "./components/faq-section";
import { KontakSection } from "./components/kontak-section";

export default function HomeIndex() {
    return (
        <HomeLayout
            title="SIM NU"
            description="Merupakan Sistem Informasi Manajemen NU yang Memuat Data PCNU, MWCNU, Ranting NU, Anak Ranting NU, Lembaga NU dan Badan Otonom NU yang berada di bawah lingkungan NU Kabupaten Tasikmalaya."
        >
            {/* Hero Section */}
            <HeroSection />
            {/* END Hero Section */}

            {/* Tentang Section */}
            <TentangSection />
            {/* END TENTANG SECTION */}

            {/* Keunggulan Section */}
            <KeunggulanSection />
            {/* END Keunggulan Section */}

            {/* Galery Section */}
            <GalerySection />
            {/* END Galery Section */}

            {/* Berita Section */}
            <BeritaSection />
            {/* END Berita Section */}

            {/* FAQ Section */}
            <FAQSection />
            {/* END FAQ Section */}

            {/* Berita Section */}
            <KontakSection />
            {/* END Berita Section */}
        </HomeLayout>
    );
}
