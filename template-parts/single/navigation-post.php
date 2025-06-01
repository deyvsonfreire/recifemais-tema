<?php
/**
 * Template Part: Navigation Post
 * 
 * Navegação entre posts com:
 * - Links para post anterior e próximo
 * - Thumbnails dos posts
 * - Informações contextuais
 * - Navegação por categoria
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_thumbnails' => true,
    'show_category' => true,
    'show_date' => true,
    'show_excerpt' => false,
    'same_category' => false,
    'layout' => 'horizontal', // horizontal, vertical, minimal
    'show_back_to_archive' => true
]);

// Obter posts adjacentes
if ($args['same_category']) {
    $prev_post = get_previous_post(true);
    $next_post = get_next_post(true);
} else {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
}

// Se não tiver posts adjacentes, não exibir
if (!$prev_post && !$next_post && !$args['show_back_to_archive']) {
    return;
}

// Dados do post atual para contexto
$current_categories = get_the_category();
$archive_url = !empty($current_categories) ? get_category_link($current_categories[0]) : home_url('/noticias/');
$archive_name = !empty($current_categories) ? $current_categories[0]->name : 'Notícias';
?>

<nav class="post-navigation bg-gray-50 border-t border-gray-200 py-8 mt-12" aria-label="Navegação entre posts">
    <div class="container mx-auto px-4">
        
        <?php if ($args['show_back_to_archive']): ?>
            <!-- Voltar para Arquivo -->
            <div class="text-center mb-8">
                <a href="<?php echo esc_url($archive_url); ?>" 
                   class="inline-flex items-center gap-2 text-recife-primary hover:text-recife-primary/80 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Voltar para <?php echo esc_html($archive_name); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if ($prev_post || $next_post): ?>
            <!-- Navegação Principal -->
            <div class="grid grid-cols-1 <?php echo ($prev_post && $next_post) ? 'lg:grid-cols-2' : 'lg:grid-cols-1'; ?> gap-6">
                
                <?php if ($prev_post): ?>
                    <!-- Post Anterior -->
                    <div class="nav-post-item prev-post">
                        <a href="<?php echo get_permalink($prev_post); ?>" 
                           class="block bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300 group">
                            
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Post Anterior</span>
                            </div>
                            
                            <div class="flex gap-4">
                                <?php if ($args['show_thumbnails']): 
                                    $prev_thumbnail = get_the_post_thumbnail_url($prev_post, 'medium');
                                    ?>
                                    <?php if ($prev_thumbnail): ?>
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200">
                                                <img src="<?php echo esc_url($prev_thumbnail); ?>" 
                                                     alt="<?php echo esc_attr($prev_post->post_title); ?>"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <?php if ($args['show_category']): 
                                        $prev_categories = get_the_category($prev_post);
                                        ?>
                                        <?php if (!empty($prev_categories)): ?>
                                            <div class="mb-2">
                                                <span class="inline-block text-xs font-semibold text-recife-primary uppercase tracking-wide">
                                                    <?php echo esc_html($prev_categories[0]->name); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-2 group-hover:text-recife-primary transition-colors">
                                        <?php echo esc_html($prev_post->post_title); ?>
                                    </h3>
                                    
                                    <?php if ($args['show_date']): ?>
                                        <time class="text-sm text-gray-500" datetime="<?php echo get_the_date('c', $prev_post); ?>">
                                            <?php echo get_the_date('d/m/Y', $prev_post); ?>
                                        </time>
                                    <?php endif; ?>
                                    
                                    <?php if ($args['show_excerpt']): 
                                        $prev_excerpt = get_the_excerpt($prev_post);
                                        ?>
                                        <?php if ($prev_excerpt): ?>
                                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                                <?php echo esc_html($prev_excerpt); ?>
                                            </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($next_post): ?>
                    <!-- Próximo Post -->
                    <div class="nav-post-item next-post">
                        <a href="<?php echo get_permalink($next_post); ?>" 
                           class="block bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300 group">
                            
                            <div class="flex items-center justify-end gap-2 text-sm text-gray-500 mb-3">
                                <span class="font-medium">Próximo Post</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="flex-1 min-w-0 text-right">
                                    <?php if ($args['show_category']): 
                                        $next_categories = get_the_category($next_post);
                                        ?>
                                        <?php if (!empty($next_categories)): ?>
                                            <div class="mb-2">
                                                <span class="inline-block text-xs font-semibold text-recife-primary uppercase tracking-wide">
                                                    <?php echo esc_html($next_categories[0]->name); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-2 group-hover:text-recife-primary transition-colors">
                                        <?php echo esc_html($next_post->post_title); ?>
                                    </h3>
                                    
                                    <?php if ($args['show_date']): ?>
                                        <time class="text-sm text-gray-500" datetime="<?php echo get_the_date('c', $next_post); ?>">
                                            <?php echo get_the_date('d/m/Y', $next_post); ?>
                                        </time>
                                    <?php endif; ?>
                                    
                                    <?php if ($args['show_excerpt']): 
                                        $next_excerpt = get_the_excerpt($next_post);
                                        ?>
                                        <?php if ($next_excerpt): ?>
                                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                                <?php echo esc_html($next_excerpt); ?>
                                            </p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($args['show_thumbnails']): 
                                    $next_thumbnail = get_the_post_thumbnail_url($next_post, 'medium');
                                    ?>
                                    <?php if ($next_thumbnail): ?>
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200">
                                                <img src="<?php echo esc_url($next_thumbnail); ?>" 
                                                     alt="<?php echo esc_attr($next_post->post_title); ?>"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Navegação Simplificada (Mobile) -->
            <div class="lg:hidden mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <?php if ($prev_post): ?>
                        <a href="<?php echo get_permalink($prev_post); ?>" 
                           class="flex items-center gap-2 text-recife-primary hover:text-recife-primary/80 font-medium transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Anterior
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    
                    <?php if ($next_post): ?>
                        <a href="<?php echo get_permalink($next_post); ?>" 
                           class="flex items-center gap-2 text-recife-primary hover:text-recife-primary/80 font-medium transition-colors">
                            Próximo
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Navegação por Categoria (se aplicável) -->
        <?php if ($args['same_category'] && !empty($current_categories)): ?>
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600 mb-4">
                    Navegando por posts da categoria: 
                    <strong class="text-recife-primary"><?php echo esc_html($current_categories[0]->name); ?></strong>
                </p>
                
                <div class="flex justify-center gap-4">
                    <a href="<?php echo get_category_link($current_categories[0]); ?>" 
                       class="inline-flex items-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        Ver Todos em <?php echo esc_html($current_categories[0]->name); ?>
                    </a>
                    
                    <a href="<?php echo home_url('/noticias/'); ?>" 
                       class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Todas as Notícias
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.nav-post-item {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
}

.nav-post-item.next-post {
    animation-delay: 0.2s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover effects */
.nav-post-item:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .nav-post-item .flex {
        flex-direction: column;
        text-align: center;
    }
    
    .nav-post-item .next-post .flex-1 {
        text-align: center;
    }
}
</style>

<script>
// Analytics para navegação
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-post-item a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            const isNext = this.closest('.next-post') !== null;
            const direction = isNext ? 'next' : 'previous';
            
            // Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'post_navigation', {
                    'direction': direction,
                    'current_post_id': '<?php echo get_the_ID(); ?>',
                    'target_url': this.href
                });
            }
            
            console.log('Post navigation:', direction, this.href);
        });
    });
});
</script> 