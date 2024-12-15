/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#E75B17"
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}