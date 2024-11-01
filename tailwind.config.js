import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: "#1D232E",
                darkVariant: "#0D0F14",
                "dark-highlight": "#191923",
                "dark-highlight-variant": "#191919",
                light: "#D3D9D4",
                lightVariant: "#748D92",
                highlight: "#124E66",
                highlightDark: "#1e333f",
            },
            boxShadow: {
                "dark-sm": "0 4px 30px rgba(0, 0, 0, 0.2)",
            },
        },
    },

    plugins: [forms, typography],
};
