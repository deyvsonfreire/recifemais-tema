<?php
/**
 * Template Part: Archive Header Section
 * 
 * Header colorido para páginas de arquivo com:
 * - Barra lateral colorida por tipo de conteúdo
 * - Título da seção com contagem
 * - Descrição contextual
 * - Estatísticas rápidas
 * - Breadcrumbs integrados
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Configurações por tipo de conteúdo
$archive_configs = [
    'eventos_festivais' => [
        'color' => 'red-600',
        'bg_color' => 'red-50',
        'icon' => '🎭',
        'title' => 'Eventos & Festivais',
        'description' => 'Descubra a agenda cultural vibrante de Recife e região metropolitana',
        'stats_label' => 'eventos ativos'
    ],
    'lugares' => [
        'color' => 'blue-600', 
        'bg_color' => 'blue-50',
        'icon' => '📍',
        'title' => 'Lugares Culturais',
        'description' => 'Explore espaços culturais, museus, teatros e pontos de interesse',
        'stats_label' => 'lugares cadastrados'
    ],
    'artistas' => [
        'color' => 'purple-600',
        'bg_color' => 'purple-50', 
        'icon' => '🎨',
        'title' => 'Artistas & Criadores',
        'description' => 'Conheça os talentos que movem a cena cultural pernambucana',
        'stats_label' => 'artistas ativos'
    ],
    'roteiros' => [
        'color' => 'orange-600',
        'bg_color' => 'orange-50',
        'icon' => '🗺️', 
        'title' => 'Roteiros Culturais',
        'description' => 'Experiências culturais curadas para você explorar Recife',
        'stats_label' => 'roteiros disponíveis'
    ],
    'organizadores' => [
        'color' => 'yellow-600',
        'bg_color' => 'yellow-50',
        'icon' => '🏢',
        'title' => 'Organizadores',
        'description' => 'Produtores e organizadores que fazem a cultura acontecer',
        'stats_label' => 'organizadores ativos'
    ],
    'agremiacoes' => [
        'color' => 'green-600',
        'bg_color' => 'green-50',
        'icon' => '🎪',
        'title' => 'Agremiações',
        'description' => 'Grupos, coletivos e agremiações culturais de Pernambuco',
        'stats_label' => 'agremiações ativas'
    ],
    'historias' => [
        'color' => 'indigo-600',
        'bg_color' => 'indigo-50',
        'icon' => '📖',
        'title' => 'Histórias & Memórias',
        'description' => 'Narrativas que preservam e celebram nossa cultura',
        'stats_label' => 'histórias publicadas'
    ],
    'guias_tematicos' => [
        'color' => 'pink-600',
        'bg_color' => 'pink-50',
        'icon' => '📚',
        'title' => 'Guias Temáticos',
        'description' => 'Guias especializados para experiências culturais únicas',
        'stats_label' => 'guias disponíveis'
    ],
    'post' => [
        'color' => 'gray-600',
        'bg_color' => 'gray-50',
        'icon' => '📰',
        'title' => 'Notícias & Artigos',
        'description' => 'Últimas notícias e artigos sobre cultura em Pernambuco',
        'stats_label' => 'artigos publicados'
    ]
];

// Detectar tipo de conteúdo atual
$current_post_type = get_post_type();
$current_object = get_queried_object();

// Para categorias de posts
if (is_category()) {
    $post_type_key = 'post';
    $archive_title = single_cat_title('', false);
    $archive_description = category_description();
    $total_posts = $current_object->count ?? 0;
} 
// Para archives de CPT
elseif (is_post_type_archive()) {
    $post_type_key = $current_post_type;
    $post_type_obj = get_post_type_object($current_post_type);
    $archive_title = $post_type_obj->labels->name ?? '';
    $archive_description = $post_type_obj->description ?? '';
    
    // Contar posts do tipo
    $count_posts = wp_count_posts($current_post_type);
    $total_posts = $count_posts->publish ?? 0;
}
// Para tags
elseif (is_tag()) {
    $post_type_key = 'post';
    $archive_title = single_tag_title('', false);
    $archive_description = tag_description();
    $total_posts = $current_object->count ?? 0;
}
// Para archives de autor
elseif (is_author()) {
    $post_type_key = 'post';
    $archive_title = get_the_author_meta('display_name');
    $archive_description = get_the_author_meta('description');
    $total_posts = count_user_posts(get_the_author_meta('ID'));
}
// Para archives de data
elseif (is_date()) {
    $post_type_key = 'post';
    $archive_title = get_the_archive_title();
    $archive_description = get_the_archive_description();
    $total_posts = $wp_query->found_posts ?? 0;
}
// Fallback
else {
    $post_type_key = 'post';
    $archive_title = get_the_archive_title();
    $archive_description = get_the_archive_description();
    $total_posts = $wp_query->found_posts ?? 0;
}

// Configuração para o tipo atual
$config = $archive_configs[$post_type_key] ?? $archive_configs['post'];

// Estatísticas adicionais simuladas (podem ser substituídas por dados reais)
$stats = [
    'total' => $total_posts,
    'this_month' => rand(5, 25),
    'featured' => rand(2, 8),
    'views' => number_format(rand(1000, 50000))
];
?>

<section class="archive-header-section bg-white border-b border-gray-200 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0 bg-gradient-to-br from-<?php echo $config['color']; ?> to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-<?php echo $config['color']; ?> rounded-full blur-3xl opacity-10 transform translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="container mx-auto px-4 py-8 lg:py-12 relative">
        <!-- Breadcrumbs -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <?php get_template_part('template-parts/archive/breadcrumbs'); ?>
        </nav>

        <div class="grid lg:grid-cols-12 gap-8 items-center">
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-8">
                <!-- Header com Barra Lateral -->
                <div class="flex items-start gap-4 mb-6">
                    <!-- Barra Lateral Colorida -->
                    <div class="flex-shrink-0">
                        <div class="w-1 h-16 bg-<?php echo $config['color']; ?> rounded-full"></div>
                    </div>
                    
                    <!-- Conteúdo do Header -->
                    <div class="flex-1 min-w-0">
                        <!-- Tag da Seção -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?php echo $config['bg_color']; ?> text-<?php echo $config['color']; ?>">
                                <span class="mr-1"><?php echo $config['icon']; ?></span>
                                <?php echo esc_html($config['title']); ?>
                            </span>
                            
                            <?php if ($total_posts > 0): ?>
                                <span class="text-sm text-gray-500">
                                    <?php echo number_format($total_posts); ?> <?php echo $config['stats_label']; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Título Principal -->
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3 leading-tight">
                            <?php echo esc_html($archive_title); ?>
                        </h1>

                        <!-- Descrição -->
                        <?php if ($archive_description || $config['description']): ?>
                            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl">
                                <?php echo $archive_description ?: $config['description']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="lg:col-span-4">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Total -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                        <div class="text-2xl font-bold text-<?php echo $config['color']; ?> mb-1">
                            <?php echo number_format($stats['total']); ?>
                        </div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>

                    <!-- Este Mês -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            +<?php echo $stats['this_month']; ?>
                        </div>
                        <div class="text-sm text-gray-600">Este mês</div>
                    </div>

                    <!-- Em Destaque -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                        <div class="text-2xl font-bold text-yellow-600 mb-1">
                            <?php echo $stats['featured']; ?>
                        </div>
                        <div class="text-sm text-gray-600">Destaque</div>
                    </div>

                    <!-- Visualizações -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 text-center hover:shadow-md transition-shadow">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            <?php echo $stats['views']; ?>
                        </div>
                        <div class="text-sm text-gray-600">Views</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <?php if (is_category() || is_tag()): ?>
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                    <?php if (is_category()): ?>
                        <span>📂 Categoria: <strong><?php echo esc_html($archive_title); ?></strong></span>
                        
                        <?php if ($current_object->parent): ?>
                            <?php $parent_cat = get_category($current_object->parent); ?>
                            <span>↗️ Categoria pai: <a href="<?php echo get_category_link($parent_cat->term_id); ?>" class="text-<?php echo $config['color']; ?> hover:underline"><?php echo esc_html($parent_cat->name); ?></a></span>
                        <?php endif; ?>
                        
                    <?php elseif (is_tag()): ?>
                        <span>🏷️ Tag: <strong><?php echo esc_html($archive_title); ?></strong></span>
                    <?php endif; ?>
                    
                    <span>📅 Última atualização: <?php echo date('d/m/Y'); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "<?php echo esc_js($archive_title); ?>",
    "description": "<?php echo esc_js(strip_tags($archive_description ?: $config['description'])); ?>",
    "url": "<?php echo esc_url(get_pagenum_link()); ?>",
    "mainEntity": {
        "@type": "ItemList",
        "numberOfItems": <?php echo intval($total_posts); ?>,
        "itemListElement": "<?php echo esc_js($config['title']); ?>"
    },
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?php echo esc_url(home_url('/')); ?>"
            },
            {
                "@type": "ListItem", 
                "position": 2,
                "name": "<?php echo esc_js($archive_title); ?>",
                "item": "<?php echo esc_url(get_pagenum_link()); ?>"
            }
        ]
    }
}
</script> 