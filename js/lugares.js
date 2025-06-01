/**
 * JavaScript Específico para Lugares
 * Funcionalidades para archive-lugares.php e single-lugares.php
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

(function($) {
    'use strict';

    // Configurações globais
    const LugaresConfig = {
        debug: window.recifemais_lugares?.debug || false,
        ajaxUrl: window.recifemais_lugares?.ajax_url || '/wp-admin/admin-ajax.php',
        nonce: window.recifemais_lugares?.nonce || '',
        apiUrl: window.recifemais_lugares?.api_url || '',
        mapsApiKey: window.recifemais_lugares?.maps_api_key || ''
    };

    // Utilitários
    const Utils = {
        log: function(message, data = null) {
            if (LugaresConfig.debug) {
                console.log('[RecifeMais Lugares]', message, data);
            }
        },

        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        showNotification: function(message, type = 'info') {
            // Sistema simples de notificações
            const notification = $(`
                <div class="lugares-notification lugares-notification--${type} fixed top-4 right-4 z-50 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}
                        </div>
                        <div class="flex-1 text-sm text-gray-700">${message}</div>
                        <button class="flex-shrink-0 text-gray-400 hover:text-gray-600" onclick="$(this).closest('.lugares-notification').remove()">
                            ✕
                        </button>
                    </div>
                </div>
            `);

            $('body').append(notification);

            // Auto-remover após 5 segundos
            setTimeout(() => {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };

    // Classe principal para Archive
    class LugaresArchive {
        constructor() {
            this.init();
        }

        init() {
            Utils.log('Inicializando LugaresArchive');
            
            this.setupStickyFilters();
            this.setupFilterAnimations();
            this.setupInfiniteScroll();
            this.setupSearchEnhancements();
            this.setupCategoryHovers();
        }

        setupStickyFilters() {
            const $filtros = $('.filtros-lugares');
            if (!$filtros.length) return;

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $filtros.removeClass('scrolled');
                        } else {
                            $filtros.addClass('scrolled');
                        }
                    });
                },
                { threshold: 0.1 }
            );

            const heroSection = document.querySelector('.hero-lugares');
            if (heroSection) {
                observer.observe(heroSection);
            }
        }

        setupFilterAnimations() {
            // Animação dos cards conforme aparecem na tela
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 100);
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.lugar-card-archive').forEach(card => {
                cardObserver.observe(card);
            });
        }

        setupInfiniteScroll() {
            // Preparação para scroll infinito (implementação futura)
            const $loadMoreBtn = $('.load-more-lugares');
            if (!$loadMoreBtn.length) return;

            $loadMoreBtn.on('click', (e) => {
                e.preventDefault();
                this.loadMoreLugares();
            });
        }

        loadMoreLugares() {
            // Implementação futura do carregamento de mais lugares
            Utils.log('Carregando mais lugares...');
            Utils.showNotification('Funcionalidade em desenvolvimento', 'info');
        }

        setupSearchEnhancements() {
            const $searchInput = $('.filtros-lugares input[name="s"]');
            if (!$searchInput.length) return;

            // Debounced search
            const debouncedSearch = Utils.debounce(() => {
                const query = $searchInput.val();
                if (query.length >= 3) {
                    this.performSearch(query);
                }
            }, 500);

            $searchInput.on('input', debouncedSearch);

            // Search suggestions (implementação futura)
            $searchInput.on('focus', () => {
                Utils.log('Search input focused - could show suggestions');
            });
        }

        performSearch(query) {
            Utils.log('Performing search for:', query);
            // Implementação futura de busca em tempo real
        }

        setupCategoryHovers() {
            $('.categorias-lugares .grid > a').each(function() {
                const $this = $(this);
                
                $this.on('mouseenter', function() {
                    $(this).find('.w-16').addClass('scale-110');
                });

                $this.on('mouseleave', function() {
                    $(this).find('.w-16').removeClass('scale-110');
                });
            });
        }
    }

    // Classe principal para Single
    class LugaresSingle {
        constructor() {
            this.init();
        }

        init() {
            Utils.log('Inicializando LugaresSingle');
            
            this.setupShareFunctionality();
            this.setupDirectionsDropdown();
            this.setupMapInteractions();
            this.setupImageLightbox();
            this.setupContactActions();
            this.setupAnalytics();
        }

        setupShareFunctionality() {
            const $shareBtn = $('#btn-compartilhar');
            if (!$shareBtn.length) return;

            $shareBtn.on('click', (e) => {
                e.preventDefault();
                this.handleShare();
            });
        }

        handleShare() {
            const shareData = {
                title: document.title,
                text: `Confira este lugar incrível: ${document.title}`,
                url: window.location.href
            };

            if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
                navigator.share(shareData)
                    .then(() => {
                        Utils.log('Compartilhamento realizado com sucesso');
                        Utils.showNotification('Compartilhado com sucesso!', 'success');
                    })
                    .catch((error) => {
                        Utils.log('Erro no compartilhamento:', error);
                        this.fallbackShare();
                    });
            } else {
                this.fallbackShare();
            }
        }

        fallbackShare() {
            // Fallback para navegadores sem suporte ao Web Share API
            const url = window.location.href;
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url)
                    .then(() => {
                        Utils.showNotification('Link copiado para a área de transferência!', 'success');
                    })
                    .catch(() => {
                        this.showShareModal();
                    });
            } else {
                this.showShareModal();
            }
        }

        showShareModal() {
            const shareModal = $(`
                <div class="lugares-share-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Compartilhar Local</h3>
                        <div class="space-y-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                📘 Compartilhar no Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(document.title)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                                🐦 Compartilhar no Twitter
                            </a>
                            <a href="https://wa.me/?text=${encodeURIComponent(document.title + ' - ' + window.location.href)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                📱 Compartilhar no WhatsApp
                            </a>
                        </div>
                        <button class="mt-4 w-full p-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" 
                                onclick="$(this).closest('.lugares-share-modal').remove()">
                            Fechar
                        </button>
                    </div>
                </div>
            `);

            $('body').append(shareModal);

            // Fechar ao clicar fora
            shareModal.on('click', function(e) {
                if (e.target === this) {
                    $(this).remove();
                }
            });
        }

        setupDirectionsDropdown() {
            const $btnDirecoes = $('#btn-direcoes');
            const $dropdown = $('#dropdown-direcoes');

            if (!$btnDirecoes.length || !$dropdown.length) return;

            $btnDirecoes.on('click', (e) => {
                e.preventDefault();
                $dropdown.toggleClass('hidden');
            });

            // Fechar ao clicar fora
            $(document).on('click', (e) => {
                if (!$btnDirecoes.is(e.target) && !$btnDirecoes.has(e.target).length &&
                    !$dropdown.is(e.target) && !$dropdown.has(e.target).length) {
                    $dropdown.addClass('hidden');
                }
            });

            // Analytics para cliques em direções
            $dropdown.find('a').on('click', function() {
                const service = $(this).text().trim();
                Utils.log('Direção solicitada:', service);
                
                // Google Analytics (se disponível)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'directions_click', {
                        'service': service,
                        'lugar_id': $('body').data('lugar-id') || 'unknown'
                    });
                }
            });
        }

        setupMapInteractions() {
            const $mapContainer = $('#lugar-mapa-principal');
            if (!$mapContainer.length) return;

            // Placeholder interativo por enquanto
            $mapContainer.on('click', () => {
                Utils.showNotification('Mapa interativo em desenvolvimento. Use os botões de navegação.', 'info');
            });

            // Preparação para integração futura com Google Maps
            if (LugaresConfig.mapsApiKey) {
                this.initializeGoogleMaps();
            }
        }

        initializeGoogleMaps() {
            // Implementação futura do Google Maps
            Utils.log('Google Maps API Key disponível, preparando integração...');
        }

        setupImageLightbox() {
            // Lightbox para imagens (implementação futura)
            $('.single-lugar img').on('click', function() {
                const src = $(this).attr('src');
                const alt = $(this).attr('alt');
                
                Utils.log('Image clicked:', { src, alt });
                // Implementar lightbox aqui
            });
        }

        setupContactActions() {
            // Analytics para ações de contato
            $('a[href^="tel:"]').on('click', function() {
                const phone = $(this).attr('href').replace('tel:', '');
                Utils.log('Phone call initiated:', phone);
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'phone_call', {
                        'phone_number': phone,
                        'lugar_id': $('body').data('lugar-id') || 'unknown'
                    });
                }
            });

            $('a[href^="mailto:"]').on('click', function() {
                const email = $(this).attr('href').replace('mailto:', '');
                Utils.log('Email initiated:', email);
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'email_click', {
                        'email': email,
                        'lugar_id': $('body').data('lugar-id') || 'unknown'
                    });
                }
            });

            $('a[href^="http"]').on('click', function() {
                const url = $(this).attr('href');
                Utils.log('External link clicked:', url);
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'external_link', {
                        'url': url,
                        'lugar_id': $('body').data('lugar-id') || 'unknown'
                    });
                }
            });
        }

        setupAnalytics() {
            // Page view tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_view', {
                    'page_title': document.title,
                    'page_location': window.location.href,
                    'content_type': 'lugar'
                });
            }

            // Scroll depth tracking
            let maxScroll = 0;
            const trackScrollDepth = Utils.debounce(() => {
                const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
                
                if (scrollPercent > maxScroll && scrollPercent % 25 === 0) {
                    maxScroll = scrollPercent;
                    
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            'scroll_percent': scrollPercent,
                            'lugar_id': $('body').data('lugar-id') || 'unknown'
                        });
                    }
                }
            }, 500);

            $(window).on('scroll', trackScrollDepth);
        }
    }

    // Classe para funcionalidades comuns
    class LugaresCommon {
        constructor() {
            this.init();
        }

        init() {
            Utils.log('Inicializando LugaresCommon');
            
            this.setupLazyLoading();
            this.setupTooltips();
            this.setupAccessibility();
        }

        setupLazyLoading() {
            // Lazy loading para imagens
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.dataset.src;
                            
                            if (src) {
                                img.src = src;
                                img.removeAttribute('data-src');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        setupTooltips() {
            // Tooltips simples
            $('[data-tooltip]').each(function() {
                const $this = $(this);
                const tooltipText = $this.data('tooltip');
                
                $this.on('mouseenter', function() {
                    const tooltip = $(`<div class="lugares-tooltip fixed z-50 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg pointer-events-none">${tooltipText}</div>`);
                    $('body').append(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    tooltip.css({
                        top: rect.top - tooltip.outerHeight() - 8,
                        left: rect.left + (rect.width / 2) - (tooltip.outerWidth() / 2)
                    });
                });

                $this.on('mouseleave', function() {
                    $('.lugares-tooltip').remove();
                });
            });
        }

        setupAccessibility() {
            // Melhorias de acessibilidade
            
            // Navegação por teclado
            $('.lugar-card-archive, .lugar-relacionado').attr('tabindex', '0');
            
            $('.lugar-card-archive, .lugar-relacionado').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const link = $(this).find('a').first();
                    if (link.length) {
                        window.location.href = link.attr('href');
                    }
                }
            });

            // Skip links
            if (!$('.skip-link').length) {
                $('body').prepend(`
                    <a href="#main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-lg z-50">
                        Pular para o conteúdo principal
                    </a>
                `);
            }
        }
    }

    // Inicialização
    $(document).ready(function() {
        Utils.log('DOM ready, inicializando módulos de lugares');

        // Sempre inicializar funcionalidades comuns
        new LugaresCommon();

        // Inicializar baseado na página atual
        if ($('.archive-lugares').length) {
            new LugaresArchive();
        }

        if ($('.single-lugar').length) {
            new LugaresSingle();
        }

        Utils.log('Módulos de lugares inicializados com sucesso');
    });

    // Expor para uso global se necessário
    window.RecifeMaisLugares = {
        Utils,
        LugaresArchive,
        LugaresSingle,
        LugaresCommon
    };

})(jQuery); 