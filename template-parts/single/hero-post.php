<?php
/**
 * Template Part: Hero Post
 * Hero section para posts com título, meta informações e imagem destacada
 *
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do post
$post_id = get_the_ID();
$category = get_the_category();
$primary_cat = !empty($category) ? $category[0] : null;
$author_id = get_the_author_meta('ID');
$post_date = get_the_date();
$reading_time = recifemais_get_reading_time($post_id);
$is_urgent = get_post_meta($post_id, '_noticia_urgencia', true) === 'urgente';
?>

<header class="single-hero-section bg-white border-b border-recife-gray-200">
    <div class="container mx-auto px-4 py-8 lg:py-12">
        <div class="max-w-4xl mx-auto">
            
            <!-- Breadcrumbs -->
            <nav class="breadcrumbs mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-sm text-recife-gray-500">
                    <li><a href="<?php echo home_url(); ?>" class="hover:text-recife-primary">🏠 Início</a></li>
                    <li class="flex items-center gap-2">
                        <span>›</span>
                        <?php if ($primary_cat) : ?>
                            <a href="<?php echo get_category_link($primary_cat->term_id); ?>" 
                               class="hover:text-recife-primary">
                                <?php echo esc_html($primary_cat->name); ?>
                            </a>
                        <?php else : ?>
                            <span>Notícias</span>
                        <?php endif; ?>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>›</span>
                        <span class="text-recife-gray-700">Artigo</span>
                    </li>
                </ol>
            </nav>
            
            <!-- Meta Info Superior -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <?php if ($is_urgent) : ?>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded-full">
                        🚨 Urgente
                    </span>
                <?php endif; ?>
                
                <?php if ($primary_cat) : ?>
                    <a href="<?php echo get_category_link($primary_cat->term_id); ?>" 
                       class="inline-flex items-center gap-1 px-3 py-1 bg-recife-primary text-white text-sm font-medium rounded-full hover:bg-recife-primary-dark transition-colors">
                        📂 <?php echo esc_html($primary_cat->name); ?>
                    </a>
                <?php endif; ?>
                
                <span class="text-sm text-recife-gray-500">
                    📅 <?php echo $post_date; ?>
                </span>
                
                <?php if ($reading_time) : ?>
                    <span class="text-sm text-recife-gray-500">
                        ⏱️ <?php echo $reading_time; ?> min de leitura
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Título Principal -->
            <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold text-recife-gray-900 leading-tight mb-6" 
                itemprop="headline">
                <?php the_title(); ?>
            </h1>
            
            <!-- Subtítulo/Excerpt -->
            <?php if (has_excerpt()) : ?>
                <div class="text-xl lg:text-2xl text-recife-gray-600 leading-relaxed mb-8 font-light" 
                     itemprop="description">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
            
            <!-- Meta Info do Autor -->
            <div class="flex flex-wrap items-center justify-between gap-6 pt-6 border-t border-recife-gray-200">
                
                <!-- Autor -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-recife-gray-200">
                        <?php echo get_avatar($author_id, 48, '', '', ['class' => 'w-full h-full object-cover']); ?>
                    </div>
                    <div>
                        <div class="font-semibold text-recife-gray-900" itemprop="author">
                            <?php the_author(); ?>
                        </div>
                        <div class="text-sm text-recife-gray-500">
                            <?php 
                            $author_role = get_the_author_meta('description') ? 'Jornalista' : 'Redator';
                            echo $author_role;
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="flex items-center gap-3">
                    
                    <!-- Compartilhar -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-recife-gray-600 font-medium">Compartilhar:</span>
                        
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition-colors"
                           aria-label="Compartilhar no Facebook">
                            📘
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="w-8 h-8 bg-sky-500 hover:bg-sky-600 text-white rounded-full flex items-center justify-center transition-colors"
                           aria-label="Compartilhar no Twitter">
                            🐦
                        </a>
                        
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors"
                           aria-label="Compartilhar no WhatsApp">
                            📱
                        </a>
                    </div>
                    
                    <!-- Favoritar -->
                    <button type="button" 
                            onclick="toggleFavorite(<?php echo $post_id; ?>)"
                            class="w-8 h-8 border border-recife-gray-300 hover:border-recife-primary text-recife-gray-600 hover:text-recife-primary rounded-full flex items-center justify-center transition-colors"
                            aria-label="Adicionar aos favoritos">
                        💖
                    </button>
                    
                </div>
            </div>
            
        </div>
    </div>
</header>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "<?php echo esc_js(get_the_title()); ?>",
    "image": "<?php echo esc_js(get_the_post_thumbnail_url($post_id, 'large')); ?>",
    "datePublished": "<?php echo get_the_date('c'); ?>",
    "dateModified": "<?php echo get_the_modified_date('c'); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_js(get_the_author()); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_js(get_site_icon_url()); ?>"
        }
    },
    "description": "<?php echo esc_js(get_the_excerpt()); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo esc_js(get_permalink()); ?>"
    }
}
</script> 