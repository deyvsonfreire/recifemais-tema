<?php
/**
 * Template Part: Cluster Events Map
 * 
 * Mapa com clustering para múltiplos eventos com:
 * - Agrupamento inteligente de marcadores
 * - Filtros dinâmicos por data, tipo, bairro
 * - Popup com informações dos eventos
 * - Integração com sistema de busca
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
    'height' => '500px',
    'zoom' => 12,
    'center_lat' => -8.0476,
    'center_lng' => -34.8770,
    'show_filters' => true,
    'show_search' => true,
    'show_user_location' => true,
    'enable_clustering' => true,
    'max_zoom' => 18,
    'cluster_max_zoom' => 15,
    'post_types' => ['eventos_festivais'],
    'posts_per_page' => 100,
    'filter_types' => ['manifestacoes_culturais', 'bairros_recife', 'tipos_eventos']
]);

// Query para buscar eventos com coordenadas
$query_args = [
    'post_type' => $args['post_types'],
    'post_status' => 'publish',
    'posts_per_page' => $args['posts_per_page'],
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'evento_data_inicio',
            'value' => date('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE'
        ]
    ]
];

// Aplicar filtros se fornecidos
if (isset($_GET['manifestacao']) && !empty($_GET['manifestacao'])) {
    $query_args['tax_query'][] = [
        'taxonomy' => 'manifestacoes_culturais',
        'field' => 'slug',
        'terms' => sanitize_text_field($_GET['manifestacao'])
    ];
}

if (isset($_GET['bairro']) && !empty($_GET['bairro'])) {
    $query_args['tax_query'][] = [
        'taxonomy' => 'bairros_recife',
        'field' => 'slug',
        'terms' => sanitize_text_field($_GET['bairro'])
    ];
}

if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
    $query_args['meta_query'][0]['value'] = sanitize_text_field($_GET['data_inicio']);
}

$eventos_query = new WP_Query($query_args);

// Preparar dados dos marcadores
$markers_data = [];
$stats = [
    'total_eventos' => 0,
    'eventos_hoje' => 0,
    'eventos_semana' => 0,
    'bairros_unicos' => []
];

if ($eventos_query->have_posts()) {
    while ($eventos_query->have_posts()) {
        $eventos_query->the_post();
        $post_id = get_the_ID();
        
        // Buscar coordenadas do local do evento
        $local_id = get_post_meta($post_id, 'evento_local', true);
        if (!$local_id) continue;
        
        $latitude = get_post_meta($local_id, 'lugar_latitude', true);
        $longitude = get_post_meta($local_id, 'lugar_longitude', true);
        
        if (!$latitude || !$longitude) continue;
        
        // Dados do evento
        $data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
        $horario_inicio = get_post_meta($post_id, 'evento_horario_inicio', true);
        $preco = get_post_meta($post_id, 'evento_preco', true);
        $local_nome = get_the_title($local_id);
        $local_endereco = get_post_meta($local_id, 'lugar_endereco', true);
        
        // Taxonomias
        $manifestacoes = get_the_terms($post_id, 'manifestacoes_culturais');
        $bairros = get_the_terms($post_id, 'bairros_recife');
        
        // Status do evento
        $status = 'futuro';
        $status_class = 'bg-blue-100 text-blue-800';
        if ($data_inicio) {
            $hoje = date('Y-m-d');
            $data_evento = date('Y-m-d', strtotime($data_inicio));
            
            if ($data_evento == $hoje) {
                $status = 'hoje';
                $status_class = 'bg-green-100 text-green-800';
                $stats['eventos_hoje']++;
            } elseif ($data_evento <= date('Y-m-d', strtotime('+7 days'))) {
                $stats['eventos_semana']++;
            }
        }
        
        // Adicionar ao array de marcadores
        $markers_data[] = [
            'lat' => floatval($latitude),
            'lng' => floatval($longitude),
            'post_id' => $post_id,
            'title' => get_the_title(),
            'excerpt' => wp_trim_words(get_the_excerpt(), 20),
            'permalink' => get_permalink(),
            'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
            'data_inicio' => $data_inicio,
            'horario_inicio' => $horario_inicio,
            'preco' => $preco,
            'local_nome' => $local_nome,
            'local_endereco' => $local_endereco,
            'manifestacoes' => $manifestacoes ? wp_list_pluck($manifestacoes, 'name') : [],
            'bairros' => $bairros ? wp_list_pluck($bairros, 'name') : [],
            'status' => $status,
            'status_class' => $status_class,
            'icon' => '🎭'
        ];
        
        $stats['total_eventos']++;
        if ($bairros) {
            foreach ($bairros as $bairro) {
                $stats['bairros_unicos'][$bairro->slug] = $bairro->name;
            }
        }
    }
    wp_reset_postdata();
}

$map_id = 'cluster-events-map-' . uniqid();

// Dados para JavaScript
$map_config = [
    'center' => ['lat' => $args['center_lat'], 'lng' => $args['center_lng']],
    'zoom' => $args['zoom'],
    'maxZoom' => $args['max_zoom'],
    'clustering' => $args['enable_clustering'],
    'clusterMaxZoom' => $args['cluster_max_zoom'],
    'markers' => $markers_data,
    'showUserLocation' => $args['show_user_location']
];
?>

<div class="cluster-events-map-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- Header com Estatísticas -->
    <div class="map-header bg-gradient-to-r from-purple-50 to-pink-50 p-4 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <!-- Título e Estatísticas -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Mapa de Eventos Culturais</h3>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <span class="flex items-center gap-2 bg-white px-3 py-1 rounded-full">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <strong><?php echo number_format($stats['total_eventos']); ?></strong> eventos
                    </span>
                    
                    <?php if ($stats['eventos_hoje'] > 0): ?>
                        <span class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <strong><?php echo $stats['eventos_hoje']; ?></strong> hoje
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($stats['eventos_semana'] > 0): ?>
                        <span class="flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <strong><?php echo $stats['eventos_semana']; ?></strong> esta semana
                        </span>
                    <?php endif; ?>
                    
                    <span class="flex items-center gap-2 bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                        📍 <strong><?php echo count($stats['bairros_unicos']); ?></strong> bairros
                    </span>
                </div>
            </div>
            
            <!-- Controles -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="resetMapView('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    Reset
                </button>
                
                <button type="button" 
                        onclick="toggleMapFullscreen('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Tela Cheia
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <?php if ($args['show_filters']): ?>
        <div class="map-filters bg-gray-50 p-4 border-b border-gray-200">
            <form id="map-filters-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Busca por Texto -->
                <?php if ($args['show_search']): ?>
                    <div>
                        <label for="search-events" class="block text-sm font-medium text-gray-700 mb-1">Buscar eventos</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search-events" 
                                   name="search"
                                   placeholder="Nome do evento..."
                                   value="<?php echo esc_attr($_GET['search'] ?? ''); ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Filtro por Manifestação Cultural -->
                <div>
                    <label for="filter-manifestacao" class="block text-sm font-medium text-gray-700 mb-1">Manifestação</label>
                    <select id="filter-manifestacao" 
                            name="manifestacao"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Todas as manifestações</option>
                        <?php
                        $manifestacoes = get_terms([
                            'taxonomy' => 'manifestacoes_culturais',
                            'hide_empty' => true
                        ]);
                        foreach ($manifestacoes as $manifestacao):
                        ?>
                            <option value="<?php echo esc_attr($manifestacao->slug); ?>" 
                                    <?php selected($_GET['manifestacao'] ?? '', $manifestacao->slug); ?>>
                                <?php echo esc_html($manifestacao->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtro por Bairro -->
                <div>
                    <label for="filter-bairro" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                    <select id="filter-bairro" 
                            name="bairro"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Todos os bairros</option>
                        <?php
                        $bairros = get_terms([
                            'taxonomy' => 'bairros_recife',
                            'hide_empty' => true
                        ]);
                        foreach ($bairros as $bairro):
                        ?>
                            <option value="<?php echo esc_attr($bairro->slug); ?>" 
                                    <?php selected($_GET['bairro'] ?? '', $bairro->slug); ?>>
                                <?php echo esc_html($bairro->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtro por Data -->
                <div>
                    <label for="filter-data" class="block text-sm font-medium text-gray-700 mb-1">A partir de</label>
                    <input type="date" 
                           id="filter-data" 
                           name="data_inicio"
                           value="<?php echo esc_attr($_GET['data_inicio'] ?? date('Y-m-d')); ?>"
                           min="<?php echo date('Y-m-d'); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <!-- Botões de Ação -->
                <div class="md:col-span-2 lg:col-span-4 flex items-center gap-3">
                    <button type="submit" 
                            class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                        </svg>
                        Filtrar
                    </button>
                    
                    <a href="<?php echo esc_url(remove_query_arg(['search', 'manifestacao', 'bairro', 'data_inicio'])); ?>" 
                       class="flex items-center gap-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Limpar
                    </a>
                    
                    <div class="ml-auto text-sm text-gray-600">
                        <span id="visible-markers-count"><?php echo count($markers_data); ?></span> eventos visíveis
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    
    <!-- Container do Mapa -->
    <div class="map-wrapper relative">
        <div id="<?php echo esc_attr($map_id); ?>" 
             class="cluster-events-map w-full"
             style="height: <?php echo esc_attr($args['height']); ?>;"
             data-map-config="<?php echo esc_attr(json_encode($map_config)); ?>">
        </div>
        
        <!-- Loading Overlay -->
        <div id="<?php echo esc_attr($map_id); ?>-loading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto mb-4"></div>
                <p class="text-lg font-medium text-gray-900 mb-2">Carregando eventos...</p>
                <p class="text-sm text-gray-600">Preparando <?php echo count($markers_data); ?> eventos no mapa</p>
            </div>
        </div>
        
        <!-- Empty State -->
        <?php if (empty($markers_data)): ?>
            <div class="absolute inset-0 bg-gray-50 flex items-center justify-center">
                <div class="text-center p-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Nenhum evento encontrado</h3>
                    <p class="text-gray-600 mb-4">Não há eventos que correspondam aos filtros selecionados.</p>
                    <a href="<?php echo esc_url(remove_query_arg(['search', 'manifestacao', 'bairro', 'data_inicio'])); ?>" 
                       class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Ver Todos os Eventos
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initClusterEventsMap('<?php echo esc_js($map_id); ?>');
    initMapFilters();
});

function initClusterEventsMap(mapId) {
    const mapContainer = document.getElementById(mapId);
    const loadingElement = document.getElementById(mapId + '-loading');
    
    if (!mapContainer) return;
    
    try {
        const mapConfig = JSON.parse(mapContainer.dataset.mapConfig);
        
        // Verificar se RecifeMaisMaps está disponível
        if (typeof RecifeMaisMaps !== 'undefined') {
            const maps = new RecifeMaisMaps();
            
            maps.initMap(mapId, mapConfig).then(() => {
                loadingElement.style.display = 'none';
                updateVisibleMarkersCount(mapConfig.markers.length);
                
                // Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'map_loaded', {
                        'map_type': 'cluster_events',
                        'total_markers': mapConfig.markers.length
                    });
                }
            }).catch(error => {
                console.error('Erro ao carregar mapa:', error);
                loadingElement.innerHTML = '<div class="text-center p-8"><p class="text-red-600">Erro ao carregar mapa</p></div>';
            });
            
        } else {
            loadingElement.innerHTML = '<div class="text-center p-8"><p class="text-gray-600">Sistema de mapas não disponível</p></div>';
        }
        
    } catch (error) {
        console.error('Erro na configuração do mapa:', error);
        loadingElement.innerHTML = '<div class="text-center p-8"><p class="text-red-600">Erro na configuração</p></div>';
    }
}

function initMapFilters() {
    const form = document.getElementById('map-filters-form');
    if (!form) return;
    
    // Auto-submit em mudanças nos selects
    const selects = form.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Busca com debounce
    const searchInput = document.getElementById('search-events');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 500);
        });
    }
}

function resetMapView(mapId) {
    const mapContainer = document.getElementById(mapId);
    if (mapContainer && mapContainer.mapInstance) {
        const mapConfig = JSON.parse(mapContainer.dataset.mapConfig);
        mapContainer.mapInstance.setCenter(mapConfig.center);
        mapContainer.mapInstance.setZoom(mapConfig.zoom);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'map_reset', {
                'map_type': 'cluster_events'
            });
        }
    }
}

function toggleMapFullscreen(mapId) {
    const mapContainer = document.getElementById(mapId);
    const wrapper = mapContainer.closest('.cluster-events-map-container');
    
    if (!document.fullscreenElement) {
        wrapper.requestFullscreen().then(() => {
            wrapper.classList.add('fullscreen-map');
            mapContainer.style.height = 'calc(100vh - 200px)';
            
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

function updateVisibleMarkersCount(count) {
    const countElement = document.getElementById('visible-markers-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

// Função para ser chamada quando filtros do mapa mudarem
function onMapFiltersChange(visibleMarkers) {
    updateVisibleMarkersCount(visibleMarkers.length);
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'map_filtered', {
            'visible_markers': visibleMarkers.length
        });
    }
}
</script>

<style>
.cluster-events-map-container.fullscreen-map {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background: white;
}

.cluster-events-map-container {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .map-header .flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .map-filters .grid {
        grid-template-columns: 1fr;
    }
    
    .map-filters .md\:col-span-2 {
        grid-column: span 1;
    }
}
</style> 