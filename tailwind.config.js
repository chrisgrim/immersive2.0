module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        fontSize: {
            'xs': '.75rem',
            'sm': '.875rem',
            'tiny': '.875rem',
            'base': '1rem',
            'lg': '1.125rem',
            'xl': '1.25rem',
            '1xl': '1.35rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '4rem',
            '7xl': '5rem',
        },
        extend: {
            spacing: {
                '128': '32rem',
            },
            boxShadow: {
                'custom-1': '0 2px 16px rgb(0 0 0 / 12%)',
                'custom-2': '0 1px 7px rgb(0 0 0 / 50%)',
                'custom-3': '0 1px 2px rgb(0 0 0 / 8%), 0 4px 12px rgb(0 0 0 / 5%)',
                'custom-4': '0 2px 0 0 rgb(0 0 0 / 10%), 0 0 0 0.5px rgb(0 0 0 / 4%)',
            },
            colors: {
                'button-red-1': '#e61e4d',
                'button-red-2': '#e31c5f',
                'button-red-3': '#d70466',
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
                '6xl': '3rem',
            },
        }
    },
    plugins: [],
}