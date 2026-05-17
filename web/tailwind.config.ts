import type { Config } from 'tailwindcss'

export default {
  content: [
    './app/**/*.{js,vue,ts}',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'Noto Sans Khmer', 'sans-serif'],
        khmer: ['Noto Sans Khmer', 'sans-serif'],
      },
    },
  },
  plugins: [],
} satisfies Config
