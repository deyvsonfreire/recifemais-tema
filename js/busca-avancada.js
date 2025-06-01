/**
 * RecifeMais - Sistema de Busca Avançada
 * JavaScript para busca inteligente com Google Maps
 */

class RecifeMaisBuscaAvancada {
    constructor() {
        this.currentRequest = null;
        this.debounceTimer = null;
        this.googleMaps = null;
        this.autocompleteService = null;
        this.placesService = null;
        this.currentPage = 1;
        this.resultsPerPage = 12;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initGoogleMaps();
        this.loadFiltersFromURL();
    }
    
    bindEvents() {
        // Toggle de filtros
        document.getElementById('toggle-filtros')?.addEventListener('click', () => {
            this.toggleFiltros();
        });
        
        // Busca em tempo real
        document.getElementById('busca-termo')?.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });
        
        // Sugestões
        document.getElementById('busca-termo')?.addEventListener('focus', () => {
            this.showSuggestions();
        });
        
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#busca-termo') && !e.target.closest('#search-suggestions')) {
                this.hideSuggestions();
            }
        });
        
        // Filtros de tipo de conteúdo
        document.querySelectorAll('input[name="post_types[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.handlePostTypeChange();
                this.updateFiltersCount();
            });
        });
        
        // Todos os outros filtros
        const filterSelects = document.querySelectorAll('#filtros-container select, #filtros-container input[type="date"], #filtros-container input[type="number"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                this.updateFiltersCount();
                if (this.hasActiveFilters()) {
                    this.performSearch();
                }
            });
        });
        
        // Botões de ação
        document.getElementById('buscar-submit')?.addEventListener('click', () => {
            this.performSearch();
        });
        
        document.getElementById('limpar-filtros')?.addEventListener('click', () => {
            this.clearFilters();
        });
        
        document.getElementById('salvar-busca')?.addEventListener('click', () => {
            this.saveSearch();
        });
        
        // Ordenação
        document.getElementById('ordenacao')?.addEventListener('change', (e) => {
            this.changeSorting(e.target.value);
        });
        
        // Enter no campo de busca
        document.getElementById('busca-termo')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.performSearch();
            }
        });
    }
    
    async initGoogleMaps() {
        if (typeof google === 'undefined' || !google.maps) {
            console.warn('Google Maps não carregado');
            return;
        }
        
        try {
            this.autocompleteService = new google.maps.places.AutocompleteService();
            this.placesService = new google.maps.places.PlacesService(document.createElement('div'));
            
            // Configurar autocomplete para endereços
            const enderecoInput = document.getElementById('endereco-busca');
            if (enderecoInput) {
                const autocomplete = new google.maps.places.Autocomplete(enderecoInput, {
                    componentRestrictions: { country: 'br' },
                    fields: ['geometry', 'formatted_address', 'name'],
                    types: ['geocode', 'establishment']
                });
                
                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.geometry) {
                        const lat = place.geometry.location.lat();
                        const lng = place.geometry.location.lng();
                        
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        
                        this.updateFiltersCount();
                        this.performSearch();
                    }
                });
            }
        } catch (error) {
            console.error('Erro ao inicializar Google Maps:', error);
        }
    }
    
    handleSearchInput(query) {
        clearTimeout(this.debounceTimer);
        
        if (query.length < 2) {
            this.hideSuggestions();
            return;
        }
        
        this.debounceTimer = setTimeout(() => {
            this.getSuggestions(query);
        }, 300);
    }
    
    async getSuggestions(query) {
        try {
            const response = await fetch(`${recifemaisSearch.ajaxurl}?action=recifemais_get_suggestions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    nonce: recifemaisSearch.nonce,
                    query: query
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displaySuggestions(data.data);
            }
        } catch (error) {
            console.error('Erro ao buscar sugestões:', error);
        }
    }
    
    displaySuggestions(suggestions) {
        const suggestionsContainer = document.getElementById('suggestions-list');
        const suggestionsBox = document.getElementById('search-suggestions');
        
        if (!suggestions || suggestions.length === 0) {
            this.hideSuggestions();
            return;
        }
        
        let html = '';
        suggestions.forEach(suggestion => {
            html += `
                <div class="suggestion-item" data-value="${suggestion.value}" data-type="${suggestion.type}">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">${suggestion.icon || '🔍'}</span>
                        <div>
                            <div class="font-medium">${suggestion.label}</div>
                            <div class="text-xs text-recife-gray-500">${suggestion.type_label}</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        suggestionsContainer.innerHTML = html;
        suggestionsBox.classList.remove('hidden');
        
        // Bind click events
        suggestionsContainer.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', () => {
                const value = item.dataset.value;
                document.getElementById('busca-termo').value = value;
                this.hideSuggestions();
                this.performSearch();
            });
        });
    }
    
    showSuggestions() {
        const query = document.getElementById('busca-termo').value;
        if (query.length >= 2) {
            this.getSuggestions(query);
        }
    }
    
    hideSuggestions() {
        document.getElementById('search-suggestions')?.classList.add('hidden');
    }
    
    toggleFiltros() {
        const container = document.getElementById('filtros-container');
        const button = document.getElementById('toggle-filtros');
        
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            button.innerHTML = `
                ${recifemaisIcons.x}
                <span>Fechar</span>
                <span id="filtros-count" class="bg-recife-primary text-white text-xs rounded-full px-2 py-1 ml-2 ${this.getActiveFiltersCount() > 0 ? '' : 'hidden'}">${this.getActiveFiltersCount()}</span>
            `;
        } else {
            container.classList.add('hidden');
            button.innerHTML = `
                ${recifemaisIcons.filter}
                <span>Filtros</span>
                <span id="filtros-count" class="bg-recife-primary text-white text-xs rounded-full px-2 py-1 ml-2 ${this.getActiveFiltersCount() > 0 ? '' : 'hidden'}">${this.getActiveFiltersCount()}</span>
            `;
        }
    }
    
    handlePostTypeChange() {
        const checkboxes = document.querySelectorAll('input[name="post_types[]"]:checked');
        const hasEventos = Array.from(checkboxes).some(cb => cb.value === 'eventos_festivais');
        
        // Mostrar/ocultar filtros específicos de eventos
        const eventoFilters = document.querySelector('.evento-filters');
        if (eventoFilters) {
            eventoFilters.style.display = hasEventos ? 'block' : 'none';
        }
    }
    
    updateFiltersCount() {
        const count = this.getActiveFiltersCount();
        const badge = document.getElementById('filtros-count');
        
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    }
    
    getActiveFiltersCount() {
        let count = 0;
        
        // Contar filtros de formulário
        const selects = document.querySelectorAll('#filtros-container select');
        selects.forEach(select => {
            if (select.value) count++;
        });
        
        const inputs = document.querySelectorAll('#filtros-container input[type="date"], #filtros-container input[type="number"]');
        inputs.forEach(input => {
            if (input.value) count++;
        });
        
        // Contar coordenadas se existirem
        if (document.getElementById('latitude')?.value) count++;
        
        return count;
    }
    
    hasActiveFilters() {
        return this.getActiveFiltersCount() > 0 || document.getElementById('busca-termo')?.value?.length > 0;
    }
    
    async performSearch() {
        const formData = new FormData(document.getElementById('form-busca-avancada'));
        
        // Adicionar dados extras
        formData.append('action', 'recifemais_busca_avancada');
        formData.append('nonce', recifemaisSearch.nonce);
        formData.append('page', this.currentPage);
        formData.append('per_page', this.resultsPerPage);
        
        // Mostrar loading
        this.showLoading(true);
        this.hideResults();
        
        try {
            // Cancelar request anterior se existir
            if (this.currentRequest) {
                this.currentRequest.abort();
            }
            
            this.currentRequest = new AbortController();
            
            const response = await fetch(recifemaisSearch.ajaxurl, {
                method: 'POST',
                body: formData,
                signal: this.currentRequest.signal
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayResults(data.data);
                this.updateURL(formData);
            } else {
                this.showError(data.data?.message || 'Erro na busca');
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Erro na busca:', error);
                this.showError('Erro de conexão. Tente novamente.');
            }
        } finally {
            this.showLoading(false);
            this.currentRequest = null;
        }
    }
    
    displayResults(data) {
        const resultsContainer = document.getElementById('resultados-lista');
        const infoContainer = document.getElementById('resultados-info');
        const paginationContainer = document.getElementById('resultados-paginacao');
        
        // Informações dos resultados
        infoContainer.innerHTML = `
            Encontrados <strong>${data.total}</strong> resultados 
            ${data.query ? `para "<strong>${data.query}</strong>"` : ''}
            ${data.filters_active ? `com filtros aplicados` : ''}
        `;
        
        // Lista de resultados
        if (data.posts && data.posts.length > 0) {
            let html = '';
            
            data.posts.forEach(post => {
                html += this.generateResultCard(post);
            });
            
            resultsContainer.innerHTML = html;
            
            // Paginação
            if (data.pagination) {
                paginationContainer.innerHTML = this.generatePagination(data.pagination);
            }
        } else {
            resultsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold text-recife-gray-900 mb-2">Nenhum resultado encontrado</h3>
                    <p class="text-recife-gray-600 mb-6">Tente ajustar os filtros ou usar termos diferentes.</p>
                    <button class="btn btn-outline" onclick="recifemaisBusca.clearFilters()">
                        Limpar Filtros
                    </button>
                </div>
            `;
        }
        
        this.showResults();
    }
    
    generateResultCard(post) {
        const distance = post.distance ? `<span class="text-recife-gray-500"> • ${post.distance}km</span>` : '';
        const price = post.meta?.preco ? `<span class="text-recife-primary font-semibold">R$ ${post.meta.preco}</span>` : '';
        const date = post.meta?.data_evento ? `<span class="text-recife-gray-600">${post.meta.data_evento}</span>` : '';
        
        return `
            <article class="result-card group">
                <div class="flex gap-4">
                    ${post.thumbnail ? `
                        <div class="flex-shrink-0">
                            <img src="${post.thumbnail}" alt="${post.title}" class="w-24 h-24 object-cover rounded-lg">
                        </div>
                    ` : ''}
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-${post.post_type_color}/20 text-${post.post_type_color} text-xs font-medium rounded-full">
                                    ${post.post_type_icon} ${post.post_type_label}
                                </span>
                            </div>
                            <div class="text-right text-sm">
                                ${price}
                                ${date}
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-recife-gray-900 group-hover:text-recife-primary transition-colors mb-2">
                            <a href="${post.permalink}" class="block">${post.title}</a>
                        </h3>
                        
                        ${post.excerpt ? `<p class="text-recife-gray-600 text-sm mb-3 line-clamp-2">${post.excerpt}</p>` : ''}
                        
                        <div class="flex items-center justify-between text-sm text-recife-gray-500">
                            <div class="flex items-center gap-4">
                                ${post.meta?.local ? `
                                    <span class="flex items-center gap-1">
                                        ${recifemaisIcons.mapPin} ${post.meta.local}${distance}
                                    </span>
                                ` : ''}
                                
                                ${post.taxonomies ? `
                                    <div class="flex gap-1">
                                        ${post.taxonomies.map(tax => `<span class="text-xs bg-recife-gray-100 px-2 py-1 rounded">${tax}</span>`).join('')}
                                    </div>
                                ` : ''}
                            </div>
                            
                            <a href="${post.permalink}" class="text-recife-primary hover:text-recife-primary-dark font-medium">
                                Ver detalhes →
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        `;
    }
    
    generatePagination(pagination) {
        const { current, total, has_prev, has_next } = pagination;
        
        let html = '<div class="flex items-center justify-center gap-2">';
        
        // Botão anterior
        html += `
            <button class="px-3 py-2 border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50 ${!has_prev ? 'opacity-50 cursor-not-allowed' : ''}" 
                    ${has_prev ? `onclick="recifemaisBusca.goToPage(${current - 1})"` : 'disabled'}>
                ← Anterior
            </button>
        `;
        
        // Números das páginas
        const startPage = Math.max(1, current - 2);
        const endPage = Math.min(total, current + 2);
        
        if (startPage > 1) {
            html += `<button class="px-3 py-2 border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50" onclick="recifemaisBusca.goToPage(1)">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-2">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <button class="px-3 py-2 border rounded-lg ${i === current ? 'bg-recife-primary text-white border-recife-primary' : 'border-recife-gray-300 hover:bg-recife-gray-50'}" 
                        ${i === current ? '' : `onclick="recifemaisBusca.goToPage(${i})"`}>
                    ${i}
                </button>
            `;
        }
        
        if (endPage < total) {
            if (endPage < total - 1) {
                html += `<span class="px-2">...</span>`;
            }
            html += `<button class="px-3 py-2 border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50" onclick="recifemaisBusca.goToPage(${total})">${total}</button>`;
        }
        
        // Botão próximo
        html += `
            <button class="px-3 py-2 border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50 ${!has_next ? 'opacity-50 cursor-not-allowed' : ''}" 
                    ${has_next ? `onclick="recifemaisBusca.goToPage(${current + 1})"` : 'disabled'}>
                Próximo →
            </button>
        `;
        
        html += '</div>';
        
        return html;
    }
    
    goToPage(page) {
        this.currentPage = page;
        this.performSearch();
        
        // Scroll para os resultados
        document.getElementById('resultados-busca')?.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
    
    changeSorting(sort) {
        const orderField = document.createElement('input');
        orderField.type = 'hidden';
        orderField.name = 'orderby';
        orderField.value = sort;
        
        document.getElementById('form-busca-avancada').appendChild(orderField);
        this.currentPage = 1;
        this.performSearch();
    }
    
    clearFilters() {
        // Limpar todos os campos
        document.getElementById('form-busca-avancada').reset();
        
        // Marcar todos os post types
        document.querySelectorAll('input[name="post_types[]"]').forEach(cb => {
            cb.checked = true;
        });
        
        // Limpar coordenadas
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        
        // Resetar página
        this.currentPage = 1;
        
        // Atualizar contadores e ocultar filtros específicos
        this.updateFiltersCount();
        this.handlePostTypeChange();
        
        // Limpar resultados
        this.hideResults();
        
        // Atualizar URL
        window.history.pushState({}, '', window.location.pathname);
    }
    
    saveSearch() {
        const formData = new FormData(document.getElementById('form-busca-avancada'));
        const searchParams = {};
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                searchParams[key] = value;
            }
        }
        
        // Salvar no localStorage ou enviar para servidor
        const savedSearches = JSON.parse(localStorage.getItem('recifemais_saved_searches') || '[]');
        const searchName = prompt('Nome para esta busca:') || `Busca ${Date.now()}`;
        
        savedSearches.push({
            name: searchName,
            params: searchParams,
            date: new Date().toISOString()
        });
        
        localStorage.setItem('recifemais_saved_searches', JSON.stringify(savedSearches));
        
        alert('Busca salva com sucesso!');
    }
    
    loadFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        urlParams.forEach((value, key) => {
            const element = document.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = true;
                } else {
                    element.value = value;
                }
            }
        });
        
        if (urlParams.has('s') || this.hasActiveFilters()) {
            this.toggleFiltros();
            this.performSearch();
        }
    }
    
    updateURL(formData) {
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value && key !== 'action' && key !== 'nonce' && key !== 'page' && key !== 'per_page') {
                params.append(key, value);
            }
        }
        
        const newURL = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.pushState({}, '', newURL);
    }
    
    showLoading(show) {
        const loading = document.getElementById('busca-loading');
        if (loading) {
            loading.classList.toggle('hidden', !show);
        }
    }
    
    showResults() {
        document.getElementById('resultados-busca')?.classList.remove('hidden');
    }
    
    hideResults() {
        document.getElementById('resultados-busca')?.classList.add('hidden');
    }
    
    showError(message) {
        const resultsContainer = document.getElementById('resultados-lista');
        if (resultsContainer) {
            resultsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">⚠️</div>
                    <h3 class="text-xl font-semibold text-recife-gray-900 mb-2">Erro na busca</h3>
                    <p class="text-recife-gray-600">${message}</p>
                </div>
            `;
        }
        this.showResults();
    }
}

// Ícones para uso no JavaScript
const recifemaisIcons = {
    search: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>',
    filter: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46"/></svg>',
    x: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    mapPin: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>'
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Só inicializar se o elemento existir na página
    if (document.getElementById('busca-avancada')) {
        window.recifemaisBusca = new RecifeMaisBuscaAvancada();
    }
}); 