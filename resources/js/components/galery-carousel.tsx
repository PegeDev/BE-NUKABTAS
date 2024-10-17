import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, EffectCoverflow, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/effect-coverflow";
import "swiper/css/pagination";
import "../../css/swipper.css";

interface GaleryCarouselProps {
    data: string[];
}

export const GaleryCarousel: React.FC<GaleryCarouselProps> = ({ data }) => {
    return (
        <Swiper
            effect={"coverflow"}
            grabCursor={true}
            centeredSlides={true}
            slidesPerView="auto"
            coverflowEffect={{
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 2.5,
                slideShadows: false,
            }}
            loop={true}
            pagination={{
                clickable: true,
            }}
            autoplay={{
                delay: 3000,
            }}
            modules={[EffectCoverflow, Autoplay, Pagination]}
            className="galery-slider"
        >
            {data?.map((val, index) => (
                <SwiperSlide key={index} className="galery-slide">
                    <div className="galery-slide-img">
                        <img src={val} alt="asu" />
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
};
