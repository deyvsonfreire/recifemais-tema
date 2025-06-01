<?php
/**
 * Template Part: Search Map
 * 
 * Mapa para busca avançada com:
 * - Busca por texto em tempo real
 * - Filtros por tipo de conteúdo
 * - Busca geográfica por área
 * - Integração com sistema de busca do plugin
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Configurações do mapa
$args = wp_parse_args($args ?? [], [
    'height' => '600px',
    'zoom' => 12,
    'center_lat' => -8.0476,
    'center_lng' => -34.8770,
    'show_search' => true,
    'show_filters' => true,
    'show_user_location' => true,
    'enable_clustering' => true,
    'enable_draw_search' => true,
    'post_types' => ['eventos_festivais', 'lugares', 'artistas', 'roteiros'],
    'max_results' => 50,
    'auto_search' => true
]);

// Parâmetros de busca da URL
$search_query = sanitize_text_field($_GET['search'] ?? '');
$selected_types = isset($_GET['types']) ? array_map('sanitize_text_field', (array)$_GET['types']) : $args['post_types'];
$selected_manifestacao = sanitize_text_field($_GET['manifestacao'] ?? '');
$selected_bairro = sanitize_text_field($_GET['bairro'] ?? '');
$selected_radius = intval($_GET['radius'] ?? 5);

// Preparar query de busca
$search_args = [
    'post_type' => $selected_types,
    'post_status' => 'publish',
    'posts_per_page' => $args['max_results'],
    's' => $search_query,
    'meta_query' => [
        'relation' => 'OR',
        [
            'key' => 'lugar_latitude',
            'compare' => 'EXISTS'
        ],
        [
            'key' => 'evento_latitude',
            'compare' => 'EXISTS'
        ]
    ]
];

// Aplicar filtros de taxonomia
if (!empty($selected_manifestacao)) {
    $search_args['tax_query'][] = [
        'taxonomy' => 'manifestacoes_culturais',
        'field' => 'slug',
        'terms' => $selected_manifestacao
    ];
}

if (!empty($selected_bairro)) {
    $search_args['tax_query'][] = [
        'taxonomy' => 'bairros_recife',
        'field' => 'slug',
        'terms' => $selected_bairro
    ];
}

// Executar busca
$search_results = new WP_Query($search_args);

// Preparar dados dos marcadores
$markers_data = [];
$results_stats = [
    'total' => 0,
    'by_type' => [],
    'by_bairro' => []
];

if ($search_results->have_posts()) {
    while ($search_results->have_posts()) {
        $search_results->the_post();
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        
        // Buscar coordenadas
        $latitude = null;
        $longitude = null;
        $endereco = '';
        
        if ($post_type === 'lugares') {
            $latitude = get_post_meta($post_id, 'lugar_latitude', true);
            $longitude = get_post_meta($post_id, 'lugar_longitude', true);
            $endereco = get_post_meta($post_id, 'lugar_endereco', true);
        } elseif ($post_type === 'eventos_festivais') {
            $local_id = get_post_meta($post_id, 'evento_local', true);
            if ($local_id) {
                $latitude = get_post_meta($local_id, 'lugar_latitude', true);
                $longitude = get_post_meta($local_id, 'lugar_longitude', true);
                $endereco = get_post_meta($local_id, 'lugar_endereco', true);
            }
        }
        
        if (!$latitude || !$longitude) continue;
        
        // Dados do post
        $thumbnail = get_the_post_thumbnail_url($post_id, 'medium');
        $excerpt = wp_trim_words(get_the_excerpt(), 15);
        
        // Taxonomias
        $manifestacoes = get_the_terms($post_id, 'manifestacoes_culturais');
        $bairros = get_the_terms($post_id, 'bairros_recife');
        
        // Ícones por tipo
        $type_icons = [
            'lugares' => '📍',
            'eventos_festivais' => '🎭',
            'artistas' => '🎨',
            'roteiros' => '🗺️',
            'organizadores' => '🏢',
            'agremiacoes' => '🎪',
            'historias' => '📖',
            'guias_tematicos' => '📚'
        ];
        
        // Cores por tipo
        $type_colors = [
            'lugares' => 'blue',
            'eventos_festivais' => 'purple',
            'artistas' => 'pink',
            'roteiros' => 'green',
            'organizadores' => 'indigo',
            'agremiacoes' => 'yellow',
            'historias' => 'gray',
            'guias_tematicos' => 'red'
        ];
        
        $markers_data[] = [
            'lat' => floatval($latitude),
            'lng' => floatval($longitude),
            'post_id' => $post_id,
            'post_type' => $post_type,
            'title' => get_the_title(),
            'excerpt' => $excerpt,
            'permalink' => get_permalink(),
            'thumbnail' => $thumbnail,
            'address' => $endereco,
            'icon' => $type_icons[$post_type] ?? '📍',
            'color' => $type_colors[$post_type] ?? 'blue',
            'manifestacoes' => $manifestacoes ? wp_list_pluck($manifestacoes, 'name') : [],
            'bairros' => $bairros ? wp_list_pluck($bairros, 'name') : []
        ];
        
        // Estatísticas
        $results_stats['total']++;
        $results_stats['by_type'][$post_type] = ($results_stats['by_type'][$post_type] ?? 0) + 1;
        
        if ($bairros) {
            foreach ($bairros as $bairro) {
                $results_stats['by_bairro'][$bairro->slug] = $bairro->name;
            }
        }
    }
    wp_reset_postdata();
}

$map_id = 'search-map-' . uniqid();

// Dados para JavaScript
$map_config = [
    'center' => ['lat' => $args['center_lat'], 'lng' => $args['center_lng']],
    'zoom' => $args['zoom'],
    'markers' => $markers_data,
    'clustering' => $args['enable_clustering'],
    'showUserLocation' => $args['show_user_location'],
    'enableDrawSearch' => $args['enable_draw_search'],
    'autoSearch' => $args['auto_search'],
    'maxResults' => $args['max_results']
];

// Labels dos tipos de post
$post_type_labels = [
    'lugares' => 'Lugares',
    'eventos_festivais' => 'Eventos',
    'artistas' => 'Artistas',
    'roteiros' => 'Roteiros',
    'organizadores' => 'Organizadores',
    'agremiacoes' => 'Agremiações',
    'historias' => 'Histórias',
    'guias_tematicos' => 'Guias'
];
?>

<div class="search-map-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- Header de Busca -->
    <div class="search-header bg-gradient-to-r from-indigo-50 to-purple-50 p-4 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <!-- Título e Estatísticas -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Busca no Mapa</h3>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <span class="flex items-center gap-2 bg-white px-3 py-1 rounded-full">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        <strong><?php echo number_format($results_stats['total']); ?></strong> resultados
                    </span>
                    
                    <?php if (!empty($search_query)): ?>
                        <span class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full">
                            🔍 "<strong><?php echo esc_html($search_query); ?></strong>"
                        </span>
                    <?php endif; ?>
                    
                    <span class="flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                        📍 <strong><?php echo count($results_stats['by_bairro']); ?></strong> bairros
                    </span>
                </div>
            </div>
            
            <!-- Controles -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="clearSearch('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    Limpar
                </button>
                
                <button type="button" 
                        onclick="toggleSearchFullscreen('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Expandir
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filtros de Busca -->
    <?php if ($args['show_filters']): ?>
        <div class="search-filters bg-gray-50 p-4 border-b border-gray-200">
            <form id="search-map-form" method="GET" class="space-y-4">
                
                <!-- Busca por Texto -->
                <?php if ($args['show_search']): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2">
                            <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Buscar por texto</label>
                            <div class="relative">
                                <input type="text" 
                                       id="search-input" 
                                       name="search"
                                       placeholder="Digite o que você procura..."
                                       value="<?php echo esc_attr($search_query); ?>"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div>
                            <label for="search-radius" class="block text-sm font-medium text-gray-700 mb-1">Raio de busca</label>
                            <select id="search-radius" 
                                    name="radius"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="1" <?php selected($selected_radius, 1); ?>>1 km</option>
                                <option value="2" <?php selected($selected_radius, 2); ?>>2 km</option>
                                <option value="5" <?php selected($selected_radius, 5); ?>>5 km</option>
                                <option value="10" <?php selected($selected_radius, 10); ?>>10 km</option>
                                <option value="20" <?php selected($selected_radius, 20); ?>>20 km</option>
                                <option value="0">Sem limite</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Filtros por Tipo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipos de conteúdo</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2">
                        <?php foreach ($args['post_types'] as $post_type): ?>
                            <label class="flex items-center gap-2 bg-white p-2 rounded-lg border border-gray-200 hover:border-indigo-300 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       name="types[]" 
                                       value="<?php echo esc_attr($post_type); ?>"
                                       <?php checked(in_array($post_type, $selected_types)); ?>
                                       class="text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="text-sm font-medium text-gray-700">
                                    <?php echo esc_html($post_type_labels[$post_type] ?? $post_type); ?>
                                    <?php if (isset($results_stats['by_type'][$post_type])): ?>
                                        <span class="text-xs text-gray-500">(<?php echo $results_stats['by_type'][$post_type]; ?>)</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Filtros por Taxonomia -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="filter-manifestacao" class="block text-sm font-medium text-gray-700 mb-1">Manifestação Cultural</label>
                        <select id="filter-manifestacao" 
                                name="manifestacao"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Todas as manifestações</option>
                            <?php
                            $manifestacoes = get_terms([
                                'taxonomy' => 'manifestacoes_culturais',
                                'hide_empty' => true
                            ]);
                            foreach ($manifestacoes as $manifestacao):
                            ?>
                                <option value="<?php echo esc_attr($manifestacao->slug); ?>" 
                                        <?php selected($selected_manifestacao, $manifestacao->slug); ?>>
                                    <?php echo esc_html($manifestacao->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="filter-bairro" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <select id="filter-bairro" 
                                name="bairro"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Todos os bairros</option>
                            <?php
                            $bairros = get_terms([
                                'taxonomy' => 'bairros_recife',
                                'hide_empty' => true
                            ]);
                            foreach ($bairros as $bairro):
                            ?>
                                <option value="<?php echo esc_attr($bairro->slug); ?>" 
                                        <?php selected($selected_bairro, $bairro->slug); ?>>
                                    <?php echo esc_html($bairro->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Botões de Ação -->
                <div class="flex items-center gap-3">
                    <button type="submit" 
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                        Buscar
                    </button>
                    
                    <a href="<?php echo esc_url(remove_query_arg(['search', 'types', 'manifestacao', 'bairro', 'radius'])); ?>" 
                       class="flex items-center gap-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Limpar Filtros
                    </a>
                    
                    <?php if ($args['enable_draw_search']): ?>
                        <button type="button" 
                                onclick="toggleDrawSearch('<?php echo esc_js($map_id); ?>')"
                                id="draw-search-btn"
                                class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"></path>
                            </svg>
                            Buscar por Área
                        </button>
                    <?php endif; ?>
                    
                    <div class="ml-auto text-sm text-gray-600">
                        <span id="visible-results-count"><?php echo $results_stats['total']; ?></span> de <?php echo $search_results->found_posts; ?> resultados
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    
    <!-- Container do Mapa -->
    <div class="map-wrapper relative">
        <div id="<?php echo esc_attr($map_id); ?>" 
             class="search-map w-full"
             style="height: <?php echo esc_attr($args['height']); ?>;"
             data-map-config="<?php echo esc_attr(json_encode($map_config)); ?>">
        </div>
        
        <!-- Loading Overlay -->
        <div id="<?php echo esc_attr($map_id); ?>-loading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <p class="text-lg font-medium text-gray-900 mb-2">Carregando resultados...</p>
                <p class="text-sm text-gray-600">Processando <?php echo count($markers_data); ?> resultados no mapa</p>
            </div>
        </div>
        
        <!-- Empty State -->
        <?php if (empty($markers_data)): ?>
            <div class="absolute inset-0 bg-gray-50 flex items-center justify-center">
                <div class="text-center p-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Nenhum resultado encontrado</h3>
                    <p class="text-gray-600 mb-4">Tente ajustar os filtros ou ampliar a área de busca.</p>
                    <button type="button" 
                            onclick="clearSearch('<?php echo esc_js($map_id); ?>')"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Limpar Busca
                    </button>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Draw Search Instructions -->
        <div id="draw-instructions" class="hidden absolute top-4 left-4 right-4 bg-purple-100 border border-purple-300 rounded-lg p-4 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-purple-900">Busca por Área Ativa</h4>
                        <p class="text-sm text-purple-700">Clique e arraste no mapa para desenhar uma área de busca</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="cancelDrawSearch('<?php echo esc_js($map_id); ?>')"
                        class="text-purple-600 hover:text-purple-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initSearchMap('<?php echo esc_js($map_id); ?>');
    initSearchFilters();
});

function initSearchMap(mapId) {
    const mapContainer = document.getElementById(mapId);
    const loadingElement = document.getElementById(mapId + '-loading');
    
    if (!mapContainer) return;
    
    try {
        const mapConfig = JSON.parse(mapContainer.dataset.mapConfig);
        
        // Verificar se RecifeMaisMaps está disponível
        if (typeof RecifeMaisMaps !== 'undefined') {
            const maps = new RecifeMaisMaps();
            
            // Configuração específica para busca
            const config = {
                ...mapConfig,
                search: true,
                drawingManager: mapConfig.enableDrawSearch,
                userLocationButton: true
            };
            
            maps.initMap(mapId, config).then(() => {
                loadingElement.style.display = 'none';
                updateResultsCount(mapConfig.markers.length);
                
                // Auto-fit bounds se houver resultados
                if (mapConfig.markers.length > 0) {
                    maps.fitBounds(mapId, mapConfig.markers);
                }
                
                // Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'search_map_loaded', {
                        'total_results': mapConfig.markers.length,
                        'search_query': '<?php echo esc_js($search_query); ?>'
                    });
                }
            }).catch(error => {
                console.error('Erro ao carregar mapa de busca:', error);
                showSearchError(mapId);
            });
            
        } else {
            showSearchError(mapId);
        }
        
    } catch (error) {
        console.error('Erro na configuração do mapa de busca:', error);
        showSearchError(mapId);
    }
}

function initSearchFilters() {
    const form = document.getElementById('search-map-form');
    if (!form) return;
    
    // Auto-submit em mudanças nos selects
    const selects = form.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (<?php echo $args['auto_search'] ? 'true' : 'false'; ?>) {
                form.submit();
            }
        });
    });
    
    // Auto-submit em mudanças nos checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (<?php echo $args['auto_search'] ? 'true' : 'false'; ?>) {
                form.submit();
            }
        });
    });
    
    // Busca com debounce
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (<?php echo $args['auto_search'] ? 'true' : 'false'; ?>) {
                    form.submit();
                }
            }, 800);
        });
    }
}

function showSearchError(mapId) {
    const loadingElement = document.getElementById(mapId + '-loading');
    if (loadingElement) {
        loadingElement.innerHTML = `
            <div class="text-center p-8">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar busca</h3>
                <p class="text-sm text-gray-600">Não foi possível carregar o mapa de busca.</p>
            </div>
        `;
    }
}

function clearSearch(mapId) {
    // Limpar formulário
    const form = document.getElementById('search-map-form');
    if (form) {
        form.reset();
    }
    
    // Redirecionar para página limpa
    window.location.href = window.location.pathname;
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'search_cleared', {
            'map_type': 'search_map'
        });
    }
}

function toggleSearchFullscreen(mapId) {
    const mapContainer = document.getElementById(mapId);
    const wrapper = mapContainer.closest('.search-map-container');
    
    if (!document.fullscreenElement) {
        wrapper.requestFullscreen().then(() => {
            wrapper.classList.add('fullscreen-map');
            mapContainer.style.height = 'calc(100vh - 300px)';
            
            // Redimensionar mapa
            setTimeout(() => {
                if (window.google && window.google.maps) {
                    google.maps.event.trigger(mapContainer.mapInstance, 'resize');
                }
            }, 100);
        });
    } else {
        document.exitFullscreen().then(() => {
            wrapper.classList.remove('fullscreen-map');
            mapContainer.style.height = '<?php echo esc_js($args['height']); ?>';
            
            // Redimensionar mapa
            setTimeout(() => {
                if (window.google && window.google.maps) {
                    google.maps.event.trigger(mapContainer.mapInstance, 'resize');
                }
            }, 100);
        });
    }
}

function toggleDrawSearch(mapId) {
    const instructions = document.getElementById('draw-instructions');
    const button = document.getElementById('draw-search-btn');
    
    if (instructions.classList.contains('hidden')) {
        // Ativar modo de desenho
        instructions.classList.remove('hidden');
        button.textContent = 'Cancelar Área';
        button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
        button.classList.add('bg-red-600', 'hover:bg-red-700');
        
        // Ativar drawing manager no mapa
        if (window.RecifeMaisMaps) {
            window.RecifeMaisMaps.enableDrawing(mapId);
        }
    } else {
        cancelDrawSearch(mapId);
    }
}

function cancelDrawSearch(mapId) {
    const instructions = document.getElementById('draw-instructions');
    const button = document.getElementById('draw-search-btn');
    
    instructions.classList.add('hidden');
    button.innerHTML = `
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"></path>
        </svg>
        Buscar por Área
    `;
    button.classList.remove('bg-red-600', 'hover:bg-red-700');
    button.classList.add('bg-purple-600', 'hover:bg-purple-700');
    
    // Desativar drawing manager no mapa
    if (window.RecifeMaisMaps) {
        window.RecifeMaisMaps.disableDrawing(mapId);
    }
}

function updateResultsCount(count) {
    const countElement = document.getElementById('visible-results-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

// Função para ser chamada quando área for desenhada
function onAreaDrawn(mapId, bounds) {
    // Buscar resultados dentro da área
    const form = document.getElementById('search-map-form');
    if (form) {
        // Adicionar bounds como campos hidden
        const boundsInput = document.createElement('input');
        boundsInput.type = 'hidden';
        boundsInput.name = 'bounds';
        boundsInput.value = JSON.stringify(bounds);
        form.appendChild(boundsInput);
        
        form.submit();
    }
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'area_search_performed', {
            'bounds': bounds
        });
    }
}
</script>

<style>
.search-map-container.fullscreen-map {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background: white;
}

.search-map-container {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .search-header .flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .search-filters .grid {
        grid-template-columns: 1fr;
    }
    
    .search-filters .lg\:col-span-2 {
        grid-column: span 1;
    }
}
</style> 