/**
 * Blog JavaScript - RecifeMais Tema
 * 
 * Funcionalidades interativas específicas das páginas do blog
 * Inclui busca, filtros, newsletter e analytics
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

class RecifeMaisBlog {
    constructor() {
        this.searchForm = null;
        this.newsletterForm = null;
        this.categoryFilters = null;
        this.tagFilters = null;
        this.loadMoreBtn = null;
        this.intersectionObserver = null;
        this.currentPage = 1;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Inicializar todas as funcionalidades
     */
    init() {
        this.setupSearch();
        this.setupNewsletter();
        this.setupFilters();
        this.setupInfiniteScroll();
        this.setupScrollAnimations();
        this.setupLazyLoading();
        this.setupAnalytics();
        this.setupKeyboardNavigation();
        this.setupPerformanceOptimizations();
        
        console.log('📰 RecifeMais Blog initialized');
    }

    /**
     * Configurar funcionalidade de busca
     */
    setupSearch() {
        this.searchForm = document.querySelector('.blog-search-form');
        
        if (!this.searchForm) return;

        const searchInput = this.searchForm.querySelector('.blog-search-input');
        
        if (searchInput) {
            // Busca em tempo real com debounce
            searchInput.addEventListener('input', this.debounce((e) => {
                this.handleLiveSearch(e.target.value);
            }, 500));

            // Autocomplete de termos populares
            this.setupSearchAutocomplete(searchInput);
        }

        // Submissão do formulário
        this.searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSearchSubmit();
        });
    }

    /**
     * Configurar autocomplete da busca
     */
    setupSearchAutocomplete(input) {
        const autocompleteContainer = document.createElement('div');
        autocompleteContainer.className = 'blog-search-autocomplete';
        input.parentNode.appendChild(autocompleteContainer);

        input.addEventListener('focus', () => {
            this.showSearchSuggestions(input, autocompleteContainer);
        });

        input.addEventListener('blur', () => {
            // Delay para permitir clique nas sugestões
            setTimeout(() => {
                autocompleteContainer.style.display = 'none';
            }, 200);
        });
    }

    /**
     * Mostrar sugestões de busca
     */
    async showSearchSuggestions(input, container) {
        const query = input.value.trim();
        
        if (query.length < 2) {
            container.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=get_search_suggestions&query=${encodeURIComponent(query)}&nonce=${recifemais_ajax.nonce}`);
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.renderSearchSuggestions(data.data, container, input);
            } else {
                container.style.display = 'none';
            }
        } catch (error) {
            console.warn('Erro ao buscar sugestões:', error);
        }
    }

    /**
     * Renderizar sugestões de busca
     */
    renderSearchSuggestions(suggestions, container, input) {
        const html = suggestions.map(suggestion => `
            <div class="blog-search-suggestion" data-query="${suggestion.query}">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span>${suggestion.query}</span>
                <small>${suggestion.count} resultados</small>
            </div>
        `).join('');

        container.innerHTML = html;
        container.style.display = 'block';

        // Adicionar eventos de clique
        container.querySelectorAll('.blog-search-suggestion').forEach(item => {
            item.addEventListener('click', () => {
                input.value = item.dataset.query;
                this.handleSearchSubmit();
                container.style.display = 'none';
            });
        });
    }

    /**
     * Manipular busca em tempo real
     */
    async handleLiveSearch(query) {
        if (query.length < 3) return;

        this.trackEvent('live_search', { query: query });

        // Implementar busca instantânea se necessário
        // Por enquanto, apenas track do evento
    }

    /**
     * Manipular submissão da busca
     */
    handleSearchSubmit() {
        const searchInput = this.searchForm.querySelector('.blog-search-input');
        const query = searchInput.value.trim();
        
        if (!query) return;

        this.trackEvent('search_submit', { query: query });
        
        // Redirecionar para página de resultados
        window.location.href = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
    }

    /**
     * Configurar formulário de newsletter
     */
    setupNewsletter() {
        this.newsletterForm = document.querySelector('.blog-newsletter-form');
        
        if (!this.newsletterForm) return;

        this.newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleNewsletterSubmit();
        });

        // Validação em tempo real
        const emailInput = this.newsletterForm.querySelector('.blog-newsletter-input');
        if (emailInput) {
            emailInput.addEventListener('input', () => {
                this.validateNewsletterEmail(emailInput);
            });
        }
    }

    /**
     * Validar email da newsletter
     */
    validateNewsletterEmail(input) {
        const email = input.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        
        input.classList.toggle('invalid', email && !isValid);
        input.classList.toggle('valid', isValid);
        
        return isValid;
    }

    /**
     * Manipular envio da newsletter
     */
    async handleNewsletterSubmit() {
        const emailInput = this.newsletterForm.querySelector('.blog-newsletter-input');
        const submitBtn = this.newsletterForm.querySelector('.blog-newsletter-btn');
        
        if (!emailInput || !submitBtn) return;

        const email = emailInput.value.trim();
        
        if (!this.validateNewsletterEmail(emailInput)) {
            this.showNewsletterMessage('Por favor, insira um email válido.', 'error');
            return;
        }

        // Mostrar loading
        this.setNewsletterLoading(submitBtn, true);

        try {
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=newsletter_signup&email=${encodeURIComponent(email)}&nonce=${recifemais_ajax.nonce}`, {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNewsletterMessage('Obrigado! Você foi inscrito com sucesso.', 'success');
                emailInput.value = '';
                this.trackEvent('newsletter_signup', { email: email, source: 'blog' });
            } else {
                this.showNewsletterMessage(data.data || 'Erro ao inscrever. Tente novamente.', 'error');
            }
        } catch (error) {
            console.error('Erro na newsletter:', error);
            this.showNewsletterMessage('Erro de conexão. Tente novamente.', 'error');
        } finally {
            this.setNewsletterLoading(submitBtn, false);
        }
    }

    /**
     * Definir estado de loading da newsletter
     */
    setNewsletterLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.textContent = 'Inscrevendo...';
        } else {
            button.disabled = false;
            button.textContent = 'Inscrever-se';
        }
    }

    /**
     * Mostrar mensagem da newsletter
     */
    showNewsletterMessage(message, type) {
        // Remover mensagem anterior
        const existingMessage = this.newsletterForm.querySelector('.newsletter-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Criar nova mensagem
        const messageEl = document.createElement('div');
        messageEl.className = `newsletter-message newsletter-message-${type}`;
        messageEl.textContent = message;
        
        this.newsletterForm.appendChild(messageEl);

        // Remover após 5 segundos
        setTimeout(() => {
            messageEl.remove();
        }, 5000);
    }

    /**
     * Configurar filtros
     */
    setupFilters() {
        this.categoryFilters = document.querySelectorAll('.blog-category-link');
        this.tagFilters = document.querySelectorAll('.blog-tag-link');

        // Filtros de categoria
        this.categoryFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                this.handleCategoryFilter(e, filter);
            });
        });

        // Filtros de tag
        this.tagFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                this.handleTagFilter(e, filter);
            });
        });
    }

    /**
     * Manipular filtro de categoria
     */
    handleCategoryFilter(e, filter) {
        const categoryName = filter.querySelector('.blog-category-name').textContent;
        
        this.trackEvent('category_filter', { 
            category: categoryName,
            url: filter.href 
        });
        
        // Permitir navegação normal
    }

    /**
     * Manipular filtro de tag
     */
    handleTagFilter(e, filter) {
        const tagName = filter.textContent.trim();
        
        this.trackEvent('tag_filter', { 
            tag: tagName,
            url: filter.href 
        });
        
        // Permitir navegação normal
    }

    /**
     * Configurar scroll infinito
     */
    setupInfiniteScroll() {
        // Verificar se há mais páginas
        const pagination = document.querySelector('.blog-pagination');
        if (!pagination) return;

        const nextPageLink = pagination.querySelector('.next');
        if (!nextPageLink) return;

        // Observar quando o usuário chega perto do final
        const sentinel = document.createElement('div');
        sentinel.className = 'infinite-scroll-sentinel';
        
        const postsContainer = document.querySelector('.blog-posts-container');
        if (postsContainer) {
            postsContainer.parentNode.insertBefore(sentinel, pagination);
            
            this.setupInfiniteScrollObserver(sentinel);
        }
    }

    /**
     * Configurar observer para scroll infinito
     */
    setupInfiniteScrollObserver(sentinel) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.isLoading) {
                    this.loadMorePosts();
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '100px'
        });

        observer.observe(sentinel);
    }

    /**
     * Carregar mais posts
     */
    async loadMorePosts() {
        if (this.isLoading) return;

        this.isLoading = true;
        this.currentPage++;

        try {
            const response = await fetch(`${window.location.href}${window.location.search ? '&' : '?'}page=${this.currentPage}&ajax=1`);
            const data = await response.text();
            
            if (data.trim()) {
                this.appendNewPosts(data);
                this.trackEvent('infinite_scroll', { page: this.currentPage });
            } else {
                // Não há mais posts
                this.hideInfiniteScrollSentinel();
            }
        } catch (error) {
            console.error('Erro ao carregar mais posts:', error);
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Adicionar novos posts ao grid
     */
    appendNewPosts(html) {
        const postsContainer = document.querySelector('.blog-posts-container');
        if (!postsContainer) return;

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        const newPosts = tempDiv.querySelectorAll('.blog-post-card');
        newPosts.forEach(post => {
            postsContainer.appendChild(post);
        });

        // Reativar animações e lazy loading
        this.setupScrollAnimations();
        this.setupLazyLoading();
    }

    /**
     * Esconder sentinel do scroll infinito
     */
    hideInfiniteScrollSentinel() {
        const sentinel = document.querySelector('.infinite-scroll-sentinel');
        if (sentinel) {
            sentinel.style.display = 'none';
        }
    }

    /**
     * Configurar animações de scroll
     */
    setupScrollAnimations() {
        if (!window.IntersectionObserver) return;

        // Limpar observer anterior
        if (this.intersectionObserver) {
            this.intersectionObserver.disconnect();
        }

        this.intersectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    this.intersectionObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        // Observar elementos animáveis
        const animatableElements = document.querySelectorAll(`
            .blog-post-card:not(.animate-in),
            .blog-featured-post:not(.animate-in),
            .blog-widget:not(.animate-in)
        `);

        animatableElements.forEach(el => {
            this.intersectionObserver.observe(el);
        });
    }

    /**
     * Configurar lazy loading de imagens
     */
    setupLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            // Navegador suporta lazy loading nativo
            const images = document.querySelectorAll('img[loading="lazy"]:not(.loaded)');
            images.forEach(img => {
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                });
            });
        } else {
            // Fallback para navegadores antigos
            this.setupIntersectionObserverLazyLoading();
        }
    }

    /**
     * Lazy loading com Intersection Observer
     */
    setupIntersectionObserverLazyLoading() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-src]:not(.loaded)');
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * Configurar analytics e tracking
     */
    setupAnalytics() {
        // Track cliques em posts
        document.addEventListener('click', (e) => {
            const postCard = e.target.closest('.blog-post-card, .blog-featured-post');
            
            if (postCard) {
                const title = this.getPostTitle(postCard);
                const category = this.getPostCategory(postCard);
                
                this.trackEvent('post_click', {
                    post_title: title,
                    post_category: category,
                    post_type: postCard.classList.contains('blog-featured-post') ? 'featured' : 'regular'
                });
            }
        });

        // Track tempo de leitura
        this.trackReadingTime();

        // Track scroll depth
        this.trackScrollDepth();
    }

    /**
     * Obter título do post
     */
    getPostTitle(postCard) {
        const titleEl = postCard.querySelector('.blog-post-title a, .blog-featured-title a');
        return titleEl ? titleEl.textContent.trim() : '';
    }

    /**
     * Obter categoria do post
     */
    getPostCategory(postCard) {
        const categoryEl = postCard.querySelector('.blog-post-category, .blog-featured-category');
        return categoryEl ? categoryEl.textContent.trim() : '';
    }

    /**
     * Rastrear tempo de leitura
     */
    trackReadingTime() {
        const startTime = Date.now();
        let maxScroll = 0;
        
        window.addEventListener('scroll', () => {
            const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
            maxScroll = Math.max(maxScroll, scrollPercent);
        });

        window.addEventListener('beforeunload', () => {
            const timeSpent = Math.round((Date.now() - startTime) / 1000);
            this.trackEvent('reading_time', { 
                time_spent: timeSpent,
                max_scroll: maxScroll
            });
        });
    }

    /**
     * Rastrear profundidade de scroll
     */
    trackScrollDepth() {
        const milestones = [25, 50, 75, 100];
        const reached = new Set();

        window.addEventListener('scroll', this.throttle(() => {
            const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
            
            milestones.forEach(milestone => {
                if (scrollPercent >= milestone && !reached.has(milestone)) {
                    reached.add(milestone);
                    this.trackEvent('scroll_depth', { depth: milestone });
                }
            });
        }, 250));
    }

    /**
     * Configurar navegação por teclado
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Esc para fechar autocomplete
            if (e.key === 'Escape') {
                const autocomplete = document.querySelector('.blog-search-autocomplete');
                if (autocomplete) {
                    autocomplete.style.display = 'none';
                }
            }
            
            // Enter para ativar links focados
            if (e.key === 'Enter') {
                const focused = document.activeElement;
                if (focused && focused.classList.contains('blog-search-suggestion')) {
                    focused.click();
                }
            }
        });
    }

    /**
     * Configurar otimizações de performance
     */
    setupPerformanceOptimizations() {
        // Debounce para redimensionamento
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });

        // Prefetch de páginas importantes
        this.setupPrefetching();
    }

    /**
     * Manipular redimensionamento
     */
    handleResize() {
        // Reajustar layouts se necessário
    }

    /**
     * Configurar prefetching
     */
    setupPrefetching() {
        // Prefetch de posts ao hover
        document.addEventListener('mouseover', (e) => {
            const link = e.target.closest('a[href]');
            if (link && this.shouldPrefetch(link.href)) {
                this.prefetchPage(link.href);
            }
        });
    }

    /**
     * Verificar se deve fazer prefetch
     */
    shouldPrefetch(url) {
        // Apenas URLs internas de posts
        return url.startsWith(window.location.origin) && 
               (url.includes('/20') || url.includes('/categoria/') || url.includes('/tag/'));
    }

    /**
     * Fazer prefetch de página
     */
    prefetchPage(url) {
        if (document.querySelector(`link[rel="prefetch"][href="${url}"]`)) {
            return; // Já foi prefetchado
        }

        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
    }

    /**
     * Rastrear evento
     */
    trackEvent(eventName, properties = {}) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                page_title: document.title,
                page_location: window.location.href,
                ...properties
            });
        }

        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', 'CustomEvent', {
                event_name: eventName,
                ...properties
            });
        }

        // Console para debug
        console.log('📊 Event tracked:', eventName, properties);
    }

    /**
     * Função utilitária para debounce
     */
    debounce(func, wait) {
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

    /**
     * Função utilitária para throttle
     */
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.RecifeMaisBlog = new RecifeMaisBlog();
});

// Exportar para uso em outros scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RecifeMaisBlog;
} 