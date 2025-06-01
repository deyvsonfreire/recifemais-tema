<?php
/**
 * Template Part: Route Display Map
 * 
 * Mapa para exibir roteiros culturais com:
 * - Pontos de interesse sequenciais
 * - Direções entre pontos
 * - Estimativa de tempo e distância
 * - Informações detalhadas de cada parada
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do roteiro
$post_id = $args['post_id'] ?? get_the_ID();
$post_type = get_post_type($post_id);

// Verificar se é um roteiro
if ($post_type !== 'roteiros') {
    return;
}

$post_title = get_the_title($post_id);

// Meta fields do roteiro
$duracao = get_post_meta($post_id, 'roteiro_duracao', true);
$dificuldade = get_post_meta($post_id, 'roteiro_dificuldade', true);
$pontos_interesse = get_post_meta($post_id, 'roteiro_pontos_interesse', true);
$transporte = get_post_meta($post_id, 'roteiro_transporte', true);
$custo_estimado = get_post_meta($post_id, 'roteiro_custo_estimado', true);
$melhor_epoca = get_post_meta($post_id, 'roteiro_melhor_epoca', true);

// Configurações do mapa
$args = wp_parse_args($args ?? [], [
    'height' => '600px',
    'zoom' => 14,
    'show_directions' => true,
    'show_route_info' => true,
    'show_waypoints' => true,
    'enable_street_view' => true,
    'travel_mode' => 'WALKING', // WALKING, DRIVING, TRANSIT
    'optimize_waypoints' => false,
    'show_elevation' => false
]);

// Preparar pontos do roteiro
$route_points = [];
$total_distance = 0;
$total_duration = 0;

if (!empty($pontos_interesse) && is_array($pontos_interesse)) {
    foreach ($pontos_interesse as $index => $ponto) {
        // Se o ponto tem um lugar_id, buscar coordenadas
        if (!empty($ponto['lugar_id'])) {
            $lugar_id = $ponto['lugar_id'];
            $latitude = get_post_meta($lugar_id, 'lugar_latitude', true);
            $longitude = get_post_meta($lugar_id, 'lugar_longitude', true);
            $endereco = get_post_meta($lugar_id, 'lugar_endereco', true);
            $lugar_nome = get_the_title($lugar_id);
        } else {
            // Usar coordenadas diretas se fornecidas
            $latitude = $ponto['latitude'] ?? '';
            $longitude = $ponto['longitude'] ?? '';
            $endereco = $ponto['endereco'] ?? '';
            $lugar_nome = $ponto['nome'] ?? "Ponto " . ($index + 1);
        }
        
        if (!$latitude || !$longitude) continue;
        
        $route_points[] = [
            'order' => $index + 1,
            'lat' => floatval($latitude),
            'lng' => floatval($longitude),
            'title' => $lugar_nome,
            'description' => $ponto['descricao'] ?? '',
            'duration_minutes' => intval($ponto['tempo_visita'] ?? 30),
            'address' => $endereco,
            'lugar_id' => $ponto['lugar_id'] ?? null,
            'tipo' => $ponto['tipo'] ?? 'ponto_interesse',
            'icon' => $ponto['icon'] ?? '📍',
            'thumbnail' => $ponto['lugar_id'] ? get_the_post_thumbnail_url($ponto['lugar_id'], 'medium') : ''
        ];
    }
}

// Se não há pontos suficientes, não exibir o mapa
if (count($route_points) < 2) {
    return;
}

$map_id = 'route-display-map-' . $post_id;

// Calcular centro do mapa baseado nos pontos
$center_lat = array_sum(array_column($route_points, 'lat')) / count($route_points);
$center_lng = array_sum(array_column($route_points, 'lng')) / count($route_points);

// Dados para JavaScript
$map_config = [
    'center' => ['lat' => $center_lat, 'lng' => $center_lng],
    'zoom' => $args['zoom'],
    'route_points' => $route_points,
    'travel_mode' => $args['travel_mode'],
    'optimize_waypoints' => $args['optimize_waypoints'],
    'show_directions' => $args['show_directions'],
    'show_elevation' => $args['show_elevation']
];

// Ícones por tipo de transporte
$transport_icons = [
    'WALKING' => '🚶',
    'DRIVING' => '🚗',
    'TRANSIT' => '🚌',
    'BICYCLING' => '🚴'
];

// Cores por dificuldade
$difficulty_colors = [
    'facil' => 'bg-green-100 text-green-800',
    'moderado' => 'bg-yellow-100 text-yellow-800',
    'dificil' => 'bg-red-100 text-red-800'
];
?>

<div class="route-display-map-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- Header do Roteiro -->
    <div class="map-header bg-gradient-to-r from-green-50 to-blue-50 p-4 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <!-- Informações do Roteiro -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo esc_html($post_title); ?></h3>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    
                    <?php if ($duracao): ?>
                        <span class="flex items-center gap-2 bg-white px-3 py-1 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <strong><?php echo esc_html($duracao); ?></strong>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($dificuldade): ?>
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full <?php echo esc_attr($difficulty_colors[strtolower($dificuldade)] ?? 'bg-gray-100 text-gray-800'); ?>">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            <strong><?php echo esc_html($dificuldade); ?></strong>
                        </span>
                    <?php endif; ?>
                    
                    <span class="flex items-center gap-2 bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                        📍 <strong><?php echo count($route_points); ?></strong> paradas
                    </span>
                    
                    <?php if ($transporte): ?>
                        <span class="flex items-center gap-2 bg-orange-100 text-orange-800 px-3 py-1 rounded-full">
                            <?php echo esc_html($transport_icons[$args['travel_mode']] ?? '🚶'); ?>
                            <strong><?php echo esc_html($transporte); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Controles do Mapa -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="toggleRouteInfo('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Detalhes
                </button>
                
                <button type="button" 
                        onclick="startNavigation('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Navegar
                </button>
                
                <button type="button" 
                        onclick="toggleRouteFullscreen('<?php echo esc_js($map_id); ?>')"
                        class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Container Principal -->
    <div class="flex flex-col lg:flex-row">
        
        <!-- Mapa -->
        <div class="lg:w-2/3">
            <div class="map-wrapper relative">
                <div id="<?php echo esc_attr($map_id); ?>" 
                     class="route-display-map w-full"
                     style="height: <?php echo esc_attr($args['height']); ?>;"
                     data-map-config="<?php echo esc_attr(json_encode($map_config)); ?>">
                </div>
                
                <!-- Loading Overlay -->
                <div id="<?php echo esc_attr($map_id); ?>-loading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                        <p class="text-lg font-medium text-gray-900 mb-2">Calculando roteiro...</p>
                        <p class="text-sm text-gray-600">Preparando <?php echo count($route_points); ?> pontos de interesse</p>
                    </div>
                </div>
                
                <!-- Informações da Rota (Overlay) -->
                <div id="route-info-overlay" class="hidden absolute top-4 left-4 right-4 bg-white rounded-lg shadow-lg p-4 z-10">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-900">Informações da Rota</h4>
                        <button type="button" 
                                onclick="toggleRouteInfo('<?php echo esc_js($map_id); ?>')"
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div id="route-stats" class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <div class="flex items-center gap-2 text-blue-600 mb-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Distância Total</span>
                            </div>
                            <p id="total-distance" class="text-lg font-bold text-gray-900">Calculando...</p>
                        </div>
                        
                        <div class="bg-green-50 p-3 rounded-lg">
                            <div class="flex items-center gap-2 text-green-600 mb-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Tempo Estimado</span>
                            </div>
                            <p id="total-duration" class="text-lg font-bold text-gray-900">Calculando...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar com Pontos do Roteiro -->
        <div class="lg:w-1/3 bg-gray-50 border-l border-gray-200">
            <div class="p-4">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Pontos do Roteiro
                </h4>
                
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <?php foreach ($route_points as $index => $point): ?>
                        <div class="route-point bg-white rounded-lg p-3 border border-gray-200 hover:border-green-300 transition-colors cursor-pointer"
                             onclick="focusMapPoint(<?php echo $index; ?>)"
                             data-point-index="<?php echo $index; ?>">
                            
                            <div class="flex items-start gap-3">
                                <!-- Número da Parada -->
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                    <?php echo $point['order']; ?>
                                </div>
                                
                                <!-- Informações do Ponto -->
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-semibold text-gray-900 truncate"><?php echo esc_html($point['title']); ?></h5>
                                    
                                    <?php if (!empty($point['description'])): ?>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                            <?php echo esc_html($point['description']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <?php if ($point['duration_minutes']): ?>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <?php echo $point['duration_minutes']; ?>min
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($point['address'])): ?>
                                            <span class="flex items-center gap-1 truncate">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <?php echo esc_html($point['address']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Thumbnail -->
                                <?php if (!empty($point['thumbnail'])): ?>
                                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="<?php echo esc_url($point['thumbnail']); ?>" 
                                             alt="<?php echo esc_attr($point['title']); ?>"
                                             class="w-full h-full object-cover">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Informações Adicionais -->
                <?php if ($custo_estimado || $melhor_epoca): ?>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h5 class="font-medium text-gray-900 mb-3">Informações Adicionais</h5>
                        
                        <?php if ($custo_estimado): ?>
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                <span><strong>Custo:</strong> <?php echo esc_html($custo_estimado); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($melhor_epoca): ?>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span><strong>Melhor época:</strong> <?php echo esc_html($melhor_epoca); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initRouteDisplayMap('<?php echo esc_js($map_id); ?>');
});

function initRouteDisplayMap(mapId) {
    const mapContainer = document.getElementById(mapId);
    const loadingElement = document.getElementById(mapId + '-loading');
    
    if (!mapContainer) return;
    
    try {
        const mapConfig = JSON.parse(mapContainer.dataset.mapConfig);
        
        // Verificar se RecifeMaisMaps está disponível
        if (typeof RecifeMaisMaps !== 'undefined') {
            const maps = new RecifeMaisMaps();
            
            // Configuração específica para roteiros
            const config = {
                ...mapConfig,
                route: true,
                directionsService: true,
                directionsRenderer: true
            };
            
            maps.initMap(mapId, config).then(() => {
                loadingElement.style.display = 'none';
                
                // Calcular e exibir rota
                calculateRoute(mapId, mapConfig);
                
                // Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'map_loaded', {
                        'map_type': 'route_display',
                        'route_points': mapConfig.route_points.length
                    });
                }
            }).catch(error => {
                console.error('Erro ao carregar mapa:', error);
                showRouteError(mapId);
            });
            
        } else {
            showRouteError(mapId);
        }
        
    } catch (error) {
        console.error('Erro na configuração do mapa:', error);
        showRouteError(mapId);
    }
}

function calculateRoute(mapId, config) {
    if (!window.google || !window.google.maps) return;
    
    const directionsService = new google.maps.DirectionsService();
    const points = config.route_points;
    
    if (points.length < 2) return;
    
    // Preparar waypoints
    const waypoints = points.slice(1, -1).map(point => ({
        location: new google.maps.LatLng(point.lat, point.lng),
        stopover: true
    }));
    
    const request = {
        origin: new google.maps.LatLng(points[0].lat, points[0].lng),
        destination: new google.maps.LatLng(points[points.length - 1].lat, points[points.length - 1].lng),
        waypoints: waypoints,
        optimizeWaypoints: config.optimize_waypoints,
        travelMode: google.maps.TravelMode[config.travel_mode],
        unitSystem: google.maps.UnitSystem.METRIC
    };
    
    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            updateRouteStats(result);
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'route_calculated', {
                    'route_id': '<?php echo $post_id; ?>',
                    'travel_mode': config.travel_mode,
                    'waypoints_count': waypoints.length
                });
            }
        } else {
            console.error('Erro ao calcular rota:', status);
        }
    });
}

function updateRouteStats(directionsResult) {
    const route = directionsResult.routes[0];
    let totalDistance = 0;
    let totalDuration = 0;
    
    route.legs.forEach(leg => {
        totalDistance += leg.distance.value;
        totalDuration += leg.duration.value;
    });
    
    // Atualizar elementos na interface
    const distanceElement = document.getElementById('total-distance');
    const durationElement = document.getElementById('total-duration');
    
    if (distanceElement) {
        const distanceKm = (totalDistance / 1000).toFixed(1);
        distanceElement.textContent = distanceKm + ' km';
    }
    
    if (durationElement) {
        const hours = Math.floor(totalDuration / 3600);
        const minutes = Math.floor((totalDuration % 3600) / 60);
        let durationText = '';
        
        if (hours > 0) {
            durationText += hours + 'h ';
        }
        durationText += minutes + 'min';
        
        durationElement.textContent = durationText;
    }
}

function showRouteError(mapId) {
    const loadingElement = document.getElementById(mapId + '-loading');
    if (loadingElement) {
        loadingElement.innerHTML = `
            <div class="text-center p-8">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar roteiro</h3>
                <p class="text-sm text-gray-600">Não foi possível calcular a rota. Verifique sua conexão.</p>
            </div>
        `;
    }
}

function toggleRouteInfo(mapId) {
    const overlay = document.getElementById('route-info-overlay');
    if (overlay) {
        overlay.classList.toggle('hidden');
    }
}

function startNavigation(mapId) {
    const mapContainer = document.getElementById(mapId);
    const config = JSON.parse(mapContainer.dataset.mapConfig);
    const firstPoint = config.route_points[0];
    
    // Abrir Google Maps para navegação
    const url = `https://www.google.com/maps/dir/?api=1&destination=${firstPoint.lat},${firstPoint.lng}&travelmode=${config.travel_mode.toLowerCase()}`;
    window.open(url, '_blank');
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'navigation_started', {
            'route_id': '<?php echo $post_id; ?>',
            'travel_mode': config.travel_mode
        });
    }
}

function toggleRouteFullscreen(mapId) {
    const mapContainer = document.getElementById(mapId);
    const wrapper = mapContainer.closest('.route-display-map-container');
    
    if (!document.fullscreenElement) {
        wrapper.requestFullscreen().then(() => {
            wrapper.classList.add('fullscreen-map');
            mapContainer.style.height = '100vh';
            
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

function focusMapPoint(pointIndex) {
    const mapContainer = document.getElementById('<?php echo esc_js($map_id); ?>');
    const config = JSON.parse(mapContainer.dataset.mapConfig);
    const point = config.route_points[pointIndex];
    
    if (mapContainer.mapInstance && point) {
        mapContainer.mapInstance.setCenter({lat: point.lat, lng: point.lng});
        mapContainer.mapInstance.setZoom(17);
        
        // Destacar ponto na sidebar
        document.querySelectorAll('.route-point').forEach(el => {
            el.classList.remove('border-green-500', 'bg-green-50');
        });
        
        const pointElement = document.querySelector(`[data-point-index="${pointIndex}"]`);
        if (pointElement) {
            pointElement.classList.add('border-green-500', 'bg-green-50');
        }
    }
}
</script>

<style>
.route-display-map-container.fullscreen-map {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background: white;
}

.route-display-map-container.fullscreen-map .flex {
    height: 100vh;
}

.route-point {
    transition: all 0.2s ease;
}

.route-point:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 1024px) {
    .route-display-map-container .flex {
        flex-direction: column;
    }
    
    .route-display-map-container .lg\:w-2\/3,
    .route-display-map-container .lg\:w-1\/3 {
        width: 100%;
    }
    
    .route-display-map-container .lg\:w-1\/3 {
        max-height: 300px;
    }
}
</style> 