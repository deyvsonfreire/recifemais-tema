/**
 * RecifeMais Maps - Sistema de Integração com Mapas V2
 * 
 * Funcionalidades:
 * - Geolocalização automática para lugares
 * - Mapa interativo nos singles  
 * - Direções e rotas para eventos
 * - Clusters de lugares por bairro
 * - Integração com Google Maps API
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

class RecifeMaisMaps {
    constructor() {
        this.apiKey = window.recifemais_maps_config?.api_key || '';
        this.defaultCenter = { lat: -8.0476, lng: -34.8770 }; // Recife
        this.defaultZoom = 13;
        this.userLocation = null;
        this.currentMap = null;
        this.markers = [];
        this.directionsService = null;
        this.directionsRenderer = null;
        this.infoWindow = null;
        
        this.init();
    }
    
    /**
     * Inicializa o sistema de mapas
     */
    init() {
        if (!this.apiKey) {
            console.warn('RecifeMais Maps: API Key não configurada');
            return;
        }
        
        // Aguarda carregamento do DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupMaps());
        } else {
            this.setupMaps();
        }
        
        // Solicita geolocalização
        this.requestUserLocation();
    }
    
    /**
     * Configura todos os mapas da página
     */
    setupMaps() {
        // Mapa de lugar único
        const singleMap = document.getElementById('recifemais-single-map');
        if (singleMap) {
            this.initSingleLocationMap(singleMap);
        }
        
        // Mapa de roteiro
        const routeMap = document.getElementById('recifemais-route-map');
        if (routeMap) {
            this.initRouteMap(routeMap);
        }
        
        // Mapa de busca/arquivo
        const searchMap = document.getElementById('recifemais-search-map');
        if (searchMap) {
            this.initSearchMap(searchMap);
        }
    }
    
    /**
     * Solicita localização do usuário
     */
    requestUserLocation() {
        if (!navigator.geolocation) return;
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                this.userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                // Mostra indicador de geolocalização ativa
                const geoIndicators = document.querySelectorAll('[data-user-location]');
                geoIndicators.forEach(indicator => {
                    indicator.style.display = 'flex';
                });
                
                // Atualiza mapas existentes
                this.updateMapsWithUserLocation();
            },
            (error) => {
                console.log('Geolocalização não disponível:', error.message);
            }
        );
    }
    
    /**
     * Inicializa mapa para lugar único
     */
    initSingleLocationMap(container) {
        const lat = parseFloat(container.dataset.lat);
        const lng = parseFloat(container.dataset.lng);
        const title = container.dataset.title || 'Localização';
        const address = container.dataset.address || '';
        
        if (!lat || !lng) {
            container.innerHTML = '<div class="map-error">Coordenadas não disponíveis</div>';
            return;
        }
        
        const position = { lat, lng };
        
        const map = new google.maps.Map(container, {
            zoom: 15,
            center: position,
            styles: this.getMapStyles(),
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });
        
        // Marker principal
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            icon: this.getCustomMarkerIcon('place')
        });
        
        // InfoWindow
        const infoContent = this.createInfoWindowContent(title, address, 'lugar');
        const infoWindow = new google.maps.InfoWindow({
            content: infoContent
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        // Botões de ação
        this.addMapControls(map, position, title);
        
        this.currentMap = map;
        this.markers.push(marker);
    }
    
    /**
     * Inicializa mapa de roteiro com rota
     */
    initRouteMap(container) {
        const roteiroData = JSON.parse(container.dataset.roteiro || '{}');
        
        if (!roteiroData.waypoints || roteiroData.waypoints.length === 0) {
            container.innerHTML = '<div class="map-error">Dados de rota não disponíveis</div>';
            return;
        }
        
        const map = new google.maps.Map(container, {
            zoom: 13,
            center: this.defaultCenter,
            styles: this.getMapStyles(),
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });
        
        this.directionsService = new google.maps.DirectionsService();
        this.directionsRenderer = new google.maps.DirectionsRenderer({
            draggable: false,
            panel: null,
            polylineOptions: {
                strokeColor: '#059669',
                strokeWeight: 4,
                strokeOpacity: 0.8
            },
            markerOptions: {
                icon: this.getCustomMarkerIcon('route')
            }
        });
        
        this.directionsRenderer.setMap(map);
        
        this.calculateAndDisplayRoute(roteiroData);
        
        this.currentMap = map;
    }
    
    /**
     * Inicializa mapa de busca com múltiplos markers
     */
    initSearchMap(container) {
        const locations = JSON.parse(container.dataset.locations || '[]');
        
        if (locations.length === 0) {
            container.innerHTML = '<div class="map-error">Nenhuma localização encontrada</div>';
            return;
        }
        
        const map = new google.maps.Map(container, {
            zoom: 12,
            center: this.defaultCenter,
            styles: this.getMapStyles(),
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true
        });
        
        // Bounds para ajustar zoom
        const bounds = new google.maps.LatLngBounds();
        
        // Criar markers para cada localização
        locations.forEach((location, index) => {
            const position = { lat: location.lat, lng: location.lng };
            
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: location.title,
                icon: this.getCustomMarkerIcon(location.type || 'place')
            });
            
            const infoContent = this.createInfoWindowContent(
                location.title,
                location.address,
                location.type,
                location.url
            );
            
            const infoWindow = new google.maps.InfoWindow({
                content: infoContent
            });
            
            marker.addListener('click', () => {
                // Fecha outras infoWindows
                if (this.infoWindow) {
                    this.infoWindow.close();
                }
                
                infoWindow.open(map, marker);
                this.infoWindow = infoWindow;
            });
            
            bounds.extend(position);
            this.markers.push(marker);
        });
        
        // Ajusta zoom para mostrar todos os markers
        if (locations.length > 1) {
            map.fitBounds(bounds);
        }
        
        this.currentMap = map;
    }
    
    /**
     * Calcula e exibe rota
     */
    calculateAndDisplayRoute(roteiroData) {
        const waypoints = roteiroData.waypoints.map(point => ({
            location: { lat: point.lat, lng: point.lng },
            stopover: true
        }));
        
        const start = waypoints.shift().location;
        const end = waypoints.pop()?.location || start;
        
        const request = {
            origin: start,
            destination: end,
            waypoints: waypoints,
            travelMode: this.getTravelMode(roteiroData.transport),
            optimizeWaypoints: true
        };
        
        this.directionsService.route(request, (result, status) => {
            if (status === 'OK') {
                this.directionsRenderer.setDirections(result);
                
                // Adiciona informações da rota
                this.addRouteInfo(result);
            } else {
                console.error('Erro ao calcular rota:', status);
                this.addRouteMarkers(roteiroData.waypoints);
            }
        });
    }
    
    /**
     * Adiciona markers individuais quando rota falha
     */
    addRouteMarkers(waypoints) {
        waypoints.forEach((point, index) => {
            const marker = new google.maps.Marker({
                position: { lat: point.lat, lng: point.lng },
                map: this.currentMap,
                title: point.title || `Parada ${index + 1}`,
                icon: this.getCustomMarkerIcon('route'),
                label: {
                    text: (index + 1).toString(),
                    color: 'white',
                    fontWeight: 'bold'
                }
            });
            
            const infoContent = this.createInfoWindowContent(
                point.title || `Parada ${index + 1}`,
                point.address || '',
                'roteiro'
            );
            
            const infoWindow = new google.maps.InfoWindow({
                content: infoContent
            });
            
            marker.addListener('click', () => {
                if (this.infoWindow) {
                    this.infoWindow.close();
                }
                infoWindow.open(this.currentMap, marker);
                this.infoWindow = infoWindow;
            });
            
            this.markers.push(marker);
        });
    }
    
    /**
     * Atualiza mapas com localização do usuário
     */
    updateMapsWithUserLocation() {
        if (!this.userLocation || !this.currentMap) return;
        
        // Adiciona marker da localização do usuário
        const userMarker = new google.maps.Marker({
            position: this.userLocation,
            map: this.currentMap,
            title: 'Sua localização',
            icon: this.getCustomMarkerIcon('user')
        });
        
        // Adiciona botão para centralizar na localização do usuário
        const centerButton = document.createElement('button');
        centerButton.textContent = '📍';
        centerButton.title = 'Centralizar na minha localização';
        centerButton.className = 'custom-map-control';
        centerButton.style.cssText = `
            background: white;
            border: 2px solid #ccc;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(0,0,0,.3);
            cursor: pointer;
            font-size: 16px;
            height: 40px;
            margin: 8px;
            padding: 0;
            text-align: center;
            width: 40px;
        `;
        
        centerButton.addEventListener('click', () => {
            this.currentMap.setCenter(this.userLocation);
            this.currentMap.setZoom(15);
        });
        
        this.currentMap.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(centerButton);
        
        this.markers.push(userMarker);
    }
    
    /**
     * Adiciona controles personalizados ao mapa
     */
    addMapControls(map, position, title) {
        // Botão para abrir no Google Maps
        const openInMapsButton = document.createElement('button');
        openInMapsButton.textContent = '🗺️';
        openInMapsButton.title = 'Abrir no Google Maps';
        openInMapsButton.className = 'custom-map-control';
        openInMapsButton.style.cssText = `
            background: white;
            border: 2px solid #ccc;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(0,0,0,.3);
            cursor: pointer;
            font-size: 16px;
            height: 40px;
            margin: 8px;
            padding: 0;
            text-align: center;
            width: 40px;
        `;
        
        openInMapsButton.addEventListener('click', () => {
            this.openInGoogleMaps(position.lat, position.lng, title);
        });
        
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(openInMapsButton);
    }
    
    /**
     * Abre localização no Google Maps
     */
    openInGoogleMaps(lat, lng, title = '') {
        const url = `https://www.google.com/maps?q=${lat},${lng}&ll=${lat},${lng}&z=15`;
        window.open(url, '_blank');
    }
    
    /**
     * Cria conteúdo para InfoWindow
     */
    createInfoWindowContent(title, address, type, url = null) {
        const typeEmoji = {
            'lugar': '📍',
            'artista': '🎨',
            'evento': '🎉',
            'roteiro': '🚶',
            'user': '📱'
        };
        
        let content = `
            <div class="custom-info-window">
                <h4 class="info-title">${typeEmoji[type] || '📍'} ${title}</h4>
        `;
        
        if (address) {
            content += `<p class="info-address">📧 ${address}</p>`;
        }
        
        if (url) {
            content += `<a href="${url}" class="info-link" target="_blank">Ver mais detalhes</a>`;
        }
        
        content += `
                <div class="info-actions">
                    <button onclick="recifemaisMaps.openInGoogleMaps(${this.currentMap?.getCenter?.()?.lat() || 0}, ${this.currentMap?.getCenter?.()?.lng() || 0}, '${title}')" 
                            class="info-button">
                        🗺️ Google Maps
                    </button>
                </div>
            </div>
        `;
        
        return content;
    }
    
    /**
     * Retorna ícone personalizado para markers
     */
    getCustomMarkerIcon(type) {
        const icons = {
            'place': {
                url: this.createMarkerSVG('#059669', '📍'),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            },
            'route': {
                url: this.createMarkerSVG('#3b82f6', '🚶'),
                scaledSize: new google.maps.Size(35, 35),
                anchor: new google.maps.Point(17.5, 35)
            },
            'user': {
                url: this.createMarkerSVG('#f59e0b', '📱'),
                scaledSize: new google.maps.Size(35, 35),
                anchor: new google.maps.Point(17.5, 35)
            },
            'artista': {
                url: this.createMarkerSVG('#8b5cf6', '🎨'),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            },
            'evento': {
                url: this.createMarkerSVG('#ef4444', '🎉'),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 40)
            }
        };
        
        return icons[type] || icons['place'];
    }
    
    /**
     * Cria SVG para marker personalizado
     */
    createMarkerSVG(color, emoji) {
        const svg = `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="15" r="12" fill="${color}" stroke="white" stroke-width="2"/>
                <text x="20" y="20" text-anchor="middle" font-size="12" font-family="Arial">${emoji}</text>
                <path d="M20 27 L14 35 L26 35 Z" fill="${color}" stroke="white" stroke-width="1"/>
            </svg>
        `;
        
        return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
    }
    
    /**
     * Retorna estilos personalizados do mapa
     */
    getMapStyles() {
        return [
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{"color": "#dedede"}, {"lightness": 21}]
            }
        ];
    }
    
    /**
     * Converte tipo de transporte para Google Maps
     */
    getTravelMode(transport) {
        const modes = {
            'walking': google.maps.TravelMode.WALKING,
            'driving': google.maps.TravelMode.DRIVING,
            'bicycling': google.maps.TravelMode.BICYCLING,
            'transit': google.maps.TravelMode.TRANSIT
        };
        
        return modes[transport] || google.maps.TravelMode.WALKING;
    }
    
    /**
     * Adiciona informações da rota calculada
     */
    addRouteInfo(result) {
        const route = result.routes[0];
        const leg = route.legs[0];
        
        const routeInfoDiv = document.createElement('div');
        routeInfoDiv.className = 'route-info';
        routeInfoDiv.innerHTML = `
            <div class="route-summary">
                <span class="route-distance">📏 ${leg.distance.text}</span>
                <span class="route-duration">⏱️ ${leg.duration.text}</span>
            </div>
        `;
        
        // Adiciona ao container do mapa
        const mapContainer = document.getElementById('recifemais-route-map');
        if (mapContainer) {
            mapContainer.appendChild(routeInfoDiv);
        }
    }
}

// Inicialização automática
let recifemaisMaps;

// Função para carregar API do Google Maps
function initRecifeMaisMaps() {
    recifemaisMaps = new RecifeMaisMaps();
}

// Carrega API se não estiver carregada
if (typeof google === 'undefined' && window.recifemais_maps_config?.api_key) {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${window.recifemais_maps_config.api_key}&callback=initRecifeMaisMaps&libraries=places,geometry`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
} else if (typeof google !== 'undefined') {
    // API já carregada
    initRecifeMaisMaps();
} 