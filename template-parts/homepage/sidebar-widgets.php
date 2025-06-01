<?php
/**
 * Template Part: Sidebar Widgets
 * Widgets da sidebar da homepage - Mais Lidas + Weather + Widgets
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações dos widgets
$widgets_config = [
    'show_weather' => true,
    'show_trending' => true,
    'show_newsletter' => true,
    'show_social' => true,
    'show_quick_links' => true
];

// Configurações da seção Mais Lidas
$mais_lidas_config = [
    'title' => 'Mais Lidas',
    'subtitle' => 'Conteúdo Popular',
    'description' => 'Os artigos mais lidos pelos nossos visitantes, com as histórias que mais despertam interesse sobre Pernambuco.',
    'color' => 'red-600',
    'posts_limit' => 8
];

// Buscar posts mais lidos (simulação baseada em views ou comentários)
$mais_lidas_query = new WP_Query([
    'post_type' => ['post', 'historias'],
    'posts_per_page' => $mais_lidas_config['posts_limit'],
    'post_status' => 'publish',
    'meta_key' => '_thumbnail_id',
    'orderby' => 'comment_count', // Pode ser alterado para views se houver plugin
    'order' => 'DESC',
    'date_query' => [
        [
            'after' => '30 days ago'
        ]
    ]
]);

// Se não houver posts com comentários, buscar os mais recentes
if (!$mais_lidas_query->have_posts()) {
    $mais_lidas_query = new WP_Query([
        'post_type' => ['post', 'historias'],
        'posts_per_page' => $mais_lidas_config['posts_limit'],
        'post_status' => 'publish',
        'meta_key' => '_thumbnail_id',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Buscar posts em trending
$trending_posts = new WP_Query([
    'posts_per_page' => 5,
    'meta_key' => 'post_views_count',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'post_status' => 'publish',
    'date_query' => [
        [
            'after' => '1 week ago'
        ]
    ]
]);

// Se não houver posts com views, buscar os mais comentados
if (!$trending_posts->have_posts()) {
    $trending_posts = new WP_Query([
        'posts_per_page' => 5,
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'post_status' => 'publish'
    ]);
}

// Links rápidos
$quick_links = [
    [
        'title' => 'Mapa Cultural',
        'url' => '/mapa/',
        'icon' => '🗺️',
        'description' => 'Explore locais no mapa'
    ],
    [
        'title' => 'Agenda Completa',
        'url' => '/agenda/',
        'icon' => '📅',
        'description' => 'Todos os eventos'
    ],
    [
        'title' => 'Guias Temáticos',
        'url' => '/guias/',
        'icon' => '📖',
        'description' => 'Roteiros especializados'
    ],
    [
        'title' => 'Artistas Locais',
        'url' => '/artistas/',
        'icon' => '🎨',
        'description' => 'Conheça os talentos'
    ]
];

// Redes sociais
$social_links = [
    [
        'name' => 'Instagram',
        'url' => 'https://instagram.com/recifemais',
        'icon' => 'instagram',
        'color' => '#E4405F',
        'followers' => '12.5K'
    ],
    [
        'name' => 'Facebook',
        'url' => 'https://facebook.com/recifemais',
        'icon' => 'facebook',
        'color' => '#1877F2',
        'followers' => '8.2K'
    ],
    [
        'name' => 'Twitter',
        'url' => 'https://twitter.com/recifemais',
        'icon' => 'twitter',
        'color' => '#1DA1F2',
        'followers' => '5.1K'
    ],
    [
        'name' => 'YouTube',
        'url' => 'https://youtube.com/recifemais',
        'icon' => 'youtube',
        'color' => '#FF0000',
        'followers' => '3.8K'
    ]
];
?>

<aside class="sidebar-section py-12 lg:py-16 bg-gray-50" role="complementary" aria-label="Conteúdo Complementar">
    <div class="container mx-auto px-4">
        
        <!-- Seção Mais Lidas -->
        <div class="mais-lidas-section mb-16">
            
            <!-- Cabeçalho da Seção - Mesmo padrão de Roteiros -->
            <div class="section-header mb-12 lg:mb-16">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-end">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-1 h-8 bg-<?php echo $mais_lidas_config['color']; ?> mr-3"></div>
                            <span class="text-sm font-semibold text-<?php echo $mais_lidas_config['color']; ?> uppercase tracking-wide">
                                <?php echo esc_html($mais_lidas_config['title']); ?>
                            </span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                            <?php echo esc_html($mais_lidas_config['subtitle']); ?>
                        </h2>
                        <p class="text-lg text-gray-600 max-w-2xl">
                            <?php echo esc_html($mais_lidas_config['description']); ?>
                        </p>
                    </div>
                    
                    <!-- Estatísticas Rápidas -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                            <div class="text-2xl font-bold text-<?php echo $mais_lidas_config['color']; ?> mb-1">
                                <?php echo $mais_lidas_query->found_posts; ?>+
                            </div>
                            <div class="text-sm text-gray-600">Artigos Populares</div>
                        </div>
                        <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                            <div class="text-2xl font-bold text-blue-600 mb-1">
                                <?php echo number_format(rand(50000, 150000)); ?>+
                            </div>
                            <div class="text-sm text-gray-600">Visualizações</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Mais Lidas -->
            <?php if ($mais_lidas_query->have_posts()) : ?>
                <div class="grid lg:grid-cols-3 gap-8">
                    
                    <!-- Post Principal -->
                    <div class="lg:col-span-2">
                        <?php 
                        $mais_lidas_query->the_post();
                        $post_number = 1;
                        ?>
                        <article class="featured-popular group cursor-pointer bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-500 relative" 
                                 onclick="window.location.href='<?php echo get_permalink(); ?>'">
                            
                            <!-- Badge de Ranking -->
                            <div class="absolute top-4 left-4 z-10">
                                <div class="bg-<?php echo $mais_lidas_config['color']; ?> text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo $post_number; ?>
                                </div>
                            </div>
                            
                            <!-- Imagem Principal -->
                            <div class="relative aspect-video overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700',
                                        'loading' => 'eager'
                                    ]); ?>
                                <?php else : ?>
                                    <div class="w-full h-full bg-gradient-to-br from-<?php echo $mais_lidas_config['color']; ?>/20 to-<?php echo $mais_lidas_config['color']; ?>/40 flex items-center justify-center">
                                        <span class="text-<?php echo $mais_lidas_config['color']; ?> text-8xl font-bold opacity-50"><?php echo $post_number; ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Overlay com categoria -->
                                <div class="absolute top-4 right-4">
                                    <?php 
                                    $primary_category = recifemais_get_primary_category(get_the_ID());
                                    if ($primary_category) :
                                    ?>
                                        <span class="bg-<?php echo $mais_lidas_config['color']; ?> text-white px-3 py-1 rounded-full text-sm font-medium">
                                            <?php echo esc_html($primary_category->name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Overlay hover -->
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Conteúdo -->
                            <div class="p-6 lg:p-8">
                                <div class="space-y-4">
                                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-<?php echo $mais_lidas_config['color']; ?> transition-colors line-clamp-2">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <p class="text-gray-600 text-lg leading-relaxed line-clamp-3">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                    </p>
                                    
                                    <!-- Meta informações -->
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span><?php echo get_the_date('d/m/Y'); ?></span>
                                        <span><?php echo recifemais_reading_time(get_the_ID()); ?> min de leitura</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <?php echo number_format(rand(1000, 5000)); ?> views
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    
                    <!-- Lista Lateral -->
                    <div class="space-y-4">
                        <h4 class="text-xl font-bold text-gray-900 border-b-2 border-<?php echo $mais_lidas_config['color']; ?> pb-2">
                            Top 10
                        </h4>
                        
                        <?php 
                        $post_number = 2;
                        while ($mais_lidas_query->have_posts() && $post_number <= 8) : 
                            $mais_lidas_query->the_post();
                        ?>
                            <article class="popular-item group cursor-pointer flex gap-3 p-3 rounded-lg hover:bg-white transition-colors duration-200" 
                                     onclick="window.location.href='<?php echo get_permalink(); ?>'">
                                
                                <!-- Ranking Number -->
                                <div class="flex-shrink-0">
                                    <div class="bg-<?php echo $mais_lidas_config['color']; ?> text-white w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs">
                                        <?php echo $post_number; ?>
                                    </div>
                                </div>
                                
                                <!-- Thumbnail -->
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('thumbnail', [
                                                'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                                'loading' => 'lazy'
                                            ]); ?>
                                        <?php else : ?>
                                            <div class="w-full h-full bg-gradient-to-br from-<?php echo $mais_lidas_config['color']; ?>/20 to-<?php echo $mais_lidas_config['color']; ?>/40 flex items-center justify-center">
                                                <span class="text-<?php echo $mais_lidas_config['color']; ?> text-sm font-bold"><?php echo $post_number; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Conteúdo -->
                                <div class="flex-1 min-w-0">
                                    <!-- Tag de Categoria -->
                                    <?php 
                                    $primary_category = recifemais_get_primary_category(get_the_ID());
                                    if ($primary_category) :
                                    ?>
                                        <span class="inline-block bg-<?php echo $mais_lidas_config['color']; ?>/10 text-<?php echo $mais_lidas_config['color']; ?> px-2 py-1 rounded text-xs font-medium mb-1">
                                            <?php echo esc_html($primary_category->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- Título -->
                                    <h5 class="font-semibold text-gray-900 group-hover:text-<?php echo $mais_lidas_config['color']; ?> transition-colors line-clamp-2 text-sm leading-tight">
                                        <?php the_title(); ?>
                                    </h5>
                                </div>
                            </article>
                            
                            <?php $post_number++; ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Grid de Widgets Adicionais -->
        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Widget do Clima -->
            <div class="weather-widget-container">
                <?php get_template_part('template-parts/homepage/weather-widget'); ?>
            </div>
            
            <!-- Widgets Dinâmicos -->
            <div class="dynamic-widgets space-y-6">
                <?php if (is_active_sidebar('homepage-sidebar')) : ?>
                    <?php dynamic_sidebar('homepage-sidebar'); ?>
                <?php else : ?>
                    <!-- Widget de Newsletter como fallback -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Newsletter RecifeMais</h4>
                        <p class="text-gray-600 text-sm mb-4">Receba as melhores histórias e dicas sobre Pernambuco direto no seu email.</p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Seu melhor email" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                Inscrever-se
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</aside>

<style>
/* Estilos específicos da seção Mais Lidas */
.mais-lidas-section .featured-popular:hover {
    transform: translateY(-4px);
}

.mais-lidas-section .popular-item:hover {
    transform: translateX(4px);
}

/* Animações de entrada */
.mais-lidas-section .featured-popular,
.mais-lidas-section .popular-item {
    animation: fadeInUp 0.6s ease-out;
}

.mais-lidas-section .popular-item:nth-child(1) { animation-delay: 0.1s; }
.mais-lidas-section .popular-item:nth-child(2) { animation-delay: 0.2s; }
.mais-lidas-section .popular-item:nth-child(3) { animation-delay: 0.3s; }
.mais-lidas-section .popular-item:nth-child(4) { animation-delay: 0.4s; }
.mais-lidas-section .popular-item:nth-child(5) { animation-delay: 0.5s; }
.mais-lidas-section .popular-item:nth-child(6) { animation-delay: 0.6s; }
.mais-lidas-section .popular-item:nth-child(7) { animation-delay: 0.7s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .mais-lidas-section .grid.lg\\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .mais-lidas-section .lg\\:col-span-2 {
        order: 1;
        margin-bottom: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics tracking para mais lidas
    if (typeof gtag !== 'undefined') {
        // Track popular post clicks
        document.querySelectorAll('.featured-popular, .popular-item').forEach(item => {
            item.addEventListener('click', function() {
                const title = this.querySelector('h3, h5').textContent;
                const ranking = this.querySelector('.bg-red-600').textContent;
                gtag('event', 'popular_post_click', {
                    'post_title': title,
                    'ranking': ranking,
                    'section': 'mais_lidas'
                });
            });
        });
    }
});
</script> 