/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
  ],
  theme: {
    theme: {
      container: {
        padding: '2rem',
        center: true,
        screens: {
          sm: '600px',
          md: '728px',
          lg: '984px',
          xl: '1240px',
        },
      },
    },
    extend: {},
  },
  plugins: [],
}