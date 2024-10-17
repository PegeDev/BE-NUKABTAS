import preset from "./vendor/filament/support/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/**/*.(jsx|js|ts|tsx)",
    ],
    theme: {
        fontFamily: {
            sans: [
                '"Poppins"',
                ...require("tailwindcss/defaultTheme").fontFamily.sans,
            ],
        },
        container: {
            center: true,
            maxWidth: {
                DEFAULT: "100%",
                sm: "540px",
                md: "720px",
                lg: "960px",
                xl: "1140px",
                "2xl": "1320px",
            },
            padding: {
                DEFAULT: "1rem",
            },
        },
        extend: {
            maxWidth: {
                "8xl": "94rem",
            },
            colors: {
                primary: "#076857",
                foreground: "#212B36",
                background: "#637381",
            },
            animation: {
                "bounce-slow": "bounce-slow 2s ease infinite",
                "bounce-in": "bounce-in .5s ease-out",
                "fade-in-left": "fade-in-left 1s ease-out",
                "fade-in-right": "fade-in-right 1s ease-out",
            },
            keyframes: {
                "fade-in-left": {
                    "0%": { opacity: "0", transform: "translateX(-50px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                "fade-in-right": {
                    "0%": { opacity: "0", transform: "translateX(50px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                "bounce-slow": {
                    "0%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-20px)" },
                    "100%": { transform: "translateY(0)" },
                },
                "bounce-in": {
                    "0%": {
                        transform: "translateY(0)",
                        opacity: "0",
                    },
                    "50%": {
                        transform: "translateY(-10px)",
                        opacity: "1",
                    },
                    "100%": {
                        transform: "translateY(0)",
                        opacity: "1",
                    },
                },
                "bounce-out": {
                    "0%": {
                        transform: "translateY(0)",
                        opacity: "0",
                    },
                    "50%": {
                        transform: "translateY(10px)",
                        opacity: "1",
                    },
                    "100%": {
                        transform: "translateY(0)",
                        opacity: "1",
                    },
                },
            },
        },
    },
};
