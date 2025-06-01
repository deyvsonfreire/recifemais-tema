<?php
/**
 * Template Part: Single Location Map
 * 
 * Mapa para exibir uma localização única com:
 * - Marcador customizado por tipo de local
 * - Informações do local em popup
 * - Botões de ação (direções, street view)
 * - Integração com plugin RecifeMais Core V2
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do local
$post_id = $args['post_id'] ?? get_the_ID();
$post_type = get_post_type($post_id);
$post_title = get_the_title($post_id);

// Meta fields de localização
$latitude = get_post_meta($post_id, 'lugar_latitude', true) ?: get_post_meta($post_id, 'evento_latitude', true);
$longitude = get_post_meta($post_id, 'lugar_longitude', true) ?: get_post_meta($post_id, 'evento_longitude', true);
$endereco = get_post_meta($post_id, 'lugar_endereco', true) ?: get_post_meta($post_id, 'evento_endereco', true);

// Se for evento, pegar dados do local relacionado
if ($post_type === 'eventos_festivais') {
    $local_id = get_post_meta($post_id, 'evento_local', true);
    if ($local_id) {
        $latitude = get_post_meta($local_id, 'lugar_latitude', true);
        $longitude = get_post_meta($local_id, 'lugar_longitude', true);
        $endereco = get_post_meta($local_id, 'lugar_endereco', true);
        $local_title = get_the_title($local_id);
    }
}

// Configurações do mapa
$args = wp_parse_args($args ?? [], [
    'height' => '400px',
    'zoom' => 16,
    'show_directions' => true,
    'show_street_view' => true,
    'show_info_popup' => true,
    'enable_fullscreen' => true,
    'marker_animation' => true,
    'custom_marker' => true
]);

// Verificar se tem coordenadas
if (!$latitude || !$longitude) {
    return;
}

// Ícones por tipo de post
$marker_icons = [
    'lugares' => '📍',
    'eventos_festivais' => '🎭',
    'artistas' => '🎨',
    'roteiros' => '🗺️',
    'organizadores' => '🏢',
    'agremiacoes' => '🎪',
    'historias' => '📖',
    'guias_tematicos' => '📚'
];

$marker_icon = $marker_icons[$post_type] ?? '📍';
$map_id = 'single-location-map-' . $post_id;

// Dados para JavaScript
$map_data = [
    'lat' => floatval($latitude),
    'lng' => floatval($longitude),
    'zoom' => intval($args['zoom']),
    'title' => $post_title,
    'address' => $endereco,
    'icon' => $marker_icon,
    'post_type' => $post_type,
    'post_id' => $post_id
];

// Informações adicionais para popup
$popup_info = [];
if ($post_type === 'lugares') {
    $telefone = get_post_meta($post_id, 'lugar_telefone', true);
    $horario = get_post_meta($post_id, 'lugar_horario_funcionamento', true);
    $faixa_preco = get_post_meta($post_id, 'lugar_faixa_preco', true);
    
    if ($telefone) $popup_info['telefone'] = $telefone;
    if ($horario) $popup_info['horario'] = $horario;
    if ($faixa_preco) $popup_info['preco'] = $faixa_preco;
} elseif ($post_type === 'eventos_festivais') {
    $data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
    $horario_inicio = get_post_meta($post_id, 'evento_horario_inicio', true);
    $preco = get_post_meta($post_id, 'evento_preco', true);
    
    if ($data_inicio) $popup_info['data'] = date('d/m/Y', strtotime($data_inicio));
    if ($horario_inicio) $popup_info['horario'] = $horario_inicio;
    if ($preco) $popup_info['preco'] = $preco;
}

$unique_id = uniqid('map_');
?>

<div class="single-location-map-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- Header do Mapa -->
    <div class="map-header bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-lg">
                    <?php echo esc_html($marker_icon); ?>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900"><?php echo esc_html($post_title); ?></h3>
                    <?php if ($endereco): ?>
                        <p class="text-sm text-gray-600"><?php echo esc_html($endereco); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Controles do Mapa -->
            <div class="flex items-center gap-2">
                <?php if ($args['show_directions']): ?>
                    <button type="button" 
                            onclick="openDirections('<?php echo esc_js($latitude); ?>', '<?php echo esc_js($longitude); ?>')"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            title="Como chegar">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="hidden sm:inline">Direções</span>
                    </button>
                <?php endif; ?>
                
                <?php if ($args['enable_fullscreen']): ?>
                    <button type="button" 
                            onclick="toggleFullscreen('<?php echo esc_js($map_id); ?>')"
                            class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                            title="Tela cheia">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Container do Mapa -->
    <div class="map-wrapper relative">
        <div id="<?php echo esc_attr($map_id); ?>" 
             class="single-location-map w-full"
             style="height: <?php echo esc_attr($args['height']); ?>;"
             data-map-config="<?php echo esc_attr(json_encode($map_data)); ?>"
             data-popup-info="<?php echo esc_attr(json_encode($popup_info)); ?>">
        </div>
        
        <!-- Loading Overlay -->
        <div id="<?php echo esc_attr($map_id); ?>-loading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                <p class="text-sm text-gray-600">Carregando mapa...</p>
            </div>
        </div>
        
        <!-- Error State -->
        <div id="<?php echo esc_attr($map_id); ?>-error" class="hidden absolute inset-0 bg-gray-50 flex items-center justify-center">
            <div class="text-center p-6">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Erro ao carregar mapa</h3>
                <p class="text-sm text-gray-600 mb-4">Não foi possível carregar o mapa. Tente novamente.</p>
                <button type="button" 
                        onclick="retryMapLoad('<?php echo esc_js($map_id); ?>')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Tentar Novamente
                </button>
            </div>
        </div>
    </div>
    
    <!-- Informações Adicionais -->
    <?php if (!empty($popup_info)): ?>
        <div class="map-footer bg-gray-50 p-4 border-t border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                
                <?php if (isset($popup_info['telefone'])): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        <a href="tel:<?php echo esc_attr($popup_info['telefone']); ?>" 
                           class="hover:text-recife-primary transition-colors">
                            <?php echo esc_html($popup_info['telefone']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($popup_info['horario'])): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($popup_info['horario']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($popup_info['preco'])): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($popup_info['preco']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($popup_info['data'])): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($popup_info['data']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initSingleLocationMap('<?php echo esc_js($map_id); ?>');
});

function initSingleLocationMap(mapId) {
    const mapContainer = document.getElementById(mapId);
    const loadingElement = document.getElementById(mapId + '-loading');
    const errorElement = document.getElementById(mapId + '-error');
    
    if (!mapContainer) return;
    
    try {
        const mapConfig = JSON.parse(mapContainer.dataset.mapConfig);
        const popupInfo = JSON.parse(mapContainer.dataset.popupInfo);
        
        // Verificar se RecifeMaisMaps está disponível
        if (typeof RecifeMaisMaps !== 'undefined') {
            const maps = new RecifeMaisMaps();
            
            // Configuração específica para localização única
            const config = {
                center: { lat: mapConfig.lat, lng: mapConfig.lng },
                zoom: mapConfig.zoom,
                markers: [{
                    lat: mapConfig.lat,
                    lng: mapConfig.lng,
                    title: mapConfig.title,
                    icon: mapConfig.icon,
                    info: popupInfo,
                    post_id: mapConfig.post_id,
                    post_type: mapConfig.post_type
                }],
                showUserLocation: false,
                clustering: false,
                fitBounds: false
            };
            
            maps.initMap(mapId, config).then(() => {
                loadingElement.style.display = 'none';
                
                // Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'map_loaded', {
                        'map_type': 'single_location',
                        'post_id': mapConfig.post_id,
                        'post_type': mapConfig.post_type
                    });
                }
            }).catch(error => {
                console.error('Erro ao carregar mapa:', error);
                showMapError(mapId);
            });
            
        } else {
            // Fallback para mapa estático
            showStaticMap(mapId, mapConfig);
        }
        
    } catch (error) {
        console.error('Erro na configuração do mapa:', error);
        showMapError(mapId);
    }
}

function showMapError(mapId) {
    const loadingElement = document.getElementById(mapId + '-loading');
    const errorElement = document.getElementById(mapId + '-error');
    
    if (loadingElement) loadingElement.style.display = 'none';
    if (errorElement) errorElement.classList.remove('hidden');
}

function showStaticMap(mapId, config) {
    const mapContainer = document.getElementById(mapId);
    const loadingElement = document.getElementById(mapId + '-loading');
    
    // Criar mapa estático usando Google Static Maps API
    const staticMapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${config.lat},${config.lng}&zoom=${config.zoom}&size=600x400&markers=color:red%7C${config.lat},${config.lng}&key=YOUR_API_KEY`;
    
    const img = document.createElement('img');
    img.src = staticMapUrl;
    img.alt = 'Mapa de ' + config.title;
    img.className = 'w-full h-full object-cover';
    
    img.onload = function() {
        mapContainer.innerHTML = '';
        mapContainer.appendChild(img);
        if (loadingElement) loadingElement.style.display = 'none';
    };
    
    img.onerror = function() {
        showMapError(mapId);
    };
}

function openDirections(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'directions_clicked', {
            'destination_lat': lat,
            'destination_lng': lng
        });
    }
}

function toggleFullscreen(mapId) {
    const mapContainer = document.getElementById(mapId);
    const wrapper = mapContainer.closest('.single-location-map-container');
    
    if (!document.fullscreenElement) {
        wrapper.requestFullscreen().then(() => {
            wrapper.classList.add('fullscreen-map');
            mapContainer.style.height = '100vh';
            
            // Redimensionar mapa
            if (window.google && window.google.maps) {
                google.maps.event.trigger(mapContainer.mapInstance, 'resize');
            }
        });
    } else {
        document.exitFullscreen().then(() => {
            wrapper.classList.remove('fullscreen-map');
            mapContainer.style.height = '<?php echo esc_js($args['height']); ?>';
            
            // Redimensionar mapa
            if (window.google && window.google.maps) {
                google.maps.event.trigger(mapContainer.mapInstance, 'resize');
            }
        });
    }
}

function retryMapLoad(mapId) {
    const errorElement = document.getElementById(mapId + '-error');
    const loadingElement = document.getElementById(mapId + '-loading');
    
    if (errorElement) errorElement.classList.add('hidden');
    if (loadingElement) loadingElement.style.display = 'flex';
    
    // Tentar carregar novamente após 1 segundo
    setTimeout(() => {
        initSingleLocationMap(mapId);
    }, 1000);
}
</script>

<style>
.fullscreen-map {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background: white;
}

.fullscreen-map .map-header {
    position: relative;
    z-index: 10000;
}

.single-location-map-container {
    transition: all 0.3s ease;
}

.single-location-map-container:hover {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

@media (max-width: 640px) {
    .map-header .flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .map-footer .grid {
        grid-template-columns: 1fr;
    }
}
</style> 