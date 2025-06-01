/**
 * Footer JavaScript - RecifeMais Tema
 * 
 * Funcionalidades interativas para o footer moderno
 * Inclui newsletter, animações e analytics
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

(function($) {
    'use strict';

    /**
     * Classe principal do Footer
     */
    class RecifeMaisFooter {
        constructor() {
            this.init();
        }

        /**
         * Inicializa todas as funcionalidades
         */
        init() {
            this.setupNewsletter();
            this.setupAnimations();
            this.setupAnalytics();
            this.setupAccessibility();
            this.setupLazyLoading();
            
            // Debug mode
            if (window.recifemais_debug) {
                console.log('RecifeMais Footer: Inicializado com sucesso');
            }
        }

        /**
         * Configuração da Newsletter
         */
        setupNewsletter() {
            const form = document.getElementById('newsletter-form');
            if (!form) return;

            const input = form.querySelector('input[name="newsletter_email"]');
            const button = form.querySelector('button[type="submit"]');
            const originalButtonText = button.innerHTML;

            // Validação em tempo real
            input.addEventListener('input', (e) => {
                this.validateEmail(e.target.value) 
                    ? this.setInputState(input, 'valid')
                    : this.setInputState(input, 'invalid');
            });

            // Submissão do formulário
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const email = input.value.trim();
                
                if (!this.validateEmail(email)) {
                    this.showMessage('Por favor, insira um email válido.', 'error');
                    return;
                }

                // Estado de loading
                this.setButtonLoading(button, true);
                
                try {
                    const response = await this.submitNewsletter(email);
                    
                    if (response.success) {
                        this.showMessage('Inscrição realizada com sucesso! 🎉', 'success');
                        form.reset();
                        this.setInputState(input, 'default');
                        
                        // Analytics
                        this.trackEvent('newsletter_signup', {
                            email_domain: email.split('@')[1],
                            source: 'footer'
                        });
                    } else {
                        throw new Error(response.message || 'Erro ao processar inscrição');
                    }
                } catch (error) {
                    console.error('Newsletter error:', error);
                    this.showMessage('Erro ao processar inscrição. Tente novamente.', 'error');
                } finally {
                    this.setButtonLoading(button, false, originalButtonText);
                }
            });
        }

        /**
         * Valida email
         */
        validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        /**
         * Define estado do input
         */
        setInputState(input, state) {
            const states = {
                default: 'border-recife-gray-600',
                valid: 'border-green-500 ring-2 ring-green-500/20',
                invalid: 'border-red-500 ring-2 ring-red-500/20'
            };

            // Remove todas as classes de estado
            Object.values(states).forEach(className => {
                className.split(' ').forEach(cls => input.classList.remove(cls));
            });

            // Adiciona a classe do estado atual
            if (states[state]) {
                states[state].split(' ').forEach(cls => input.classList.add(cls));
            }
        }

        /**
         * Define estado de loading do botão
         */
        setButtonLoading(button, loading, originalText = '') {
            if (loading) {
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processando...
                `;
            } else {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }

        /**
         * Submete newsletter via AJAX
         */
        async submitNewsletter(email) {
            const formData = new FormData();
            formData.append('action', 'recifemais_newsletter_signup');
            formData.append('email', email);
            formData.append('nonce', window.recifemais_ajax?.nonce || '');

            const response = await fetch(window.recifemais_ajax?.url || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            });

            return await response.json();
        }

        /**
         * Exibe mensagem de feedback
         */
        showMessage(message, type = 'info') {
            // Remove mensagem anterior se existir
            const existingMessage = document.querySelector('.footer-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            const messageEl = document.createElement('div');
            messageEl.className = `footer-message fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-y-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white'
            };

            messageEl.className += ` ${colors[type] || colors.info}`;
            messageEl.textContent = message;

            document.body.appendChild(messageEl);

            // Animação de entrada
            setTimeout(() => {
                messageEl.classList.remove('translate-y-full');
            }, 100);

            // Remove após 5 segundos
            setTimeout(() => {
                messageEl.classList.add('translate-y-full');
                setTimeout(() => messageEl.remove(), 300);
            }, 5000);
        }

        /**
         * Configuração de animações
         */
        setupAnimations() {
            // Intersection Observer para animações de entrada
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observa seções do footer
            document.querySelectorAll('.footer-section').forEach(section => {
                observer.observe(section);
            });

            // Animação de hover para estatísticas
            this.setupStatsAnimation();
            
            // Animação de parallax sutil para background
            this.setupParallaxBackground();
        }

        /**
         * Animação das estatísticas
         */
        setupStatsAnimation() {
            const statsNumbers = document.querySelectorAll('.footer-stats-number');
            
            const animateNumber = (element) => {
                const target = parseInt(element.textContent) || 0;
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current);
                }, 16);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateNumber(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            statsNumbers.forEach(number => observer.observe(number));
        }

        /**
         * Parallax sutil para background
         */
        setupParallaxBackground() {
            const footer = document.querySelector('footer[role="contentinfo"]');
            if (!footer) return;

            const parallaxElements = footer.querySelectorAll('.absolute.inset-0 > div');
            
            const handleScroll = () => {
                const scrolled = window.pageYOffset;
                const footerTop = footer.offsetTop;
                const windowHeight = window.innerHeight;
                
                if (scrolled + windowHeight > footerTop) {
                    const parallaxSpeed = (scrolled + windowHeight - footerTop) * 0.1;
                    
                    parallaxElements.forEach((el, index) => {
                        const speed = (index + 1) * 0.5;
                        el.style.transform = `translateY(${parallaxSpeed * speed}px)`;
                    });
                }
            };

            // Throttle scroll event
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        /**
         * Configuração de Analytics
         */
        setupAnalytics() {
            // Track cliques em links sociais
            document.querySelectorAll('.social-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    const platform = this.getSocialPlatform(link.href);
                    this.trackEvent('social_click', {
                        platform: platform,
                        location: 'footer'
                    });
                });
            });

            // Track cliques em navegação
            document.querySelectorAll('.footer-nav-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    this.trackEvent('footer_navigation', {
                        link_text: link.textContent.trim(),
                        link_url: link.href
                    });
                });
            });

            // Track cliques em categorias
            document.querySelectorAll('.category-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    this.trackEvent('footer_category', {
                        category: link.textContent.trim(),
                        link_url: link.href
                    });
                });
            });
        }

        /**
         * Identifica plataforma social
         */
        getSocialPlatform(url) {
            const platforms = {
                'instagram.com': 'instagram',
                'facebook.com': 'facebook',
                'twitter.com': 'twitter',
                'youtube.com': 'youtube',
                'wa.me': 'whatsapp',
                'whatsapp.com': 'whatsapp'
            };

            for (const [domain, platform] of Object.entries(platforms)) {
                if (url.includes(domain)) {
                    return platform;
                }
            }
            return 'unknown';
        }

        /**
         * Track eventos
         */
        trackEvent(eventName, properties = {}) {
            // Google Analytics 4
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, properties);
            }

            // Facebook Pixel
            if (typeof fbq !== 'undefined') {
                fbq('track', eventName, properties);
            }

            // Custom analytics
            if (window.recifemais_analytics) {
                window.recifemais_analytics.track(eventName, properties);
            }

            // Debug
            if (window.recifemais_debug) {
                console.log('Analytics Event:', eventName, properties);
            }
        }

        /**
         * Configuração de Acessibilidade
         */
        setupAccessibility() {
            // Navegação por teclado melhorada
            this.setupKeyboardNavigation();
            
            // ARIA labels dinâmicos
            this.setupAriaLabels();
            
            // Skip links
            this.setupSkipLinks();
        }

        /**
         * Navegação por teclado
         */
        setupKeyboardNavigation() {
            const focusableElements = document.querySelectorAll(
                'footer a, footer button, footer input, footer [tabindex]:not([tabindex="-1"])'
            );

            focusableElements.forEach((element, index) => {
                element.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        // Adiciona classe de foco visível
                        element.classList.add('footer-focusable');
                    }
                });

                element.addEventListener('blur', () => {
                    element.classList.remove('footer-focusable');
                });
            });
        }

        /**
         * ARIA labels dinâmicos
         */
        setupAriaLabels() {
            // Atualiza contadores de estatísticas
            const statsItems = document.querySelectorAll('.footer-stats-item');
            statsItems.forEach(item => {
                const number = item.querySelector('.footer-stats-number');
                const label = item.querySelector('.footer-stats-label');
                
                if (number && label) {
                    item.setAttribute('aria-label', 
                        `${number.textContent} ${label.textContent}`);
                }
            });
        }

        /**
         * Skip links para acessibilidade
         */
        setupSkipLinks() {
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.textContent = 'Pular para o conteúdo principal';
            skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-recife-primary text-white px-4 py-2 rounded z-50';
            
            document.body.insertBefore(skipLink, document.body.firstChild);
        }

        /**
         * Lazy loading para elementos pesados
         */
        setupLazyLoading() {
            // Lazy load para ícones SVG complexos se necessário
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Carrega recursos pesados apenas quando visíveis
                        this.loadHeavyResources(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            document.querySelectorAll('.lazy-load').forEach(el => {
                observer.observe(el);
            });
        }

        /**
         * Carrega recursos pesados
         */
        loadHeavyResources(element) {
            // Implementar conforme necessário
            if (window.recifemais_debug) {
                console.log('Loading heavy resources for:', element);
            }
        }
    }

    /**
     * Utilitários globais
     */
    window.RecifeMaisFooter = {
        /**
         * Força atualização das estatísticas
         */
        updateStats: function(stats) {
            Object.entries(stats).forEach(([key, value]) => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) {
                    element.textContent = value;
                }
            });
        },

        /**
         * Adiciona nova mensagem
         */
        showMessage: function(message, type = 'info') {
            if (window.footerInstance) {
                window.footerInstance.showMessage(message, type);
            }
        }
    };

    /**
     * Inicialização quando DOM estiver pronto
     */
    $(document).ready(function() {
        window.footerInstance = new RecifeMaisFooter();
    });

    /**
     * Reinicializa em mudanças de página (SPA)
     */
    $(document).on('pjax:complete', function() {
        if (window.footerInstance) {
            window.footerInstance.init();
        }
    });

})(jQuery); 