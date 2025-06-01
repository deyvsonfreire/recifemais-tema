<?php
/**
 * Template para Archive de Histórias
 * Refatorado para usar template-parts modulares
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Configurações específicas para Histórias
$archive_config = array(
    'post_type' => 'historias',
    'title' => 'Histórias de Pernambuco',
    'description' => 'Mergulhe nas narrativas que moldaram nossa cultura e identidade pernambucana',
    'icon' => '📖',
    'color' => 'purple-600', // Cor roxa para histórias
    'sidebar_color' => 'purple-600',
    'stats' => array(
        array('label' => 'Histórias', 'value' => wp_count_posts('historias')->publish),
        array('label' => 'Personagens', 'value' => wp_count_terms('personagens_historicos'))
    )
);
?>

<main id="main" class="site-main bg-recife-gray-50 min-h-screen">
    
    <!-- Header Section Modular -->
    <?php 
    get_template_part('template-parts/archive/header-section', null, $archive_config);
    ?>
    
    <!-- Breadcrumbs -->
    <?php 
    get_template_part('template-parts/archive/breadcrumbs', null, array(
        'post_type' => 'historias',
        'archive_title' => 'Histórias'
    ));
    ?>
    
    <!-- Filters Bar -->
    <?php 
    get_template_part('template-parts/archive/filters-bar', null, array(
        'post_type' => 'historias',
        'taxonomies' => array('tipos_historias', 'periodos_historicos', 'bairros_recife'),
        'meta_filters' => array(
            'historia_periodo' => 'Período Histórico',
            'historia_categoria' => 'Categoria',
            'historia_relevancia' => 'Relevância'
        ),
        'search_placeholder' => 'Buscar histórias, personagens, períodos...'
    ));
    ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-3">
                <?php if (have_posts()) : ?>
                    
                    <!-- Content Grid/List Toggle -->
                    <?php 
                    get_template_part('template-parts/archive/content-list', null, array(
                        'post_type' => 'historias',
                        'layout' => 'magazine', // Layout especial para histórias
                        'show_excerpt' => true,
                        'excerpt_length' => 30,
                        'meta_fields' => array(
                            'historia_periodo' => array(
                                'label' => 'Período',
                                'icon' => 'calendar'
                            ),
                            'historia_categoria' => array(
                                'label' => 'Categoria', 
                                'icon' => 'tag'
                            ),
                            'historia_personagens' => array(
                                'label' => 'Personagens',
                                'icon' => 'users'
                            )
                        )
                    ));
                    ?>
                    
                    <!-- Pagination -->
                    <?php 
                    get_template_part('template-parts/archive/pagination', null, array(
                        'post_type' => 'historias'
                    ));
                    ?>
                    
                <?php else : ?>
                    
                    <!-- No Results -->
                    <?php 
                    get_template_part('template-parts/archive/no-results', null, array(
                        'post_type' => 'historias',
                        'title' => 'Nenhuma história encontrada',
                        'description' => 'Não encontramos histórias que correspondam aos seus critérios de busca.',
                        'suggestions' => array(
                            array(
                                'title' => 'Ver Eventos Históricos',
                                'url' => get_post_type_archive_link('eventos_festivais'),
                                'icon' => '🎭'
                            ),
                            array(
                                'title' => 'Explorar Lugares Históricos', 
                                'url' => get_post_type_archive_link('lugares'),
                                'icon' => '🏛️'
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
                    'post_type' => 'historias',
                    'widgets' => array(
                        'featured_stories' => true,
                        'recent_posts' => true,
                        'popular_tags' => true,
                        'newsletter' => true
                    ),
                    'related_cpts' => array(
                        'lugares' => 'Lugares Históricos',
                        'eventos_festivais' => 'Eventos Culturais'
                    )
                ));
                ?>
            </div>
            
        </div>
    </div>
    
</main>

<?php get_footer(); ?> 