/**
 * Single Post JavaScript - RecifeMais
 * Funcionalidades específicas para páginas de posts/notícias
 * Inspirado na experiência da Globo.com
 */

(function($) {
    'use strict';

    // Aguarda o DOM estar pronto
    $(document).ready(function() {
        initSinglePostFeatures();
    });

    /**
     * Inicializa todas as funcionalidades do single post
     */
    function initSinglePostFeatures() {
        initLazyLoadImages();
        initSmoothScrolling();
        initReadingProgress();
        initSocialSharing();
        initRelatedPostsFilter();
        initPrintFunctionality();
        initAccessibilityFeatures();
        initPerformanceOptimizations();
    }

    /**
     * Lazy Loading para imagens do conteúdo
     */
    function initLazyLoadImages() {
        // Verifica se o navegador suporta Intersection Observer
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

            // Observa todas as imagens com lazy loading
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Smooth scrolling para links internos
     */
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800, 'easeInOutQuart');
            }
        });
    }

    /**
     * Barra de progresso de leitura
     */
    function initReadingProgress() {
        // Cria a barra de progresso
        const progressBar = $('<div class="reading-progress-bar"></div>');
        progressBar.css({
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'width': '0%',
            'height': '3px',
            'background': 'linear-gradient(90deg, #e11d48, #ff6b35)',
            'z-index': '9999',
            'transition': 'width 0.3s ease'
        });
        
        $('body').prepend(progressBar);

        // Atualiza o progresso durante o scroll
        $(window).on('scroll', function() {
            const scrollTop = $(window).scrollTop();
            const docHeight = $(document).height() - $(window).height();
            const scrollPercent = (scrollTop / docHeight) * 100;
            
            progressBar.css('width', scrollPercent + '%');
        });
    }

    /**
     * Funcionalidades de compartilhamento social
     */
    function initSocialSharing() {
        // Compartilhamento via Web Share API (se disponível)
        if (navigator.share) {
            $('.share-native').on('click', function(e) {
                e.preventDefault();
                
                navigator.share({
                    title: document.title,
                    text: $('meta[name="description"]').attr('content'),
                    url: window.location.href
                }).catch(console.error);
            });
        }

        // Compartilhamento tradicional
        $('.share-facebook').on('click', function(e) {
            e.preventDefault();
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, 'facebook-share', 'width=580,height=296');
        });

        $('.share-twitter').on('click', function(e) {
            e.preventDefault();
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, 'twitter-share', 'width=550,height=235');
        });

        $('.share-whatsapp').on('click', function(e) {
            e.preventDefault();
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(document.title);
            window.open(`https://wa.me/?text=${text} ${url}`, 'whatsapp-share');
        });

        $('.share-linkedin').on('click', function(e) {
            e.preventDefault();
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, 'linkedin-share', 'width=550,height=235');
        });

        // Copiar link
        $('.share-copy').on('click', function(e) {
            e.preventDefault();
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showNotification('Link copiado para a área de transferência!');
                });
            } else {
                // Fallback para navegadores mais antigos
                const textArea = document.createElement('textarea');
                textArea.value = window.location.href;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Link copiado para a área de transferência!');
            }
        });
    }

    /**
     * Filtros para posts relacionados
     */
    function initRelatedPostsFilter() {
        window.filterRelatedPosts = function(category) {
            const posts = document.querySelectorAll('.related-post-item');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Atualiza botões ativos
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filtra posts
            posts.forEach(post => {
                const categories = post.dataset.categories.split(' ');
                
                if (category === 'all' || categories.includes(category)) {
                    post.style.display = 'block';
                    post.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    post.style.display = 'none';
                }
            });
        };
    }

    /**
     * Funcionalidade de impressão
     */
    function initPrintFunctionality() {
        $('.print-article').on('click', function(e) {
            e.preventDefault();
            window.print();
        });

        // Otimizações para impressão
        window.addEventListener('beforeprint', function() {
            // Remove elementos desnecessários para impressão
            $('.author-meta .flex.gap-2').hide();
            $('.breadcrumbs').hide();
            $('.post-meta-footer').hide();
        });

        window.addEventListener('afterprint', function() {
            // Restaura elementos após impressão
            $('.author-meta .flex.gap-2').show();
            $('.breadcrumbs').show();
            $('.post-meta-footer').show();
        });
    }

    /**
     * Recursos de acessibilidade
     */
    function initAccessibilityFeatures() {
        // Navegação por teclado melhorada
        $(document).on('keydown', function(e) {
            // ESC para fechar modais/dropdowns
            if (e.key === 'Escape') {
                $('.dropdown-menu').removeClass('show');
                $('.modal').removeClass('show');
            }
        });

        // Foco visível melhorado
        $('a, button, input, textarea, select').on('focus', function() {
            $(this).addClass('focus-visible');
        }).on('blur', function() {
            $(this).removeClass('focus-visible');
        });

        // Skip links para navegação rápida
        if (!$('.skip-link').length) {
            const skipLink = $('<a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>');
            skipLink.css({
                'position': 'absolute',
                'top': '-40px',
                'left': '6px',
                'background': '#e11d48',
                'color': 'white',
                'padding': '8px',
                'text-decoration': 'none',
                'border-radius': '4px',
                'z-index': '10000'
            });
            
            skipLink.on('focus', function() {
                $(this).css('top', '6px');
            }).on('blur', function() {
                $(this).css('top', '-40px');
            });
            
            $('body').prepend(skipLink);
        }
    }

    /**
     * Otimizações de performance
     */
    function initPerformanceOptimizations() {
        // Debounce para eventos de scroll
        let scrollTimeout;
        $(window).on('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            
            scrollTimeout = setTimeout(function() {
                // Código que deve executar após o scroll parar
                updateReadingProgress();
            }, 100);
        });

        // Preload de links importantes
        $('a[href^="/"]').on('mouseenter', function() {
            const link = this.href;
            if (!document.querySelector(`link[rel="prefetch"][href="${link}"]`)) {
                const prefetchLink = document.createElement('link');
                prefetchLink.rel = 'prefetch';
                prefetchLink.href = link;
                document.head.appendChild(prefetchLink);
            }
        });
    }

    /**
     * Atualiza o progresso de leitura
     */
    function updateReadingProgress() {
        const scrollTop = $(window).scrollTop();
        const docHeight = $(document).height() - $(window).height();
        const scrollPercent = (scrollTop / docHeight) * 100;
        
        $('.reading-progress-bar').css('width', Math.min(scrollPercent, 100) + '%');
    }

    /**
     * Mostra notificação temporária
     */
    function showNotification(message, type = 'success') {
        const notification = $(`
            <div class="notification notification-${type}">
                <span>${message}</span>
            </div>
        `);
        
        notification.css({
            'position': 'fixed',
            'top': '20px',
            'right': '20px',
            'background': type === 'success' ? '#10b981' : '#ef4444',
            'color': 'white',
            'padding': '12px 20px',
            'border-radius': '8px',
            'box-shadow': '0 4px 6px rgba(0, 0, 0, 0.1)',
            'z-index': '10000',
            'transform': 'translateX(100%)',
            'transition': 'transform 0.3s ease'
        });
        
        $('body').append(notification);
        
        // Anima entrada
        setTimeout(() => {
            notification.css('transform', 'translateX(0)');
        }, 100);
        
        // Remove após 3 segundos
        setTimeout(() => {
            notification.css('transform', 'translateX(100%)');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Utilitários para analytics
     */
    function trackEvent(action, category = 'Single Post', label = '') {
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                'event_category': category,
                'event_label': label
            });
        }
    }

    // Tracking de eventos importantes
    $(document).on('click', '.share-facebook', () => trackEvent('Share', 'Social', 'Facebook'));
    $(document).on('click', '.share-twitter', () => trackEvent('Share', 'Social', 'Twitter'));
    $(document).on('click', '.share-whatsapp', () => trackEvent('Share', 'Social', 'WhatsApp'));
    $(document).on('click', '.share-linkedin', () => trackEvent('Share', 'Social', 'LinkedIn'));
    $(document).on('click', '.share-copy', () => trackEvent('Share', 'Social', 'Copy Link'));
    $(document).on('click', '.print-article', () => trackEvent('Print', 'Article'));

})(jQuery); 