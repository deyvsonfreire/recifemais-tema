<?php
/**
 * Template Part: Map Controls
 * 
 * Controles avançados para mapas com:
 * - Controles de zoom e navegação
 * - Alternância de camadas
 * - Geolocalização do usuário
 * - Modo tela cheia
 * - Controles de desenho
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Configurações dos controles
$args = wp_parse_args($args ?? [], [
    'map_id' => 'map',
    'show_zoom' => true,
    'show_layers' => true,
    'show_geolocation' => true,
    'show_fullscreen' => true,
    'show_drawing' => false,
    'show_measure' => false,
    'show_share' => true,
    'show_print' => false,
    'position' => 'top-right', // top-left, top-right, bottom-left, bottom-right
    'compact_mode' => false,
    'available_layers' => [
        'satellite' => 'Satélite',
        'terrain' => 'Terreno',
        'traffic' => 'Trânsito',
        'transit' => 'Transporte Público'
    ]
]);

$control_id = 'map-controls-' . uniqid();
$position_classes = [
    'top-left' => 'top-4 left-4',
    'top-right' => 'top-4 right-4',
    'bottom-left' => 'bottom-4 left-4',
    'bottom-right' => 'bottom-4 right-4'
];
?>

<div class="map-controls-container absolute <?php echo esc_attr($position_classes[$args['position']] ?? $position_classes['top-right']); ?> z-10" 
     id="<?php echo esc_attr($control_id); ?>"
     data-map-id="<?php echo esc_attr($args['map_id']); ?>"
     data-compact="<?php echo $args['compact_mode'] ? 'true' : 'false'; ?>">
    
    <!-- Controles Principais -->
    <div class="controls-main bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Controles de Zoom -->
        <?php if ($args['show_zoom']): ?>
            <div class="zoom-controls border-b border-gray-200">
                <button type="button" 
                        onclick="mapZoomIn('<?php echo esc_js($args['map_id']); ?>')"
                        class="w-full p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 group"
                        title="Aumentar zoom">
                    <svg class="w-5 h-5 mx-auto text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <button type="button" 
                        onclick="mapZoomOut('<?php echo esc_js($args['map_id']); ?>')"
                        class="w-full p-3 hover:bg-gray-50 transition-colors group"
                        title="Diminuir zoom">
                    <svg class="w-5 h-5 mx-auto text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Controle de Geolocalização -->
        <?php if ($args['show_geolocation']): ?>
            <div class="geolocation-control border-b border-gray-200">
                <button type="button" 
                        onclick="centerOnUserLocation('<?php echo esc_js($args['map_id']); ?>')"
                        id="geolocation-btn-<?php echo esc_attr($control_id); ?>"
                        class="w-full p-3 hover:bg-gray-50 transition-colors group"
                        title="Minha localização">
                    <svg class="w-5 h-5 mx-auto text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Controle de Tela Cheia -->
        <?php if ($args['show_fullscreen']): ?>
            <div class="fullscreen-control border-b border-gray-200">
                <button type="button" 
                        onclick="toggleMapFullscreen('<?php echo esc_js($args['map_id']); ?>')"
                        id="fullscreen-btn-<?php echo esc_attr($control_id); ?>"
                        class="w-full p-3 hover:bg-gray-50 transition-colors group"
                        title="Tela cheia">
                    <svg class="w-5 h-5 mx-auto text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Controle de Compartilhamento -->
        <?php if ($args['show_share']): ?>
            <div class="share-control">
                <button type="button" 
                        onclick="shareMapLocation('<?php echo esc_js($args['map_id']); ?>')"
                        class="w-full p-3 hover:bg-gray-50 transition-colors group"
                        title="Compartilhar localização">
                    <svg class="w-5 h-5 mx-auto text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Controles de Camadas -->
    <?php if ($args['show_layers'] && !empty($args['available_layers'])): ?>
        <div class="layers-control mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="layers-header p-3 bg-gray-50 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 000 2h14a1 1 0 100-2H3zM3 8a1 1 0 000 2h14a1 1 0 100-2H3zM3 12a1 1 0 100 2h14a1 1 0 100-2H3z"></path>
                    </svg>
                    Camadas
                </h4>
            </div>
            <div class="layers-list p-2 space-y-1">
                <?php foreach ($args['available_layers'] as $layer_key => $layer_label): ?>
                    <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded cursor-pointer transition-colors">
                        <input type="checkbox" 
                               name="map_layers[]" 
                               value="<?php echo esc_attr($layer_key); ?>"
                               onchange="toggleMapLayer('<?php echo esc_js($args['map_id']); ?>', '<?php echo esc_js($layer_key); ?>', this.checked)"
                               class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700"><?php echo esc_html($layer_label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Controles de Desenho -->
    <?php if ($args['show_drawing']): ?>
        <div class="drawing-controls mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="drawing-header p-3 bg-gray-50 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                    Ferramentas
                </h4>
            </div>
            <div class="drawing-tools p-2 space-y-1">
                <button type="button" 
                        onclick="activateDrawingTool('<?php echo esc_js($args['map_id']); ?>', 'marker')"
                        class="w-full flex items-center gap-2 p-2 hover:bg-gray-50 rounded transition-colors text-left">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Adicionar Marcador</span>
                </button>
                <button type="button" 
                        onclick="activateDrawingTool('<?php echo esc_js($args['map_id']); ?>', 'circle')"
                        class="w-full flex items-center gap-2 p-2 hover:bg-gray-50 rounded transition-colors text-left">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Desenhar Área</span>
                </button>
                <button type="button" 
                        onclick="clearDrawings('<?php echo esc_js($args['map_id']); ?>')"
                        class="w-full flex items-center gap-2 p-2 hover:bg-red-50 rounded transition-colors text-left text-red-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 012 0v6a1 1 0 11-2 0V7zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V7z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">Limpar Desenhos</span>
                </button>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Controles de Medição -->
    <?php if ($args['show_measure']): ?>
        <div class="measure-controls mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="measure-header p-3 bg-gray-50 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                    </svg>
                    Medições
                </h4>
            </div>
            <div class="measure-tools p-2 space-y-1">
                <button type="button" 
                        onclick="activateMeasureTool('<?php echo esc_js($args['map_id']); ?>', 'distance')"
                        class="w-full flex items-center gap-2 p-2 hover:bg-gray-50 rounded transition-colors text-left">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Medir Distância</span>
                </button>
                <button type="button" 
                        onclick="activateMeasureTool('<?php echo esc_js($args['map_id']); ?>', 'area')"
                        class="w-full flex items-center gap-2 p-2 hover:bg-gray-50 rounded transition-colors text-left">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Medir Área</span>
                </button>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Status e Informações -->
    <div class="map-status mt-2 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <div class="status-content p-3">
            <div class="flex items-center justify-between text-xs text-gray-600">
                <span id="map-coordinates-<?php echo esc_attr($control_id); ?>">-8.047, -34.877</span>
                <span id="map-zoom-level-<?php echo esc_attr($control_id); ?>">Zoom: 12</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initMapControls('<?php echo esc_js($control_id); ?>');
});

function initMapControls(controlId) {
    const container = document.getElementById(controlId);
    if (!container) return;
    
    const mapId = container.dataset.mapId;
    
    // Inicializar eventos dos controles
    initControlEvents(controlId, mapId);
    
    // Atualizar status inicial
    updateMapStatus(controlId, mapId);
}

function initControlEvents(controlId, mapId) {
    // Eventos de teclado para zoom
    document.addEventListener('keydown', function(e) {
        if (e.target.closest(`#${mapId}`)) {
            if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                mapZoomIn(mapId);
            } else if (e.key === '-') {
                e.preventDefault();
                mapZoomOut(mapId);
            }
        }
    });
    
    // Atualizar coordenadas quando o mapa se move
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        window.RecifeMaisMaps.maps[mapId].on('move', function() {
            updateMapStatus(controlId, mapId);
        });
    }
}

function mapZoomIn(mapId) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        const map = window.RecifeMaisMaps.maps[mapId];
        map.setZoom(map.getZoom() + 1);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'map_zoom_in', {
                'map_id': mapId
            });
        }
    }
}

function mapZoomOut(mapId) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        const map = window.RecifeMaisMaps.maps[mapId];
        map.setZoom(map.getZoom() - 1);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'map_zoom_out', {
                'map_id': mapId
            });
        }
    }
}

function centerOnUserLocation(mapId) {
    const button = document.querySelector(`#geolocation-btn-${mapId.replace('map', 'controls')}`);
    
    if (!navigator.geolocation) {
        alert('Geolocalização não é suportada pelo seu navegador.');
        return;
    }
    
    // Mostrar loading
    if (button) {
        button.innerHTML = `
            <svg class="w-5 h-5 mx-auto text-blue-600 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
        `;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
                const map = window.RecifeMaisMaps.maps[mapId];
                map.setCenter({lat, lng});
                map.setZoom(16);
                
                // Adicionar marcador da localização do usuário
                if (window.google && window.google.maps) {
                    new google.maps.Marker({
                        position: {lat, lng},
                        map: map,
                        title: 'Sua localização',
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#3b82f6">
                                    <circle cx="12" cy="12" r="8" fill="#3b82f6" stroke="white" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="3" fill="white"/>
                                </svg>
                            `),
                            scaledSize: new google.maps.Size(24, 24)
                        }
                    });
                }
            }
            
            // Restaurar ícone
            if (button) {
                button.innerHTML = `
                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                `;
            }
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'user_location_found', {
                    'map_id': mapId
                });
            }
        },
        function(error) {
            console.error('Erro ao obter localização:', error);
            alert('Não foi possível obter sua localização.');
            
            // Restaurar ícone
            if (button) {
                button.innerHTML = `
                    <svg class="w-5 h-5 mx-auto text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                `;
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}

function toggleMapFullscreen(mapId) {
    const mapContainer = document.getElementById(mapId);
    const button = document.querySelector(`#fullscreen-btn-${mapId.replace('map', 'controls')}`);
    
    if (!mapContainer) return;
    
    if (!document.fullscreenElement) {
        // Entrar em tela cheia
        mapContainer.requestFullscreen().then(() => {
            mapContainer.classList.add('fullscreen-active');
            
            if (button) {
                button.innerHTML = `
                    <svg class="w-5 h-5 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a1 1 0 00-1 1v4a1 1 0 002 0V6.414l2.293 2.293a1 1 0 001.414-1.414L6.414 5H8a1 1 0 000-2H4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                `;
            }
            
            // Redimensionar mapa
            if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
                setTimeout(() => {
                    google.maps.event.trigger(window.RecifeMaisMaps.maps[mapId], 'resize');
                }, 100);
            }
        });
    } else {
        // Sair da tela cheia
        document.exitFullscreen().then(() => {
            mapContainer.classList.remove('fullscreen-active');
            
            if (button) {
                button.innerHTML = `
                    <svg class="w-5 h-5 mx-auto text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                    </svg>
                `;
            }
            
            // Redimensionar mapa
            if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
                setTimeout(() => {
                    google.maps.event.trigger(window.RecifeMaisMaps.maps[mapId], 'resize');
                }, 100);
            }
        });
    }
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'map_fullscreen_toggle', {
            'map_id': mapId,
            'entering_fullscreen': !document.fullscreenElement
        });
    }
}

function toggleMapLayer(mapId, layerType, enabled) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        const map = window.RecifeMaisMaps.maps[mapId];
        
        switch (layerType) {
            case 'satellite':
                map.setMapTypeId(enabled ? 'satellite' : 'roadmap');
                break;
            case 'terrain':
                map.setMapTypeId(enabled ? 'terrain' : 'roadmap');
                break;
            case 'traffic':
                if (window.google && window.google.maps) {
                    const trafficLayer = new google.maps.TrafficLayer();
                    if (enabled) {
                        trafficLayer.setMap(map);
                    } else {
                        trafficLayer.setMap(null);
                    }
                }
                break;
            case 'transit':
                if (window.google && window.google.maps) {
                    const transitLayer = new google.maps.TransitLayer();
                    if (enabled) {
                        transitLayer.setMap(map);
                    } else {
                        transitLayer.setMap(null);
                    }
                }
                break;
        }
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'map_layer_toggle', {
                'map_id': mapId,
                'layer_type': layerType,
                'enabled': enabled
            });
        }
    }
}

function shareMapLocation(mapId) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        const map = window.RecifeMaisMaps.maps[mapId];
        const center = map.getCenter();
        const zoom = map.getZoom();
        
        const shareUrl = `${window.location.origin}${window.location.pathname}?lat=${center.lat()}&lng=${center.lng()}&zoom=${zoom}`;
        
        if (navigator.share) {
            navigator.share({
                title: 'Localização no RecifeMais',
                text: 'Confira esta localização no mapa do RecifeMais',
                url: shareUrl
            });
        } else {
            // Fallback para copiar para clipboard
            navigator.clipboard.writeText(shareUrl).then(() => {
                alert('Link copiado para a área de transferência!');
            }).catch(() => {
                prompt('Copie este link:', shareUrl);
            });
        }
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'map_location_shared', {
                'map_id': mapId
            });
        }
    }
}

function updateMapStatus(controlId, mapId) {
    const coordsElement = document.getElementById(`map-coordinates-${controlId}`);
    const zoomElement = document.getElementById(`map-zoom-level-${controlId}`);
    
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        const map = window.RecifeMaisMaps.maps[mapId];
        const center = map.getCenter();
        const zoom = map.getZoom();
        
        if (coordsElement) {
            coordsElement.textContent = `${center.lat().toFixed(3)}, ${center.lng().toFixed(3)}`;
        }
        
        if (zoomElement) {
            zoomElement.textContent = `Zoom: ${Math.round(zoom)}`;
        }
    }
}

// Ferramentas de desenho (se habilitadas)
function activateDrawingTool(mapId, toolType) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        // Implementar ferramentas de desenho
        console.log(`Ativando ferramenta de desenho: ${toolType} no mapa ${mapId}`);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'drawing_tool_activated', {
                'map_id': mapId,
                'tool_type': toolType
            });
        }
    }
}

function clearDrawings(mapId) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        // Implementar limpeza de desenhos
        console.log(`Limpando desenhos do mapa ${mapId}`);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'drawings_cleared', {
                'map_id': mapId
            });
        }
    }
}

// Ferramentas de medição (se habilitadas)
function activateMeasureTool(mapId, measureType) {
    if (window.RecifeMaisMaps && window.RecifeMaisMaps.maps[mapId]) {
        // Implementar ferramentas de medição
        console.log(`Ativando ferramenta de medição: ${measureType} no mapa ${mapId}`);
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'measure_tool_activated', {
                'map_id': mapId,
                'measure_type': measureType
            });
        }
    }
}
</script>

<style>
.map-controls-container {
    max-width: 200px;
    user-select: none;
}

.controls-main button {
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fullscreen-active {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
}

.fullscreen-active .map-controls-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 10000;
}

@media (max-width: 768px) {
    .map-controls-container {
        max-width: 160px;
    }
    
    .controls-main button {
        min-height: 40px;
    }
    
    .layers-control,
    .drawing-controls,
    .measure-controls {
        display: none;
    }
    
    .map-controls-container[data-compact="true"] .layers-control,
    .map-controls-container[data-compact="true"] .drawing-controls,
    .map-controls-container[data-compact="true"] .measure-controls {
        display: block;
    }
}

/* Animações */
.controls-main button:active {
    transform: scale(0.95);
}

.map-status {
    font-family: 'Courier New', monospace;
}

/* Estados dos botões */
.controls-main button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.controls-main button.active {
    background-color: #3b82f6;
    color: white;
}

.controls-main button.active svg {
    color: white;
}
</style> 