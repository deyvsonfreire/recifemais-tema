<?php
/**
 * Template Part: Archive Content Grid
 * 
 * Grid responsivo para exibição de conteúdo em archives com:
 * - Layout adaptativo por tipo de conteúdo
 * - Cards otimizados para cada CPT
 * - Lazy loading de imagens
 * - Estados de loading e vazio
 * - Infinite scroll opcional
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Detectar contexto atual
$current_post_type = get_post_type();
$queried_object = get_queried_object();

// Configurações de grid por tipo de conteúdo
$grid_configs = [
    'eventos_festivais' => [
        'columns' => 'lg:grid-cols-3',
        'card_type' => 'evento',
        'show_meta' => ['date', 'location', 'price'],
        'featured_size' => 'large'
    ],
    'lugares' => [
        'columns' => 'lg:grid-cols-4',
        'card_type' => 'lugar',
        'show_meta' => ['location', 'type', 'rating'],
        'featured_size' => 'medium'
    ],
    'artistas' => [
        'columns' => 'lg:grid-cols-4',
        'card_type' => 'artista',
        'show_meta' => ['area', 'location', 'experience'],
        'featured_size' => 'medium'
    ],
    'roteiros' => [
        'columns' => 'lg:grid-cols-3',
        'card_type' => 'roteiro',
        'show_meta' => ['duration', 'difficulty', 'category'],
        'featured_size' => 'large'
    ],
    'post' => [
        'columns' => 'lg:grid-cols-3',
        'card_type' => 'post',
        'show_meta' => ['date', 'author', 'category'],
        'featured_size' => 'large'
    ]
];

// Configuração para o tipo atual
$config = $grid_configs[$current_post_type] ?? $grid_configs['post'];

// Verificar se há posts
global $wp_query;
$has_posts = have_posts();
$total_posts = $wp_query->found_posts ?? 0;
$current_page = get_query_var('paged') ?: 1;
$posts_per_page = get_query_var('posts_per_page') ?: get_option('posts_per_page');
$max_pages = $wp_query->max_num_pages ?? 1;

// Calcular estatísticas de exibição
$showing_start = (($current_page - 1) * $posts_per_page) + 1;
$showing_end = min($current_page * $posts_per_page, $total_posts);
?>

<div class="archive-content-grid" id="content-grid">
    <?php if ($has_posts): ?>
        <!-- Header do Grid -->
        <div class="grid-header flex items-center justify-between mb-6">
            <div class="grid-info">
                <p class="text-sm text-gray-600">
                    Exibindo <strong><?php echo number_format($showing_start); ?>-<?php echo number_format($showing_end); ?></strong> 
                    de <strong><?php echo number_format($total_posts); ?></strong> resultados
                    <?php if ($current_page > 1): ?>
                        (Página <?php echo $current_page; ?> de <?php echo $max_pages; ?>)
                    <?php endif; ?>
                </p>
            </div>
            
            <!-- View Toggle -->
            <div class="view-toggle flex items-center gap-2">
                <span class="text-sm text-gray-600">Visualização:</span>
                <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                    <button type="button" 
                            class="view-btn active px-3 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                            data-view="grid"
                            title="Visualização em grade">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            class="view-btn px-3 py-2 text-sm bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                            data-view="list"
                            title="Visualização em lista">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Grid de Conteúdo -->
        <div class="content-grid grid grid-cols-1 md:grid-cols-2 <?php echo $config['columns']; ?> gap-6 mb-8" 
             data-view="grid" 
             data-post-type="<?php echo esc_attr($current_post_type); ?>">
            
            <?php 
            $post_index = 0;
            while (have_posts()): 
                the_post();
                $post_index++;
                
                // Determinar se é post em destaque (primeiro da primeira página)
                $is_featured = ($current_page === 1 && $post_index === 1 && $config['featured_size'] === 'large');
                
                // Classes do item
                $item_classes = [
                    'grid-item',
                    'animate-fade-in-up',
                    'transition-all',
                    'duration-300'
                ];
                
                if ($is_featured) {
                    $item_classes[] = 'md:col-span-2';
                    $item_classes[] = 'featured-item';
                }
                
                // Delay de animação escalonado
                $animation_delay = min($post_index * 100, 800);
                ?>
                
                <article class="<?php echo implode(' ', $item_classes); ?>" 
                         style="animation-delay: <?php echo $animation_delay; ?>ms"
                         data-post-id="<?php echo get_the_ID(); ?>"
                         data-post-type="<?php echo get_post_type(); ?>">
                    
                    <?php
                    // Renderizar card apropriado baseado no tipo
                    switch ($config['card_type']) {
                        case 'evento':
                            get_template_part('components/cards/card-evento', null, [
                                'post_id' => get_the_ID(),
                                'size' => $is_featured ? 'large' : 'medium',
                                'show_meta' => $config['show_meta']
                            ]);
                            break;
                            
                        case 'lugar':
                            get_template_part('components/cards/card-lugar', null, [
                                'post_id' => get_the_ID(),
                                'size' => $is_featured ? 'large' : 'medium',
                                'show_meta' => $config['show_meta']
                            ]);
                            break;
                            
                        case 'artista':
                            // Usar card específico para artistas
                            get_template_part('components/cards/card-artista', null, [
                                'post_id' => get_the_ID(),
                                'variant' => $is_featured ? 'hero' : 'standard',
                                'size' => $is_featured ? 'lg' : 'md',
                                'show_meta' => true,
                                'show_social' => true
                            ]);
                            break;
                            
                        case 'roteiro':
                            get_template_part('components/cards/card-roteiro', null, [
                                'post_id' => get_the_ID(),
                                'variant' => $is_featured ? 'hero' : 'standard',
                                'size' => $is_featured ? 'lg' : 'md',
                                'show_meta' => true
                            ]);
                            break;
                            
                        default:
                            get_template_part('components/cards/card-post', null, [
                                'post_id' => get_the_ID(),
                                'size' => $is_featured ? 'large' : 'medium',
                                'show_meta' => $config['show_meta']
                            ]);
                            break;
                    }
                    ?>
                </article>
                
            <?php endwhile; ?>
        </div>
        
        <!-- Loading Indicator para Infinite Scroll -->
        <div id="loading-indicator" class="hidden text-center py-8">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-white rounded-lg shadow-sm border">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-600 border-t-transparent"></div>
                <span class="text-sm text-gray-600">Carregando mais conteúdo...</span>
            </div>
        </div>
        
        <!-- Infinite Scroll Trigger -->
        <div id="infinite-scroll-trigger" class="h-1" data-page="<?php echo $current_page; ?>" data-max-pages="<?php echo $max_pages; ?>"></div>
        
    <?php else: ?>
        <!-- Estado Vazio -->
        <?php get_template_part('template-parts/archive/no-results'); ?>
    <?php endif; ?>
</div>

<!-- CSS específico para o grid -->
<style>
.archive-content-grid {
    min-height: 400px;
}

.content-grid {
    transition: all 0.3s ease;
}

.content-grid[data-view="list"] {
    display: block;
}

.content-grid[data-view="list"] .grid-item {
    margin-bottom: 1.5rem;
}

.grid-item {
    opacity: 0;
    transform: translateY(20px);
}

.grid-item.animate-fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.featured-item {
    position: relative;
}

.featured-item::before {
    content: "Destaque";
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 10;
    background: linear-gradient(135deg, #e11d48, #be123c);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.view-btn.active {
    background-color: #2563eb;
    color: white;
}

.view-btn:not(.active) {
    background-color: white;
    color: #374151;
}

/* Loading states */
.grid-item.loading {
    opacity: 0.6;
    pointer-events: none;
}

.grid-item.loading::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .view-toggle {
        align-self: flex-end;
    }
}

/* Print styles */
@media print {
    .view-toggle,
    #loading-indicator,
    #infinite-scroll-trigger {
        display: none !important;
    }
    
    .content-grid {
        display: block !important;
    }
    
    .grid-item {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
}
</style>

<!-- JavaScript para funcionalidades do grid -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.querySelector('.content-grid');
    const viewButtons = document.querySelectorAll('.view-btn');
    const loadingIndicator = document.getElementById('loading-indicator');
    const infiniteScrollTrigger = document.getElementById('infinite-scroll-trigger');
    
    // Toggle de visualização
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Atualizar botões
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Atualizar grid
            grid.dataset.view = view;
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'view_toggle', {
                    'view_type': view,
                    'page_location': window.location.href
                });
            }
            
            // Salvar preferência
            localStorage.setItem('archive_view_preference', view);
        });
    });
    
    // Restaurar preferência de visualização
    const savedView = localStorage.getItem('archive_view_preference');
    if (savedView) {
        const targetButton = document.querySelector(`[data-view="${savedView}"]`);
        if (targetButton) {
            targetButton.click();
        }
    }
    
    // Infinite Scroll
    if (infiniteScrollTrigger) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadMorePosts();
                }
            });
        }, {
            rootMargin: '100px'
        });
        
        observer.observe(infiniteScrollTrigger);
    }
    
    // Função para carregar mais posts
    async function loadMorePosts() {
        const currentPage = parseInt(infiniteScrollTrigger.dataset.page);
        const maxPages = parseInt(infiniteScrollTrigger.dataset.maxPages);
        
        if (currentPage >= maxPages) return;
        
        const nextPage = currentPage + 1;
        loadingIndicator.classList.remove('hidden');
        
        try {
            const url = new URL(window.location);
            url.searchParams.set('paged', nextPage);
            
            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newItems = doc.querySelectorAll('.grid-item');
            
            // Adicionar novos items com animação
            newItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 100}ms`;
                grid.appendChild(item);
            });
            
            // Atualizar página atual
            infiniteScrollTrigger.dataset.page = nextPage;
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'infinite_scroll', {
                    'page_number': nextPage,
                    'total_pages': maxPages
                });
            }
            
        } catch (error) {
            console.error('Erro ao carregar mais posts:', error);
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }
    
    // Lazy loading de imagens
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            }
        });
    });
    
    // Observar imagens lazy
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
    
    // Analytics para cliques em cards
    grid.addEventListener('click', function(e) {
        const card = e.target.closest('.grid-item');
        if (card) {
            const postId = card.dataset.postId;
            const postType = card.dataset.postType;
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'card_click', {
                    'post_id': postId,
                    'post_type': postType,
                    'page_location': window.location.href
                });
            }
        }
    });
});
</script> 