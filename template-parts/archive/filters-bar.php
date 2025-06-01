<?php
/**
 * Template Part: Archive Filters Bar
 * 
 * Barra de filtros para páginas de arquivo com:
 * - Filtros dinâmicos por tipo de conteúdo
 * - Busca em tempo real
 * - Ordenação múltipla
 * - Layout responsivo
 * - Estados visuais claros
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Detectar contexto atual
$current_post_type = get_post_type();
$queried_object = get_queried_object();

// Configurações de filtros por tipo de conteúdo
$filter_configs = [
    'eventos_festivais' => [
        'filters' => [
            'data_inicio' => 'Data',
            'preco' => 'Preço',
            'bairro' => 'Bairro',
            'tipo_evento' => 'Tipo'
        ],
        'sorts' => [
            'data_inicio' => 'Data do evento',
            'title' => 'Nome A-Z',
            'meta_value_num' => 'Preço',
            'date' => 'Publicação'
        ]
    ],
    'lugares' => [
        'filters' => [
            'bairro' => 'Bairro',
            'tipo_lugar' => 'Tipo',
            'acessibilidade' => 'Acessibilidade',
            'funcionamento' => 'Funcionamento'
        ],
        'sorts' => [
            'title' => 'Nome A-Z',
            'bairro' => 'Bairro',
            'date' => 'Publicação',
            'meta_value_num' => 'Avaliação'
        ]
    ],
    'artistas' => [
        'filters' => [
            'area_atuacao' => 'Área',
            'bairro' => 'Bairro',
            'experiencia' => 'Experiência',
            'disponibilidade' => 'Disponibilidade'
        ],
        'sorts' => [
            'title' => 'Nome A-Z',
            'area_atuacao' => 'Área',
            'date' => 'Publicação',
            'meta_value_num' => 'Experiência'
        ]
    ],
    'roteiros' => [
        'filters' => [
            'duracao' => 'Duração',
            'dificuldade' => 'Dificuldade',
            'categoria' => 'Categoria',
            'bairro' => 'Região'
        ],
        'sorts' => [
            'title' => 'Nome A-Z',
            'meta_value_num' => 'Duração',
            'dificuldade' => 'Dificuldade',
            'date' => 'Publicação'
        ]
    ],
    'post' => [
        'filters' => [
            'category' => 'Categoria',
            'tag' => 'Tags',
            'author' => 'Autor',
            'date' => 'Data'
        ],
        'sorts' => [
            'date' => 'Mais recentes',
            'title' => 'Título A-Z',
            'comment_count' => 'Mais comentados',
            'meta_value_num' => 'Mais lidos'
        ]
    ]
];

// Configuração para o tipo atual
$current_config = $filter_configs[$current_post_type] ?? $filter_configs['post'];

// Obter valores atuais dos filtros
$current_filters = [];
$current_sort = $_GET['orderby'] ?? 'date';
$current_order = $_GET['order'] ?? 'DESC';
$current_search = $_GET['s'] ?? '';

// Processar filtros ativos
foreach ($current_config['filters'] as $filter_key => $filter_label) {
    if (isset($_GET[$filter_key]) && !empty($_GET[$filter_key])) {
        $current_filters[$filter_key] = sanitize_text_field($_GET[$filter_key]);
    }
}

// Contar resultados
global $wp_query;
$total_results = $wp_query->found_posts ?? 0;
$results_text = $total_results === 1 ? 'resultado' : 'resultados';
?>

<div class="filters-bar bg-white border-b border-gray-200 sticky top-0 z-40" id="archive-filters">
    <div class="container mx-auto px-4">
        <!-- Header da Barra de Filtros -->
        <div class="flex items-center justify-between py-4 border-b border-gray-100">
            <!-- Resultados e Toggle -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">
                        <strong class="text-gray-900"><?php echo number_format($total_results); ?></strong> 
                        <?php echo $results_text; ?>
                    </span>
                    
                    <?php if (!empty($current_filters)): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?php echo count($current_filters); ?> filtro<?php echo count($current_filters) > 1 ? 's' : ''; ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Toggle Filtros Mobile -->
                <button type="button" 
                        class="lg:hidden inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        onclick="toggleFilters()"
                        aria-expanded="false"
                        aria-controls="filters-content">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros
                </button>
            </div>
            
            <!-- Ordenação Rápida -->
            <div class="flex items-center gap-3">
                <label for="quick-sort" class="text-sm text-gray-600 hidden sm:block">Ordenar por:</label>
                <select id="quick-sort" 
                        class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="applySort(this.value)">
                    <?php foreach ($current_config['sorts'] as $sort_key => $sort_label): ?>
                        <option value="<?php echo esc_attr($sort_key); ?>" 
                                <?php selected($current_sort, $sort_key); ?>>
                            <?php echo esc_html($sort_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Toggle Ordem -->
                <button type="button" 
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                        onclick="toggleOrder()"
                        title="<?php echo $current_order === 'ASC' ? 'Ordem crescente' : 'Ordem decrescente'; ?>">
                    <?php if ($current_order === 'ASC'): ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                    <?php else: ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                        </svg>
                    <?php endif; ?>
                </button>
            </div>
        </div>
        
        <!-- Conteúdo dos Filtros -->
        <div id="filters-content" class="filters-content hidden lg:block py-4">
            <form method="GET" action="<?php echo esc_url(get_pagenum_link(1)); ?>" class="space-y-4">
                <!-- Preservar parâmetros existentes -->
                <?php if (is_category()): ?>
                    <input type="hidden" name="cat" value="<?php echo get_queried_object_id(); ?>">
                <?php elseif (is_tag()): ?>
                    <input type="hidden" name="tag" value="<?php echo get_queried_object()->slug; ?>">
                <?php elseif (is_author()): ?>
                    <input type="hidden" name="author" value="<?php echo get_queried_object_id(); ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    <!-- Busca -->
                    <div class="lg:col-span-2">
                        <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">
                            Buscar
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="search-input"
                                   name="s" 
                                   value="<?php echo esc_attr($current_search); ?>"
                                   placeholder="Digite sua busca..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtros Dinâmicos -->
                    <?php foreach ($current_config['filters'] as $filter_key => $filter_label): ?>
                        <div>
                            <label for="filter-<?php echo esc_attr($filter_key); ?>" class="block text-sm font-medium text-gray-700 mb-1">
                                <?php echo esc_html($filter_label); ?>
                            </label>
                            
                            <?php if ($filter_key === 'category' && is_category()): ?>
                                <!-- Filtro de categorias -->
                                <select id="filter-<?php echo esc_attr($filter_key); ?>" 
                                        name="<?php echo esc_attr($filter_key); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todas</option>
                                    <?php 
                                    $categories = get_categories(['hide_empty' => true]);
                                    foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" 
                                                <?php selected($current_filters[$filter_key] ?? '', $category->term_id); ?>>
                                            <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                            <?php elseif ($filter_key === 'date'): ?>
                                <!-- Filtro de data -->
                                <select id="filter-<?php echo esc_attr($filter_key); ?>" 
                                        name="<?php echo esc_attr($filter_key); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Qualquer data</option>
                                    <option value="today" <?php selected($current_filters[$filter_key] ?? '', 'today'); ?>>Hoje</option>
                                    <option value="week" <?php selected($current_filters[$filter_key] ?? '', 'week'); ?>>Esta semana</option>
                                    <option value="month" <?php selected($current_filters[$filter_key] ?? '', 'month'); ?>>Este mês</option>
                                    <option value="year" <?php selected($current_filters[$filter_key] ?? '', 'year'); ?>>Este ano</option>
                                </select>
                                
                            <?php else: ?>
                                <!-- Filtro genérico (meta fields) -->
                                <select id="filter-<?php echo esc_attr($filter_key); ?>" 
                                        name="<?php echo esc_attr($filter_key); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todos</option>
                                    <?php
                                    // Buscar valores únicos do meta field
                                    global $wpdb;
                                    $meta_values = $wpdb->get_col($wpdb->prepare(
                                        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                                         WHERE meta_key = %s AND meta_value != '' 
                                         ORDER BY meta_value ASC",
                                        $filter_key
                                    ));
                                    
                                    foreach ($meta_values as $value):
                                        if (!empty(trim($value))): ?>
                                            <option value="<?php echo esc_attr($value); ?>" 
                                                    <?php selected($current_filters[$filter_key] ?? '', $value); ?>>
                                                <?php echo esc_html($value); ?>
                                            </option>
                                        <?php endif;
                                    endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Botões de Ação -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Aplicar Filtros
                        </button>
                        
                        <?php if (!empty($current_filters) || !empty($current_search)): ?>
                            <a href="<?php echo esc_url(strtok($_SERVER['REQUEST_URI'], '?')); ?>" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Limpar Filtros
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Filtros Ativos -->
                    <?php if (!empty($current_filters)): ?>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm text-gray-600">Filtros ativos:</span>
                            <?php foreach ($current_filters as $filter_key => $filter_value): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    <?php echo esc_html($current_config['filters'][$filter_key] ?? $filter_key); ?>: 
                                    <strong><?php echo esc_html($filter_value); ?></strong>
                                    <button type="button" 
                                            onclick="removeFilter('<?php echo esc_js($filter_key); ?>')"
                                            class="ml-1 text-blue-600 hover:text-blue-800">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidades dos filtros -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filtros mobile
    window.toggleFilters = function() {
        const content = document.getElementById('filters-content');
        const button = document.querySelector('[aria-controls="filters-content"]');
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            content.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
        } else {
            content.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    };
    
    // Aplicar ordenação
    window.applySort = function(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('orderby', sortValue);
        url.searchParams.delete('paged'); // Reset pagination
        window.location.href = url.toString();
    };
    
    // Toggle ordem
    window.toggleOrder = function() {
        const url = new URL(window.location);
        const currentOrder = url.searchParams.get('order') || 'DESC';
        const newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';
        url.searchParams.set('order', newOrder);
        url.searchParams.delete('paged'); // Reset pagination
        window.location.href = url.toString();
    };
    
    // Remover filtro específico
    window.removeFilter = function(filterKey) {
        const url = new URL(window.location);
        url.searchParams.delete(filterKey);
        url.searchParams.delete('paged'); // Reset pagination
        window.location.href = url.toString();
    };
    
    // Auto-submit em mudanças de filtro (opcional)
    const filterSelects = document.querySelectorAll('#filters-content select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Opcional: auto-submit quando filtro muda
            // this.form.submit();
        });
    });
    
    // Busca em tempo real (debounced)
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Opcional: busca automática após 500ms
                // this.form.submit();
            }, 500);
        });
    }
    
    // Analytics tracking
    document.querySelectorAll('#filters-content button, #filters-content select').forEach(element => {
        element.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'filter_interaction', {
                    'filter_type': this.name || this.id,
                    'filter_value': this.value || this.textContent,
                    'page_location': window.location.href
                });
            }
        });
    });
});
</script>

<!-- CSS adicional para filtros -->
<style>
.filters-bar {
    backdrop-filter: blur(8px);
    background-color: rgba(255, 255, 255, 0.95);
}

.filters-content {
    transition: all 0.3s ease;
}

.filters-content.hidden {
    max-height: 0;
    overflow: hidden;
    padding-top: 0;
    padding-bottom: 0;
}

@media (max-width: 1023px) {
    .filters-content:not(.hidden) {
        animation: slideDown 0.3s ease;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Sticky behavior */
.filters-bar.sticky {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Focus states */
.filters-bar select:focus,
.filters-bar input:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    border-color: transparent;
}
</style> 