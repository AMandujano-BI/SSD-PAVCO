const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            transitionProperty: {
                position: 'right, left, top, bottom, margin, padding',
                textColor: 'color'
              },
            colors: {
                'primary': {
                    DEFAULT: '#0271C5',
                    '50': '#81C8FE',
                    '100': '#6DBFFE',
                    '200': '#44ADFD',
                    '300': '#1C9CFD',
                    '400': '#0288ED',
                    '500': '#0271C5',
                    '600': '#01518D',
                    '700': '#013156',
                    '800': '#00111E',
                    '900': '#000000'
                },
            }
        },
        // container: {
        // center: true
        // }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
