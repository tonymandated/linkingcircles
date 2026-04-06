/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/**/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/livewire/flux-pro/stubs/**/*.blade.php',
    './vendor/livewire/flux/stubs/**/*.blade.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-brand-primary)',
        heading: 'var(--color-brand-heading)',
        content: 'var(--color-brand-body)',
        surface: 'var(--color-brand-surface)',
        zinc: {
          50: 'var(--color-zinc-50)',
          100: 'var(--color-zinc-100)',
          200: 'var(--color-zinc-200)',
          300: 'var(--color-zinc-300)',
          400: 'var(--color-zinc-400)',
          500: 'var(--color-zinc-500)',
          600: 'var(--color-zinc-600)',
          700: 'var(--color-zinc-700)',
          800: 'var(--color-zinc-800)',
          900: 'var(--color-zinc-900)',
          950: 'var(--color-zinc-950)',
        },
      },
      fontFamily: {
        serif: ['Georgia', 'Cambria', 'Times New Roman', 'serif'],
      },
    },
  },
  plugins: [],
}
