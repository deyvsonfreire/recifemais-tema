<?php
/**
 * Template Single Post - RecifeMais (Estilo Globo.com)
 * Template específico para posts nativos do WordPress
 * Layout inspirado na Globo.com com design limpo e profissional
 *
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    
    $post_id = get_the_ID();
    $categories = get_the_category();
    $primary_cat = !empty($categories) ? $categories[0] : null;
    $author_id = get_the_author_meta('ID');
    $reading_time = recifemais_get_reading_time($post_id);
    
    ?>
    
    <article id="post-<?php echo $post_id; ?>" <?php post_class('single-post-article bg-white'); ?> itemscope itemtype="https://schema.org/NewsArticle">
        
        <!-- Header da Notícia (Estilo Globo) -->
        <header class="post-header bg-white border-b border-gray-200">
            <div class="container mx-auto px-4 py-6">
                <div class="max-w-4xl mx-auto">
                    
                    <!-- Breadcrumbs -->
                    <nav class="breadcrumbs mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center gap-2 text-sm text-gray-500">
                            <li>
                                <a href="<?php echo home_url(); ?>" class="hover:text-red-600 transition-colors">
                                    Início
                                </a>
                            </li>
                            <li class="flex items-center gap-2">
                                <span>›</span>
                                <?php if ($primary_cat) : ?>
                                    <a href="<?php echo get_category_link($primary_cat->term_id); ?>" 
                                       class="hover:text-red-600 transition-colors">
                                        <?php echo esc_html($primary_cat->name); ?>
                                    </a>
                                <?php else : ?>
                                    <span>Notícias</span>
                                <?php endif; ?>
                            </li>
                            <li class="flex items-center gap-2">
                                <span>›</span>
                                <span class="text-gray-700">Notícia</span>
                            </li>
                        </ol>
                    </nav>
                    
                    <!-- Meta Info Superior -->
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <?php if ($primary_cat) : ?>
                            <span class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded-full">
                                <?php echo esc_html(strtoupper($primary_cat->name)); ?>
                            </span>
                        <?php endif; ?>
                        
                        <time datetime="<?php echo get_the_date('c'); ?>" class="text-sm text-gray-600">
                            <?php echo get_the_date('d/m/Y \à\s H:i'); ?>
                        </time>
                        
                        <span class="text-sm text-gray-600">
                            Atualizado <?php echo get_the_modified_date('d/m/Y \à\s H:i'); ?>
                        </span>
                        
                        <?php if ($reading_time) : ?>
                            <span class="text-sm text-gray-600">
                                <?php echo $reading_time; ?> min de leitura
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Título Principal -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-6" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Subtítulo/Lead -->
                    <?php if (has_excerpt()) : ?>
                        <div class="text-xl text-gray-700 leading-relaxed mb-8 font-normal" itemprop="description">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </header>
        
        <!-- Imagem Destacada -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="featured-image-container">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <figure class="mb-8">
                            <?php 
                            the_post_thumbnail('large', [
                                'class' => 'w-full h-auto rounded-lg shadow-sm',
                                'itemprop' => 'image'
                            ]); 
                            ?>
                            <?php 
                            $caption = get_the_post_thumbnail_caption();
                            if ($caption) : ?>
                                <figcaption class="mt-3 text-sm text-gray-600 italic text-center">
                                    <?php echo esc_html($caption); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Conteúdo Principal + Sidebar -->
        <div class="main-content-area bg-gray-50">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Conteúdo Principal (3/4) -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm p-6 lg:p-8">
                            
                            <!-- Autor e Meta Info -->
                            <div class="author-meta flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
                                        <?php echo get_avatar($author_id, 48, '', '', ['class' => 'w-full h-full object-cover']); ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                            <span itemprop="name"><?php the_author(); ?></span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <?php 
                                            $author_description = get_the_author_meta('description');
                                            echo $author_description ? esc_html($author_description) : 'Redação RecifeMais';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Compartilhamento -->
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 hidden sm:block">Compartilhar:</span>
                                    <div class="flex gap-2">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                           target="_blank" 
                                           rel="noopener"
                                           class="w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition-colors"
                                           aria-label="Compartilhar no Facebook">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                           target="_blank" 
                                           rel="noopener"
                                           class="w-8 h-8 bg-sky-500 hover:bg-sky-600 text-white rounded-full flex items-center justify-center transition-colors"
                                           aria-label="Compartilhar no Twitter">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                                           target="_blank" 
                                           rel="noopener"
                                           class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors"
                                           aria-label="Compartilhar no WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Conteúdo do Post -->
                            <div class="post-content prose prose-lg max-w-none prose-headings:text-gray-900 prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-red-600 prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-blockquote:border-l-4 prose-blockquote:border-red-600 prose-blockquote:bg-gray-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:text-gray-700" itemprop="articleBody">
                                <?php the_content(); ?>
                            </div>
                            
                            <!-- Tags e Categorias -->
                            <div class="post-meta-footer mt-8 pt-6 border-t border-gray-200">
                                
                                <!-- Tags -->
                                <?php if (has_tag()) : ?>
                                    <div class="post-tags mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Tags:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php 
                                            $tags = get_the_tags();
                                            foreach ($tags as $tag) : ?>
                                                <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                                                   class="inline-block px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-full transition-colors">
                                                    #<?php echo esc_html($tag->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Categorias -->
                                <?php if (has_category()) : ?>
                                    <div class="post-categories">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Categorias:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php 
                                            foreach ($categories as $category) : ?>
                                                <a href="<?php echo get_category_link($category->term_id); ?>" 
                                                   class="inline-block px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm rounded-full transition-colors">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                            
                        </div>
                        
                        <!-- Bio do Autor -->
                        <?php if (get_the_author_meta('description')) : ?>
                            <?php get_template_part('template-parts/single/author-bio'); ?>
                        <?php endif; ?>
                        
                        <!-- Posts Relacionados -->
                        <?php get_template_part('template-parts/single/related-posts'); ?>
                        
                        <!-- Navegação entre Posts -->
                        <?php get_template_part('template-parts/single/navigation-post'); ?>
                        
                        <!-- Comentários -->
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="comments-section mt-8">
                                <div class="bg-white rounded-lg shadow-sm p-6 lg:p-8">
                                    <?php comments_template(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Sidebar (1/4) -->
                    <div class="lg:col-span-1">
                        <?php get_template_part('template-parts/single/sidebar-post'); ?>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </article>
    
    <?php
    
endwhile;

get_footer();

// Schema.org Structured Data
?>
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
    "description": "<?php echo esc_js(wp_strip_all_tags(get_the_excerpt())); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo esc_js(get_permalink()); ?>"
    }
}
</script> 