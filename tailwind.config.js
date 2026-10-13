/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1536px',},
      extend: {
        dropShadow:{
          neon:'0 0 5px #0ff, 0 0 10px #off, 0 0 20px #off, 0 0 40px #0ff'
        },keyframes:{
          flicker:{
            '0%,19.999%,22%,62.999%,64%,100%':{opacity:0.99},
            '20%,21.999%,63%,63.999%':{opacity:0.4},
          },
        },animation:{
          flicker:'flicker 2s infinite linear'
        },
        colors: {
          'primary-bg': 'var(--primary-bg)',
          'secondary-bg': 'var(--secondary-bg)',
          'primary-button': 'var(--primary-button)',
          'secondary-button': 'var(--secondary-button)',
          'primary': 'var(--primary-color)',
          'first': 'var(--first)',
          'second': 'var(--second)',
          'third': 'var(--third)',
          'hover':'var(--hover-color)',
          'hover2':'var(--hover2-color)',
          
      },
      backgroundImage:theme=>({
        'gradient-to-b':'linear-gradient(to bottom,#326868,#64CECE)'
      }),},
      fontFamily: {
        TimesNewRoman: ["Times New Roman", 'serif'],
        arial: ["Arial"],
        simplifiedArabic:['simplified arabic']


    },

},


plugins: []}

