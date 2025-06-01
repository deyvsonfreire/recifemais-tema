<?php
/**
 * Template: Blog/Notícias - RecifeMais
 * 
 * Página principal do blog com listagem de posts
 * Layout inspirado na Globo.com com grid responsivo
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

get_header(); ?>

<main id="blog-main" class="blog-container" role="main">
    
    <!-- ===== BREADCRUMBS ===== -->
    <section class="blog-breadcrumbs" aria-label="Navegação">
        <div class="container">
            <?php get_template_part('template-parts/archive/breadcrumbs'); ?>
        </div>
    </section>

    <!-- ===== HEADER DA SEÇÃO ===== -->
    <section class="blog-header" aria-label="Cabeçalho do blog">
        <div class="container">
            <div class="blog-header-content">
                <h1 class="blog-title">Notícias</h1>
                <p class="blog-subtitle">Fique por dentro de tudo que acontece em Recife</p>
                
                <!-- ===== ESTATÍSTICAS ===== -->
                <div class="blog-stats">
                    <div class="blog-stat">
                        <span class="blog-stat-number"><?php echo wp_count_posts('post')->publish; ?></span>
                        <span class="blog-stat-label">Notícias</span>
                    </div>
                    <div class="blog-stat">
                        <span class="blog-stat-number"><?php echo get_comments_number(); ?></span>
                        <span class="blog-stat-label">Comentários</span>
                    </div>
                    <div class="blog-stat">
                        <span class="blog-stat-number"><?php echo wp_count_terms('category'); ?></span>
                        <span class="blog-stat-label">Categorias</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CONTAINER PRINCIPAL ===== -->
    <div class="container blog-main-container">
        
        <!-- ===== FILTROS E BUSCA ===== -->
        <section class="blog-filters" aria-label="Filtros e busca">
            <?php get_template_part('template-parts/archive/filters-bar'); ?>
        </section>

        <!-- ===== GRID LAYOUT ===== -->
        <div class="blog-grid">
            
            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="blog-main-content">
                
                <?php if (have_posts()) : ?>
                
                    <!-- ===== POST DESTACADO (PRIMEIRO POST) ===== -->
                    <?php 
                    $first_post = true;
                    while (have_posts()) : the_post(); 
                        if ($first_post) :
                    ?>
                    <article class="blog-featured-post" id="post-<?php the_ID(); ?>">
                        <div class="blog-featured-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'blog-featured-img',
                                        'loading' => 'eager'
                                    ]); ?>
                                </a>
                            <?php endif; ?>
                            
                            <!-- ===== CATEGORIA ===== -->
                            <?php 
                            $primary_category = recifemais_get_primary_category();
                            if ($primary_category) :
                            ?>
                            <span class="blog-featured-category" style="background-color: <?php echo recifemais_get_category_color($primary_category->slug); ?>">
                                <?php echo esc_html($primary_category->name); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="blog-featured-content">
                            <header class="blog-featured-header">
                                <h2 class="blog-featured-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="blog-featured-meta">
                                    <time class="blog-featured-date" datetime="<?php echo get_the_date('c'); ?>">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </time>
                                    <span class="blog-featured-author">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <?php the_author(); ?>
                                    </span>
                                    <span class="blog-featured-read-time">
                                        <i class="fas fa-clock" aria-hidden="true"></i>
                                        <?php echo recifemais_reading_time(); ?> min
                                    </span>
                                    <?php if (recifemais_is_breaking_news()) : ?>
                                    <span class="blog-featured-breaking">
                                        <i class="fas fa-bolt" aria-hidden="true"></i>
                                        Última Hora
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </header>
                            
                            <div class="blog-featured-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="blog-featured-actions">
                                <a href="<?php the_permalink(); ?>" class="blog-featured-btn">
                                    Ler Matéria Completa
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    
                    <?php 
                        $first_post = false;
                        endif;
                        break;
                    endwhile; 
                    ?>

                    <!-- ===== GRID DE POSTS ===== -->
                    <section class="blog-posts-grid" aria-label="Lista de notícias">
                        <div class="blog-posts-container">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="blog-post-card" id="post-<?php the_ID(); ?>">
                                    
                                    <!-- ===== IMAGEM ===== -->
                                    <?php if (has_post_thumbnail()) : ?>
                                    <div class="blog-post-image">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('medium', [
                                                'class' => 'blog-post-img',
                                                'loading' => 'lazy'
                                            ]); ?>
                                        </a>
                                        
                                        <!-- ===== CATEGORIA ===== -->
                                        <?php 
                                        $primary_category = recifemais_get_primary_category();
                                        if ($primary_category) :
                                        ?>
                                        <span class="blog-post-category" style="background-color: <?php echo recifemais_get_category_color($primary_category->slug); ?>">
                                            <?php echo esc_html($primary_category->name); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- ===== CONTEÚDO ===== -->
                                    <div class="blog-post-content">
                                        <header class="blog-post-header">
                                            <h3 class="blog-post-title">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="blog-post-meta">
                                                <time class="blog-post-date" datetime="<?php echo get_the_date('c'); ?>">
                                                    <?php echo get_the_date('d/m/Y'); ?>
                                                </time>
                                                <span class="blog-post-read-time">
                                                    <?php echo recifemais_reading_time(); ?> min
                                                </span>
                                                <?php if (recifemais_is_breaking_news()) : ?>
                                                <span class="blog-post-breaking">
                                                    <i class="fas fa-bolt" aria-hidden="true"></i>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </header>
                                        
                                        <div class="blog-post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                        </div>
                                        
                                        <div class="blog-post-actions">
                                            <a href="<?php the_permalink(); ?>" class="blog-post-btn">
                                                Ler mais
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </section>

                    <!-- ===== PAGINAÇÃO ===== -->
                    <section class="blog-pagination" aria-label="Navegação entre páginas">
                        <?php get_template_part('template-parts/archive/pagination'); ?>
                    </section>

                <?php else : ?>
                    
                    <!-- ===== SEM RESULTADOS ===== -->
                    <section class="blog-no-results" aria-label="Nenhum resultado encontrado">
                        <?php get_template_part('template-parts/archive/no-results'); ?>
                    </section>

                <?php endif; ?>

            </div>

            <!-- ===== SIDEBAR ===== -->
            <aside class="blog-sidebar" aria-label="Conteúdo complementar">
                
                <!-- ===== BUSCA ===== -->
                <div class="blog-widget blog-search">
                    <h3 class="blog-widget-title">Buscar Notícias</h3>
                    <form class="blog-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="blog-search-input-group">
                            <input type="search" 
                                   class="blog-search-input" 
                                   placeholder="Digite sua busca..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   aria-label="Buscar notícias">
                            <button type="submit" class="blog-search-btn" aria-label="Buscar">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ===== CATEGORIAS ===== -->
                <div class="blog-widget blog-categories">
                    <h3 class="blog-widget-title">Categorias</h3>
                    <ul class="blog-categories-list">
                        <?php
                        $categories = get_categories([
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 8,
                            'hide_empty' => true
                        ]);
                        
                        foreach ($categories as $category) :
                        ?>
                        <li class="blog-category-item">
                            <a href="<?php echo get_category_link($category->term_id); ?>" 
                               class="blog-category-link"
                               style="border-left-color: <?php echo recifemais_get_category_color($category->slug); ?>">
                                <span class="blog-category-name"><?php echo esc_html($category->name); ?></span>
                                <span class="blog-category-count"><?php echo $category->count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- ===== POSTS MAIS LIDOS ===== -->
                <div class="blog-widget blog-popular">
                    <h3 class="blog-widget-title">Mais Lidas</h3>
                    <div class="blog-popular-list">
                        <?php
                        $popular_posts = get_posts([
                            'post_type' => 'post',
                            'posts_per_page' => 5,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC'
                        ]);
                        
                        foreach ($popular_posts as $post) : setup_postdata($post);
                        ?>
                        <article class="blog-popular-item">
                            <div class="blog-popular-content">
                                <h4 class="blog-popular-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="blog-popular-meta">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </time>
                                    <span class="blog-popular-views">
                                        <?php echo get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0'; ?> visualizações
                                    </span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>

                <!-- ===== TAGS ===== -->
                <div class="blog-widget blog-tags">
                    <h3 class="blog-widget-title">Tags Populares</h3>
                    <div class="blog-tags-cloud">
                        <?php
                        $tags = get_tags([
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 15
                        ]);
                        
                        foreach ($tags as $tag) :
                        ?>
                        <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                           class="blog-tag-link"
                           title="<?php echo $tag->count; ?> posts">
                            <?php echo esc_html($tag->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ===== NEWSLETTER ===== -->
                <div class="blog-widget blog-newsletter">
                    <h3 class="blog-widget-title">Newsletter</h3>
                    <p class="blog-newsletter-description">
                        Receba as principais notícias de Recife no seu email
                    </p>
                    <form class="blog-newsletter-form">
                        <input type="email" 
                               class="blog-newsletter-input" 
                               placeholder="Seu email" 
                               required>
                        <button type="submit" class="blog-newsletter-btn">
                            Inscrever-se
                        </button>
                    </form>
                </div>

            </aside>

        </div>

    </div>

</main>

<!-- ===== SCRIPTS ESPECÍFICOS DO BLOG ===== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades do blog
    if (typeof RecifeMaisBlog !== 'undefined') {
        new RecifeMaisBlog();
    }
});
</script>

<?php get_footer(); ?> 