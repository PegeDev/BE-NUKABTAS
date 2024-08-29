import preset from "./vendor/filament/support/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
    theme: {
        fontFamily: {
            sans: [
                '"Inter"',
                ...require("tailwindcss/defaultTheme").fontFamily.sans,
            ],
        },
        extend: {
            colors: {
                primary: "#076857",
            },
        },
    },
};
