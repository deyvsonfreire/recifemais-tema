/**
 * Header JavaScript - RecifeMais
 * Funcionalidades do header: busca dropdown e menu mobile
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Aguardar DOM carregar
    document.addEventListener('DOMContentLoaded', function() {
        
        // ===== BUSCA DROPDOWN =====
        const searchToggle = document.getElementById('search-toggle');
        const searchDropdown = document.getElementById('search-dropdown');
        const searchInput = searchDropdown?.querySelector('input[type="search"]');

        if (searchToggle && searchDropdown) {
            // Toggle do dropdown de busca
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isVisible = !searchDropdown.classList.contains('hidden');
                
                if (isVisible) {
                    closeSearchDropdown();
                } else {
                    openSearchDropdown();
                }
            });

            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (!searchDropdown.contains(e.target) && !searchToggle.contains(e.target)) {
                    closeSearchDropdown();
                }
            });

            // Fechar dropdown com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSearchDropdown();
                }
            });

            // Focar no input quando abrir
            function openSearchDropdown() {
                searchDropdown.classList.remove('hidden');
                searchToggle.setAttribute('aria-expanded', 'true');
                
                // Focar no input após animação
                setTimeout(() => {
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 100);
            }

            function closeSearchDropdown() {
                searchDropdown.classList.add('hidden');
                searchToggle.setAttribute('aria-expanded', 'false');
            }
        }

        // ===== MENU MOBILE =====
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuToggle && mobileMenu) {
            // Toggle do menu mobile
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                const isVisible = !mobileMenu.classList.contains('hidden');
                
                if (isVisible) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            // Fechar menu mobile ao redimensionar para desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    closeMobileMenu();
                }
            });

            // Fechar menu mobile com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            function openMobileMenu() {
                mobileMenu.classList.remove('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
                
                // Alterar ícone para X
                const icon = mobileMenuToggle.querySelector('svg');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                }
                
                // Prevenir scroll do body
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileMenu.classList.add('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                
                // Restaurar ícone de hambúrguer
                const icon = mobileMenuToggle.querySelector('svg');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                }
                
                // Restaurar scroll do body
                document.body.style.overflow = '';
            }
        }

        // ===== HEADER SCROLL EFFECT =====
        const header = document.getElementById('masthead');
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateHeader() {
            const currentScrollY = window.scrollY;
            
            if (header) {
                if (currentScrollY > 100) {
                    // Adicionar sombra quando rolar
                    header.classList.add('shadow-lg');
                    header.classList.remove('shadow-sm');
                } else {
                    // Remover sombra no topo
                    header.classList.remove('shadow-lg');
                    header.classList.add('shadow-sm');
                }

                // Hide/show header baseado na direção do scroll
                if (currentScrollY > lastScrollY && currentScrollY > 200) {
                    // Scrolling down - hide header
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up - show header
                    header.style.transform = 'translateY(0)';
                }
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }

        // Throttle scroll events
        window.addEventListener('scroll', requestTick, { passive: true });

        // ===== BUSCA AVANÇADA =====
        const searchForm = searchDropdown?.querySelector('.search-form');
        
        if (searchForm) {
            // Adicionar funcionalidade de busca por categoria
            const categoryLinks = searchDropdown.querySelectorAll('a[href*="/eventos_festivais"], a[href*="/lugares"], a[href*="/artistas"]');
            
            categoryLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const searchTerm = searchInput?.value.trim();
                    const categoryUrl = this.getAttribute('href');
                    
                    if (searchTerm) {
                        // Buscar dentro da categoria específica
                        window.location.href = `${categoryUrl}?s=${encodeURIComponent(searchTerm)}`;
                    } else {
                        // Ir para a página da categoria
                        window.location.href = categoryUrl;
                    }
                });
            });

            // Melhorar UX do formulário de busca
            searchForm.addEventListener('submit', function(e) {
                const searchTerm = searchInput?.value.trim();
                
                if (!searchTerm) {
                    e.preventDefault();
                    searchInput?.focus();
                    
                    // Feedback visual
                    searchInput?.classList.add('border-red-300', 'focus:ring-red-500');
                    setTimeout(() => {
                        searchInput?.classList.remove('border-red-300', 'focus:ring-red-500');
                    }, 2000);
                }
            });
        }

        // ===== ACESSIBILIDADE =====
        
        // Navegação por teclado no menu
        const menuItems = document.querySelectorAll('#primary-menu a, #mobile-primary-menu a');
        
        menuItems.forEach((item, index) => {
            item.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const nextItem = menuItems[index + 1];
                    if (nextItem) {
                        nextItem.focus();
                    }
                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const prevItem = menuItems[index - 1];
                    if (prevItem) {
                        prevItem.focus();
                    }
                }
            });
        });

        // ===== ANALYTICS =====
        
        // Track search interactions
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'search', {
                        'search_term': searchInput?.value || '',
                        'event_category': 'Header',
                        'event_label': 'Header Search'
                    });
                }
            });
        }

        // Track menu interactions
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        'event_category': 'Navigation',
                        'event_label': this.textContent.trim(),
                        'value': 1
                    });
                }
            });
        });

        // ===== INICIALIZAÇÃO =====
        console.log('RecifeMais Header: JavaScript inicializado com sucesso');
    });

})(); 