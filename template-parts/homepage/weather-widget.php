<?php
/**
 * Weather Widget - Homepage RecifeMais
 * Widget de clima para Recife com informações culturais contextuais
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações do widget
$weather_config = [
    'city' => 'Recife',
    'state' => 'PE',
    'show_forecast' => true,
    'show_cultural_tips' => true,
    'show_uv_index' => true,
    'enable_geolocation' => true
];

// Dados simulados do clima (integrar com API real)
$weather_data = [
    'current' => [
        'temperature' => 28,
        'feels_like' => 32,
        'humidity' => 78,
        'wind_speed' => 12,
        'uv_index' => 8,
        'condition' => 'partly_cloudy',
        'condition_text' => 'Parcialmente Nublado',
        'icon' => '⛅',
        'last_updated' => current_time('timestamp')
    ],
    'forecast' => [
        [
            'day' => 'Hoje',
            'high' => 30,
            'low' => 24,
            'condition' => 'sunny',
            'icon' => '☀️',
            'rain_chance' => 20
        ],
        [
            'day' => 'Amanhã',
            'high' => 29,
            'low' => 23,
            'condition' => 'partly_cloudy',
            'icon' => '⛅',
            'rain_chance' => 40
        ],
        [
            'day' => 'Quinta',
            'high' => 27,
            'low' => 22,
            'condition' => 'rainy',
            'icon' => '🌧️',
            'rain_chance' => 80
        ]
    ]
];

// Dicas culturais baseadas no clima
$cultural_tips = [
    'sunny' => [
        'title' => 'Dia Perfeito para Explorar!',
        'tip' => 'Que tal visitar o Marco Zero ou fazer um passeio pelo Recife Antigo?',
        'events' => 'Confira eventos ao ar livre hoje'
    ],
    'partly_cloudy' => [
        'title' => 'Clima Ideal para Cultura',
        'tip' => 'Perfeito para visitar museus ou centros culturais',
        'events' => 'Veja exposições em ambientes cobertos'
    ],
    'rainy' => [
        'title' => 'Cultura Indoor',
        'tip' => 'Dia ideal para teatros, cinemas e centros culturais',
        'events' => 'Confira nossa agenda de eventos cobertos'
    ],
    'cloudy' => [
        'title' => 'Explore com Conforto',
        'tip' => 'Temperatura agradável para caminhadas culturais',
        'events' => 'Roteiros a pé pelo centro histórico'
    ]
];

$current_tip = $cultural_tips[$weather_data['current']['condition']] ?? $cultural_tips['partly_cloudy'];
?>

<div class="weather-widget bg-gradient-to-br from-blue-50 via-white to-cyan-50 rounded-2xl shadow-lg border border-blue-100 overflow-hidden" 
     id="weather-widget"
     data-component="weather-widget"
     data-city="<?php echo esc_attr($weather_config['city']); ?>">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">🌤️</span>
                <div>
                    <h3 class="font-semibold text-lg">Clima em Recife</h3>
                    <p class="text-blue-100 text-sm">
                        Atualizado há 
                        <span data-last-updated><?php echo human_time_diff($weather_data['current']['last_updated']); ?></span>
                    </p>
                </div>
            </div>
            <button class="weather-refresh-btn p-2 hover:bg-white/20 rounded-lg transition-colors" 
                    data-refresh-weather
                    title="Atualizar clima">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Clima Atual -->
    <div class="p-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Temperatura Principal -->
            <div class="text-center">
                <div class="text-5xl mb-2" data-weather-icon>
                    <?php echo $weather_data['current']['icon']; ?>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1" data-temperature>
                    <?php echo $weather_data['current']['temperature']; ?>°C
                </div>
                <div class="text-sm text-gray-600" data-condition>
                    <?php echo esc_html($weather_data['current']['condition_text']); ?>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Sensação: <span data-feels-like><?php echo $weather_data['current']['feels_like']; ?>°C</span>
                </div>
            </div>

            <!-- Detalhes -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 flex items-center gap-1">
                        💧 Umidade
                    </span>
                    <span class="font-medium" data-humidity><?php echo $weather_data['current']['humidity']; ?>%</span>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 flex items-center gap-1">
                        💨 Vento
                    </span>
                    <span class="font-medium" data-wind><?php echo $weather_data['current']['wind_speed']; ?> km/h</span>
                </div>
                
                <?php if ($weather_config['show_uv_index']) : ?>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 flex items-center gap-1">
                        ☀️ UV
                    </span>
                    <span class="font-medium" data-uv>
                        <span class="px-2 py-1 rounded-full text-xs <?php echo $weather_data['current']['uv_index'] > 7 ? 'bg-red-100 text-red-800' : ($weather_data['current']['uv_index'] > 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'); ?>">
                            <?php echo $weather_data['current']['uv_index']; ?>
                        </span>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Previsão -->
        <?php if ($weather_config['show_forecast']) : ?>
        <div class="border-t border-gray-100 pt-4 mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Próximos Dias</h4>
            <div class="grid grid-cols-3 gap-2">
                <?php foreach ($weather_data['forecast'] as $day) : ?>
                <div class="text-center p-2 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="text-xs text-gray-600 mb-1"><?php echo esc_html($day['day']); ?></div>
                    <div class="text-lg mb-1"><?php echo $day['icon']; ?></div>
                    <div class="text-xs font-medium text-gray-900">
                        <?php echo $day['high']; ?>° / <?php echo $day['low']; ?>°
                    </div>
                    <div class="text-xs text-blue-600 mt-1">
                        <?php echo $day['rain_chance']; ?>% 🌧️
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Dicas Culturais -->
        <?php if ($weather_config['show_cultural_tips']) : ?>
        <div class="bg-gradient-to-r from-recife-primary/5 to-recife-secondary/5 rounded-xl p-4 border border-recife-primary/10">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-recife-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-recife-primary">💡</span>
                </div>
                <div class="flex-1">
                    <h5 class="font-semibold text-gray-900 mb-1">
                        <?php echo esc_html($current_tip['title']); ?>
                    </h5>
                    <p class="text-sm text-gray-600 mb-2">
                        <?php echo esc_html($current_tip['tip']); ?>
                    </p>
                    <a href="/agenda-cultural" 
                       class="inline-flex items-center gap-1 text-xs font-medium text-recife-primary hover:text-recife-primary/80 transition-colors">
                        <?php echo esc_html($current_tip['events']); ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Localização -->
        <?php if ($weather_config['enable_geolocation']) : ?>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <button class="w-full flex items-center justify-center gap-2 text-sm text-gray-600 hover:text-recife-primary transition-colors py-2"
                    data-location-btn>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span data-location-text>Usar minha localização</span>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Loading State -->
    <div class="weather-loading hidden absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center" data-loading>
        <div class="text-center">
            <div class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
            <div class="text-sm text-gray-600">Atualizando clima...</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weatherWidget = document.getElementById('weather-widget');
    const refreshBtn = document.querySelector('[data-refresh-weather]');
    const locationBtn = document.querySelector('[data-location-btn]');
    const loadingState = document.querySelector('[data-loading]');
    
    if (!weatherWidget) return;

    // Atualizar timestamp
    function updateTimestamp() {
        const timestampEl = document.querySelector('[data-last-updated]');
        if (timestampEl) {
            const now = new Date();
            timestampEl.textContent = 'poucos minutos';
        }
    }

    // Refresh do clima
    if (refreshBtn) {
        refreshBtn.addEventListener('click', async function() {
            await refreshWeather();
        });
    }

    // Geolocalização
    if (locationBtn) {
        locationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                const locationText = this.querySelector('[data-location-text]');
                locationText.textContent = 'Obtendo localização...';
                
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const { latitude, longitude } = position.coords;
                        await getWeatherByCoords(latitude, longitude);
                        locationText.textContent = 'Localização atualizada';
                        
                        setTimeout(() => {
                            locationText.textContent = 'Usar minha localização';
                        }, 2000);
                    },
                    (error) => {
                        console.error('Erro de geolocalização:', error);
                        locationText.textContent = 'Erro na localização';
                        
                        setTimeout(() => {
                            locationText.textContent = 'Usar minha localização';
                        }, 2000);
                    }
                );
            } else {
                alert('Geolocalização não suportada pelo navegador');
            }
        });
    }

    async function refreshWeather() {
        showLoading(true);
        
        try {
            // Simular chamada à API
            await simulateWeatherAPI();
            
            // Atualizar dados na interface
            updateWeatherDisplay();
            updateTimestamp();
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'weather_refresh', {
                    event_category: 'Weather Widget',
                    event_label: 'Manual Refresh'
                });
            }
            
        } catch (error) {
            console.error('Erro ao atualizar clima:', error);
            showError('Erro ao atualizar clima');
        } finally {
            showLoading(false);
        }
    }

    async function getWeatherByCoords(lat, lon) {
        showLoading(true);
        
        try {
            // Simular chamada à API com coordenadas
            await simulateWeatherAPI(lat, lon);
            updateWeatherDisplay();
            updateTimestamp();
            
        } catch (error) {
            console.error('Erro ao obter clima por coordenadas:', error);
            showError('Erro ao obter clima da localização');
        } finally {
            showLoading(false);
        }
    }

    function updateWeatherDisplay() {
        // Simular novos dados (integrar com API real)
        const newData = {
            temperature: Math.floor(Math.random() * 10) + 25,
            feels_like: Math.floor(Math.random() * 10) + 28,
            humidity: Math.floor(Math.random() * 30) + 60,
            wind_speed: Math.floor(Math.random() * 15) + 5,
            uv_index: Math.floor(Math.random() * 10) + 1
        };

        // Atualizar elementos
        const tempEl = document.querySelector('[data-temperature]');
        const feelsLikeEl = document.querySelector('[data-feels-like]');
        const humidityEl = document.querySelector('[data-humidity]');
        const windEl = document.querySelector('[data-wind]');
        const uvEl = document.querySelector('[data-uv] span');

        if (tempEl) tempEl.textContent = newData.temperature + '°C';
        if (feelsLikeEl) feelsLikeEl.textContent = newData.feels_like + '°C';
        if (humidityEl) humidityEl.textContent = newData.humidity + '%';
        if (windEl) windEl.textContent = newData.wind_speed + ' km/h';
        
        if (uvEl) {
            uvEl.textContent = newData.uv_index;
            // Atualizar classe do UV
            uvEl.className = 'px-2 py-1 rounded-full text-xs ' + 
                (newData.uv_index > 7 ? 'bg-red-100 text-red-800' : 
                 newData.uv_index > 4 ? 'bg-yellow-100 text-yellow-800' : 
                 'bg-green-100 text-green-800');
        }

        // Animação de atualização
        weatherWidget.style.transform = 'scale(1.02)';
        setTimeout(() => {
            weatherWidget.style.transform = 'scale(1)';
        }, 200);
    }

    function showLoading(show) {
        if (loadingState) {
            loadingState.classList.toggle('hidden', !show);
        }
        
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('svg');
            if (show) {
                icon.classList.add('animate-spin');
            } else {
                icon.classList.remove('animate-spin');
            }
        }
    }

    function showError(message) {
        // Criar notificação de erro temporária
        const errorDiv = document.createElement('div');
        errorDiv.className = 'absolute top-4 right-4 bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm border border-red-200 z-10';
        errorDiv.textContent = message;
        
        weatherWidget.style.position = 'relative';
        weatherWidget.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }

    async function simulateWeatherAPI(lat = null, lon = null) {
        // Simular delay da API
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Simular possível erro (2% de chance)
        if (Math.random() < 0.02) {
            throw new Error('API Error');
        }
        
        // Aqui você integraria com uma API real como OpenWeatherMap
        console.log('Weather API call simulated', { lat, lon });
        
        return { success: true };
    }

    // Auto-refresh a cada 30 minutos
    setInterval(() => {
        refreshWeather();
    }, 30 * 60 * 1000);

    // Atualizar timestamp a cada minuto
    setInterval(updateTimestamp, 60000);
});
</script>

<style>
.weather-widget {
    position: relative;
    transition: transform 0.2s ease;
}

.weather-widget:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.weather-refresh-btn:hover svg {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

@media (max-width: 640px) {
    .weather-widget .grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .weather-widget .grid-cols-3 {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }
}

/* Animação de loading */
@keyframes pulse-weather {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.weather-loading {
    animation: pulse-weather 2s infinite;
}
</style> 