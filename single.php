<?php
/**
 * Template Single Universal - RecifeMais Theme
 * Single inteligente que funciona para posts e todos os CPTs
 * Inspirado no padrão Globo.com
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
    
    // Detectar tipo de conteúdo
    $post_type = get_post_type();
    $post_id = get_the_ID();
    
    ?>
    
    <article id="post-<?php echo $post_id; ?>" <?php post_class('single-article'); ?>>
        
        <!-- Hero Section Universal -->
        <?php get_template_part('template-parts/single/hero-post'); ?>
        
        <div class="single-content-wrapper bg-white">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Conteúdo Principal -->
                    <div class="lg:col-span-2">
                        
                        <!-- Meta Fields Específicos por CPT -->
                        <?php 
                        // Meta fields para CPTs do plugin
                        if (in_array($post_type, ['eventos_festivais', 'lugares', 'artistas', 'roteiros', 'organizadores'])) {
                            // Verificar se arquivo de meta específico existe
                            $meta_template = 'template-parts/single/meta-' . $post_type;
                            if (locate_template($meta_template . '.php')) {
                                get_template_part($meta_template);
                            } else {
                                // Fallback para meta genérico
                                get_template_part('template-parts/single/meta-post');
                            }
                        }
                        ?>
                        
                        <!-- Conteúdo Principal -->
                        <div class="single-main-content prose prose-lg max-w-none">
                            
                            <!-- Conteúdo do Post -->
                            <?php if (get_post_type() === 'post') : ?>
                                <!-- Conteúdo padrão para notícias/posts -->
                                <?php the_content(); ?>
                                
                            <?php else : ?>
                                <!-- Conteúdo para CPTs -->
                                <?php 
                                // Template específico se existir
                                $content_template = 'template-parts/single/content-' . $post_type;
                                if (locate_template($content_template . '.php')) {
                                    get_template_part($content_template);
                                } else {
                                    // Fallback para conteúdo padrão
                                    the_content();
                                }
                                ?>
                                
                            <?php endif; ?>
                            
                            <!-- Tags e Categorias -->
                            <?php if (get_post_type() === 'post') : ?>
                                <div class="post-meta-tags mt-8 pt-6 border-t border-recife-gray-200">
                                    
                                    <!-- Categorias -->
                                    <?php if (has_category()) : ?>
                                        <div class="meta-categories mb-4">
                                            <strong class="text-recife-gray-700">Categorias:</strong>
                                            <?php the_category(', '); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Tags -->
                                    <?php if (has_tag()) : ?>
                                        <div class="meta-tags">
                                            <strong class="text-recife-gray-700">Tags:</strong>
                                            <?php the_tags('', ', ', ''); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            <?php endif; ?>
                            
                        </div>
                        
                        <!-- Bio do Autor (apenas para posts) -->
                        <?php if (get_post_type() === 'post' && get_the_author_meta('description')) : ?>
                            <?php get_template_part('template-parts/single/author-bio'); ?>
                        <?php endif; ?>
                        
                        <!-- Compartilhamento Social -->
                        <?php get_template_part('template-parts/single/social-share'); ?>
                        
                        <!-- Posts/Conteúdo Relacionado -->
                        <?php 
                        $related_template = 'template-parts/single/related-' . $post_type;
                        if (locate_template($related_template . '.php')) {
                            get_template_part($related_template);
                        } else {
                            get_template_part('template-parts/single/related-posts');
                        }
                        ?>
                        
                        <!-- Navegação entre Posts -->
                        <?php get_template_part('template-parts/single/navigation-post'); ?>
                        
                        <!-- Comentários -->
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="comments-section mt-12 pt-8 border-t border-recife-gray-200">
                                <?php comments_template(); ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Sidebar Contextual -->
                    <div class="lg:col-span-1">
                        <?php 
                        // Sidebar específica por tipo se existir
                        $sidebar_template = 'template-parts/single/sidebar-' . $post_type;
                        if (locate_template($sidebar_template . '.php')) {
                            get_template_part($sidebar_template);
                        } else {
                            // Fallback para sidebar padrão
                            get_template_part('template-parts/single/sidebar-post');
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </article>
    
    <?php
    
endwhile;

get_footer();
?>