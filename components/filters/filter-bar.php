<?php
/**
 * Componente: Barra de Filtros
 * 
 * Sistema de filtros inteligente para CPTs do RecifeMais
 * Baseado no Design System RecifeMais
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Parâmetros aceitos:
 * 
 * @param string $post_type      Tipo de post para filtrar
 * @param array  $taxonomies     Taxonomias a incluir nos filtros
 * @param bool   $show_search    Exibir campo de busca
 * @param bool   $show_sort      Exibir opções de ordenação
 * @param bool   $show_view      Exibir opções de visualização (grid/list)
 * @param string $current_view   Visualização atual ('grid', 'list')
 * @param array  $classes        Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'post_type' => get_post_type() ?: 'post',
    'taxonomies' => [],
    'show_search' => true,
    'show_sort' => true,
    'show_view' => true,
    'current_view' => 'grid',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Auto-detectar taxonomias se não especificadas
if (empty($taxonomies)) {
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $taxonomies = array_filter($taxonomies, function($tax) {
        return $tax->public && $tax->show_ui;
    });
}

// Configurações específicas por CPT
$cpt_configs = [
    'eventos_festivais' => [
        'taxonomies' => ['tipos_eventos', 'bairros_recife'],
        'meta_filters' => ['evento_gratuito', 'evento_data_inicio'],
        'sort_options' => [
            'date_asc' => 'Data (mais próximo)',
            'date_desc' => 'Data (mais distante)',
            'title_asc' => 'Nome (A-Z)',
            'title_desc' => 'Nome (Z-A)'
        ]
    ],
    'lugares' => [
        'taxonomies' => ['tipos_lugares', 'bairros_recife'],
        'meta_filters' => ['lugar_faixa_preco'],
        'sort_options' => [
            'title_asc' => 'Nome (A-Z)',
            'title_desc' => 'Nome (Z-A)',
            'rating_desc' => 'Melhor avaliados'
        ]
    ],
    'artistas' => [
        'taxonomies' => ['generos_musicais', 'bairros_recife'],
        'meta_filters' => [],
        'sort_options' => [
            'title_asc' => 'Nome (A-Z)',
            'title_desc' => 'Nome (Z-A)',
            'date_desc' => 'Mais recentes'
        ]
    ]
];

$config = $cpt_configs[$post_type] ?? [];
$current_url = home_url(add_query_arg(null, null));

// Classes CSS
$filter_classes = ['filter-bar', 'bg-white', 'border-b', 'border-recife-gray-200', 'py-4'];
$filter_classes = array_merge($filter_classes, $classes);

?>

<div class="<?php echo esc_attr(implode(' ', $filter_classes)); ?>">
    <div class="container mx-auto px-4">
        <form method="GET" action="<?php echo esc_url($current_url); ?>" class="filter-form">
            
            <!-- Preservar query vars existentes -->
            <?php
            $preserve_vars = ['post_type', 'paged'];
            foreach ($preserve_vars as $var) {
                $value = get_query_var($var);
                if ($value) {
                    echo '<input type="hidden" name="' . esc_attr($var) . '" value="' . esc_attr($value) . '">';
                }
            }
            ?>
            
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                
                <!-- Filtros principais -->
                <div class="flex flex-col sm:flex-row gap-3 flex-1">
                    
                    <?php if ($show_search): ?>
                        <!-- Campo de busca -->
                        <div class="relative flex-1 max-w-md">
                            <input type="search" 
                                   name="s" 
                                   value="<?php echo esc_attr(get_search_query()); ?>"
                                   placeholder="Buscar..."
                                   class="w-full pl-10 pr-4 py-2 border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-recife-primary focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-recife-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Filtros por taxonomia -->
                    <?php foreach ($taxonomies as $taxonomy_slug => $taxonomy): ?>
                        <?php
                        if (is_object($taxonomy)) {
                            $taxonomy_slug = $taxonomy->name;
                            $taxonomy_name = $taxonomy->labels->name;
                        } else {
                            $taxonomy_name = $taxonomy;
                        }
                        
                        $terms = get_terms([
                            'taxonomy' => $taxonomy_slug,
                            'hide_empty' => true,
                            'number' => 20
                        ]);
                        
                        if (empty($terms) || is_wp_error($terms)) continue;
                        
                        $current_term = get_query_var($taxonomy_slug);
                        ?>
                        <div class="relative">
                            <select name="<?php echo esc_attr($taxonomy_slug); ?>" 
                                    class="appearance-none bg-white border border-recife-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-recife-primary focus:border-transparent">
                                <option value="">Todos os <?php echo esc_html($taxonomy_name); ?></option>
                                <?php foreach ($terms as $term): ?>
                                    <option value="<?php echo esc_attr($term->slug); ?>" 
                                            <?php selected($current_term, $term->slug); ?>>
                                        <?php echo esc_html($term->name); ?> (<?php echo esc_html($term->count); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-recife-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Filtros específicos por meta fields -->
                    <?php if (!empty($config['meta_filters'])): ?>
                        <?php foreach ($config['meta_filters'] as $meta_key): ?>
                            <?php if ($meta_key === 'evento_gratuito'): ?>
                                <!-- Filtro de eventos gratuitos -->
                                <div class="flex items-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" 
                                               name="evento_gratuito" 
                                               value="1"
                                               <?php checked(get_query_var('evento_gratuito'), '1'); ?>
                                               class="rounded border-recife-gray-300 text-recife-primary focus:ring-recife-primary">
                                        <span class="text-sm text-recife-gray-700">Apenas gratuitos</span>
                                    </label>
                                </div>
                            
                            <?php elseif ($meta_key === 'lugar_faixa_preco'): ?>
                                <!-- Filtro de faixa de preço -->
                                <div class="relative">
                                    <select name="lugar_faixa_preco" 
                                            class="appearance-none bg-white border border-recife-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-recife-primary focus:border-transparent">
                                        <option value="">Qualquer preço</option>
                                        <option value="$" <?php selected(get_query_var('lugar_faixa_preco'), '$'); ?>>$ - Econômico</option>
                                        <option value="$$" <?php selected(get_query_var('lugar_faixa_preco'), '$$'); ?>>$$ - Moderado</option>
                                        <option value="$$$" <?php selected(get_query_var('lugar_faixa_preco'), '$$$'); ?>>$$$ - Premium</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-recife-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Botões de ação -->
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="btn btn-primary btn-sm">
                            Filtrar
                        </button>
                        
                        <a href="<?php echo esc_url(strtok($current_url, '?')); ?>" 
                           class="btn btn-outline btn-sm">
                            Limpar
                        </a>
                    </div>
                </div>
                
                <!-- Controles de visualização e ordenação -->
                <div class="flex items-center gap-3">
                    
                    <?php if ($show_sort && !empty($config['sort_options'])): ?>
                        <!-- Ordenação -->
                        <div class="relative">
                            <select name="orderby" 
                                    class="appearance-none bg-white border border-recife-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:ring-2 focus:ring-recife-primary focus:border-transparent">
                                <?php foreach ($config['sort_options'] as $value => $label): ?>
                                    <option value="<?php echo esc_attr($value); ?>" 
                                            <?php selected(get_query_var('orderby'), $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-3 h-3 text-recife-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_view): ?>
                        <!-- Visualização -->
                        <div class="flex border border-recife-gray-300 rounded-lg overflow-hidden">
                            <button type="button" 
                                    class="view-toggle p-2 <?php echo $current_view === 'grid' ? 'bg-recife-primary text-white' : 'bg-white text-recife-gray-600 hover:bg-recife-gray-50'; ?>"
                                    data-view="grid"
                                    aria-label="Visualização em grade">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button type="button" 
                                    class="view-toggle p-2 <?php echo $current_view === 'list' ? 'bg-recife-primary text-white' : 'bg-white text-recife-gray-600 hover:bg-recife-gray-50'; ?>"
                                    data-view="list"
                                    aria-label="Visualização em lista">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Filtros ativos -->
            <?php
            $active_filters = [];
            
            // Busca
            if (get_search_query()) {
                $active_filters[] = [
                    'label' => 'Busca: "' . get_search_query() . '"',
                    'remove_url' => remove_query_arg('s')
                ];
            }
            
            // Taxonomias
            foreach ($taxonomies as $taxonomy_slug => $taxonomy) {
                if (is_object($taxonomy)) {
                    $taxonomy_slug = $taxonomy->name;
                }
                
                $current_term_slug = get_query_var($taxonomy_slug);
                if ($current_term_slug) {
                    $term = get_term_by('slug', $current_term_slug, $taxonomy_slug);
                    if ($term) {
                        $active_filters[] = [
                            'label' => $term->name,
                            'remove_url' => remove_query_arg($taxonomy_slug)
                        ];
                    }
                }
            }
            
            // Meta filters
            if (get_query_var('evento_gratuito')) {
                $active_filters[] = [
                    'label' => 'Apenas gratuitos',
                    'remove_url' => remove_query_arg('evento_gratuito')
                ];
            }
            
            if (get_query_var('lugar_faixa_preco')) {
                $preco_labels = ['$' => 'Econômico', '$$' => 'Moderado', '$$$' => 'Premium'];
                $preco = get_query_var('lugar_faixa_preco');
                $active_filters[] = [
                    'label' => $preco_labels[$preco] ?? $preco,
                    'remove_url' => remove_query_arg('lugar_faixa_preco')
                ];
            }
            ?>
            
            <?php if (!empty($active_filters)): ?>
                <div class="mt-4 pt-4 border-t border-recife-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm text-recife-gray-600 font-medium">Filtros ativos:</span>
                        
                        <?php foreach ($active_filters as $filter): ?>
                            <span class="inline-flex items-center gap-1 bg-recife-primary/10 text-recife-primary px-3 py-1 rounded-full text-sm">
                                <?php echo esc_html($filter['label']); ?>
                                <a href="<?php echo esc_url($filter['remove_url']); ?>" 
                                   class="text-recife-primary hover:text-recife-primary-dark"
                                   aria-label="Remover filtro">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </span>
                        <?php endforeach; ?>
                        
                        <a href="<?php echo esc_url(strtok($current_url, '?')); ?>" 
                           class="text-sm text-recife-gray-500 hover:text-recife-primary underline">
                            Limpar todos
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit em mudanças de select
    const selects = document.querySelectorAll('.filter-form select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Toggle de visualização
    const viewToggles = document.querySelectorAll('.view-toggle');
    viewToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Atualizar URL
            const url = new URL(window.location);
            url.searchParams.set('view', view);
            window.history.pushState({}, '', url);
            
            // Trigger evento customizado para atualizar a visualização
            document.dispatchEvent(new CustomEvent('viewChanged', {
                detail: { view: view }
            }));
            
            // Atualizar botões
            viewToggles.forEach(btn => {
                btn.classList.remove('bg-recife-primary', 'text-white');
                btn.classList.add('bg-white', 'text-recife-gray-600');
            });
            
            this.classList.remove('bg-white', 'text-recife-gray-600');
            this.classList.add('bg-recife-primary', 'text-white');
        });
    });
});
</script> 