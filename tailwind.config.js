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
                "dark-variant": "#0D0F14",
                "dark-highlight": "#252d3b",
                "dark-highlight-variant": "#42bfdd",
                "dark-box": "#2f2f3a",
                "dark-box-40": "#242833",
                light: "#D3D9D4",
                lightVariant: "#babade",
                highlight: "#124E66",
                highlightDark: "#1e333f",
                "highlight-bright": "#42bfdd",
                "card-gradient-color-start": "#757F9A",
                "card-gradient-color-end": "#D7DDE8",
            },
            boxShadow: {
                "dark-sm": "0 4px 30px rgba(0, 0, 0, 0.2)",
            },
        },
    },

    plugins: [forms, typography],
};
