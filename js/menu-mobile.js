/**
 * Menu Mobile - RecifeMais Tema
 * Controla a abertura/fechamento do menu mobile com acessibilidade
 */

(function() {
    'use strict';

    // Elementos do DOM
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const body = document.body;

    // Verifica se os elementos existem
    if (!mobileMenuToggle || !mobileMenu) {
        return;
    }

    /**
     * Abre o menu mobile
     */
    function openMobileMenu() {
        mobileMenu.classList.add('active');
        mobileMenuToggle.setAttribute('aria-expanded', 'true');
        mobileMenu.setAttribute('aria-hidden', 'false');
        body.style.overflow = 'hidden'; // Previne scroll do body
        
        // Foca no botão de fechar para acessibilidade
        if (mobileMenuClose) {
            setTimeout(() => {
                mobileMenuClose.focus();
            }, 300);
        }
    }

    /**
     * Fecha o menu mobile
     */
    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
        body.style.overflow = ''; // Restaura scroll do body
        
        // Retorna foco para o botão de toggle
        mobileMenuToggle.focus();
    }

    /**
     * Toggle do menu mobile
     */
    function toggleMobileMenu() {
        if (mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }

    // Event Listeners
    mobileMenuToggle.addEventListener('click', toggleMobileMenu);

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // Fecha menu com tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // Fecha menu ao redimensionar para desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // Trap focus dentro do menu mobile quando aberto
    document.addEventListener('keydown', function(e) {
        if (!mobileMenu.classList.contains('active') || e.key !== 'Tab') {
            return;
        }

        const focusableElements = mobileMenu.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else {
            // Tab
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    });

})(); 