import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Use a softer, modern palette by mapping common color names
                // to Tailwind's gentler palettes. This will update existing
                // `bg-blue-*`, `text-blue-*`, `bg-indigo-*` usages across
                // the app without changing blade templates.
                blue: colors.sky,
                indigo: colors.cyan,
                slate: colors.slate,
                emerald: colors.emerald,
                amber: colors.amber,
            },
        },
    },

    plugins: [forms],
};
