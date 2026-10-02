import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                outfit: ['"Outfit"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#9E3924',
                'primary-hover': '#862F1D',
                'primary-active': '#722718',
                'primary-subtle': '#FDF2F0',

                ink: '#1A1D20',
                'ink-heading': '#111827',
                body: '#4B5563',
                'body-strong': '#374151',
                muted: '#6B7280',
                'muted-soft': '#9CA3AF',
                hairline: '#E5E7EB',
                'hairline-soft': '#F3F4F6',
                canvas: '#F8F9FA',
                'canvas-alt': '#FAFAFA',

                'surface-card': '#FFFFFF',
                'surface-subtle': '#F9FAFB',
                'surface-dark': '#0F172A',
                'surface-forest': '#071A16',
                'surface-forest-card': '#0D2721',
                'surface-forest-border': '#153A32',
                'surface-forest-tag': '#163830',

                'grade-a-bg': '#EAF5EF',
                'grade-a-text': '#226848',
                'grade-a-dot': '#10B981',
                'grade-b-bg': '#FFF0E6',
                'grade-b-text': '#B85320',
                'grade-b-dot': '#F59E0B',
                'grade-c-bg': '#FDECEB',
                'grade-c-text': '#B92F26',
                'grade-c-dot': '#F43F5E',
            },
        },
    },

    plugins: [forms],
};
