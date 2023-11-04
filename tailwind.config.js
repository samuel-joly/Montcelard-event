/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/*/*.{html,js}"
    ],
    theme: {
        extend: {
            colors: {
                'primary':   '#83889c',
                'secondary': '#545871', 
                'ternary':   '#ebf1f7',
                'focus':     '#cbdcef',
                'inactif':   '#b0b0b0',
                'neutral':   '#f4f4f4',
                'light':     '#bcbcbc',
                'focus-blue':'#759CC9',
            },
            height: {
                'h-85-vh': '85vh'
            },
            fontFamily: {
                'ledger': 'Ledger',  
            },
        },
    },
    plugins: [],
}
