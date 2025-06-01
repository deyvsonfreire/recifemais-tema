<?php
/**
 * Template Part: Interactive Map Filters
 * 
 * Filtros interativos para mapas com:
 * - Filtros dinâmicos em tempo real
 * - Interface moderna e responsiva
 * - Integração AJAX para atualização sem reload
 * - Estatísticas em tempo real
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Configurações dos filtros
$args = wp_parse_args($args ?? [], [
    'map_id' => 'interactive-map',
    'show_search' => true,
    'show_date_filter' => true,
    'show_type_filter' => true,
    'show_taxonomy_filters' => true,
    'show_radius_filter' => true,
    'show_price_filter' => false,
    'show_stats' => true,
    'enable_presets' => true,
    'auto_apply' => true,
    'post_types' => ['eventos_festivais', 'lugares', 'artistas'],
    'taxonomies' => ['manifestacoes_culturais', 'bairros_recife', 'tipos_lugares'],
    'preset_filters' => [
        'hoje' => 'Eventos Hoje',
        'fim_semana' => 'Fim de Semana',
        'gratuitos' => 'Eventos Gratuitos',
        'centro' => 'Centro do Recife'
    ]
]);

// Obter termos das taxonomias
$taxonomy_terms = [];
foreach ($args['taxonomies'] as $taxonomy) {
    $taxonomy_terms[$taxonomy] = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ]);
}

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

// Labels das taxonomias
$taxonomy_labels = [
    'manifestacoes_culturais' => 'Manifestação Cultural',
    'bairros_recife' => 'Bairro',
    'tipos_lugares' => 'Tipo de Local',
    'generos_musicais' => 'Gênero Musical',
    'tipos_eventos' => 'Tipo de Evento'
];

$filter_id = 'interactive-filters-' . uniqid();
?>

<div class="interactive-filters-container bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" 
     id="<?php echo esc_attr($filter_id); ?>"
     data-map-id="<?php echo esc_attr($args['map_id']); ?>"
     data-auto-apply="<?php echo $args['auto_apply'] ? 'true' : 'false'; ?>">
    
    <!-- Header dos Filtros -->
    <div class="filters-header bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Filtros Interativos</h3>
                    <p class="text-sm text-gray-600">Refine sua busca em tempo real</p>
                </div>
            </div>
            
            <!-- Controles do Header -->
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="resetAllFilters('<?php echo esc_js($filter_id); ?>')"
                        class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    Limpar
                </button>
                
                <button type="button" 
                        onclick="toggleFiltersCollapse('<?php echo esc_js($filter_id); ?>')"
                        id="collapse-btn-<?php echo esc_attr($filter_id); ?>"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="hidden sm:inline">Recolher</span>
                </button>
            </div>
        </div>
        
        <!-- Estatísticas em Tempo Real -->
        <?php if ($args['show_stats']): ?>
            <div id="filter-stats" class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                <span class="flex items-center gap-2 bg-white px-3 py-1 rounded-full">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span id="total-results">0</span> resultados
                </span>
                <span class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span id="visible-results">0</span> visíveis
                </span>
                <span class="flex items-center gap-2 bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                    📍 <span id="unique-locations">0</span> locais
                </span>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Container dos Filtros -->
    <div id="filters-content-<?php echo esc_attr($filter_id); ?>" class="filters-content p-4 space-y-6">
        
        <!-- Filtros Predefinidos -->
        <?php if ($args['enable_presets'] && !empty($args['preset_filters'])): ?>
            <div class="preset-filters">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtros Rápidos</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($args['preset_filters'] as $preset_key => $preset_label): ?>
                        <button type="button" 
                                onclick="applyPresetFilter('<?php echo esc_js($preset_key); ?>', '<?php echo esc_js($filter_id); ?>')"
                                class="preset-filter-btn px-3 py-2 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-lg text-sm font-medium transition-colors border border-gray-200 hover:border-blue-300"
                                data-preset="<?php echo esc_attr($preset_key); ?>">
                            <?php echo esc_html($preset_label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Busca por Texto -->
        <?php if ($args['show_search']): ?>
            <div class="search-filter">
                <label for="search-input-<?php echo esc_attr($filter_id); ?>" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar por texto
                </label>
                <div class="relative">
                    <input type="text" 
                           id="search-input-<?php echo esc_attr($filter_id); ?>" 
                           name="search"
                           placeholder="Digite o que você procura..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Filtros por Tipo de Post -->
        <?php if ($args['show_type_filter']): ?>
            <div class="type-filter">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipos de conteúdo</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                    <?php foreach ($args['post_types'] as $post_type): ?>
                        <label class="flex items-center gap-2 bg-gray-50 hover:bg-blue-50 p-2 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors">
                            <input type="checkbox" 
                                   name="post_types[]" 
                                   value="<?php echo esc_attr($post_type); ?>"
                                   checked
                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700">
                                <?php echo esc_html($post_type_labels[$post_type] ?? $post_type); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Filtros de Data -->
        <?php if ($args['show_date_filter']): ?>
            <div class="date-filter">
                <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date-from-<?php echo esc_attr($filter_id); ?>" class="block text-xs text-gray-600 mb-1">De</label>
                        <input type="date" 
                               id="date-from-<?php echo esc_attr($filter_id); ?>" 
                               name="date_from"
                               value="<?php echo date('Y-m-d'); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="date-to-<?php echo esc_attr($filter_id); ?>" class="block text-xs text-gray-600 mb-1">Até</label>
                        <input type="date" 
                               id="date-to-<?php echo esc_attr($filter_id); ?>" 
                               name="date_to"
                               value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Atalhos de Data -->
                <div class="flex flex-wrap gap-2 mt-2">
                    <button type="button" 
                            onclick="setDateRange('today', '<?php echo esc_js($filter_id); ?>')"
                            class="date-shortcut px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs font-medium transition-colors">
                        Hoje
                    </button>
                    <button type="button" 
                            onclick="setDateRange('tomorrow', '<?php echo esc_js($filter_id); ?>')"
                            class="date-shortcut px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs font-medium transition-colors">
                        Amanhã
                    </button>
                    <button type="button" 
                            onclick="setDateRange('weekend', '<?php echo esc_js($filter_id); ?>')"
                            class="date-shortcut px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs font-medium transition-colors">
                        Fim de Semana
                    </button>
                    <button type="button" 
                            onclick="setDateRange('week', '<?php echo esc_js($filter_id); ?>')"
                            class="date-shortcut px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded text-xs font-medium transition-colors">
                        Esta Semana
                    </button>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Filtros por Taxonomia -->
        <?php if ($args['show_taxonomy_filters']): ?>
            <div class="taxonomy-filters space-y-4">
                <?php foreach ($args['taxonomies'] as $taxonomy): ?>
                    <?php if (!empty($taxonomy_terms[$taxonomy])): ?>
                        <div class="taxonomy-filter" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo esc_html($taxonomy_labels[$taxonomy] ?? $taxonomy); ?>
                            </label>
                            
                            <!-- Select para muitos termos -->
                            <?php if (count($taxonomy_terms[$taxonomy]) > 8): ?>
                                <select name="<?php echo esc_attr($taxonomy); ?>" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todos</option>
                                    <?php foreach ($taxonomy_terms[$taxonomy] as $term): ?>
                                        <option value="<?php echo esc_attr($term->slug); ?>">
                                            <?php echo esc_html($term->name); ?> (<?php echo $term->count; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            
                            <!-- Checkboxes para poucos termos -->
                            <?php else: ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <?php foreach ($taxonomy_terms[$taxonomy] as $term): ?>
                                        <label class="flex items-center gap-2 bg-gray-50 hover:bg-blue-50 p-2 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-colors">
                                            <input type="checkbox" 
                                                   name="<?php echo esc_attr($taxonomy); ?>[]" 
                                                   value="<?php echo esc_attr($term->slug); ?>"
                                                   class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="text-sm text-gray-700">
                                                <?php echo esc_html($term->name); ?>
                                                <span class="text-xs text-gray-500">(<?php echo $term->count; ?>)</span>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Filtro de Raio -->
        <?php if ($args['show_radius_filter']): ?>
            <div class="radius-filter">
                <label for="radius-<?php echo esc_attr($filter_id); ?>" class="block text-sm font-medium text-gray-700 mb-2">
                    Raio de busca: <span id="radius-value-<?php echo esc_attr($filter_id); ?>">5</span> km
                </label>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-gray-500">1km</span>
                    <input type="range" 
                           id="radius-<?php echo esc_attr($filter_id); ?>" 
                           name="radius"
                           min="1" 
                           max="50" 
                           value="5"
                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                    <span class="text-xs text-gray-500">50km</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Próximo</span>
                    <span>Região Metropolitana</span>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Filtro de Preço -->
        <?php if ($args['show_price_filter']): ?>
            <div class="price-filter">
                <label class="block text-sm font-medium text-gray-700 mb-2">Faixa de preço</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <label class="flex items-center gap-2 bg-gray-50 hover:bg-green-50 p-2 rounded-lg border border-gray-200 hover:border-green-300 cursor-pointer transition-colors">
                        <input type="checkbox" 
                               name="price_range[]" 
                               value="gratuito"
                               class="text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Gratuito</span>
                    </label>
                    <label class="flex items-center gap-2 bg-gray-50 hover:bg-green-50 p-2 rounded-lg border border-gray-200 hover:border-green-300 cursor-pointer transition-colors">
                        <input type="checkbox" 
                               name="price_range[]" 
                               value="$"
                               class="text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">$ (Barato)</span>
                    </label>
                    <label class="flex items-center gap-2 bg-gray-50 hover:bg-green-50 p-2 rounded-lg border border-gray-200 hover:border-green-300 cursor-pointer transition-colors">
                        <input type="checkbox" 
                               name="price_range[]" 
                               value="$$"
                               class="text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">$$ (Médio)</span>
                    </label>
                    <label class="flex items-center gap-2 bg-gray-50 hover:bg-green-50 p-2 rounded-lg border border-gray-200 hover:border-green-300 cursor-pointer transition-colors">
                        <input type="checkbox" 
                               name="price_range[]" 
                               value="$$$"
                               class="text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">$$$ (Caro)</span>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Botões de Ação -->
        <div class="filter-actions flex items-center gap-3 pt-4 border-t border-gray-200">
            <button type="button" 
                    onclick="applyFilters('<?php echo esc_js($filter_id); ?>')"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                </svg>
                Aplicar Filtros
            </button>
            
            <button type="button" 
                    onclick="saveFilterPreset('<?php echo esc_js($filter_id); ?>')"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                Salvar
            </button>
            
            <div class="ml-auto text-sm text-gray-600">
                <span id="filter-status">Filtros prontos</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initInteractiveFilters('<?php echo esc_js($filter_id); ?>');
});

function initInteractiveFilters(filterId) {
    const container = document.getElementById(filterId);
    if (!container) return;
    
    const autoApply = container.dataset.autoApply === 'true';
    
    // Inicializar eventos dos filtros
    initFilterEvents(filterId, autoApply);
    
    // Inicializar slider de raio
    initRadiusSlider(filterId);
    
    // Aplicar filtros iniciais se auto-apply estiver ativo
    if (autoApply) {
        applyFilters(filterId);
    }
}

function initFilterEvents(filterId, autoApply) {
    const container = document.getElementById(filterId);
    
    // Busca por texto com debounce
    const searchInput = container.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (autoApply) {
                    applyFilters(filterId);
                }
            }, 500);
        });
    }
    
    // Checkboxes e selects
    const inputs = container.querySelectorAll('input[type="checkbox"], select, input[type="date"]');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            if (autoApply) {
                applyFilters(filterId);
            }
        });
    });
    
    // Range slider
    const radiusSlider = container.querySelector('input[type="range"]');
    if (radiusSlider) {
        radiusSlider.addEventListener('input', function() {
            updateRadiusValue(filterId, this.value);
            if (autoApply) {
                clearTimeout(window.radiusTimeout);
                window.radiusTimeout = setTimeout(() => {
                    applyFilters(filterId);
                }, 300);
            }
        });
    }
}

function initRadiusSlider(filterId) {
    const slider = document.getElementById(`radius-${filterId}`);
    const valueDisplay = document.getElementById(`radius-value-${filterId}`);
    
    if (slider && valueDisplay) {
        updateRadiusValue(filterId, slider.value);
    }
}

function updateRadiusValue(filterId, value) {
    const valueDisplay = document.getElementById(`radius-value-${filterId}`);
    if (valueDisplay) {
        valueDisplay.textContent = value;
    }
}

function applyFilters(filterId) {
    const container = document.getElementById(filterId);
    const mapId = container.dataset.mapId;
    
    // Coletar dados dos filtros
    const filterData = collectFilterData(filterId);
    
    // Atualizar status
    updateFilterStatus(filterId, 'Aplicando filtros...');
    
    // Aplicar filtros no mapa
    if (window.RecifeMaisMaps && mapId) {
        window.RecifeMaisMaps.applyFilters(mapId, filterData).then(results => {
            updateFilterStats(filterId, results);
            updateFilterStatus(filterId, 'Filtros aplicados');
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'filters_applied', {
                    'filter_count': Object.keys(filterData).length,
                    'results_count': results.total
                });
            }
        }).catch(error => {
            console.error('Erro ao aplicar filtros:', error);
            updateFilterStatus(filterId, 'Erro ao aplicar filtros');
        });
    }
}

function collectFilterData(filterId) {
    const container = document.getElementById(filterId);
    const data = {};
    
    // Busca por texto
    const searchInput = container.querySelector('input[name="search"]');
    if (searchInput && searchInput.value.trim()) {
        data.search = searchInput.value.trim();
    }
    
    // Tipos de post
    const typeCheckboxes = container.querySelectorAll('input[name="post_types[]"]:checked');
    if (typeCheckboxes.length > 0) {
        data.post_types = Array.from(typeCheckboxes).map(cb => cb.value);
    }
    
    // Datas
    const dateFrom = container.querySelector('input[name="date_from"]');
    const dateTo = container.querySelector('input[name="date_to"]');
    if (dateFrom && dateFrom.value) data.date_from = dateFrom.value;
    if (dateTo && dateTo.value) data.date_to = dateTo.value;
    
    // Taxonomias
    const taxonomySelects = container.querySelectorAll('select[name*="manifestacoes"], select[name*="bairros"], select[name*="tipos"]');
    taxonomySelects.forEach(select => {
        if (select.value) {
            data[select.name] = select.value;
        }
    });
    
    // Taxonomias (checkboxes)
    const taxonomyCheckboxes = container.querySelectorAll('input[type="checkbox"][name*="manifestacoes"], input[type="checkbox"][name*="bairros"], input[type="checkbox"][name*="tipos"]');
    const taxonomyGroups = {};
    taxonomyCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const groupName = checkbox.name.replace('[]', '');
            if (!taxonomyGroups[groupName]) taxonomyGroups[groupName] = [];
            taxonomyGroups[groupName].push(checkbox.value);
        }
    });
    Object.assign(data, taxonomyGroups);
    
    // Raio
    const radiusSlider = container.querySelector('input[name="radius"]');
    if (radiusSlider) {
        data.radius = radiusSlider.value;
    }
    
    // Preço
    const priceCheckboxes = container.querySelectorAll('input[name="price_range[]"]:checked');
    if (priceCheckboxes.length > 0) {
        data.price_range = Array.from(priceCheckboxes).map(cb => cb.value);
    }
    
    return data;
}

function updateFilterStats(filterId, results) {
    const totalElement = document.getElementById('total-results');
    const visibleElement = document.getElementById('visible-results');
    const locationsElement = document.getElementById('unique-locations');
    
    if (totalElement) totalElement.textContent = results.total || 0;
    if (visibleElement) visibleElement.textContent = results.visible || 0;
    if (locationsElement) locationsElement.textContent = results.unique_locations || 0;
}

function updateFilterStatus(filterId, status) {
    const statusElement = document.getElementById('filter-status');
    if (statusElement) {
        statusElement.textContent = status;
    }
}

function resetAllFilters(filterId) {
    const container = document.getElementById(filterId);
    
    // Resetar todos os inputs
    const inputs = container.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            if (input.name === 'post_types[]') {
                input.checked = true; // Manter tipos selecionados
            } else {
                input.checked = false;
            }
        } else if (input.type === 'range') {
            input.value = 5; // Valor padrão do raio
            updateRadiusValue(filterId, 5);
        } else if (input.type === 'date') {
            if (input.name === 'date_from') {
                input.value = new Date().toISOString().split('T')[0];
            } else if (input.name === 'date_to') {
                const futureDate = new Date();
                futureDate.setDate(futureDate.getDate() + 30);
                input.value = futureDate.toISOString().split('T')[0];
            }
        } else {
            input.value = '';
        }
    });
    
    // Remover seleção dos presets
    const presetButtons = container.querySelectorAll('.preset-filter-btn');
    presetButtons.forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    // Aplicar filtros resetados
    applyFilters(filterId);
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'filters_reset');
    }
}

function toggleFiltersCollapse(filterId) {
    const content = document.getElementById(`filters-content-${filterId}`);
    const button = document.getElementById(`collapse-btn-${filterId}`);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        button.innerHTML = `
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="hidden sm:inline">Recolher</span>
        `;
    } else {
        content.style.display = 'none';
        button.innerHTML = `
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="hidden sm:inline">Expandir</span>
        `;
    }
}

function applyPresetFilter(presetKey, filterId) {
    const container = document.getElementById(filterId);
    
    // Resetar filtros primeiro
    resetAllFilters(filterId);
    
    // Aplicar preset específico
    switch (presetKey) {
        case 'hoje':
            const today = new Date().toISOString().split('T')[0];
            container.querySelector('input[name="date_from"]').value = today;
            container.querySelector('input[name="date_to"]').value = today;
            break;
            
        case 'fim_semana':
            setDateRange('weekend', filterId);
            break;
            
        case 'gratuitos':
            const gratuitoCheckbox = container.querySelector('input[value="gratuito"]');
            if (gratuitoCheckbox) gratuitoCheckbox.checked = true;
            break;
            
        case 'centro':
            const centroSelect = container.querySelector('select[name*="bairros"]');
            if (centroSelect) {
                const centroOption = centroSelect.querySelector('option[value*="centro"]');
                if (centroOption) centroSelect.value = centroOption.value;
            }
            break;
    }
    
    // Destacar botão ativo
    const presetButtons = container.querySelectorAll('.preset-filter-btn');
    presetButtons.forEach(btn => {
        if (btn.dataset.preset === presetKey) {
            btn.classList.remove('bg-gray-100', 'text-gray-700');
            btn.classList.add('bg-blue-500', 'text-white');
        } else {
            btn.classList.remove('bg-blue-500', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        }
    });
    
    // Aplicar filtros
    applyFilters(filterId);
}

function setDateRange(range, filterId) {
    const container = document.getElementById(filterId);
    const dateFrom = container.querySelector('input[name="date_from"]');
    const dateTo = container.querySelector('input[name="date_to"]');
    
    const today = new Date();
    let fromDate, toDate;
    
    switch (range) {
        case 'today':
            fromDate = toDate = today;
            break;
            
        case 'tomorrow':
            fromDate = toDate = new Date(today.getTime() + 24 * 60 * 60 * 1000);
            break;
            
        case 'weekend':
            // Próximo fim de semana
            const daysUntilSaturday = (6 - today.getDay()) % 7;
            fromDate = new Date(today.getTime() + daysUntilSaturday * 24 * 60 * 60 * 1000);
            toDate = new Date(fromDate.getTime() + 24 * 60 * 60 * 1000);
            break;
            
        case 'week':
            fromDate = today;
            toDate = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
            break;
    }
    
    if (dateFrom && fromDate) {
        dateFrom.value = fromDate.toISOString().split('T')[0];
    }
    if (dateTo && toDate) {
        dateTo.value = toDate.toISOString().split('T')[0];
    }
}

function saveFilterPreset(filterId) {
    const filterData = collectFilterData(filterId);
    const presetName = prompt('Nome para este filtro personalizado:');
    
    if (presetName && presetName.trim()) {
        // Salvar no localStorage
        const savedFilters = JSON.parse(localStorage.getItem('recifemais_filter_presets') || '{}');
        savedFilters[presetName.trim()] = filterData;
        localStorage.setItem('recifemais_filter_presets', JSON.stringify(savedFilters));
        
        alert('Filtro salvo com sucesso!');
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'filter_preset_saved', {
                'preset_name': presetName.trim()
            });
        }
    }
}
</script>

<style>
.interactive-filters-container {
    transition: all 0.3s ease;
}

.preset-filter-btn.active {
    background-color: #3b82f6;
    color: white;
    border-color: #2563eb;
}

.slider {
    background: linear-gradient(to right, #3b82f6 0%, #3b82f6 var(--value, 10%), #e5e7eb var(--value, 10%), #e5e7eb 100%);
}

.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .filters-header .flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .filter-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-actions .ml-auto {
        margin-left: 0;
        text-align: center;
        margin-top: 0.5rem;
    }
}
</style> 