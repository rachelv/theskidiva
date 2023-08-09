/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './index.html',
  ],
  theme: {
    extend: {
      fontFamily: {
        'lato': ['"Lato"', 'sans-serif']
      },
      colors: {
        'sd-purple': {
          DEFAULT: '#1e73be',
        },
        'sd-blue': {
          DEFAULT: '#165389',
        },
        'sd-pink': {
          DEFAULT: '#ee4d73',
          hover: '#f92d5e',
        },
      },
    },
  },
  plugins: [],
}