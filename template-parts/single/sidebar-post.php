<?php
/**
 * Template Part: Sidebar Post
 * Sidebar contextual para posts com widgets relevantes
 *
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do post atual
$post_id = get_the_ID();
$categories = get_the_category($post_id);
$primary_cat = !empty($categories) ? $categories[0] : null;
?>

<aside class="single-sidebar" role="complementary">
    
    <!-- Widget: Outros Posts da Categoria -->
    <?php if ($primary_cat) : ?>
        <div class="sidebar-widget bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6 mb-8">
            <h3 class="widget-title text-lg font-semibold text-recife-gray-900 mb-4 pb-2 border-b border-recife-gray-200">
                📂 Mais em <?php echo esc_html($primary_cat->name); ?>
            </h3>
            
            <?php
            $related_posts = get_posts(array(
                'category' => $primary_cat->term_id,
                'numberposts' => 5,
                'post__not_in' => array($post_id),
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($related_posts) : ?>
                <div class="related-posts-list space-y-4">
                    <?php foreach ($related_posts as $related_post) : ?>
                        <article class="related-post-item group">
                            <div class="flex gap-3">
                                <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                    <div class="flex-shrink-0">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" class="block">
                                            <img src="<?php echo get_the_post_thumbnail_url($related_post->ID, 'thumbnail'); ?>" 
                                                 alt="<?php echo esc_attr($related_post->post_title); ?>"
                                                 class="w-16 h-16 rounded-lg object-cover group-hover:scale-105 transition-transform">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-recife-gray-900 leading-tight mb-1">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" 
                                           class="hover:text-recife-primary transition-colors line-clamp-2">
                                            <?php echo esc_html($related_post->post_title); ?>
                                        </a>
                                    </h4>
                                    <p class="text-xs text-recife-gray-500">
                                        📅 <?php echo get_the_date('d/m/Y', $related_post->ID); ?>
                                    </p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-4 pt-4 border-t border-recife-gray-200">
                    <a href="<?php echo get_category_link($primary_cat->term_id); ?>" 
                       class="text-sm text-recife-primary hover:text-recife-primary-dark font-medium transition-colors">
                        Ver todos em <?php echo esc_html($primary_cat->name); ?> →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Widget: Newsletter -->
    <div class="sidebar-widget bg-gradient-to-br from-recife-primary to-recife-secondary rounded-lg p-6 mb-8 text-white">
        <h3 class="widget-title text-lg font-semibold mb-2">
            📧 Newsletter RecifeMais
        </h3>
        <p class="text-sm opacity-90 mb-4">
            Receba as principais notícias de Recife direto no seu email.
        </p>
        
        <form class="newsletter-sidebar-form" id="sidebar-newsletter-form">
            <div class="space-y-3">
                <input type="email" 
                       class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:bg-white/30 focus:border-white focus:outline-none transition-all"
                       placeholder="Seu melhor email..."
                       required>
                
                <button type="submit" 
                        class="w-full bg-white text-recife-primary font-semibold py-2 rounded-lg hover:bg-recife-gray-50 transition-colors">
                    ✉️ Assinar Grátis
                </button>
            </div>
            
            <p class="text-xs opacity-75 mt-2">
                Cancelar a qualquer momento. Sem spam.
            </p>
        </form>
    </div>
    
    <!-- Widget: Posts Populares -->
    <div class="sidebar-widget bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6 mb-8">
        <h3 class="widget-title text-lg font-semibold text-recife-gray-900 mb-4 pb-2 border-b border-recife-gray-200">
            🔥 Mais Lidas
        </h3>
        
        <?php
        $popular_posts = get_posts(array(
            'numberposts' => 5,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'post__not_in' => array($post_id)
        ));
        
        if ($popular_posts) : ?>
            <div class="popular-posts-list space-y-4">
                <?php foreach ($popular_posts as $index => $popular_post) : ?>
                    <article class="popular-post-item group flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-recife-primary text-white rounded-full flex items-center justify-center text-sm font-bold">
                            <?php echo $index + 1; ?>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-recife-gray-900 leading-tight mb-1">
                                <a href="<?php echo get_permalink($popular_post->ID); ?>" 
                                   class="hover:text-recife-primary transition-colors line-clamp-2">
                                    <?php echo esc_html($popular_post->post_title); ?>
                                </a>
                            </h4>
                            <p class="text-xs text-recife-gray-500">
                                📅 <?php echo get_the_date('d/m/Y', $popular_post->ID); ?>
                                <?php
                                $views = get_post_meta($popular_post->ID, 'post_views_count', true);
                                if ($views) : ?>
                                    • 👁️ <?php echo number_format($views); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="text-sm text-recife-gray-500">Nenhum post popular encontrado.</p>
        <?php endif; ?>
    </div>
    
    <!-- Widget: Eventos Próximos -->
    <?php
    $upcoming_events = get_posts(array(
        'post_type' => 'eventos_festivais',
        'numberposts' => 3,
        'meta_key' => 'evento_data_inicio',
        'meta_value' => date('Y-m-d'),
        'meta_compare' => '>=',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ));
    
    if ($upcoming_events) : ?>
        <div class="sidebar-widget bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6 mb-8">
            <h3 class="widget-title text-lg font-semibold text-recife-gray-900 mb-4 pb-2 border-b border-recife-gray-200">
                🎭 Próximos Eventos
            </h3>
            
            <div class="events-list space-y-4">
                <?php foreach ($upcoming_events as $event) : 
                    $event_date = get_post_meta($event->ID, 'evento_data_inicio', true);
                    $event_local = get_post_meta($event->ID, 'evento_local', true);
                    ?>
                    <article class="event-item group">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-recife-secondary text-white rounded-lg flex flex-col items-center justify-center text-xs">
                                <?php if ($event_date) : 
                                    $date_obj = DateTime::createFromFormat('Y-m-d', $event_date);
                                    if ($date_obj) : ?>
                                        <span class="font-bold"><?php echo $date_obj->format('d'); ?></span>
                                        <span class="text-xs opacity-90"><?php echo $date_obj->format('M'); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-recife-gray-900 leading-tight mb-1">
                                    <a href="<?php echo get_permalink($event->ID); ?>" 
                                       class="hover:text-recife-primary transition-colors line-clamp-2">
                                        <?php echo esc_html($event->post_title); ?>
                                    </a>
                                </h4>
                                <?php if ($event_local) : 
                                    $local_post = get_post($event_local);
                                    if ($local_post) : ?>
                                        <p class="text-xs text-recife-gray-500">
                                            📍 <?php echo esc_html($local_post->post_title); ?>
                                        </p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-4 pt-4 border-t border-recife-gray-200">
                <a href="<?php echo home_url('/eventos'); ?>" 
                   class="text-sm text-recife-primary hover:text-recife-primary-dark font-medium transition-colors">
                    Ver todos os eventos →
                </a>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Widget: Categorias -->
    <div class="sidebar-widget bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6 mb-8">
        <h3 class="widget-title text-lg font-semibold text-recife-gray-900 mb-4 pb-2 border-b border-recife-gray-200">
            🏷️ Categorias
        </h3>
        
        <?php
        $categories = get_categories(array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 8,
            'hide_empty' => true
        ));
        
        if ($categories) : ?>
            <div class="categories-list space-y-2">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo get_category_link($category->term_id); ?>" 
                       class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-recife-gray-50 transition-colors group">
                        <span class="text-sm text-recife-gray-700 group-hover:text-recife-primary">
                            <?php echo esc_html($category->name); ?>
                        </span>
                        <span class="text-xs text-recife-gray-500 bg-recife-gray-100 px-2 py-1 rounded-full">
                            <?php echo $category->count; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Widget: Anúncio/Banner (se habilitado) -->
    <?php if (get_theme_mod('show_sidebar_ads', false)) : ?>
        <div class="sidebar-widget mb-8">
            <div class="bg-recife-gray-100 border-2 border-dashed border-recife-gray-300 rounded-lg p-8 text-center">
                <div class="text-recife-gray-500 mb-2">📢</div>
                <p class="text-sm text-recife-gray-600">
                    Espaço publicitário
                </p>
                <p class="text-xs text-recife-gray-500 mt-2">
                    <a href="<?php echo home_url('/anuncie'); ?>" class="hover:text-recife-primary">
                        Anuncie aqui
                    </a>
                </p>
            </div>
        </div>
    <?php endif; ?>
    
</aside>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 1023px) {
    .single-sidebar {
        margin-top: 2rem;
    }
}
</style> 