/**
 * Filtros por Meta Fields - JavaScript
 * Sistema de filtros dinâmicos para CPTs usando meta fields
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

(function($) {
    'use strict';

    const RecifeMaisFilters = {
        // Configurações
        config: {
            apiUrl: '/wp-json/recifemais/v1/',
            debounceTime: 300,
            animationDuration: 300
        },

        // Estado atual dos filtros
        currentFilters: {},
        currentPostType: '',
        isLoading: false,

        /**
         * Inicialização
         */
        init: function() {
            this.bindEvents();
            this.initializeState();
            this.setupUrlHandling();
        },

        /**
         * Vincula eventos
         */
        bindEvents: function() {
            const self = this;

            // Filtros rápidos
            $(document).on('click', '.filter-btn', function(e) {
                e.preventDefault();
                self.handleQuickFilter($(this));
            });

            // Toggle dos filtros avançados
            $(document).on('click', '.advanced-toggle', function(e) {
                e.preventDefault();
                self.toggleAdvancedFilters($(this));
            });

            // Campos de filtro avançado
            $(document).on('change', '#meta-fields-filters select, #meta-fields-filters input[type="checkbox"]', 
                self.debounce(function() {
                    self.handleFilterChange();
                }, self.config.debounceTime)
            );

            // Botões de ação
            $(document).on('click', '#apply-filters', function(e) {
                e.preventDefault();
                self.applyFilters();
            });

            $(document).on('click', '#clear-filters', function(e) {
                e.preventDefault();
                self.clearFilters();
            });

            // Remover filtros ativos
            $(document).on('click', '.active-filter-remove', function(e) {
                e.preventDefault();
                self.removeActiveFilter($(this));
            });

            // Paginação AJAX
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                self.loadPage($(this).attr('href'));
            });
        },

        /**
         * Inicializa o estado dos filtros
         */
        initializeState: function() {
            const $container = $('.filters-meta-fields');
            if ($container.length) {
                this.currentPostType = $container.data('post-type');
                this.loadFiltersFromUrl();
            }
        },

        /**
         * Gerencia filtros rápidos
         */
        handleQuickFilter: function($btn) {
            // Remove estado ativo de outros botões
            $('.filter-btn').removeClass('active').removeClass(function(index, css) {
                return (css.match(/\bbg-\S+/g) || []).join(' ');
            }).addClass('bg-white text-recife-gray-700 border border-recife-gray-300');

            // Ativa o botão clicado
            const colorClass = this.getColorClass();
            $btn.addClass('active').removeClass('bg-white text-recife-gray-700 border border-recife-gray-300')
                .addClass(`bg-${colorClass} text-white`);

            // Aplica o filtro
            if ($btn.data('filter') === 'all') {
                this.clearFilters(false); // Não recarregar, só limpar
            } else {
                this.currentFilters = {};
                this.currentFilters[$btn.data('filter')] = [$btn.data('value')];
                this.applyFilters();
            }
        },

        /**
         * Toggle dos filtros avançados
         */
        toggleAdvancedFilters: function($toggle) {
            const $content = $toggle.next('.advanced-content');
            const $icon = $toggle.find('svg');

            if ($content.hasClass('hidden')) {
                $content.removeClass('hidden').hide().slideDown(this.config.animationDuration);
                $icon.addClass('rotate-180');
            } else {
                $content.slideUp(this.config.animationDuration, function() {
                    $content.addClass('hidden');
                });
                $icon.removeClass('rotate-180');
            }
        },

        /**
         * Gerencia mudanças nos filtros avançados
         */
        handleFilterChange: function() {
            if (!this.isLoading) {
                this.collectFilters();
                this.updateActiveFiltersDisplay();
            }
        },

        /**
         * Coleta todos os filtros ativos
         */
        collectFilters: function() {
            const self = this;
            this.currentFilters = {};

            $('#meta-fields-filters').find('select, input[type="checkbox"]:checked').each(function() {
                const $field = $(this);
                const name = $field.attr('name');
                const value = $field.val();

                if (value && value !== '') {
                    // Remove [] do nome para checkboxes
                    const cleanName = name.replace('[]', '');
                    
                    if (!self.currentFilters[cleanName]) {
                        self.currentFilters[cleanName] = [];
                    }
                    
                    if (Array.isArray(value)) {
                        self.currentFilters[cleanName] = self.currentFilters[cleanName].concat(value);
                    } else {
                        if (!self.currentFilters[cleanName].includes(value)) {
                            self.currentFilters[cleanName].push(value);
                        }
                    }
                }
            });
        },

        /**
         * Aplica os filtros
         */
        applyFilters: function() {
            if (this.isLoading) return;

            this.isLoading = true;
            this.showLoading();
            this.updateUrl();

            const self = this;
            const params = this.buildApiParams();

            $.ajax({
                url: this.config.apiUrl + 'posts/filter',
                method: 'GET',
                data: params,
                success: function(response) {
                    self.renderResults(response);
                    self.updateResultsCount(response.found_posts);
                    self.updateActiveFiltersDisplay();
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao aplicar filtros:', error);
                    self.showError('Erro ao carregar os resultados. Tente novamente.');
                },
                complete: function() {
                    self.isLoading = false;
                    self.hideLoading();
                }
            });
        },

        /**
         * Constrói parâmetros para API
         */
        buildApiParams: function() {
            const params = {
                post_type: this.currentPostType,
                per_page: 12
            };

            // Adiciona filtros de meta fields
            Object.keys(this.currentFilters).forEach(key => {
                if (this.currentFilters[key].length > 0) {
                    params[key] = this.currentFilters[key].join(',');
                }
            });

            return params;
        },

        /**
         * Renderiza os resultados
         */
        renderResults: function(response) {
            const $container = this.getResultsContainer();
            
            if (response.posts && response.posts.length > 0) {
                let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">';
                
                response.posts.forEach(post => {
                    html += this.renderPostCard(post);
                });
                
                html += '</div>';
                
                // Adiciona paginação se necessário
                if (response.total_pages > 1) {
                    html += this.renderPagination(response);
                }
                
                $container.html(html);
            } else {
                $container.html(this.renderEmptyState());
            }

            // Scroll suave para os resultados
            $('html, body').animate({
                scrollTop: $container.offset().top - 100
            }, 500);
        },

        /**
         * Renderiza um card de post
         */
        renderPostCard: function(post) {
            const colorClass = this.getColorClass();
            const metaFields = post.meta_fields || {};
            
            let html = `
                <article class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow group border border-recife-gray-200">
                    <div class="aspect-w-16 aspect-h-9 overflow-hidden relative">
            `;

            // Imagem
            if (post.featured_image) {
                html += `
                    <a href="${post.link}">
                        <img src="${post.featured_image}" alt="${post.title.rendered}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                `;
            } else {
                html += `
                    <div class="w-full h-full bg-gradient-to-br from-${colorClass} to-${colorClass}/80 flex items-center justify-center">
                        <span class="text-2xl text-white">${this.getPostTypeIcon()}</span>
                    </div>
                `;
            }

            html += `
                    </div>
                    <div class="p-4">
                        <span class="inline-block px-2 py-1 bg-${colorClass} text-white text-xs font-medium rounded-full mb-3">
                            ${this.getPostTypeLabel()}
                        </span>
                        
                        <h3 class="text-sm font-bold text-recife-gray-900 mb-3 line-clamp-2 group-hover:text-${colorClass} transition-colors">
                            <a href="${post.link}">${post.title.rendered}</a>
                        </h3>
            `;

            // Meta fields específicos
            html += this.renderPostMeta(metaFields, post);

            html += `
                        <a href="${post.link}" 
                           class="inline-flex items-center px-3 py-2 bg-recife-primary text-white font-medium rounded-lg hover:bg-recife-primary-dark transition-colors text-xs w-full justify-center">
                            Ver Detalhes
                        </a>
                    </div>
                </article>
            `;

            return html;
        },

        /**
         * Renderiza meta fields do post
         */
        renderPostMeta: function(metaFields, post) {
            let html = '';

            // Meta fields específicos por tipo de post
            switch (this.currentPostType) {
                case 'eventos_festivais':
                    if (metaFields._data_inicio) {
                        const date = new Date(metaFields._data_inicio);
                        html += `
                            <div class="flex items-center text-sm font-bold text-recife-primary mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>${date.toLocaleDateString('pt-BR')}</span>
                            </div>
                        `;
                    }
                    if (metaFields._local) {
                        html += `
                            <div class="flex items-center text-sm text-recife-gray-700 mb-3">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="font-medium">${metaFields._local.substring(0, 30)}...</span>
                            </div>
                        `;
                    }
                    break;

                case 'lugares':
                    if (metaFields._tipo_lugar) {
                        html += `
                            <div class="text-xs text-recife-gray-600 mb-2">
                                📍 ${this.getMetaFieldLabel('_tipo_lugar', metaFields._tipo_lugar)}
                            </div>
                        `;
                    }
                    break;

                case 'artistas':
                    if (metaFields._tipo_artista) {
                        html += `
                            <div class="text-xs text-recife-gray-600 mb-2">
                                🎨 ${this.getMetaFieldLabel('_tipo_artista', metaFields._tipo_artista)}
                            </div>
                        `;
                    }
                    break;
            }

            return html;
        },

        /**
         * Obtém label de meta field
         */
        getMetaFieldLabel: function(metaKey, value) {
            // Esta função deveria consultar as opções configuradas
            // Por agora, retorna o valor como está
            return value;
        },

        /**
         * Renderiza estado vazio
         */
        renderEmptyState: function() {
            const colorClass = this.getColorClass();
            const icon = this.getPostTypeIcon();

            return `
                <div class="text-center py-12 bg-recife-gray-50 rounded-lg border-2 border-dashed border-recife-gray-300">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 bg-${colorClass}/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl text-${colorClass}">${icon}</span>
                        </div>
                        <h3 class="text-lg font-bold text-recife-gray-900 mb-2">Nenhum resultado encontrado</h3>
                        <p class="text-sm text-recife-gray-600 mb-4">Tente ajustar os filtros ou limpar a busca.</p>
                        <button type="button" onclick="RecifeMaisFilters.clearFilters()" 
                                class="inline-flex items-center px-4 py-2 bg-${colorClass} text-white rounded-lg hover:opacity-90 transition-colors text-sm">
                            Limpar Filtros
                        </button>
                    </div>
                </div>
            `;
        },

        /**
         * Limpa todos os filtros
         */
        clearFilters: function(reload = true) {
            this.currentFilters = {};
            
            // Limpa formulário
            $('#meta-fields-filters')[0].reset();
            $('input[type="checkbox"]').prop('checked', false);
            
            // Reset botões rápidos
            $('.filter-btn').removeClass('active');
            $('.filter-btn[data-filter="all"]').addClass('active');
            
            // Limpa filtros ativos
            this.updateActiveFiltersDisplay();
            
            if (reload) {
                this.applyFilters();
            }
        },

        /**
         * Remove filtro ativo específico
         */
        removeActiveFilter: function($btn) {
            const filterKey = $btn.data('filter');
            const filterValue = $btn.data('value');

            if (this.currentFilters[filterKey]) {
                const index = this.currentFilters[filterKey].indexOf(filterValue);
                if (index > -1) {
                    this.currentFilters[filterKey].splice(index, 1);
                    
                    // Remove a chave se o array estiver vazio
                    if (this.currentFilters[filterKey].length === 0) {
                        delete this.currentFilters[filterKey];
                    }
                }
            }

            // Atualiza formulário
            this.updateFormFromFilters();
            this.applyFilters();
        },

        /**
         * Atualiza formulário baseado nos filtros atuais
         */
        updateFormFromFilters: function() {
            // Reset form
            $('#meta-fields-filters')[0].reset();
            $('input[type="checkbox"]').prop('checked', false);

            // Aplica filtros atuais
            Object.keys(this.currentFilters).forEach(key => {
                this.currentFilters[key].forEach(value => {
                    // Select
                    $(`select[name="${key}"]`).val(value);
                    
                    // Checkbox
                    $(`input[name="${key}[]"][value="${value}"]`).prop('checked', true);
                });
            });
        },

        /**
         * Atualiza exibição dos filtros ativos
         */
        updateActiveFiltersDisplay: function() {
            const $container = $('#active-filters');
            const $list = $('#active-filters-list');

            if (Object.keys(this.currentFilters).length === 0) {
                $container.addClass('hidden');
                return;
            }

            let html = '';
            Object.keys(this.currentFilters).forEach(key => {
                this.currentFilters[key].forEach(value => {
                    const label = this.getFilterLabel(key, value);
                    html += `
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-recife-primary text-white rounded-full text-xs">
                            ${label}
                            <button type="button" class="active-filter-remove ml-1 hover:opacity-70" 
                                    data-filter="${key}" data-value="${value}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    `;
                });
            });

            $list.html(html);
            $container.removeClass('hidden');
        },

        /**
         * Obtém label amigável do filtro
         */
        getFilterLabel: function(key, value) {
            // Simplificado - em implementação real, consultaria configuração
            return value;
        },

        /**
         * Atualiza contador de resultados
         */
        updateResultsCount: function(count) {
            const text = count === 1 ? `${count} resultado` : `${count} resultados`;
            $('#results-count').text(text);
        },

        /**
         * Estados de loading
         */
        showLoading: function() {
            $('#filters-loading').removeClass('hidden');
            this.getResultsContainer().addClass('opacity-50');
        },

        hideLoading: function() {
            $('#filters-loading').addClass('hidden');
            this.getResultsContainer().removeClass('opacity-50');
        },

        showError: function(message) {
            // Implementar notificação de erro
            console.error(message);
        },

        /**
         * Gerenciamento de URL
         */
        updateUrl: function() {
            const params = new URLSearchParams();
            
            Object.keys(this.currentFilters).forEach(key => {
                if (this.currentFilters[key].length > 0) {
                    params.set(key, this.currentFilters[key].join(','));
                }
            });

            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newUrl);
        },

        loadFiltersFromUrl: function() {
            const params = new URLSearchParams(window.location.search);
            this.currentFilters = {};

            params.forEach((value, key) => {
                this.currentFilters[key] = value.split(',');
            });

            if (Object.keys(this.currentFilters).length > 0) {
                this.updateFormFromFilters();
                this.updateActiveFiltersDisplay();
            }
        },

        setupUrlHandling: function() {
            const self = this;
            window.addEventListener('popstate', function() {
                self.loadFiltersFromUrl();
                self.applyFilters();
            });
        },

        /**
         * Utilitários
         */
        getResultsContainer: function() {
            return $('.grid').first().parent();
        },

        getColorClass: function() {
            const colors = {
                'eventos_festivais': 'cpt-eventos',
                'lugares': 'cpt-lugares',
                'artistas': 'cpt-artistas',
                'historias': 'cpt-historias',
                'guias_tematicos': 'cpt-guias',
                'roteiros': 'cpt-roteiros',
                'organizadores': 'cpt-organizadores'
            };
            return colors[this.currentPostType] || 'recife-primary';
        },

        getPostTypeIcon: function() {
            const icons = {
                'eventos_festivais': '🎪',
                'lugares': '📍',
                'artistas': '🎨',
                'historias': '📚',
                'guias_tematicos': '🗺️',
                'roteiros': '🛤️',
                'organizadores': '🏢'
            };
            return icons[this.currentPostType] || '📄';
        },

        getPostTypeLabel: function() {
            const labels = {
                'eventos_festivais': 'Evento',
                'lugares': 'Lugar',
                'artistas': 'Artista',
                'historias': 'História',
                'guias_tematicos': 'Guia',
                'roteiros': 'Roteiro',
                'organizadores': 'Organizador'
            };
            return labels[this.currentPostType] || 'Post';
        },

        /**
         * Utilitário debounce
         */
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
        }
    };

    // Inicialização quando documento estiver pronto
    $(document).ready(function() {
        if ($('.filters-meta-fields').length) {
            RecifeMaisFilters.init();
        }
    });

    // Disponibiliza globalmente para debugging
    window.RecifeMaisFilters = RecifeMaisFilters;

})(jQuery); 