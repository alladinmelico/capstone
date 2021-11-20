const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    light: '#b4ffff',
                    DEFAULT: '#80deea',
                    dark: '#4bacb8'
                },
                secondary: {
                    light: '#4fb3bf',
                    DEFAULT: '#00838f',
                    dark: '#00838F'
                },
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            ringWidth: ['hover', 'active'],
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
