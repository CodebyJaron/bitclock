/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            colors: {
                success: '#28a745',
                danger: '#dc3545',
                info: '#17a2b8',
            },
        },
    },
};
