<?php
/**
 * Template para Archive de Organizadores
 * Refatorado para usar template-parts modulares
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Configurações específicas para Organizadores
$archive_config = array(
    'post_type' => 'organizadores',
    'title' => 'Organizadores Culturais',
    'description' => 'Conheça os produtores, organizadores e empresários culturais que fazem a cena de Pernambuco acontecer',
    'icon' => '🏢',
    'color' => 'indigo-600', // Cor índigo para organizadores
    'sidebar_color' => 'indigo-600',
    'stats' => array(
        array('label' => 'Organizadores', 'value' => wp_count_posts('organizadores')->publish),
        array('label' => 'Tipos', 'value' => wp_count_terms('tipos_organizadores'))
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
        'post_type' => 'organizadores',
        'archive_title' => 'Organizadores'
    ));
    ?>
    
    <!-- Filters Bar -->
    <?php 
    get_template_part('template-parts/archive/filters-bar', null, array(
        'post_type' => 'organizadores',
        'taxonomies' => array('tipos_organizadores', 'cidades_pernambuco'),
        'meta_filters' => array(
            'organizador_tipo' => 'Tipo de Organizador',
            'organizador_especialidades' => 'Especialidades',
            'organizador_responsavel' => 'Responsável'
        ),
        'search_placeholder' => 'Buscar organizadores por nome, especialidade...'
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
                        'post_type' => 'organizadores',
                        'layout' => 'cards', // Layout em cards para organizadores
                        'columns' => 3, // 3 colunas para organizadores
                        'show_contact_info' => true,
                        'meta_fields' => array(
                            'organizador_especialidade' => array(
                                'label' => 'Especialidade',
                                'icon' => 'briefcase'
                            ),
                            'organizador_ano_inicio' => array(
                                'label' => 'Desde',
                                'icon' => 'calendar'
                            ),
                            'organizador_telefone' => array(
                                'label' => 'Telefone',
                                'icon' => 'phone'
                            ),
                            'organizador_website' => array(
                                'label' => 'Website',
                                'icon' => 'external-link'
                            )
                        )
                    ));
                    ?>
                    
                    <!-- Pagination -->
                    <?php 
                    get_template_part('template-parts/archive/pagination', null, array(
                        'post_type' => 'organizadores'
                    ));
                    ?>
                    
                <?php else : ?>
                    
                    <!-- No Results -->
                    <?php 
                    get_template_part('template-parts/archive/no-results', null, array(
                        'post_type' => 'organizadores',
                        'title' => 'Nenhum organizador encontrado',
                        'description' => 'Não encontramos organizadores que correspondam aos seus critérios de busca.',
                        'suggestions' => array(
                            array(
                                'title' => 'Ver Artistas',
                                'url' => get_post_type_archive_link('artistas'),
                                'icon' => '🎨'
                            ),
                            array(
                                'title' => 'Explorar Eventos',
                                'url' => get_post_type_archive_link('eventos_festivais'),
                                'icon' => '🎭'
                            ),
                            array(
                                'title' => 'Conhecer Agremiações',
                                'url' => get_post_type_archive_link('agremiacoes'),
                                'icon' => '🎪'
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
                    'post_type' => 'organizadores',
                    'widgets' => array(
                        'featured_organizers' => true,
                        'organizer_types' => true,
                        'contact_directory' => true,
                        'business_network' => true,
                        'newsletter' => true
                    ),
                    'related_cpts' => array(
                        'eventos_festivais' => 'Eventos Organizados',
                        'artistas' => 'Artistas Parceiros'
                    )
                ));
                ?>
            </div>
            
        </div>
    </div>
    
</main>

<?php get_footer(); ?> 