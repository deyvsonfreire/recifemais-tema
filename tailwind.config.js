/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './**/*.html',
    './**/*.js',
    './src/**/*.css',
    './components/**/*.php',
    './includes/**/*.php',
    './templates/**/*.php',
    './parts/**/*.php',
    './patterns/**/*.php',
  ],
  theme: {
    extend: {
      // === CORES DO DESIGN SYSTEM ===
      colors: {
        'recife': {
          'primary': '#e11d48',
          'primary-dark': '#be123c',
          'primary-light': '#f43f5e',
          'secondary': '#ff6b35',
          'secondary-dark': '#ea580c',
          'secondary-light': '#fb923c',
          'accent': '#0ea5e9',
          'accent-dark': '#0284c7',
          'accent-light': '#38bdf8',
          'success': '#10b981',
          'creative': '#8b5cf6',
          'warning': '#f59e0b',
        },
        'recife-gray': {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
        'cpt': {
          'eventos': '#e11d48',
          'lugares': '#0ea5e9',
          'artistas': '#8b5cf6',
          'organizadores': '#f59e0b',
          'roteiros': '#ff6b35',
          'agremiacoes': '#10b981',
          'historias': '#6366f1',
          'guias': '#ec4899',
        }
      },
      
      // === TIPOGRAFIA ===
      fontFamily: {
        'sans': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        'display': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
      },
      
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1.4' }],
        'sm': ['0.875rem', { lineHeight: '1.5' }],
        'base': ['1rem', { lineHeight: '1.7' }],
        'lg': ['1.125rem', { lineHeight: '1.6' }],
        'xl': ['1.25rem', { lineHeight: '1.5' }],
        '2xl': ['1.5rem', { lineHeight: '1.4' }],
        '3xl': ['1.875rem', { lineHeight: '1.3' }],
        '4xl': ['2.25rem', { lineHeight: '1.2' }],
        '5xl': ['3rem', { lineHeight: '1.1' }],
        '6xl': ['4rem', { lineHeight: '1' }],
        'headline-display': ['clamp(2.5rem, 5vw, 4rem)', { lineHeight: '1.1', letterSpacing: '-0.02em' }],
        'headline-xl': ['clamp(2rem, 4vw, 3rem)', { lineHeight: '1.2' }],
        'headline-lg': ['clamp(1.5rem, 3vw, 2.25rem)', { lineHeight: '1.3' }],
      },
      
      // === ESPAÇAMENTO ===
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      
      // === SOMBRAS ===
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'recife': '0 4px 14px 0 rgba(225, 29, 72, 0.15)',
        'recife-lg': '0 10px 25px -3px rgba(225, 29, 72, 0.1), 0 4px 6px -2px rgba(225, 29, 72, 0.05)',
      },
      
      // === BORDAS ===
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
      },
      
      // === BREAKPOINTS ===
      screens: {
        'xs': '475px',
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
      },
      
      // === ASPECT RATIOS ===
      aspectRatio: {
        'hero': '16 / 9',
        'card': '4 / 3',
        'thumb': '1 / 1',
      },
      
      // === ANIMAÇÕES ===
      animation: {
        'fade-in-up': 'fadeInUp 0.5s ease-out',
        'shimmer': 'shimmer 1.5s infinite',
        'bounce-gentle': 'bounceGentle 2s infinite',
        'float': 'float 3s ease-in-out infinite',
      },
      
      keyframes: {
        fadeInUp: {
          '0%': {
            opacity: '0',
            transform: 'translateY(20px)',
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
        shimmer: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' },
        },
        bounceGentle: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' },
        },
      },
      
      // === TRANSIÇÕES ===
      transitionDuration: {
        '400': '400ms',
        '600': '600ms',
      },
      
      // === BACKDROP BLUR ===
      backdropBlur: {
        'xs': '2px',
      },
      
      // === GRID ===
      gridTemplateColumns: {
        'portal': '1fr 300px',
        'auto-fit': 'repeat(auto-fit, minmax(250px, 1fr))',
        'auto-fill': 'repeat(auto-fill, minmax(250px, 1fr))',
      },
      
      // === CONTAINER ===
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '1.5rem',
          lg: '2rem',
          xl: '2.5rem',
        },
        screens: {
          sm: '640px',
          md: '768px',
          lg: '1024px',
          xl: '1280px',
        },
      },
    },
  },
  plugins: [
    // Plugin para adicionar utilitários customizados
    function({ addUtilities, addComponents, theme }) {
      // Utilitários para scroll snap
      addUtilities({
        '.scroll-snap-x': {
          'scroll-snap-type': 'x mandatory',
        },
        '.scroll-snap-y': {
          'scroll-snap-type': 'y mandatory',
        },
        '.scroll-snap-start': {
          'scroll-snap-align': 'start',
        },
        '.scroll-snap-center': {
          'scroll-snap-align': 'center',
        },
        '.scroll-snap-end': {
          'scroll-snap-align': 'end',
        },
      });
      
      // Utilitários para esconder scrollbar
      addUtilities({
        '.hide-scrollbar': {
          '-ms-overflow-style': 'none',
          'scrollbar-width': 'none',
          '&::-webkit-scrollbar': {
            display: 'none',
          },
        },
      });
      
      // Utilitários para safe areas (mobile)
      addUtilities({
        '.safe-top': {
          'padding-top': 'env(safe-area-inset-top)',
        },
        '.safe-bottom': {
          'padding-bottom': 'env(safe-area-inset-bottom)',
        },
        '.safe-left': {
          'padding-left': 'env(safe-area-inset-left)',
        },
        '.safe-right': {
          'padding-right': 'env(safe-area-inset-right)',
        },
      });
      
      // Componentes customizados
      addComponents({
        '.glass': {
          'background-color': 'rgba(255, 255, 255, 0.2)',
          'backdrop-filter': 'blur(12px)',
          'border': '1px solid rgba(255, 255, 255, 0.3)',
        },
        '.text-gradient-recife': {
          'background': `linear-gradient(135deg, ${theme('colors.recife.primary')}, ${theme('colors.recife.secondary')})`,
          '-webkit-background-clip': 'text',
          '-webkit-text-fill-color': 'transparent',
          'background-clip': 'text',
        },
      });
    },
    
    // Plugin para adicionar variantes customizadas
    function({ addVariant }) {
      addVariant('hocus', ['&:hover', '&:focus']);
      addVariant('group-hocus', ['.group:hover &', '.group:focus &']);
    },
  ],
  
  // === CONFIGURAÇÕES ADICIONAIS ===
  future: {
    hoverOnlyWhenSupported: true,
  },
  
  experimental: {
    optimizeUniversalDefaults: true,
  },
  
  corePlugins: {
    // Desabilitar plugins não utilizados para reduzir o tamanho do CSS
    preflight: true,
    container: true,
  },
} 