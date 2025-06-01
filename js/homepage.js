/**
 * Homepage JavaScript - RecifeMais Tema
 * 
 * Funcionalidades interativas específicas da homepage
 * Inclui breaking news ticker, filtros, animações e analytics
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

class RecifeMaisHomepage {
    constructor() {
        this.breakingNewsTicker = null;
        this.roteirosFilters = null;
        this.agendaNav = null;
        this.newsletterForm = null;
        this.weatherWidget = null;
        this.intersectionObserver = null;
        
        this.init();
    }

    /**
     * Inicializar todas as funcionalidades
     */
    init() {
        this.setupBreakingNewsTicker();
        this.setupRoteirosFilters();
        this.setupAgendaNavigation();
        this.setupNewsletterForm();
        this.setupWeatherWidget();
        this.setupScrollAnimations();
        this.setupLazyLoading();
        this.setupAnalytics();
        this.setupKeyboardNavigation();
        this.setupPerformanceOptimizations();
        
        console.log('🏠 RecifeMais Homepage initialized');
    }

    /**
     * Configurar ticker de breaking news
     */
    setupBreakingNewsTicker() {
        this.breakingNewsTicker = document.getElementById('breaking-news-ticker');
        
        if (!this.breakingNewsTicker) return;

        // Pausar animação ao hover
        this.breakingNewsTicker.addEventListener('mouseenter', () => {
            this.breakingNewsTicker.style.animationPlayState = 'paused';
        });

        this.breakingNewsTicker.addEventListener('mouseleave', () => {
            this.breakingNewsTicker.style.animationPlayState = 'running';
        });

        // Controle de velocidade baseado no conteúdo
        this.adjustTickerSpeed();

        // Atualizar ticker periodicamente
        this.setupTickerAutoUpdate();
    }

    /**
     * Ajustar velocidade do ticker baseado no conteúdo
     */
    adjustTickerSpeed() {
        if (!this.breakingNewsTicker) return;

        const items = this.breakingNewsTicker.querySelectorAll('.homepage-breaking-news-item');
        const totalWidth = Array.from(items).reduce((width, item) => {
            return width + item.offsetWidth + 48; // 3rem margin
        }, 0);

        // Calcular duração baseada no conteúdo (aproximadamente 100px por segundo)
        const duration = Math.max(30, totalWidth / 100);
        this.breakingNewsTicker.style.animationDuration = `${duration}s`;
    }

    /**
     * Atualização automática do ticker
     */
    setupTickerAutoUpdate() {
        // Atualizar a cada 5 minutos
        setInterval(() => {
            this.updateBreakingNews();
        }, 300000);
    }

    /**
     * Atualizar breaking news via AJAX
     */
    async updateBreakingNews() {
        try {
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=get_breaking_news&nonce=${recifemais_ajax.nonce}`);
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.refreshTickerContent(data.data);
            }
        } catch (error) {
            console.warn('Erro ao atualizar breaking news:', error);
        }
    }

    /**
     * Atualizar conteúdo do ticker
     */
    refreshTickerContent(newsItems) {
        if (!this.breakingNewsTicker) return;

        const newContent = newsItems.map(item => `
            <div class="homepage-breaking-news-item">
                <a href="${item.url}" title="${item.title}">
                    ${item.title}
                </a>
            </div>
        `).join('');

        this.breakingNewsTicker.innerHTML = newContent;
        this.adjustTickerSpeed();
    }

    /**
     * Configurar filtros de roteiros
     */
    setupRoteirosFilters() {
        this.roteirosFilters = document.querySelectorAll('.homepage-roteiros-filter');
        
        if (!this.roteirosFilters.length) return;

        this.roteirosFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleRoteirosFilter(filter);
            });
        });
    }

    /**
     * Manipular filtro de roteiros
     */
    async handleRoteirosFilter(activeFilter) {
        // Atualizar estado visual
        this.roteirosFilters.forEach(filter => {
            filter.classList.remove('active');
        });
        activeFilter.classList.add('active');

        const filterType = activeFilter.dataset.filter || 'all';
        
        // Mostrar loading
        this.showRoteirosLoading();

        try {
            // Buscar roteiros filtrados
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=filter_roteiros&filter=${filterType}&nonce=${recifemais_ajax.nonce}`);
            const data = await response.json();
            
            if (data.success) {
                this.updateRoteirosGrid(data.data);
                this.trackEvent('roteiros_filter', { filter_type: filterType });
            }
        } catch (error) {
            console.error('Erro ao filtrar roteiros:', error);
            this.showRoteirosError();
        }
    }

    /**
     * Mostrar loading nos roteiros
     */
    showRoteirosLoading() {
        const grid = document.querySelector('.homepage-roteiros-grid');
        if (grid) {
            grid.innerHTML = '<div class="loading-skeleton">Carregando roteiros...</div>';
        }
    }

    /**
     * Mostrar erro nos roteiros
     */
    showRoteirosError() {
        const grid = document.querySelector('.homepage-roteiros-grid');
        if (grid) {
            grid.innerHTML = '<div class="error-message">Erro ao carregar roteiros. Tente novamente.</div>';
        }
    }

    /**
     * Atualizar grid de roteiros
     */
    updateRoteirosGrid(roteiros) {
        const grid = document.querySelector('.homepage-roteiros-grid');
        if (!grid) return;

        const html = roteiros.map(roteiro => `
            <article class="homepage-roteiro-card">
                <img src="${roteiro.image}" alt="${roteiro.title}" class="homepage-roteiro-image" loading="lazy">
                <div class="homepage-roteiro-content">
                    <div class="homepage-roteiro-badges">
                        <span class="homepage-roteiro-badge duration">${roteiro.duration}</span>
                        <span class="homepage-roteiro-badge difficulty">${roteiro.difficulty}</span>
                    </div>
                    <h3 class="homepage-roteiro-title">
                        <a href="${roteiro.url}">${roteiro.title}</a>
                    </h3>
                    <p class="homepage-roteiro-description">${roteiro.excerpt}</p>
                    <div class="homepage-roteiro-stats">
                        <span class="homepage-roteiro-stat">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            ${roteiro.locations_count} locais
                        </span>
                        <span class="homepage-roteiro-stat">
                            <i class="fas fa-star" aria-hidden="true"></i>
                            ${roteiro.rating}
                        </span>
                    </div>
                </div>
            </article>
        `).join('');

        grid.innerHTML = html;
        
        // Reativar animações
        this.setupScrollAnimations();
    }

    /**
     * Configurar navegação da agenda
     */
    setupAgendaNavigation() {
        this.agendaNav = document.querySelectorAll('.homepage-agenda-nav-btn');
        
        if (!this.agendaNav.length) return;

        this.agendaNav.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleAgendaNavigation(btn);
            });
        });
    }

    /**
     * Manipular navegação da agenda
     */
    async handleAgendaNavigation(activeBtn) {
        // Atualizar estado visual
        this.agendaNav.forEach(btn => {
            btn.classList.remove('active');
        });
        activeBtn.classList.add('active');

        const period = activeBtn.dataset.period || 'today';
        
        // Mostrar loading
        this.showAgendaLoading();

        try {
            // Buscar eventos do período
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=get_agenda_events&period=${period}&nonce=${recifemais_ajax.nonce}`);
            const data = await response.json();
            
            if (data.success) {
                this.updateAgendaGrid(data.data);
                this.trackEvent('agenda_navigation', { period: period });
            }
        } catch (error) {
            console.error('Erro ao carregar agenda:', error);
            this.showAgendaError();
        }
    }

    /**
     * Mostrar loading na agenda
     */
    showAgendaLoading() {
        const grid = document.querySelector('.homepage-agenda-grid');
        if (grid) {
            grid.innerHTML = '<div class="loading-skeleton">Carregando eventos...</div>';
        }
    }

    /**
     * Mostrar erro na agenda
     */
    showAgendaError() {
        const grid = document.querySelector('.homepage-agenda-grid');
        if (grid) {
            grid.innerHTML = '<div class="error-message">Erro ao carregar eventos. Tente novamente.</div>';
        }
    }

    /**
     * Atualizar grid da agenda
     */
    updateAgendaGrid(eventos) {
        const grid = document.querySelector('.homepage-agenda-grid');
        if (!grid) return;

        if (eventos.length === 0) {
            grid.innerHTML = '<div class="empty-state">Nenhum evento encontrado para este período.</div>';
            return;
        }

        const html = eventos.map(evento => `
            <article class="homepage-evento-card">
                <div class="homepage-evento-date">
                    <div class="homepage-evento-day">
                        <span class="homepage-evento-day-number">${evento.day}</span>
                        <span class="homepage-evento-day-month">${evento.month}</span>
                    </div>
                    <div class="homepage-evento-time">${evento.time}</div>
                </div>
                <div class="homepage-evento-content">
                    <h3 class="homepage-evento-title">
                        <a href="${evento.url}">${evento.title}</a>
                    </h3>
                    <div class="homepage-evento-location">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        ${evento.location}
                    </div>
                    <div class="homepage-evento-tags">
                        ${evento.tags.map(tag => `<span class="homepage-evento-tag ${tag.class}">${tag.name}</span>`).join('')}
                    </div>
                </div>
            </article>
        `).join('');

        grid.innerHTML = html;
        
        // Reativar animações
        this.setupScrollAnimations();
    }

    /**
     * Configurar formulário de newsletter
     */
    setupNewsletterForm() {
        this.newsletterForm = document.querySelector('.homepage-newsletter-form');
        
        if (!this.newsletterForm) return;

        this.newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleNewsletterSubmit();
        });

        // Validação em tempo real
        const emailInput = this.newsletterForm.querySelector('.homepage-newsletter-input');
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
        const emailInput = this.newsletterForm.querySelector('.homepage-newsletter-input');
        const submitBtn = this.newsletterForm.querySelector('.homepage-newsletter-btn');
        
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
                this.trackEvent('newsletter_signup', { email: email });
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
     * Configurar widget do clima
     */
    setupWeatherWidget() {
        this.weatherWidget = document.querySelector('.homepage-weather');
        
        if (!this.weatherWidget) return;

        // Atualizar clima a cada 30 minutos
        this.updateWeather();
        setInterval(() => {
            this.updateWeather();
        }, 1800000);
    }

    /**
     * Atualizar dados do clima
     */
    async updateWeather() {
        try {
            const response = await fetch(`${recifemais_ajax.ajax_url}?action=get_weather_data&nonce=${recifemais_ajax.nonce}`);
            const data = await response.json();
            
            if (data.success) {
                this.updateWeatherDisplay(data.data);
            }
        } catch (error) {
            console.warn('Erro ao atualizar clima:', error);
        }
    }

    /**
     * Atualizar exibição do clima
     */
    updateWeatherDisplay(weatherData) {
        if (!this.weatherWidget || !weatherData) return;

        const tempEl = this.weatherWidget.querySelector('.homepage-weather-temp');
        const descEl = this.weatherWidget.querySelector('.homepage-weather-description');
        const iconEl = this.weatherWidget.querySelector('.homepage-weather-icon');

        if (tempEl) tempEl.textContent = `${weatherData.temperature}°C`;
        if (descEl) descEl.textContent = weatherData.description;
        if (iconEl) iconEl.textContent = weatherData.icon;

        // Atualizar previsão
        const forecastEl = this.weatherWidget.querySelector('.homepage-weather-forecast');
        if (forecastEl && weatherData.forecast) {
            forecastEl.innerHTML = weatherData.forecast.map(day => `
                <div class="homepage-weather-day">
                    <div class="homepage-weather-day-name">${day.name}</div>
                    <div class="homepage-weather-day-icon">${day.icon}</div>
                    <div class="homepage-weather-day-temp">${day.temp}°</div>
                </div>
            `).join('');
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
            .homepage-featured-card,
            .homepage-discover-card,
            .homepage-roteiro-card,
            .homepage-evento-card,
            .homepage-section-header
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
            const images = document.querySelectorAll('img[loading="lazy"]');
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

        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * Configurar analytics e tracking
     */
    setupAnalytics() {
        // Track cliques em cards
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.homepage-featured-card, .homepage-discover-card, .homepage-roteiro-card, .homepage-evento-card');
            
            if (card) {
                const cardType = this.getCardType(card);
                const title = this.getCardTitle(card);
                
                this.trackEvent('card_click', {
                    card_type: cardType,
                    card_title: title
                });
            }
        });

        // Track tempo na página
        this.trackPageTime();
    }

    /**
     * Obter tipo do card
     */
    getCardType(card) {
        if (card.classList.contains('homepage-featured-card')) return 'featured';
        if (card.classList.contains('homepage-discover-card')) return 'discover';
        if (card.classList.contains('homepage-roteiro-card')) return 'roteiro';
        if (card.classList.contains('homepage-evento-card')) return 'evento';
        return 'unknown';
    }

    /**
     * Obter título do card
     */
    getCardTitle(card) {
        const titleEl = card.querySelector('h3 a, .homepage-featured-title, .homepage-discover-title, .homepage-roteiro-title, .homepage-evento-title');
        return titleEl ? titleEl.textContent.trim() : '';
    }

    /**
     * Rastrear tempo na página
     */
    trackPageTime() {
        const startTime = Date.now();
        
        window.addEventListener('beforeunload', () => {
            const timeSpent = Math.round((Date.now() - startTime) / 1000);
            this.trackEvent('page_time', { time_spent: timeSpent });
        });
    }

    /**
     * Configurar navegação por teclado
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Esc para fechar modais/overlays
            if (e.key === 'Escape') {
                this.closeAllOverlays();
            }
            
            // Enter/Space para ativar elementos focados
            if (e.key === 'Enter' || e.key === ' ') {
                const focused = document.activeElement;
                if (focused && focused.classList.contains('homepage-roteiros-filter')) {
                    e.preventDefault();
                    focused.click();
                }
            }
        });
    }

    /**
     * Fechar todos os overlays
     */
    closeAllOverlays() {
        // Implementar quando houver modais/overlays
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
        // Reajustar ticker se necessário
        if (this.breakingNewsTicker) {
            this.adjustTickerSpeed();
        }
    }

    /**
     * Configurar prefetching
     */
    setupPrefetching() {
        // Prefetch de links importantes ao hover
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
        // Apenas URLs internas
        return url.startsWith(window.location.origin);
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
    window.RecifeMaisHomepage = RecifeMaisHomepage;
});

// Exportar para uso em outros scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RecifeMaisHomepage;
} 