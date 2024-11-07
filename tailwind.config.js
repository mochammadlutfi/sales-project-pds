import defaultTheme from 'tailwindcss/defaultTheme';
// import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    corePlugins: {
        preflight: true,
    },
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        container: {
            center: true,
        },

        fontFamily: {
            'base': ['Inter', 'sans-serif'],
        },

        extend: {
            colors: {
                'primary': "var(--el-color-primary)",
                'secondary': "var(--secondary-color)",
                'success': "var(--success-color)",
                'warning': "var(--warning-color)",
                'info': "var(--info-color)",
                'danger': "var(--e-color)",
                'light': "var(--light-color)",
                'dark': "var(--dark-color)",
            },
        },
    },

    plugins: [],
};
