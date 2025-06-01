<?php
/**
 * Template Part: Featured Stories
 * Histórias em destaque - Estilo Globo.com
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações da seção
$section_config = [
    'title' => 'Histórias',
    'subtitle' => 'Conteúdo Especial',
    'description' => 'Histórias exclusivas sobre a cultura pernambucana, com narrativas envolventes e experiências autênticas do nosso povo.',
    'color' => 'purple-600', // Roxo
    'featured_limit' => 1,
    'secondary_limit' => 5
];

// Buscar posts em destaque
$featured_query = new WP_Query([
    'post_type' => ['post', 'historias'],
    'posts_per_page' => $section_config['featured_limit'],
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => '_recifemais_featured',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Se não houver posts em destaque, buscar os mais recentes
if (!$featured_query->have_posts()) {
    $featured_query = new WP_Query([
        'post_type' => ['post', 'historias'],
        'posts_per_page' => $section_config['featured_limit'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Buscar posts secundários (excluindo o featured)
$featured_ids = wp_list_pluck($featured_query->posts, 'ID');
$secondary_query = new WP_Query([
    'post_type' => ['post', 'historias'],
    'posts_per_page' => $section_config['secondary_limit'],
    'post_status' => 'publish',
    'post__not_in' => $featured_ids,
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Contar total de histórias
$total_stories = $featured_query->found_posts + $secondary_query->found_posts;
?>

<section class="featured-stories-section py-12 lg:py-16 bg-gray-50" id="historias" role="region" aria-label="Histórias em Destaque">
    <div class="container mx-auto px-4">
        
        <!-- Cabeçalho da Seção - Mesmo padrão de Roteiros -->
        <div class="section-header mb-12 lg:mb-16">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-end">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-8 bg-<?php echo $section_config['color']; ?> mr-3"></div>
                        <span class="text-sm font-semibold text-<?php echo $section_config['color']; ?> uppercase tracking-wide">
                            <?php echo esc_html($section_config['title']); ?>
                        </span>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <?php echo esc_html($section_config['subtitle']); ?>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl">
                        <?php echo esc_html($section_config['description']); ?>
                    </p>
                </div>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-<?php echo $section_config['color']; ?> mb-1">
                            <?php echo $total_stories; ?>+
                        </div>
                        <div class="text-sm text-gray-600">Histórias</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-red-600 mb-1">
                            <?php echo date('Y') - 1537; ?>
                        </div>
                        <div class="text-sm text-gray-600">Anos de História</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Coluna Principal - História em Destaque -->
            <div class="lg:col-span-2">
                <?php if ($featured_query->have_posts()) : ?>
                    <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                        <article class="featured-story group cursor-pointer bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-500" 
                                 onclick="window.location.href='<?php echo get_permalink(); ?>'">
                            
                            <!-- Imagem Principal -->
                            <div class="relative aspect-video overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700',
                                        'loading' => 'eager'
                                    ]); ?>
                                <?php else : ?>
                                    <div class="w-full h-full bg-gradient-to-br from-<?php echo $section_config['color']; ?>/20 to-<?php echo $section_config['color']; ?>/40 flex items-center justify-center">
                                        <span class="text-<?php echo $section_config['color']; ?> text-8xl font-bold opacity-50">H</span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Overlay com categoria -->
                                <div class="absolute top-6 left-6">
                                    <?php 
                                    $primary_category = recifemais_get_primary_category(get_the_ID());
                                    if ($primary_category) :
                                    ?>
                                        <span class="bg-<?php echo $section_config['color']; ?> text-white px-4 py-2 rounded-full text-sm font-medium">
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
                                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-<?php echo $section_config['color']; ?> transition-colors line-clamp-2">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <p class="text-gray-600 text-lg leading-relaxed line-clamp-3">
                                        <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                                    </p>
                                    
                                    <!-- Meta informações -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span><?php echo get_the_date('d/m/Y'); ?></span>
                                            <span><?php echo recifemais_reading_time(get_the_ID()); ?> min de leitura</span>
                                        </div>
                                        
                                        <!-- Autor -->
                                        <div class="flex items-center gap-2">
                                            <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'w-8 h-8 rounded-full']); ?>
                                            <span class="text-sm text-gray-600 font-medium">
                                                <?php the_author(); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
            
            <!-- Coluna Lateral - Mais Histórias (Simplificada) -->
            <div class="space-y-6">
                <h4 class="text-xl font-bold text-gray-900 border-b-2 border-<?php echo $section_config['color']; ?> pb-2">
                    Mais Histórias
                </h4>
                
                <?php if ($secondary_query->have_posts()) : ?>
                    <div class="space-y-4">
                        <?php while ($secondary_query->have_posts()) : $secondary_query->the_post(); ?>
                            <article class="story-item group cursor-pointer" 
                                     onclick="window.location.href='<?php echo get_permalink(); ?>'">
                                <div class="flex gap-3">
                                    <!-- Thumbnail -->
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('thumbnail', [
                                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                                    'loading' => 'lazy'
                                                ]); ?>
                                            <?php else : ?>
                                                <div class="w-full h-full bg-gradient-to-br from-<?php echo $section_config['color']; ?>/20 to-<?php echo $section_config['color']; ?>/40 flex items-center justify-center">
                                                    <span class="text-<?php echo $section_config['color']; ?> text-sm font-bold">H</span>
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
                                            <span class="inline-block bg-<?php echo $section_config['color']; ?>/10 text-<?php echo $section_config['color']; ?> px-2 py-1 rounded text-xs font-medium mb-1">
                                                <?php echo esc_html($primary_category->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <!-- Título -->
                                        <h5 class="font-semibold text-gray-900 group-hover:text-<?php echo $section_config['color']; ?> transition-colors line-clamp-2 text-sm leading-tight">
                                            <?php the_title(); ?>
                                        </h5>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Link Ver Mais -->
                <div class="pt-6 border-t border-gray-100">
                    <a href="/historias" 
                       class="inline-flex items-center gap-2 text-<?php echo $section_config['color']; ?> hover:text-<?php echo $section_config['color']; ?>/80 font-medium text-sm transition-colors">
                        Ver todas as histórias
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Estilos específicos da seção Featured Stories */
.featured-stories-section .featured-story:hover {
    transform: translateY(-4px);
}

.featured-stories-section .story-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 1rem;
    padding: 1rem;
    margin: -1rem;
}

/* Animações de entrada */
.featured-stories-section .featured-story,
.featured-stories-section .story-item {
    animation: fadeInUp 0.6s ease-out;
}

.featured-stories-section .story-item:nth-child(1) { animation-delay: 0.1s; }
.featured-stories-section .story-item:nth-child(2) { animation-delay: 0.2s; }
.featured-stories-section .story-item:nth-child(3) { animation-delay: 0.3s; }
.featured-stories-section .story-item:nth-child(4) { animation-delay: 0.4s; }
.featured-stories-section .story-item:nth-child(5) { animation-delay: 0.5s; }

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
    .featured-stories-section .grid.lg\\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .featured-stories-section .lg\\:col-span-2 {
        order: 1;
        margin-bottom: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics tracking
    if (typeof gtag !== 'undefined') {
        // Track story clicks
        document.querySelectorAll('.featured-story, .story-item').forEach(story => {
            story.addEventListener('click', function() {
                const title = this.querySelector('h3, h5').textContent;
                gtag('event', 'story_click', {
                    'story_title': title,
                    'section': 'featured_stories'
                });
            });
        });
    }
    
    // Lazy loading para imagens
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px'
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});
</script> 