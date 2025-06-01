<?php
/**
 * Template Archive Geral (Fallback)
 * Usado quando não há template específico para o tipo de conteúdo
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Detectar tipo de archive e configurar adequadamente
$archive_type = '';
$archive_config = array();

if (is_category()) {
    $category = get_queried_object();
    $archive_config = array(
        'post_type' => 'post',
        'title' => 'Categoria: ' . $category->name,
        'description' => $category->description ?: 'Explore todos os posts desta categoria',
        'icon' => '📂',
        'color' => 'gray-600',
        'sidebar_color' => 'gray-600',
        'stats' => array(
            array('label' => 'Posts', 'value' => $category->count)
        )
    );
} elseif (is_tag()) {
    $tag = get_queried_object();
    $archive_config = array(
        'post_type' => 'post',
        'title' => 'Tag: ' . $tag->name,
        'description' => $tag->description ?: 'Todos os posts marcados com esta tag',
        'icon' => '🏷️',
        'color' => 'gray-600',
        'sidebar_color' => 'gray-600',
        'stats' => array(
            array('label' => 'Posts', 'value' => $tag->count)
        )
    );
} elseif (is_author()) {
    $author = get_queried_object();
    $archive_config = array(
        'post_type' => 'post',
        'title' => 'Autor: ' . $author->display_name,
        'description' => get_the_author_meta('description', $author->ID) ?: 'Todos os posts deste autor',
        'icon' => '👤',
        'color' => 'gray-600',
        'sidebar_color' => 'gray-600',
        'stats' => array(
            array('label' => 'Posts', 'value' => count_user_posts($author->ID))
        )
    );
} elseif (is_date()) {
    if (is_year()) {
        $year = get_query_var('year');
        $title = 'Arquivo: ' . $year;
    } elseif (is_month()) {
        $month = get_query_var('monthnum');
        $year = get_query_var('year');
        $title = 'Arquivo: ' . date_i18n('F Y', mktime(0, 0, 0, $month, 1, $year));
    } elseif (is_day()) {
        $day = get_query_var('day');
        $month = get_query_var('monthnum');
        $year = get_query_var('year');
        $title = 'Arquivo: ' . date_i18n('j F Y', mktime(0, 0, 0, $month, $day, $year));
    }
    
    $archive_config = array(
        'post_type' => 'post',
        'title' => $title,
        'description' => 'Posts publicados neste período',
        'icon' => '📅',
        'color' => 'gray-600',
        'sidebar_color' => 'gray-600',
        'stats' => array()
    );
} else {
    // Archive genérico
    $archive_config = array(
        'post_type' => get_post_type(),
        'title' => post_type_archive_title('', false) ?: 'Arquivo',
        'description' => 'Explore todo o conteúdo disponível',
        'icon' => '📋',
        'color' => 'gray-600',
        'sidebar_color' => 'gray-600',
        'stats' => array()
    );
}
?>

<main id="main" class="site-main bg-recife-gray-50 min-h-screen">
    
    <!-- Header Section Modular -->
    <?php 
    get_template_part('template-parts/archive/header-section', null, $archive_config);
    ?>
    
    <!-- Breadcrumbs -->
    <?php 
    get_template_part('template-parts/archive/breadcrumbs', null, array(
        'post_type' => $archive_config['post_type'],
        'archive_title' => $archive_config['title']
    ));
    ?>
    
    <!-- Filters Bar (simplificado para archives gerais) -->
    <?php 
    get_template_part('template-parts/archive/filters-bar', null, array(
        'post_type' => $archive_config['post_type'],
        'taxonomies' => array('category', 'post_tag'),
        'meta_filters' => array(),
        'search_placeholder' => 'Buscar conteúdo...',
        'simple_mode' => true
    ));
    ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-3">
                <?php if (have_posts()) : ?>
                    
                    <!-- Content List (layout blog para archives gerais) -->
                    <?php 
                    get_template_part('template-parts/archive/content-list', null, array(
                        'post_type' => $archive_config['post_type'],
                        'layout' => 'blog', // Layout blog para posts
                        'show_excerpt' => true,
                        'excerpt_length' => 25,
                        'meta_fields' => array(
                            'post_date' => array(
                                'label' => 'Data',
                                'icon' => 'calendar'
                            ),
                            'post_author' => array(
                                'label' => 'Autor',
                                'icon' => 'user'
                            ),
                            'comment_count' => array(
                                'label' => 'Comentários',
                                'icon' => 'message-circle'
                            )
                        )
                    ));
                    ?>
                    
                    <!-- Pagination -->
                    <?php 
                    get_template_part('template-parts/archive/pagination', null, array(
                        'post_type' => $archive_config['post_type']
                    ));
                    ?>
                    
                <?php else : ?>
                    
                    <!-- No Results -->
                    <?php 
                    get_template_part('template-parts/archive/no-results', null, array(
                        'post_type' => $archive_config['post_type'],
                        'title' => 'Nenhum conteúdo encontrado',
                        'description' => 'Não encontramos conteúdo que corresponda aos seus critérios.',
                        'suggestions' => array(
                            array(
                                'title' => 'Ver Eventos',
                                'url' => get_post_type_archive_link('eventos_festivais'),
                                'icon' => '🎭'
                            ),
                            array(
                                'title' => 'Explorar Lugares', 
                                'url' => get_post_type_archive_link('lugares'),
                                'icon' => '📍'
                            ),
                            array(
                                'title' => 'Conhecer Artistas',
                                'url' => get_post_type_archive_link('artistas'), 
                                'icon' => '🎨'
                            )
                        )
                    ));
                    ?>
                    
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <?php 
                get_template_part('template-parts/archive/sidebar-archive', null, array(
                    'post_type' => $archive_config['post_type'],
                    'widgets' => array(
                        'recent_posts' => true,
                        'popular_categories' => true,
                        'tag_cloud' => true,
                        'newsletter' => true
                    ),
                    'related_cpts' => array(
                        'eventos_festivais' => 'Eventos Culturais',
                        'lugares' => 'Lugares Especiais'
                    )
                ));
                ?>
            </div>
            
        </div>
    </div>
    
</main>

<?php get_footer(); ?>