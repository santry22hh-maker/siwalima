import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  safelist: [
    'bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-300',
    'bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-300',
    'bg-purple-100', 'text-purple-800', 'dark:bg-purple-900', 'dark:text-purple-300',
    'bg-orange-100', 'text-orange-800', 'dark:bg-orange-900', 'dark:text-orange-300',
    'bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300',
    'bg-gray-100', 'text-gray-800', 'dark:bg-gray-900', 'dark:text-gray-300',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [forms],
}
