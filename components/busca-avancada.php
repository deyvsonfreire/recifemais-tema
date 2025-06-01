<?php
/**
 * Componente de Busca Avançada - RecifeMais
 * Sistema completo de busca com filtros dinâmicos
 */

// Configuração dos CPTs para busca
$search_post_types = [
    'eventos_festivais' => [
        'label' => 'Eventos e Festivais',
        'icon' => '🎭',
        'color' => 'cpt-eventos'
    ],
    'lugares' => [
        'label' => 'Lugares',
        'icon' => '📍', 
        'color' => 'cpt-lugares'
    ],
    'artistas' => [
        'label' => 'Artistas',
        'icon' => '🎨',
        'color' => 'cpt-artistas'
    ],
    'roteiros' => [
        'label' => 'Roteiros',
        'icon' => '🗺️',
        'color' => 'cpt-roteiros'
    ],
    'organizadores' => [
        'label' => 'Organizadores',
        'icon' => '🏢',
        'color' => 'cpt-organizadores'
    ],
    'agremiacoes' => [
        'label' => 'Agremiações',
        'icon' => '🎪',
        'color' => 'cpt-artistas'
    ],
    'historias' => [
        'label' => 'Histórias',
        'icon' => '📖',
        'color' => 'cpt-lugares'
    ],
    'guias_tematicos' => [
        'label' => 'Guias Temáticos',
        'icon' => '📚',
        'color' => 'cpt-roteiros'
    ]
];

// Buscar dicionários para filtros
if (class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
    $tipos_eventos = $dicionarios->get_valores('tipos_eventos');
    $publico_alvo = $dicionarios->get_valores('publico_alvo');
    $ritmos_musicais = $dicionarios->get_valores('ritmos_musicais');
    $especialidades = $dicionarios->get_valores('especialidades_gastronomicas');
}

// Buscar taxonomias para filtros geográficos
$bairros = get_terms([
    'taxonomy' => 'bairros_recife',
    'hide_empty' => false,
    'orderby' => 'name'
]);

$cidades = get_terms([
    'taxonomy' => 'cidades_pernambuco', 
    'hide_empty' => false,
    'orderby' => 'name'
]);
?>

<div id="busca-avancada" class="bg-white rounded-2xl shadow-xl border border-recife-gray-100 p-6 mb-8">
    <!-- Header da Busca -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-r from-recife-primary to-recife-secondary rounded-lg flex items-center justify-center">
                <?php echo recifemais_get_icon_svg('search', '20', 'white'); ?>
            </div>
            <div>
                <h3 class="text-xl font-bold text-recife-gray-900">Busca Avançada</h3>
                <p class="text-sm text-recife-gray-600">Encontre exatamente o que procura na cultura de Pernambuco</p>
            </div>
        </div>
        
        <button id="toggle-filtros" class="btn btn-outline" type="button">
            <?php echo recifemais_get_icon_svg('filter', '16'); ?>
            <span>Filtros</span>
            <span id="filtros-count" class="bg-recife-primary text-white text-xs rounded-full px-2 py-1 ml-2 hidden">0</span>
        </button>
    </div>

    <!-- Busca Principal -->
    <form id="form-busca-avancada" class="space-y-6">
        
        <!-- Campo de Busca Principal -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <?php echo recifemais_get_icon_svg('search', '20', '#6b7280'); ?>
            </div>
            <input 
                type="text" 
                id="busca-termo"
                name="s" 
                placeholder="Digite sua busca: frevo, teatro, restaurante..." 
                class="w-full pl-12 pr-4 py-4 border border-recife-gray-300 rounded-xl focus:ring-2 focus:ring-recife-primary focus:border-transparent text-lg"
                autocomplete="off"
            />
            
            <!-- Sugestões em Tempo Real -->
            <div id="search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-recife-gray-200 rounded-b-xl shadow-lg z-50 hidden max-h-60 overflow-y-auto">
                <div class="p-2">
                    <div class="text-xs text-recife-gray-500 px-3 py-2 border-b">Sugestões</div>
                    <div id="suggestions-list"></div>
                </div>
            </div>
        </div>

        <!-- Filtros Expandíveis -->
        <div id="filtros-container" class="hidden space-y-6">
            
            <!-- Seleção de Tipos de Conteúdo -->
            <div class="bg-recife-gray-50 rounded-xl p-4">
                <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                    <?php echo recifemais_get_icon_svg('folder', '16'); ?>
                    <span class="ml-2">Tipos de Conteúdo</span>
                </label>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php foreach ($search_post_types as $post_type => $config): ?>
                    <label class="flex items-center gap-2 p-3 border border-recife-gray-200 rounded-lg hover:bg-white cursor-pointer transition-colors">
                        <input 
                            type="checkbox" 
                            name="post_types[]" 
                            value="<?php echo esc_attr($post_type); ?>"
                            class="rounded border-recife-gray-300 text-<?php echo $config['color']; ?> focus:ring-<?php echo $config['color']; ?>"
                            checked
                        />
                        <span class="text-lg"><?php echo $config['icon']; ?></span>
                        <span class="text-sm font-medium text-recife-gray-700"><?php echo $config['label']; ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Filtros Geográficos -->
            <div class="grid md:grid-cols-2 gap-6">
                
                <!-- Bairros -->
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('map-pin', '16'); ?>
                        <span class="ml-2">Bairro do Recife</span>
                    </label>
                    <select 
                        name="bairro" 
                        id="filtro-bairro"
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todos os bairros</option>
                        <?php if ($bairros): ?>
                            <?php foreach ($bairros as $bairro): ?>
                            <option value="<?php echo esc_attr($bairro->slug); ?>">
                                <?php echo esc_html($bairro->name); ?>
                                <span class="text-recife-gray-500">(<?php echo $bairro->count; ?>)</span>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Cidades -->
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('globe', '16'); ?>
                        <span class="ml-2">Cidade de Pernambuco</span>
                    </label>
                    <select 
                        name="cidade" 
                        id="filtro-cidade"
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todas as cidades</option>
                        <?php if ($cidades): ?>
                            <?php foreach ($cidades as $cidade): ?>
                            <option value="<?php echo esc_attr($cidade->slug); ?>">
                                <?php echo esc_html($cidade->name); ?>
                                <span class="text-recife-gray-500">(<?php echo $cidade->count; ?>)</span>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Filtros por Meta Fields -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Tipo de Evento -->
                <?php if (isset($tipos_eventos) && $tipos_eventos): ?>
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('calendar', '16'); ?>
                        <span class="ml-2">Tipo de Evento</span>
                    </label>
                    <select 
                        name="tipo_evento" 
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todos os tipos</option>
                        <?php foreach ($tipos_eventos as $tipo): ?>
                        <option value="<?php echo esc_attr($tipo->valor); ?>">
                            <?php echo esc_html($tipo->label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Público-Alvo -->
                <?php if (isset($publico_alvo) && $publico_alvo): ?>
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('user', '16'); ?>
                        <span class="ml-2">Público-Alvo</span>
                    </label>
                    <select 
                        name="publico_alvo" 
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todos os públicos</option>
                        <?php foreach ($publico_alvo as $publico): ?>
                        <option value="<?php echo esc_attr($publico->valor); ?>">
                            <?php echo esc_html($publico->label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Ritmo Musical -->
                <?php if (isset($ritmos_musicais) && $ritmos_musicais): ?>
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('music', '16'); ?>
                        <span class="ml-2">Ritmo Musical</span>
                    </label>
                    <select 
                        name="ritmo_musical" 
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todos os ritmos</option>
                        <?php foreach ($ritmos_musicais as $ritmo): ?>
                        <option value="<?php echo esc_attr($ritmo->valor); ?>">
                            <?php echo esc_html($ritmo->label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Especialidade Gastronômica -->
                <?php if (isset($especialidades) && $especialidades): ?>
                <div>
                    <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                        <?php echo recifemais_get_icon_svg('star', '16'); ?>
                        <span class="ml-2">Especialidade</span>
                    </label>
                    <select 
                        name="especialidade" 
                        class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                    >
                        <option value="">Todas as especialidades</option>
                        <?php foreach ($especialidades as $especialidade): ?>
                        <option value="<?php echo esc_attr($especialidade->valor); ?>">
                            <?php echo esc_html($especialidade->label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <!-- Filtros de Data (apenas para eventos) -->
            <div class="bg-recife-gray-50 rounded-xl p-4 evento-filters" style="display: none;">
                <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                    <?php echo recifemais_get_icon_svg('calendar', '16'); ?>
                    <span class="ml-2">Período do Evento</span>
                </label>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-recife-gray-600 mb-1">Data Início</label>
                        <input 
                            type="date" 
                            name="data_inicio"
                            class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-recife-gray-600 mb-1">Data Fim</label>
                        <input 
                            type="date" 
                            name="data_fim"
                            class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-recife-gray-600 mb-1">Preço máximo (R$)</label>
                        <input 
                            type="number" 
                            name="preco_max"
                            placeholder="Ex: 50"
                            class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                        />
                    </div>
                </div>
            </div>

            <!-- Busca por Proximidade (Google Maps) -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-200">
                <label class="block text-sm font-semibold text-recife-gray-900 mb-3">
                    <?php echo recifemais_get_icon_svg('map-pin', '16'); ?>
                    <span class="ml-2">Busca por Proximidade</span>
                </label>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            id="endereco-busca"
                            name="endereco"
                            placeholder="Digite um endereço ou local..."
                            class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                        />
                        <input type="hidden" id="latitude" name="latitude" />
                        <input type="hidden" id="longitude" name="longitude" />
                    </div>
                    <div>
                        <select 
                            name="raio" 
                            class="w-full border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                        >
                            <option value="">Raio de busca</option>
                            <option value="1">1 km</option>
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="20">20 km</option>
                            <option value="50">50 km</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-between pt-4 border-t border-recife-gray-200">
                <button 
                    type="button" 
                    id="limpar-filtros"
                    class="text-recife-gray-600 hover:text-recife-gray-900 transition-colors"
                >
                    <?php echo recifemais_get_icon_svg('x', '16'); ?>
                    <span class="ml-2">Limpar Filtros</span>
                </button>
                
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        id="buscar-submit"
                        class="btn btn-primary"
                    >
                        <?php echo recifemais_get_icon_svg('search', '16'); ?>
                        <span>Buscar</span>
                    </button>
                    
                    <button 
                        type="button" 
                        id="salvar-busca"
                        class="btn btn-outline"
                        title="Salvar esta busca"
                    >
                        <?php echo recifemais_get_icon_svg('heart', '16'); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Resultados da Busca -->
    <div id="resultados-busca" class="mt-8 hidden">
        <div class="flex items-center justify-between mb-4">
            <div id="resultados-info" class="text-sm text-recife-gray-600"></div>
            <div class="flex gap-2">
                <select id="ordenacao" class="text-sm border border-recife-gray-300 rounded-lg">
                    <option value="relevance">Relevância</option>
                    <option value="date">Data</option>
                    <option value="title">Título A-Z</option>
                    <option value="distance">Distância</option>
                </select>
            </div>
        </div>
        
        <div id="resultados-lista" class="space-y-4"></div>
        
        <div id="resultados-paginacao" class="mt-8 text-center"></div>
    </div>

    <!-- Loading -->
    <div id="busca-loading" class="hidden flex items-center justify-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-recife-primary"></div>
        <span class="ml-3 text-recife-gray-600">Buscando...</span>
    </div>
</div>

<style>
/* Animações personalizadas para a busca */
.suggestion-item {
    @apply px-3 py-2 hover:bg-recife-gray-50 cursor-pointer transition-colors;
}

.suggestion-item:hover {
    @apply bg-recife-primary text-white;
}

.filter-tag {
    @apply inline-flex items-center gap-1 px-3 py-1 bg-recife-primary text-white text-sm rounded-full;
}

.result-card {
    @apply border border-recife-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow;
}

.result-highlight {
    @apply bg-yellow-200 px-1 rounded;
}
</style> 