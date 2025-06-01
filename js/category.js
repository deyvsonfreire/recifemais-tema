/**
 * Category Page JavaScript - RecifeMais
 * Funcionalidades específicas para páginas de categoria
 * Inspirado na experiência da Globo.com
 */

(function($) {
    'use strict';

    // Aguarda o DOM estar pronto
    $(document).ready(function() {
        initCategoryFeatures();
    });

    /**
     * Inicializa todas as funcionalidades da página de categoria
     */
    function initCategoryFeatures() {
        initFilterSorting();
        initInfiniteScroll();
        initShareButtons();
        initFollowCategory();
        initLazyLoadImages();
        initReadingProgress();
        initSearchInCategory();
        initViewModeToggle();
    }

    /**
     * Filtros e ordenação
     */
    function initFilterSorting() {
        const sortSelect = $('.category-page select');
        
        if (sortSelect.length) {
            sortSelect.on('change', function() {
                const sortBy = $(this).val();
                const currentUrl = new URL(window.location);
                
                // Adicionar parâmetro de ordenação
                currentUrl.searchParams.set('orderby', sortBy);
                
                // Recarregar página com novo parâmetro
                window.location.href = currentUrl.toString();
            });
        }
    }

    /**
     * Scroll infinito para carregar mais posts
     */
    function initInfiniteScroll() {
        let loading = false;
        let page = 2;
        const postsContainer = $('.all-posts .space-y-6');
        const pagination = $('.pagination-category');
        
        // Verificar se há mais páginas
        const hasMorePages = pagination.find('.next').length > 0;
        
        if (!hasMorePages) return;
        
        // Ocultar paginação padrão
        pagination.hide();
        
        // Adicionar botão "Carregar mais"
        const loadMoreBtn = $(`
            <div class="text-center mt-8">
                <button class="load-more-btn inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Carregar mais notícias
                </button>
            </div>
        `);
        
        postsContainer.after(loadMoreBtn);
        
        // Evento do botão
        loadMoreBtn.on('click', '.load-more-btn', function() {
            if (loading) return;
            
            loading = true;
            const btn = $(this);
            const originalText = btn.html();
            
            // Estado de loading
            btn.html(`
                <svg class="w-4 h-4 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                </svg>
                Carregando...
            `).prop('disabled', true);
            
            // AJAX request
            const categoryId = $('body').data('category-id') || getCategoryFromUrl();
            
            $.ajax({
                url: recifemaisAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_more_category_posts',
                    category_id: categoryId,
                    page: page,
                    nonce: recifemaisAjax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.posts) {
                        // Adicionar novos posts
                        postsContainer.append(response.data.posts);
                        
                        // Incrementar página
                        page++;
                        
                        // Verificar se há mais páginas
                        if (!response.data.has_more) {
                            loadMoreBtn.remove();
                            postsContainer.after('<p class="text-center text-gray-500 mt-8">Todas as notícias foram carregadas</p>');
                        }
                        
                        // Inicializar lazy loading nas novas imagens
                        initLazyLoadImages();
                        
                        // Tracking
                        trackEvent('Load More Posts', 'Category', `Page ${page - 1}`);
                    }
                },
                error: function() {
                    showNotification('Erro ao carregar mais notícias. Tente novamente.', 'error');
                },
                complete: function() {
                    loading = false;
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    }

    /**
     * Botões de compartilhamento
     */
    function initShareButtons() {
        const shareBtn = $('.category-header button[title="Compartilhar categoria"]');
        
        shareBtn.on('click', function() {
            const categoryName = $('.category-header h1').text();
            const categoryUrl = window.location.href;
            
            if (navigator.share) {
                // API nativa de compartilhamento
                navigator.share({
                    title: `${categoryName} - RecifeMais`,
                    text: `Confira as últimas notícias de ${categoryName}`,
                    url: categoryUrl
                });
            } else {
                // Fallback: copiar URL
                copyToClipboard(categoryUrl);
                showNotification('Link copiado para a área de transferência!', 'success');
            }
            
            trackEvent('Share Category', 'Category', categoryName);
        });
    }

    /**
     * Seguir categoria
     */
    function initFollowCategory() {
        const followBtn = $('.category-header button[title="Seguir categoria"]');
        
        followBtn.on('click', function() {
            const categoryId = getCategoryFromUrl();
            const isFollowing = $(this).hasClass('following');
            
            // Toggle visual
            if (isFollowing) {
                $(this).removeClass('following')
                       .attr('title', 'Seguir categoria')
                       .find('svg').replaceWith(recifemaisGetIconSvg('bell', '20', 'currentColor'));
                showNotification('Você não está mais seguindo esta categoria', 'info');
            } else {
                $(this).addClass('following')
                       .attr('title', 'Deixar de seguir')
                       .find('svg').replaceWith(recifemaisGetIconSvg('bell-filled', '20', 'currentColor'));
                showNotification('Agora você está seguindo esta categoria!', 'success');
            }
            
            // AJAX para salvar preferência
            $.ajax({
                url: recifemaisAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'toggle_follow_category',
                    category_id: categoryId,
                    follow: !isFollowing,
                    nonce: recifemaisAjax.nonce
                }
            });
            
            trackEvent('Follow Category', 'Category', isFollowing ? 'Unfollow' : 'Follow');
        });
    }

    /**
     * Lazy loading para imagens
     */
    function initLazyLoadImages() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Progresso de leitura da página
     */
    function initReadingProgress() {
        const progressBar = $('<div class="reading-progress"></div>');
        $('body').append(progressBar);
        
        $(window).on('scroll', updateReadingProgress);
    }

    /**
     * Busca dentro da categoria
     */
    function initSearchInCategory() {
        // Adicionar campo de busca se não existir
        const searchContainer = $(`
            <div class="category-search mb-6">
                <div class="relative">
                    <input type="text" 
                           placeholder="Buscar nesta categoria..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        `);
        
        $('.all-posts h2').after(searchContainer);
        
        // Busca em tempo real
        let searchTimeout;
        searchContainer.find('input').on('input', function() {
            const query = $(this).val();
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (query.length >= 3) {
                    performCategorySearch(query);
                } else if (query.length === 0) {
                    resetCategorySearch();
                }
            }, 300);
        });
    }

    /**
     * Toggle entre modos de visualização
     */
    function initViewModeToggle() {
        const viewToggle = $(`
            <div class="view-mode-toggle flex items-center gap-2">
                <button class="view-mode-btn active" data-mode="list" title="Visualização em lista">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <button class="view-mode-btn" data-mode="grid" title="Visualização em grade">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
            </div>
        `);
        
        $('.all-posts .flex.items-center.justify-between').append(viewToggle);
        
        viewToggle.on('click', '.view-mode-btn', function() {
            const mode = $(this).data('mode');
            const postsContainer = $('.all-posts .space-y-6');
            
            // Toggle active state
            viewToggle.find('.view-mode-btn').removeClass('active');
            $(this).addClass('active');
            
            // Apply view mode
            if (mode === 'grid') {
                postsContainer.removeClass('space-y-6').addClass('grid grid-cols-1 md:grid-cols-2 gap-6');
                postsContainer.find('article .flex').removeClass('md:flex-row').addClass('flex-col');
            } else {
                postsContainer.removeClass('grid grid-cols-1 md:grid-cols-2 gap-6').addClass('space-y-6');
                postsContainer.find('article .flex').addClass('md:flex-row').removeClass('flex-col');
            }
            
            // Salvar preferência
            localStorage.setItem('category-view-mode', mode);
            trackEvent('Change View Mode', 'Category', mode);
        });
        
        // Carregar preferência salva
        const savedMode = localStorage.getItem('category-view-mode');
        if (savedMode) {
            viewToggle.find(`[data-mode="${savedMode}"]`).click();
        }
    }

    /**
     * Funções auxiliares
     */
    function getCategoryFromUrl() {
        const pathParts = window.location.pathname.split('/');
        return pathParts[pathParts.length - 2] || pathParts[pathParts.length - 1];
    }

    function performCategorySearch(query) {
        const categoryId = getCategoryFromUrl();
        const postsContainer = $('.all-posts .space-y-6');
        
        // Mostrar loading
        postsContainer.addClass('category-loading');
        
        $.ajax({
            url: recifemaisAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'search_category_posts',
                query: query,
                category_id: categoryId,
                nonce: recifemaisAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    postsContainer.html(response.data.posts);
                    
                    if (response.data.count === 0) {
                        postsContainer.html(`
                            <div class="text-center py-8">
                                <p class="text-gray-500">Nenhuma notícia encontrada para "${query}"</p>
                            </div>
                        `);
                    }
                }
            },
            complete: function() {
                postsContainer.removeClass('category-loading');
            }
        });
        
        trackEvent('Search in Category', 'Category', query);
    }

    function resetCategorySearch() {
        window.location.reload();
    }

    function updateReadingProgress() {
        const scrollTop = $(window).scrollTop();
        const docHeight = $(document).height() - $(window).height();
        const progress = (scrollTop / docHeight) * 100;
        
        $('.reading-progress').css('width', progress + '%');
    }

    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            // Fallback
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }
    }

    function showNotification(message, type = 'success') {
        const notification = $(`
            <div class="notification notification-${type} fixed top-4 right-4 z-50 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? 
                            '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                            '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
                        }
                    </div>
                    <p class="text-sm text-gray-700">${message}</p>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        // Auto remove
        setTimeout(() => {
            notification.fadeOut(() => notification.remove());
        }, 4000);
    }

    function trackEvent(action, category = 'Category Page', label = '') {
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label
            });
        }
    }

    // Função global para ícones (se não existir)
    window.recifemaisGetIconSvg = window.recifemaisGetIconSvg || function(name, size, color) {
        // Fallback simples
        return `<svg class="w-${size} h-${size}" fill="${color}" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"/></svg>`;
    };

})(jQuery); 