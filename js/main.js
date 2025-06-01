/**
 * RecifeMais Tema - JavaScript Principal
 * 
 * JavaScript focado apenas na apresentação e UX do tema,
 * utilizando as funcionalidades do plugin RecifeMais Core
 * 
 * @package RecifeMais
 * @since 2.0.0
 */

(function($) {
    'use strict';

    const RecifeMaisTema = {
        
        init: function() {
            this.initMobileMenu();
            this.initSmoothScroll();
            this.initLazyLoading();
            this.initShareButtons();
            this.initAccessibility();
            this.initScrollEffects();
            this.initEventFilters();
            this.initNewsletterForm();
        },
        
        /**
         * Menu móvel (apenas UX/animações)
         */
        initMobileMenu: function() {
            const $toggle = $('.mobile-menu-toggle');
            const $menu = $('.mobile-menu');
            const $close = $('.mobile-menu-close');
            const $overlay = $('.mobile-menu-overlay');
            
            $toggle.on('click', function(e) {
                e.preventDefault();
                $menu.addClass('active');
                $('body').addClass('menu-open');
            });
            
            $close.add($overlay).on('click', function(e) {
                e.preventDefault();
                $menu.removeClass('active');
                $('body').removeClass('menu-open');
            });
            
            // Fechar com ESC
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $menu.hasClass('active')) {
                    $menu.removeClass('active');
                    $('body').removeClass('menu-open');
                }
            });
        },
        
        /**
         * Scroll suave para âncoras
         */
        initSmoothScroll: function() {
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 500);
                }
            });
        },
        
        /**
         * Lazy loading para imagens
         */
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
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
        },
        
        /**
         * Botões de compartilhamento
         */
        initShareButtons: function() {
            $('.share-btn').on('click', function(e) {
                e.preventDefault();
                
                const title = document.title;
                const url = window.location.href;
                
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    });
                } else {
                    // Fallback: copiar URL
                    navigator.clipboard.writeText(url).then(() => {
                        // Feedback visual
                        const $btn = $(this);
                        const originalText = $btn.text();
                        $btn.text('Link copiado!');
                        setTimeout(() => $btn.text(originalText), 2000);
                    });
                }
            });
        },
        
        /**
         * Melhorias de acessibilidade
         */
        initAccessibility: function() {
            // Focus trap em modais
            $('.modal').on('keydown', function(e) {
                if (e.key === 'Tab') {
                    const focusableElements = $(this).find('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                    const firstElement = focusableElements.first();
                    const lastElement = focusableElements.last();
                    
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement[0]) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement[0]) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
            
            // Skip links
            $('.skip-link').on('click', function(e) {
                e.preventDefault();
                const target = $($(this).attr('href'));
                if (target.length) {
                    target.attr('tabindex', '-1').focus();
                }
            });
        },
        
        /**
         * Efeitos de scroll
         */
        initScrollEffects: function() {
            let ticking = false;
            
            function updateScrollEffects() {
                const scrollTop = window.pageYOffset;
                
                // Header fixo
                if (scrollTop > 100) {
                    $('body').addClass('scrolled');
                } else {
                    $('body').removeClass('scrolled');
                }
                
                // Botão voltar ao topo
                if (scrollTop > 500) {
                    $('.back-to-top').addClass('visible');
                } else {
                    $('.back-to-top').removeClass('visible');
                }
                
                ticking = false;
            }
            
            $(window).on('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(updateScrollEffects);
                    ticking = true;
                }
            });
            
            // Botão voltar ao topo
            $('.back-to-top').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: 0 }, 500);
            });
        },
        
        /**
         * Filtros de eventos na front-page
         */
        initEventFilters: function() {
            const $filterBtns = $('.filter-event-btn');
            const $eventCards = $('.event-card');

            if ($filterBtns.length === 0 || $eventCards.length === 0) return;

            $filterBtns.on('click', function(e) {
                e.preventDefault();
                
                const $btn = $(this);
                const filter = $btn.data('filter');
                
                // Atualiza estado dos botões
                $filterBtns.removeClass('active bg-recife-primary text-white')
                          .addClass('bg-white text-recife-gray-700 border border-recife-gray-300');
                
                $btn.removeClass('bg-white text-recife-gray-700 border border-recife-gray-300')
                   .addClass('active bg-recife-primary text-white');

                // Aplica filtro
                RecifeMaisTema.filterEvents(filter, $eventCards);
            });
        },

        filterEvents: function(filter, $eventCards) {
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            
            // Calcula datas
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            const weekEnd = new Date(today);
            weekEnd.setDate(today.getDate() + 7);
            const weekEndStr = weekEnd.toISOString().split('T')[0];
            
            // Próximo sábado
            const nextSaturday = new Date(today);
            nextSaturday.setDate(today.getDate() + (6 - today.getDay()));
            const saturdayStr = nextSaturday.toISOString().split('T')[0];
            
            // Próximo domingo
            const nextSunday = new Date(nextSaturday);
            nextSunday.setDate(nextSaturday.getDate() + 1);
            const sundayStr = nextSunday.toISOString().split('T')[0];

            $eventCards.each(function() {
                const $card = $(this);
                const eventDate = $card.data('event-date');
                let show = false;

                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'today':
                        show = eventDate === todayStr;
                        break;
                    case 'week':
                        show = eventDate >= todayStr && eventDate <= weekEndStr;
                        break;
                    case 'weekend':
                        show = eventDate === saturdayStr || eventDate === sundayStr;
                        break;
                }

                if (show) {
                    $card.fadeIn(300);
                } else {
                    $card.fadeOut(300);
                }
            });

            // Mostra mensagem se nenhum evento for encontrado
            setTimeout(() => {
                const visibleCards = $eventCards.filter(':visible').length;
                const $noResults = $('#no-events-message');
                
                if (visibleCards === 0) {
                    if ($noResults.length === 0) {
                        $('#events-grid').append(`
                            <div id="no-events-message" class="col-span-full text-center py-8">
                                <div class="text-recife-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-recife-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Nenhum evento encontrado</p>
                                    <p class="text-sm">Tente outro filtro ou volte mais tarde</p>
                                </div>
                            </div>
                        `);
                    } else {
                        $noResults.fadeIn(300);
                    }
                } else {
                    $noResults.fadeOut(300);
                }
            }, 350);
        },
        
        /**
         * Inicialização do formulário de newsletter
         */
        initNewsletterForm: function() {
            // Implemente a lógica para inicializar o formulário de newsletter
        }
    };
    
    /**
     * Extensões para funcionalidades do plugin
     */
    const RecifeMaisPluginExtensions = {
        
        init: function() {
            // Só inicializa se o plugin estiver ativo
            if (typeof recifemais_ajax !== 'undefined') {
                this.enhanceDropdowns();
                this.enhanceFilters();
            }
        },
        
        /**
         * Melhorias visuais para dropdowns do plugin
         */
        enhanceDropdowns: function() {
            $('.recifemais-dropdown').each(function() {
                const $dropdown = $(this);
                
                // Adicionar animações
                $dropdown.on('change', function() {
                    $(this).addClass('loading');
                });
                
                // Remover loading quando atualizado
                $(document).on('recifemais:dropdown:updated', function(e, dropdown) {
                    $(dropdown).removeClass('loading');
                });
            });
        },
        
        /**
         * Melhorias visuais para filtros do plugin
         */
        enhanceFilters: function() {
            // Feedback visual durante filtros AJAX
            $(document).on('recifemais:filters:loading', function() {
                $('.filtros-container').addClass('loading');
            });
            
            $(document).on('recifemais:filters:loaded', function() {
                $('.filtros-container').removeClass('loading');
            });
            
            // Contadores de resultados
            $(document).on('recifemais:filters:results', function(e, data) {
                $('.results-count').text(data.total + ' resultados encontrados');
            });
        }
    };
    
    // Inicialização quando documento estiver pronto
    $(document).ready(function() {
        RecifeMaisTema.init();
        RecifeMaisPluginExtensions.init();
        
        // Debug: confirma que o JS está carregando
        console.log('RecifeMais Tema JS carregado com sucesso!');
        
        // Toggle do menu mobile
        $('.mobile-menu-toggle').on('click', function() {
            const mobileMenu = $('#mobile-menu');
            const isHidden = mobileMenu.hasClass('hidden');
            
            if (isHidden) {
                mobileMenu.removeClass('hidden');
                $(this).attr('aria-expanded', 'true');
            } else {
                mobileMenu.addClass('hidden');
                $(this).attr('aria-expanded', 'false');
            }
        });

        // Fechar menu mobile ao clicar fora
        $(document).on('click', function(e) {
            const mobileMenu = $('#mobile-menu');
            const toggleButton = $('.mobile-menu-toggle');
            
            if (!mobileMenu.is(e.target) && 
                !mobileMenu.has(e.target).length && 
                !toggleButton.is(e.target) && 
                !toggleButton.has(e.target).length) {
                mobileMenu.addClass('hidden');
                toggleButton.attr('aria-expanded', 'false');
            }
        });

        // Smooth scroll para links internos
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });

        // Lazy loading para imagens (fallback se não houver suporte nativo)
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
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

        // Adicionar classe de scroll ao header
        $(window).on('scroll', function() {
            const header = $('#masthead');
            if ($(window).scrollTop() > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });

        // Animações de entrada para elementos
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                animationObserver.observe(el);
            });
        }
    });
    
})(jQuery); 