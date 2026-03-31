import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f4f4ff',
                    100: '#ebe9ff',
                    200: '#d9d4ff',
                    300: '#bdb0ff',
                    400: '#9a84ff',
                    500: '#7958ff',
                    600: '#6237f5',
                    700: '#5329da',
                    800: '#4524b1',
                    900: '#39238b',
                    950: '#25175e',
                },
                ink: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
                surface: {
                    50: '#ffffff',
                    100: '#f8fafc',
                    200: '#f1f5f9',
                    300: '#e2e8f0',
                },
                wb: {
                    'pink': '#CB11AB',
                    'pink-light': '#E91E8C',
                    'pink-dark': '#A00D8A',
                    'purple-light': '#F4EBFF',
                    'gray-bg': '#F5F5F5',
                    'gray-card': '#FAFAFA',
                    'gray-text': '#707070',
                    'red-discount': '#FF3B30',
                    'green-rating': '#34C759',
                    'orange-new': '#FF9500',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                section: '80rem',
                admin: '96rem',
            },
            borderRadius: {
                'brand-sm': '1rem',
                'brand-md': '1.25rem',
                'brand-lg': '1.75rem',
                'brand-xl': '2rem',
            },
            boxShadow: {
                soft: '0 10px 30px -18px rgba(15, 23, 42, 0.22)',
                surface: '0 24px 60px -28px rgba(76, 29, 149, 0.24), 0 18px 28px -24px rgba(15, 23, 42, 0.18)',
                brand: '0 18px 38px -20px rgba(99, 56, 255, 0.55)',
                'brand-lg': '0 28px 60px -26px rgba(79, 70, 229, 0.5)',
                inset: 'inset 0 1px 0 rgba(255, 255, 255, 0.75)',
            },
            backgroundImage: {
                'gradient-brand': 'linear-gradient(135deg, #6237f5 0%, #7c3aed 46%, #2563eb 100%)',
                'gradient-brand-soft': 'linear-gradient(135deg, rgba(98, 55, 245, 0.12) 0%, rgba(124, 58, 237, 0.08) 46%, rgba(37, 99, 235, 0.12) 100%)',
                'brand-mesh': 'radial-gradient(circle at top left, rgba(124, 58, 237, 0.18), transparent 32%), radial-gradient(circle at top right, rgba(37, 99, 235, 0.14), transparent 28%), linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(248, 250, 252, 0.96))',
                'admin-grid': 'linear-gradient(rgba(148, 163, 184, 0.10) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.10) 1px, transparent 1px), linear-gradient(180deg, rgba(248, 250, 252, 0.98), rgba(241, 245, 249, 0.96))',
            },
            gridTemplateColumns: {
                'products-mobile': 'repeat(2, minmax(0, 1fr))',
                'products-tablet': 'repeat(3, minmax(0, 1fr))',
                'products-desktop': 'repeat(4, minmax(0, 1fr))',
                'products-wide': 'repeat(5, minmax(0, 1fr))',
            },
        },
    },

    plugins: [forms],
};
