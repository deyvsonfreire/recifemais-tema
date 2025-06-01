<?php
/**
 * Template: Index (Fallback) - RecifeMais
 * 
 * Template principal de fallback para todas as páginas
 * Usado quando não há template específico disponível
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

get_header(); ?>

<main id="main-content" class="main-container" role="main">
    
    <!-- ===== BREADCRUMBS ===== -->
    <section class="main-breadcrumbs" aria-label="Navegação">
        <div class="container">
            <?php get_template_part('template-parts/archive/breadcrumbs'); ?>
        </div>
    </section>

    <!-- ===== HEADER DA SEÇÃO ===== -->
    <section class="main-header" aria-label="Cabeçalho da página">
        <div class="container">
            <div class="main-header-content">
                <?php if (is_home() && !is_front_page()) : ?>
                    <h1 class="main-title">Blog</h1>
                    <p class="main-subtitle">Últimas notícias e atualizações</p>
                <?php elseif (is_search()) : ?>
                    <h1 class="main-title">Resultados da Busca</h1>
                    <p class="main-subtitle">
                        <?php
                        printf(
                            'Resultados para: <strong>%s</strong>',
                            get_search_query()
                        );
                        ?>
                    </p>
                <?php elseif (is_category()) : ?>
                    <h1 class="main-title"><?php single_cat_title(); ?></h1>
                    <?php if (category_description()) : ?>
                        <div class="main-subtitle"><?php echo category_description(); ?></div>
                    <?php endif; ?>
                <?php elseif (is_tag()) : ?>
                    <h1 class="main-title">Tag: <?php single_tag_title(); ?></h1>
                    <?php if (tag_description()) : ?>
                        <div class="main-subtitle"><?php echo tag_description(); ?></div>
                    <?php endif; ?>
                <?php elseif (is_author()) : ?>
                    <h1 class="main-title">Autor: <?php echo get_the_author(); ?></h1>
                    <?php if (get_the_author_meta('description')) : ?>
                        <div class="main-subtitle"><?php echo get_the_author_meta('description'); ?></div>
                    <?php endif; ?>
                <?php elseif (is_date()) : ?>
                    <h1 class="main-title">
                        <?php
                        if (is_year()) {
                            echo get_the_date('Y');
                        } elseif (is_month()) {
                            echo get_the_date('F Y');
                        } elseif (is_day()) {
                            echo get_the_date('j F Y');
                        }
                        ?>
                    </h1>
                    <p class="main-subtitle">Arquivo de posts</p>
                <?php else : ?>
                    <h1 class="main-title">Conteúdo</h1>
                    <p class="main-subtitle">Explore nosso conteúdo</p>
                <?php endif; ?>
                
                <!-- ===== ESTATÍSTICAS ===== -->
                <?php if (have_posts()) : ?>
                <div class="main-stats">
                    <div class="main-stat">
                        <span class="main-stat-number"><?php echo $wp_query->found_posts; ?></span>
                        <span class="main-stat-label">
                            <?php echo ($wp_query->found_posts == 1) ? 'resultado' : 'resultados'; ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== CONTAINER PRINCIPAL ===== -->
    <div class="container main-content-container">
        
        <!-- ===== FILTROS E BUSCA ===== -->
        <?php if (!is_search()) : ?>
        <section class="main-filters" aria-label="Filtros e busca">
            <?php get_template_part('template-parts/archive/filters-bar'); ?>
        </section>
        <?php endif; ?>

        <!-- ===== GRID LAYOUT ===== -->
        <div class="main-grid">
            
            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="main-content-area">
                
                <?php if (have_posts()) : ?>
                
                    <!-- ===== GRID DE POSTS ===== -->
                    <section class="main-posts-grid" aria-label="Lista de conteúdo">
                        <div class="main-posts-container">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="main-post-card" id="post-<?php the_ID(); ?>">
                                    
                                    <!-- ===== IMAGEM ===== -->
                                    <?php if (has_post_thumbnail()) : ?>
                                    <div class="main-post-image">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('medium', [
                                                'class' => 'main-post-img',
                                                'loading' => 'lazy'
                                            ]); ?>
                                        </a>
                                        
                                        <!-- ===== TIPO DE POST ===== -->
                                        <span class="main-post-type">
                                            <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- ===== CONTEÚDO ===== -->
                                    <div class="main-post-content">
                                        <header class="main-post-header">
                                            <h3 class="main-post-title">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="main-post-meta">
                                                <time class="main-post-date" datetime="<?php echo get_the_date('c'); ?>">
                                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                                    <?php echo get_the_date('d/m/Y'); ?>
                                                </time>
                                                
                                                <?php if (get_post_type() === 'post') : ?>
                                                <span class="main-post-author">
                                                    <i class="fas fa-user" aria-hidden="true"></i>
                                                    <?php the_author(); ?>
                                                </span>
                                                <?php endif; ?>
                                                
                                                <span class="main-post-read-time">
                                                    <i class="fas fa-clock" aria-hidden="true"></i>
                                                    <?php echo recifemais_reading_time(); ?> min
                                                </span>
                                                
                                                <?php if (get_comments_number() > 0) : ?>
                                                <span class="main-post-comments">
                                                    <i class="fas fa-comments" aria-hidden="true"></i>
                                                    <?php comments_number('0', '1', '%'); ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </header>
                                        
                                        <div class="main-post-excerpt">
                                            <?php 
                                            if (has_excerpt()) {
                                                the_excerpt();
                                            } else {
                                                echo wp_trim_words(get_the_content(), 25);
                                            }
                                            ?>
                                        </div>
                                        
                                        <!-- ===== CATEGORIAS/TAXONOMIAS ===== -->
                                        <?php 
                                        $post_type = get_post_type();
                                        $taxonomies = get_object_taxonomies($post_type, 'objects');
                                        
                                        foreach ($taxonomies as $taxonomy) :
                                            if ($taxonomy->public && !in_array($taxonomy->name, ['post_tag', 'post_format'])) :
                                                $terms = get_the_terms(get_the_ID(), $taxonomy->name);
                                                if ($terms && !is_wp_error($terms)) :
                                        ?>
                                        <div class="main-post-terms">
                                            <span class="main-post-terms-label"><?php echo $taxonomy->label; ?>:</span>
                                            <?php foreach ($terms as $term) : ?>
                                                <a href="<?php echo get_term_link($term); ?>" 
                                                   class="main-post-term"
                                                   style="background-color: <?php echo recifemais_get_term_color($term->slug); ?>">
                                                    <?php echo esc_html($term->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php 
                                                endif;
                                            endif;
                                        endforeach; 
                                        ?>
                                        
                                        <div class="main-post-actions">
                                            <a href="<?php the_permalink(); ?>" class="main-post-btn">
                                                Ler mais
                                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </section>

                    <!-- ===== PAGINAÇÃO ===== -->
                    <section class="main-pagination" aria-label="Navegação entre páginas">
                        <?php get_template_part('template-parts/archive/pagination'); ?>
                    </section>

                <?php else : ?>
                    
                    <!-- ===== SEM RESULTADOS ===== -->
                    <section class="main-no-results" aria-label="Nenhum resultado encontrado">
                        <?php get_template_part('template-parts/archive/no-results'); ?>
                    </section>

                <?php endif; ?>

            </div>

            <!-- ===== SIDEBAR ===== -->
            <aside class="main-sidebar" aria-label="Conteúdo complementar">
                
                <!-- ===== BUSCA ===== -->
                <div class="main-widget main-search">
                    <h3 class="main-widget-title">Buscar</h3>
                    <form class="main-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="main-search-input-group">
                            <input type="search" 
                                   class="main-search-input" 
                                   placeholder="Digite sua busca..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   aria-label="Buscar conteúdo">
                            <button type="submit" class="main-search-btn" aria-label="Buscar">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ===== CATEGORIAS/TAXONOMIAS ===== -->
                <?php 
                $current_post_type = get_post_type();
                if (is_home() || is_category() || is_tag()) {
                    $current_post_type = 'post';
                }
                
                $taxonomies = get_object_taxonomies($current_post_type, 'objects');
                
                foreach ($taxonomies as $taxonomy) :
                    if ($taxonomy->public && !in_array($taxonomy->name, ['post_tag', 'post_format'])) :
                        $terms = get_terms([
                            'taxonomy' => $taxonomy->name,
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 8,
                            'hide_empty' => true
                        ]);
                        
                        if ($terms && !is_wp_error($terms)) :
                ?>
                <div class="main-widget main-taxonomy">
                    <h3 class="main-widget-title"><?php echo $taxonomy->label; ?></h3>
                    <ul class="main-taxonomy-list">
                        <?php foreach ($terms as $term) : ?>
                        <li class="main-taxonomy-item">
                            <a href="<?php echo get_term_link($term); ?>" 
                               class="main-taxonomy-link"
                               style="border-left-color: <?php echo recifemais_get_term_color($term->slug); ?>">
                                <span class="main-taxonomy-name"><?php echo esc_html($term->name); ?></span>
                                <span class="main-taxonomy-count"><?php echo $term->count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php 
                        endif;
                    endif;
                endforeach; 
                ?>

                <!-- ===== POSTS RECENTES ===== -->
                <div class="main-widget main-recent">
                    <h3 class="main-widget-title">Recentes</h3>
                    <div class="main-recent-list">
                        <?php
                        $recent_posts = get_posts([
                            'post_type' => $current_post_type,
                            'posts_per_page' => 5,
                            'post_status' => 'publish'
                        ]);
                        
                        foreach ($recent_posts as $post) : setup_postdata($post);
                        ?>
                        <article class="main-recent-item">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="main-recent-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail', [
                                        'class' => 'main-recent-img',
                                        'loading' => 'lazy'
                                    ]); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <div class="main-recent-content">
                                <h4 class="main-recent-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="main-recent-meta">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </time>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>

                <!-- ===== ARQUIVO ===== -->
                <div class="main-widget main-archive">
                    <h3 class="main-widget-title">Arquivo</h3>
                    <ul class="main-archive-list">
                        <?php wp_get_archives([
                            'type' => 'monthly',
                            'limit' => 12,
                            'format' => 'custom',
                            'before' => '<li class="main-archive-item">',
                            'after' => '</li>'
                        ]); ?>
                    </ul>
                </div>

                <!-- ===== NEWSLETTER ===== -->
                <div class="main-widget main-newsletter">
                    <h3 class="main-widget-title">Newsletter</h3>
                    <p class="main-newsletter-description">
                        Receba as principais atualizações no seu email
                    </p>
                    <form class="main-newsletter-form">
                        <input type="email" 
                               class="main-newsletter-input" 
                               placeholder="Seu email" 
                               required>
                        <button type="submit" class="main-newsletter-btn">
                            Inscrever-se
                        </button>
                    </form>
                </div>

            </aside>

        </div>

    </div>

</main>

<!-- ===== SCRIPTS ESPECÍFICOS ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades específicas
    if (typeof RecifeMaisIndex !== 'undefined') {
        new RecifeMaisIndex();
    } else if (typeof RecifeMaisBlog !== 'undefined') {
        // Fallback para funcionalidades do blog
        new RecifeMaisBlog();
    }
});
</script>

<?php get_footer(); ?> 