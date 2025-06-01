/**
 * JavaScript para Páginas de Eventos
 * Archive e Single Pages
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

(function($) {
    'use strict';

    // Objeto principal para eventos
    const RecifeMaisEventos = {
        
        // Inicialização
        init: function() {
            this.initArchiveFunctionalities();
            this.initSingleFunctionalities();
            this.initCommonFunctionalities();
        },
        
        // Funcionalidades do Archive
        initArchiveFunctionalities: function() {
            if (!$('.archive-eventos').length) return;
            
            this.initStickyFilters();
            this.initFilterAnimations();
            this.initCardAnimations();
            this.initInfiniteScroll();
        },
        
        // Funcionalidades do Single
        initSingleFunctionalities: function() {
            if (!$('.single-evento').length) return;
            
            this.initShareFunctionality();
            this.initCalendarIntegration();
            this.initMapIntegration();
            this.initImageGallery();
        },
        
        // Funcionalidades comuns
        initCommonFunctionalities: function() {
            this.initLazyLoading();
            this.initTooltips();
            this.initAnalytics();
        },
        
        // Filtros sticky no archive
        initStickyFilters: function() {
            const $filtersSection = $('.filtros-eventos');
            if (!$filtersSection.length) return;
            
            const filtersOffset = $filtersSection.offset().top;
            
            $(window).on('scroll', function() {
                const scrollTop = $(window).scrollTop();
                
                if (scrollTop > filtersOffset) {
                    $filtersSection.addClass('scrolled');
                } else {
                    $filtersSection.removeClass('scrolled');
                }
            });
        },
        
        // Animações dos filtros
        initFilterAnimations: function() {
            // Animação suave ao aplicar filtros
            $('.filtros-eventos form').on('submit', function(e) {
                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                
                // Loading state
                $submitBtn.prop('disabled', true).html(`
                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Filtrando...
                `);
            });
            
            // Reset de filtros com animação
            $('.filtros-eventos .bg-gray-100').on('click', function(e) {
                e.preventDefault();
                
                const $cards = $('.evento-card-archive');
                $cards.fadeOut(300, function() {
                    window.location.href = $(e.target).attr('href');
                });
            });
        },
        
        // Animações dos cards
        initCardAnimations: function() {
            // Intersection Observer para animações de entrada
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('fade-in-up');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });
                
                $('.evento-card-archive').each(function() {
                    observer.observe(this);
                });
            }
            
            // Hover effects aprimorados
            $('.evento-card-archive').on('mouseenter', function() {
                $(this).find('img').css('transform', 'scale(1.05)');
            }).on('mouseleave', function() {
                $(this).find('img').css('transform', 'scale(1)');
            });
        },
        
        // Scroll infinito (opcional)
        initInfiniteScroll: function() {
            let loading = false;
            let page = 2;
            const $loadMore = $('.pagination-eventos');
            
            if (!$loadMore.length) return;
            
            $(window).on('scroll', function() {
                if (loading) return;
                
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                
                if (scrollTop + windowHeight >= documentHeight - 1000) {
                    loading = true;
                    
                    // Mostrar loading
                    $loadMore.html(`
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                            <span class="ml-3 text-gray-600">Carregando mais eventos...</span>
                        </div>
                    `);
                    
                    // Simular carregamento (implementar AJAX real conforme necessário)
                    setTimeout(() => {
                        loading = false;
                        page++;
                    }, 2000);
                }
            });
        },
        
        // Funcionalidade de compartilhamento
        initShareFunctionality: function() {
            // Compartilhamento nativo
            window.compartilharEvento = function() {
                const title = document.title;
                const text = $('meta[name="description"]').attr('content') || '';
                const url = window.location.href;
                
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: text,
                        url: url
                    }).catch(console.error);
                } else {
                    // Fallback para clipboard
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(url).then(() => {
                            RecifeMaisEventos.showNotification('Link copiado para a área de transferência!', 'success');
                        }).catch(() => {
                            RecifeMaisEventos.showShareModal(title, url);
                        });
                    } else {
                        RecifeMaisEventos.showShareModal(title, url);
                    }
                }
            };
        },
        
        // Integração com calendário
        initCalendarIntegration: function() {
            window.adicionarCalendario = function() {
                const evento = RecifeMaisEventos.getEventData();
                
                if (!evento.start) {
                    RecifeMaisEventos.showNotification('Dados do evento não encontrados', 'error');
                    return;
                }
                
                // Criar URL do Google Calendar
                const startDate = evento.start.replace(/[-:]/g, '');
                const endDate = evento.end.replace(/[-:]/g, '');
                
                const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE` +
                    `&text=${encodeURIComponent(evento.title)}` +
                    `&dates=${startDate}/${endDate}` +
                    `&location=${encodeURIComponent(evento.location)}` +
                    `&details=${encodeURIComponent(evento.description)}`;
                
                // Abrir em nova aba
                window.open(googleCalendarUrl, '_blank');
                
                // Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'add_to_calendar', {
                        event_category: 'engagement',
                        event_label: evento.title
                    });
                }
            };
        },
        
        // Integração com mapas
        initMapIntegration: function() {
            const $mapContainer = $('#evento-mapa');
            if (!$mapContainer.length) return;
            
            // Verificar se há coordenadas
            const lat = $mapContainer.data('lat');
            const lng = $mapContainer.data('lng');
            
            if (lat && lng) {
                this.initInteractiveMap(lat, lng);
            } else {
                this.initStaticMap();
            }
        },
        
        // Mapa interativo (Google Maps ou OpenStreetMap)
        initInteractiveMap: function(lat, lng) {
            // Implementação básica - pode ser expandida com Google Maps API
            const $mapContainer = $('#evento-mapa');
            
            // Por enquanto, usar um mapa estático melhorado
            $mapContainer.html(`
                <div class="relative w-full h-full bg-gray-200 rounded-lg overflow-hidden">
                    <iframe 
                        src="https://www.openstreetmap.org/export/embed.html?bbox=${lng-0.01},${lat-0.01},${lng+0.01},${lat+0.01}&layer=mapnik&marker=${lat},${lng}"
                        class="w-full h-full border-0"
                        loading="lazy">
                    </iframe>
                    <div class="absolute top-4 right-4">
                        <button onclick="RecifeMaisEventos.openFullscreenMap(${lat}, ${lng})" 
                                class="bg-white p-2 rounded-lg shadow-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);
        },
        
        // Mapa estático
        initStaticMap: function() {
            const $mapContainer = $('#evento-mapa');
            const localNome = $mapContainer.data('local') || 'Local do evento';
            const endereco = $mapContainer.data('endereco') || '';
            
            $mapContainer.html(`
                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">${localNome}</h3>
                        ${endereco ? `<p class="text-gray-600 text-sm">${endereco}</p>` : ''}
                        <button onclick="RecifeMaisEventos.searchLocation('${localNome} ${endereco}')" 
                                class="mt-4 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                            Ver no mapa
                        </button>
                    </div>
                </div>
            `);
        },
        
        // Galeria de imagens
        initImageGallery: function() {
            // Lightbox simples para imagens do evento
            $('.single-evento .prose img').on('click', function() {
                const src = $(this).attr('src');
                const alt = $(this).attr('alt') || '';
                
                RecifeMaisEventos.openLightbox(src, alt);
            });
        },
        
        // Lazy loading de imagens
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('loading-skeleton');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                $('img[data-src]').each(function() {
                    imageObserver.observe(this);
                });
            }
        },
        
        // Tooltips
        initTooltips: function() {
            $('[data-tooltip]').on('mouseenter', function() {
                const text = $(this).data('tooltip');
                const $tooltip = $(`<div class="tooltip">${text}</div>`);
                
                $('body').append($tooltip);
                
                const rect = this.getBoundingClientRect();
                $tooltip.css({
                    top: rect.top - $tooltip.outerHeight() - 10,
                    left: rect.left + (rect.width / 2) - ($tooltip.outerWidth() / 2)
                });
            }).on('mouseleave', function() {
                $('.tooltip').remove();
            });
        },
        
        // Analytics
        initAnalytics: function() {
            // Tracking de eventos importantes
            $('.single-evento a[href*="inscricao"]').on('click', function() {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click_ticket_link', {
                        event_category: 'engagement',
                        event_label: document.title
                    });
                }
            });
            
            $('.single-evento a[href*="maps.google.com"]').on('click', function() {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click_directions', {
                        event_category: 'engagement',
                        event_label: document.title
                    });
                }
            });
        },
        
        // Utilitários
        getEventData: function() {
            return {
                title: document.title,
                start: $('meta[name="event:start_time"]').attr('content') || '',
                end: $('meta[name="event:end_time"]').attr('content') || '',
                location: $('meta[name="event:location"]').attr('content') || '',
                description: $('meta[name="description"]').attr('content') || ''
            };
        },
        
        showNotification: function(message, type = 'info') {
            const $notification = $(`
                <div class="notification notification-${type} fixed top-4 right-4 z-50 bg-white border-l-4 p-4 rounded-lg shadow-lg max-w-sm">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <button class="ml-4 text-gray-400 hover:text-gray-600" onclick="$(this).parent().parent().fadeOut()">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);
            
            // Cores por tipo
            if (type === 'success') {
                $notification.addClass('border-green-500');
            } else if (type === 'error') {
                $notification.addClass('border-red-500');
            } else {
                $notification.addClass('border-blue-500');
            }
            
            $('body').append($notification);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                $notification.fadeOut();
            }, 5000);
        },
        
        showShareModal: function(title, url) {
            const $modal = $(`
                <div class="share-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 class="text-lg font-semibold mb-4">Compartilhar evento</h3>
                        <div class="space-y-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                            <a href="https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                WhatsApp
                            </a>
                        </div>
                        <button onclick="$('.share-modal').remove()" 
                                class="mt-4 w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            `);
            
            $('body').append($modal);
        },
        
        openLightbox: function(src, alt) {
            const $lightbox = $(`
                <div class="lightbox fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90" onclick="$(this).remove()">
                    <div class="relative max-w-4xl max-h-full p-4">
                        <img src="${src}" alt="${alt}" class="max-w-full max-h-full object-contain">
                        <button onclick="$('.lightbox').remove()" 
                                class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);
            
            $('body').append($lightbox);
        },
        
        openFullscreenMap: function(lat, lng) {
            window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
        },
        
        searchLocation: function(query) {
            window.open(`https://www.google.com/maps/search/${encodeURIComponent(query)}`, '_blank');
        }
    };

    // Inicializar quando o documento estiver pronto
    $(document).ready(function() {
        RecifeMaisEventos.init();
    });

    // Expor globalmente para uso em inline scripts
    window.RecifeMaisEventos = RecifeMaisEventos;

})(jQuery); 