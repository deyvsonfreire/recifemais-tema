<?php
/**
 * Template para Archive de Roteiros
 * Refatorado para usar template-parts modulares
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Configurações específicas para Roteiros
$archive_config = array(
    'post_type' => 'roteiros',
    'title' => 'Roteiros Culturais',
    'description' => 'Descubra roteiros incríveis para explorar a cultura, gastronomia e pontos turísticos de Pernambuco',
    'icon' => '🗺️',
    'color' => 'orange-600', // Cor laranja para roteiros
    'sidebar_color' => 'orange-600',
    'stats' => array(
        array('label' => 'Roteiros', 'value' => wp_count_posts('roteiros')->publish),
        array('label' => 'Destinos', 'value' => wp_count_terms('tipos_roteiros'))
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
        'post_type' => 'roteiros',
        'archive_title' => 'Roteiros'
    ));
    ?>
    
    <!-- Filters Bar -->
    <?php 
    get_template_part('template-parts/archive/filters-bar', null, array(
        'post_type' => 'roteiros',
        'taxonomies' => array('tipos_roteiros', 'duracao_roteiros', 'bairros_recife'),
        'meta_filters' => array(
            'roteiro_duracao' => 'Duração',
            'roteiro_dificuldade' => 'Dificuldade',
            'roteiro_custo_estimado' => 'Custo Estimado',
            'roteiro_publico_alvo' => 'Público-Alvo'
        ),
        'search_placeholder' => 'Buscar roteiros por tema, duração, local...'
    ));
    ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-3">
                <?php if (have_posts()) : ?>
                    
                    <!-- Content Grid -->
                    <?php 
                    get_template_part('template-parts/archive/content-grid', null, array(
                        'post_type' => 'roteiros',
                        'layout' => 'cards', // Layout em cards para roteiros
                        'columns' => 2, // 2 colunas para roteiros (cards maiores)
                        'show_map_preview' => true,
                        'meta_fields' => array(
                            'roteiro_duracao' => array(
                                'label' => 'Duração',
                                'icon' => 'clock'
                            ),
                            'roteiro_dificuldade' => array(
                                'label' => 'Dificuldade',
                                'icon' => 'trending-up'
                            ),
                            'roteiro_custo_estimado' => array(
                                'label' => 'Custo',
                                'icon' => 'dollar-sign'
                            ),
                            'roteiro_pontos_interesse' => array(
                                'label' => 'Pontos de Interesse',
                                'icon' => 'map-pin'
                            )
                        )
                    ));
                    ?>
                    
                    <!-- Pagination -->
                    <?php 
                    get_template_part('template-parts/archive/pagination', null, array(
                        'post_type' => 'roteiros'
                    ));
                    ?>
                    
                <?php else : ?>
                    
                    <!-- No Results -->
                    <?php 
                    get_template_part('template-parts/archive/no-results', null, array(
                        'post_type' => 'roteiros',
                        'title' => 'Nenhum roteiro encontrado',
                        'description' => 'Não encontramos roteiros que correspondam aos seus critérios de busca.',
                        'suggestions' => array(
                            array(
                                'title' => 'Explorar Lugares',
                                'url' => get_post_type_archive_link('lugares'),
                                'icon' => '📍'
                            ),
                            array(
                                'title' => 'Ver Eventos', 
                                'url' => get_post_type_archive_link('eventos_festivais'),
                                'icon' => '🎭'
                            ),
                            array(
                                'title' => 'Guias Temáticos',
                                'url' => get_post_type_archive_link('guias_tematicos'), 
                                'icon' => '📚'
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
                    'post_type' => 'roteiros',
                    'widgets' => array(
                        'featured_routes' => true,
                        'duration_filter' => true,
                        'difficulty_levels' => true,
                        'popular_destinations' => true,
                        'newsletter' => true
                    ),
                    'related_cpts' => array(
                        'lugares' => 'Lugares nos Roteiros',
                        'eventos_festivais' => 'Eventos Relacionados'
                    )
                ));
                ?>
            </div>
            
        </div>
    </div>
    
</main>

<?php get_footer(); ?>