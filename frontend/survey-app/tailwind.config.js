/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./*.html",
    "./src/**/*.{html,js,vue}"
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Karla', 'sans-serif'],
        'domine': ['domine', 'sans']
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

