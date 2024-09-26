import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    // darkMode: "",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",

        // POWERGRID
        './app/Livewire/**/*Table.php',
        './app/PowerGridThemes/*.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "pg-primary": colors.gray,
                "pg-secondary": colors.green,
            },
        },
    },
    presets: [
        // POWERGRID
        require("./vendor/power-components/livewire-powergrid/tailwind.config.js"),
    ],

    plugins: [forms, require('flowbite/plugin')],
};
