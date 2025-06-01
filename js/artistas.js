/**
 * JavaScript específico para Artistas
 * Funcionalidades para archive e single de artistas
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

(function($) {
    'use strict';

    // Configurações globais
    const ArtistasConfig = {
        debug: window.recifemaisData?.debug || false,
        ajaxUrl: window.recifemaisData?.ajaxUrl || '/wp-admin/admin-ajax.php',
        nonce: window.recifemaisData?.nonce || '',
        apiUrl: window.recifemaisData?.apiUrl || '',
        breakpoints: {
            mobile: 640,
            tablet: 1024,
            desktop: 1280
        }
    };

    /**
     * Classe principal para Archive de Artistas
     */
    class ArtistasArchive {
        constructor() {
            this.init();
        }

        init() {
            this.setupStickyFilters();
            this.setupSearchEnhancements();
            this.setupAnimations();
            this.setupCategoryHovers();
            this.setupFilterTracking();
            
            if (ArtistasConfig.debug) {
                console.log('ArtistasArchive: Inicializado');
            }
        }

        /**
         * Filtros sticky com backdrop blur
         */
        setupStickyFilters() {
            const $filtros = $('.filtros-artistas');
            if (!$filtros.length) return;

            let lastScrollTop = 0;
            const stickyOffset = $filtros.offset().top;

            $(window).on('scroll', () => {
                const scrollTop = $(window).scrollTop();
                
                if (scrollTop > stickyOffset) {
                    $filtros.addClass('sticky');
                    
                    // Adicionar blur baseado na velocidade do scroll
                    const scrollSpeed = Math.abs(scrollTop - lastScrollTop);
                    if (scrollSpeed > 5) {
                        $filtros.css('backdrop-filter', 'blur(20px)');
                    } else {
                        $filtros.css('backdrop-filter', 'blur(10px)');
                    }
                } else {
                    $filtros.removeClass('sticky');
                    $filtros.css('backdrop-filter', 'blur(10px)');
                }
                
                lastScrollTop = scrollTop;
            });
        }

        /**
         * Melhorias na busca com debounce
         */
        setupSearchEnhancements() {
            const $searchInput = $('.filtros-artistas input[name="s"]');
            if (!$searchInput.length) return;

            let searchTimeout;
            
            $searchInput.on('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value;
                
                // Debounce de 300ms
                searchTimeout = setTimeout(() => {
                    this.handleSearchSuggestions(query);
                }, 300);
            });

            // Limpar sugestões ao focar fora
            $searchInput.on('blur', () => {
                setTimeout(() => {
                    $('.search-suggestions').fadeOut(200);
                }, 150);
            });
        }

        /**
         * Sugestões de busca (placeholder para implementação futura)
         */
        handleSearchSuggestions(query) {
            if (query.length < 2) return;
            
            // TODO: Implementar busca AJAX para sugestões
            if (ArtistasConfig.debug) {
                console.log('Busca por:', query);
            }
        }

        /**
         * Animações com Intersection Observer
         */
        setupAnimations() {
            if (!window.IntersectionObserver) return;

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const $element = $(entry.target);
                        
                        // Adicionar delay escalonado para cards
                        if ($element.hasClass('artista-card-archive')) {
                            const index = $element.index();
                            setTimeout(() => {
                                $element.addClass('animate-in');
                            }, index * 100);
                        } else {
                            $element.addClass('animate-in');
                        }
                        
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observar cards de artistas
            $('.artista-card-archive').each(function() {
                observer.observe(this);
            });

            // Observar seções
            $('.generos-musicais .group').each(function() {
                observer.observe(this);
            });
        }

        /**
         * Hovers nas categorias de gêneros
         */
        setupCategoryHovers() {
            $('.generos-musicais .group').on('mouseenter', function() {
                const $this = $(this);
                const $icon = $this.find('.w-16');
                
                // Animação do ícone
                $icon.css('transform', 'scale(1.1)');
                
                // Efeito de brilho
                $this.css('box-shadow', '0 10px 25px rgba(124, 58, 237, 0.15)');
                
            }).on('mouseleave', function() {
                const $this = $(this);
                const $icon = $this.find('.w-16');
                
                $icon.css('transform', 'scale(1)');
                $this.css('box-shadow', '');
            });
        }

        /**
         * Tracking de filtros (Analytics)
         */
        setupFilterTracking() {
            $('.filtros-artistas form').on('submit', (e) => {
                const formData = new FormData(e.target);
                const filters = {};
                
                for (let [key, value] of formData.entries()) {
                    if (value) filters[key] = value;
                }
                
                // Enviar para analytics
                this.trackEvent('filter_applied', {
                    filters: filters,
                    page: 'archive_artistas'
                });
            });
        }

        /**
         * Enviar eventos para analytics
         */
        trackEvent(eventName, data) {
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, data);
            }
            
            if (ArtistasConfig.debug) {
                console.log('Analytics Event:', eventName, data);
            }
        }
    }

    /**
     * Classe principal para Single Artista
     */
    class ArtistasSingle {
        constructor() {
            this.init();
        }

        init() {
            this.setupShareButton();
            this.setupSocialDropdown();
            this.setupContactActions();
            this.setupImageInteractions();
            this.setupScrollAnimations();
            
            if (ArtistasConfig.debug) {
                console.log('ArtistasSingle: Inicializado');
            }
        }

        /**
         * Botão de compartilhamento com Web Share API
         */
        setupShareButton() {
            const $shareBtn = $('#btn-compartilhar-artista');
            if (!$shareBtn.length) return;

            $shareBtn.on('click', async (e) => {
                e.preventDefault();
                
                const shareData = {
                    title: document.title,
                    text: `Conheça ${$('h1').first().text()} - Artista de Pernambuco`,
                    url: window.location.href
                };

                try {
                    // Tentar Web Share API primeiro
                    if (navigator.share) {
                        await navigator.share(shareData);
                        this.trackEvent('share_success', { method: 'native' });
                    } else {
                        // Fallback para clipboard
                        await navigator.clipboard.writeText(window.location.href);
                        this.showNotification('Link copiado para a área de transferência!', 'success');
                        this.trackEvent('share_success', { method: 'clipboard' });
                    }
                } catch (error) {
                    // Fallback final
                    this.showSocialShareModal(shareData);
                    this.trackEvent('share_fallback', { error: error.message });
                }
            });
        }

        /**
         * Dropdown de redes sociais
         */
        setupSocialDropdown() {
            const $btn = $('#btn-redes-sociais');
            const $dropdown = $('#dropdown-redes');
            
            if (!$btn.length || !$dropdown.length) return;

            $btn.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                $dropdown.toggleClass('show');
                
                // Fechar ao clicar fora
                $(document).one('click', () => {
                    $dropdown.removeClass('show');
                });
            });

            // Tracking de cliques nas redes sociais
            $dropdown.find('a').on('click', function() {
                const network = $(this).find('span').text().trim();
                ArtistasCommon.trackEvent('social_click', {
                    network: network,
                    page: 'single_artista'
                });
            });
        }

        /**
         * Ações de contato
         */
        setupContactActions() {
            // Tracking de cliques em links de contato
            $('a[href^="tel:"]').on('click', function() {
                ArtistasCommon.trackEvent('contact_click', {
                    type: 'phone',
                    page: 'single_artista'
                });
            });

            $('a[href^="mailto:"]').on('click', function() {
                ArtistasCommon.trackEvent('contact_click', {
                    type: 'email',
                    page: 'single_artista'
                });
            });

            $('a[target="_blank"]').on('click', function() {
                const url = $(this).attr('href');
                ArtistasCommon.trackEvent('external_link_click', {
                    url: url,
                    page: 'single_artista'
                });
            });
        }

        /**
         * Interações com imagens
         */
        setupImageInteractions() {
            const $heroImage = $('.hero-artista img');
            
            if ($heroImage.length) {
                // Efeito parallax sutil no hero
                $(window).on('scroll', () => {
                    const scrolled = $(window).scrollTop();
                    const rate = scrolled * -0.5;
                    
                    $heroImage.css('transform', `translateY(${rate}px) scale(1.05)`);
                });

                // Preparar lightbox (implementação futura)
                $heroImage.on('click', () => {
                    // TODO: Implementar lightbox para galeria de imagens
                    if (ArtistasConfig.debug) {
                        console.log('Lightbox: Clique na imagem do hero');
                    }
                });
            }
        }

        /**
         * Animações baseadas em scroll
         */
        setupScrollAnimations() {
            if (!window.IntersectionObserver) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('animate-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observar elementos para animação
            $('.artista-meta-info, .artista-relacionado').each(function() {
                observer.observe(this);
            });
        }

        /**
         * Modal de compartilhamento social (fallback)
         */
        showSocialShareModal(shareData) {
            const networks = [
                {
                    name: 'Facebook',
                    url: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareData.url)}`,
                    color: 'bg-blue-600'
                },
                {
                    name: 'Twitter',
                    url: `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareData.text)}&url=${encodeURIComponent(shareData.url)}`,
                    color: 'bg-sky-500'
                },
                {
                    name: 'WhatsApp',
                    url: `https://wa.me/?text=${encodeURIComponent(shareData.text + ' ' + shareData.url)}`,
                    color: 'bg-green-600'
                }
            ];

            let modalHtml = `
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl p-6 max-w-sm w-full">
                        <h3 class="text-lg font-bold mb-4">Compartilhar Artista</h3>
                        <div class="space-y-3">
            `;

            networks.forEach(network => {
                modalHtml += `
                    <a href="${network.url}" target="_blank" 
                       class="flex items-center gap-3 ${network.color} text-white px-4 py-3 rounded-lg hover:opacity-90 transition-opacity">
                        <span class="font-medium">${network.name}</span>
                    </a>
                `;
            });

            modalHtml += `
                        </div>
                        <button class="mt-4 w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors" onclick="this.closest('.fixed').remove()">
                            Fechar
                        </button>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);
        }

        /**
         * Mostrar notificação toast
         */
        showNotification(message, type = 'info') {
            ArtistasCommon.showNotification(message, type);
        }

        /**
         * Tracking de eventos
         */
        trackEvent(eventName, data) {
            ArtistasCommon.trackEvent(eventName, data);
        }
    }

    /**
     * Funcionalidades comuns para artistas
     */
    class ArtistasCommon {
        static init() {
            this.setupLazyLoading();
            this.setupTooltips();
            this.setupAccessibility();
            this.setupKeyboardNavigation();
        }

        /**
         * Lazy loading para imagens
         */
        static setupLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        /**
         * Tooltips dinâmicos
         */
        static setupTooltips() {
            $('[data-tooltip]').each(function() {
                const $this = $(this);
                const text = $this.data('tooltip');
                
                $this.on('mouseenter', function(e) {
                    const tooltip = $(`<div class="tooltip">${text}</div>`);
                    $('body').append(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    tooltip.css({
                        top: rect.top - tooltip.outerHeight() - 5,
                        left: rect.left + (rect.width / 2) - (tooltip.outerWidth() / 2)
                    });
                    
                    tooltip.fadeIn(200);
                });
                
                $this.on('mouseleave', function() {
                    $('.tooltip').fadeOut(200, function() {
                        $(this).remove();
                    });
                });
            });
        }

        /**
         * Melhorias de acessibilidade
         */
        static setupAccessibility() {
            // Adicionar ARIA labels dinâmicos
            $('.artista-card-archive').each(function() {
                const title = $(this).find('h2, h3').first().text();
                $(this).attr('aria-label', `Artista: ${title}`);
            });

            // Melhorar navegação por teclado
            $('.filtros-artistas select, .filtros-artistas input').on('focus', function() {
                $(this).closest('.filtros-artistas').addClass('keyboard-focus');
            }).on('blur', function() {
                $(this).closest('.filtros-artistas').removeClass('keyboard-focus');
            });
        }

        /**
         * Navegação por teclado
         */
        static setupKeyboardNavigation() {
            $(document).on('keydown', (e) => {
                // ESC para fechar dropdowns
                if (e.key === 'Escape') {
                    $('#dropdown-redes').removeClass('show');
                    $('.tooltip').remove();
                }
                
                // Enter/Space para botões customizados
                if ((e.key === 'Enter' || e.key === ' ') && $(e.target).hasClass('btn-custom')) {
                    e.preventDefault();
                    $(e.target).click();
                }
            });
        }

        /**
         * Sistema de notificações toast
         */
        static showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const notification = $(`
                <div class="fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
                    ${message}
                </div>
            `);

            $('body').append(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.removeClass('translate-x-full');
            }, 100);
            
            // Remover após 3 segundos
            setTimeout(() => {
                notification.addClass('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        /**
         * Tracking de eventos para analytics
         */
        static trackEvent(eventName, data) {
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: 'artistas',
                    ...data
                });
            }
            
            // Facebook Pixel
            if (typeof fbq !== 'undefined') {
                fbq('track', 'CustomEvent', {
                    event_name: eventName,
                    ...data
                });
            }
            
            if (ArtistasConfig.debug) {
                console.log('Analytics Event:', eventName, data);
            }
        }

        /**
         * Utilitários de debug
         */
        static debug(message, data = null) {
            if (ArtistasConfig.debug) {
                console.log(`[Artistas Debug] ${message}`, data);
            }
        }
    }

    /**
     * Inicialização quando o DOM estiver pronto
     */
    $(document).ready(() => {
        // Inicializar funcionalidades comuns
        ArtistasCommon.init();
        
        // Inicializar baseado na página atual
        if ($('body').hasClass('post-type-archive-artistas')) {
            new ArtistasArchive();
        }
        
        if ($('body').hasClass('single-artistas')) {
            new ArtistasSingle();
        }
        
        if (ArtistasConfig.debug) {
            console.log('Artistas JS: Totalmente carregado');
        }
    });

    // Expor classes globalmente para uso externo
    window.RecifeMaisArtistas = {
        Archive: ArtistasArchive,
        Single: ArtistasSingle,
        Common: ArtistasCommon,
        Config: ArtistasConfig
    };

})(jQuery); 