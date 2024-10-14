import { ImgHTMLAttributes } from "react";

export default function AppLogo(props: ImgHTMLAttributes<HTMLImageElement>) {
    return <img src="/images/logo.png" alt="" {...props} />;
}
