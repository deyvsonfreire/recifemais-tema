<?php
/**
 * Componente de Filtros por Meta Fields
 * Interface frontend para filtrar CPTs usando os meta fields configurados
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 * 
 * @param string $post_type - Tipo de post para filtrar
 * @param array $filters_config - Configuração dos filtros a exibir
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Parâmetros padrão
$post_type = isset($post_type) ? $post_type : get_post_type();
$filters_config = isset($filters_config) ? $filters_config : array();

// Se não há configuração, carrega os filtros padrão baseados no post type
if (empty($filters_config)) {
    $default_configs = array(
        'eventos_festivais' => array(
            'tipo_evento' => array(
                'label' => 'Tipo de Evento',
                'meta_key' => '_tipo_evento',
                'type' => 'select',
                'icon' => '🎪'
            ),
            'publico_alvo' => array(
                'label' => 'Público-Alvo',
                'meta_key' => '_publico_alvo',
                'type' => 'checkbox',
                'icon' => '👥'
            ),
            'faixa_preco' => array(
                'label' => 'Faixa de Preço',
                'meta_key' => '_faixa_preco',
                'type' => 'select',
                'icon' => '💰'
            )
        ),
        'lugares' => array(
            'tipo_lugar' => array(
                'label' => 'Tipo de Lugar',
                'meta_key' => '_tipo_lugar',
                'type' => 'select',
                'icon' => '📍'
            ),
            'especialidades' => array(
                'label' => 'Especialidades',
                'meta_key' => '_especialidades',
                'type' => 'checkbox',
                'icon' => '🍽️'
            ),
            'faixa_preco' => array(
                'label' => 'Faixa de Preço',
                'meta_key' => '_faixa_preco',
                'type' => 'select',
                'icon' => '💰'
            )
        ),
        'artistas' => array(
            'manifestacoes' => array(
                'label' => 'Manifestações',
                'meta_key' => '_manifestacoes',
                'type' => 'checkbox',
                'icon' => '🎭'
            ),
            'tipo_artista' => array(
                'label' => 'Tipo de Artista',
                'meta_key' => '_tipo_artista',
                'type' => 'select',
                'icon' => '🎨'
            )
        ),
        'historias' => array(
            'tipo_historia' => array(
                'label' => 'Tipo de História',
                'meta_key' => '_tipo_historia',
                'type' => 'select',
                'icon' => '📚'
            ),
            'periodo' => array(
                'label' => 'Período',
                'meta_key' => '_periodo',
                'type' => 'select',
                'icon' => '⏰'
            )
        ),
        'guias_tematicos' => array(
            'tema_guia' => array(
                'label' => 'Tema do Guia',
                'meta_key' => '_tema_guia',
                'type' => 'select',
                'icon' => '🗺️'
            ),
            'dificuldade' => array(
                'label' => 'Dificuldade',
                'meta_key' => '_dificuldade',
                'type' => 'select',
                'icon' => '⭐'
            ),
            'duracao' => array(
                'label' => 'Duração',
                'meta_key' => '_duracao',
                'type' => 'select',
                'icon' => '⏱️'
            )
        ),
        'roteiros' => array(
            'tipo_roteiro' => array(
                'label' => 'Tipo de Roteiro',
                'meta_key' => '_tipo_roteiro',
                'type' => 'select',
                'icon' => '🛤️'
            ),
            'duracao' => array(
                'label' => 'Duração',
                'meta_key' => '_duracao',
                'type' => 'select',
                'icon' => '⏱️'
            )
        ),
        'organizadores' => array(
            'tipo_organizador' => array(
                'label' => 'Tipo de Organizador',
                'meta_key' => '_tipo_organizador',
                'type' => 'select',
                'icon' => '🏢'
            ),
            'especialidades_organizacao' => array(
                'label' => 'Especialidades',
                'meta_key' => '_especialidades_organizacao',
                'type' => 'checkbox',
                'icon' => '🎯'
            )
        )
    );
    
    $filters_config = isset($default_configs[$post_type]) ? $default_configs[$post_type] : array();
}

// Se ainda não há configuração, não renderiza nada
if (empty($filters_config)) {
    return;
}

// Cores por CPT
$cpt_colors = array(
    'eventos_festivais' => 'cpt-eventos',
    'lugares' => 'cpt-lugares',
    'artistas' => 'cpt-artistas',
    'historias' => 'cpt-historias',
    'guias_tematicos' => 'cpt-guias',
    'roteiros' => 'cpt-roteiros',
    'organizadores' => 'cpt-organizadores'
);

$color_class = isset($cpt_colors[$post_type]) ? $cpt_colors[$post_type] : 'recife-primary';
?>

<section class="filters-meta-fields py-6 bg-recife-gray-50 border-b border-recife-gray-200" 
         data-post-type="<?php echo esc_attr($post_type); ?>">
    <div class="container mx-auto px-4">
        
        <!-- Cabeçalho dos Filtros -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-<?php echo $color_class; ?> rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-recife-gray-900">Filtros</h3>
            </div>
            
            <!-- Contador de Resultados -->
            <div class="text-sm text-recife-gray-600">
                <span id="results-count" class="font-medium">
                    <?php
                    global $wp_query;
                    $total = $wp_query->found_posts;
                    printf(_n('%s resultado', '%s resultados', $total, 'recifemais-tema'), number_format_i18n($total));
                    ?>
                </span>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters-container">
            <form id="meta-fields-filters" class="space-y-4">
                
                <!-- Barra de Filtros Rápidos -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button type="button" 
                            class="filter-btn active px-3 py-2 bg-<?php echo $color_class; ?> text-white rounded-lg text-sm font-medium hover:opacity-90 transition-all"
                            data-filter="all">
                        Todos
                    </button>
                    
                    <!-- Filtros rápidos baseados no primeiro meta field -->
                    <?php if (!empty($filters_config)) :
                        $first_filter = array_values($filters_config)[0];
                        $quick_filters = recifemais_get_meta_field_options($first_filter['meta_key']);
                        
                        if (!empty($quick_filters)) :
                            foreach (array_slice($quick_filters, 0, 4) as $value => $label) : ?>
                                <button type="button" 
                                        class="filter-btn px-3 py-2 bg-white text-recife-gray-700 border border-recife-gray-300 rounded-lg text-sm font-medium hover:bg-recife-gray-50 transition-all"
                                        data-filter="<?php echo esc_attr($first_filter['meta_key']); ?>"
                                        data-value="<?php echo esc_attr($value); ?>">
                                    <?php echo esc_html($label); ?>
                                </button>
                            <?php endforeach;
                        endif;
                    endif; ?>
                </div>
                
                <!-- Filtros Avançados (Expansível) -->
                <div class="advanced-filters">
                    <button type="button" 
                            class="advanced-toggle flex items-center gap-2 text-<?php echo $color_class; ?> hover:text-<?php echo $color_class; ?>/80 font-medium mb-4">
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        Filtros Avançados
                    </button>
                    
                    <div class="advanced-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4 bg-white rounded-lg border border-recife-gray-200">
                            
                            <?php foreach ($filters_config as $filter_key => $filter) : ?>
                                <div class="filter-group">
                                    <label class="block text-sm font-medium text-recife-gray-700 mb-2">
                                        <span class="mr-2"><?php echo $filter['icon']; ?></span>
                                        <?php echo esc_html($filter['label']); ?>
                                    </label>
                                    
                                    <?php if ($filter['type'] === 'select') : ?>
                                        <!-- Select Field -->
                                        <select name="<?php echo esc_attr($filter['meta_key']); ?>" 
                                                class="w-full px-3 py-2 border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-<?php echo $color_class; ?> focus:border-transparent text-sm">
                                            <option value="">Todos</option>
                                            <?php
                                            $options = recifemais_get_meta_field_options($filter['meta_key']);
                                            foreach ($options as $value => $label) :
                                            ?>
                                                <option value="<?php echo esc_attr($value); ?>">
                                                    <?php echo esc_html($label); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                    <?php elseif ($filter['type'] === 'checkbox') : ?>
                                        <!-- Checkbox Group -->
                                        <div class="space-y-2 max-h-32 overflow-y-auto">
                                            <?php
                                            $options = recifemais_get_meta_field_options($filter['meta_key']);
                                            foreach ($options as $value => $label) :
                                            ?>
                                                <label class="flex items-center text-sm">
                                                    <input type="checkbox" 
                                                           name="<?php echo esc_attr($filter['meta_key']); ?>[]" 
                                                           value="<?php echo esc_attr($value); ?>"
                                                           class="mr-2 rounded border-recife-gray-300 text-<?php echo $color_class; ?> focus:ring-<?php echo $color_class; ?>">
                                                    <span class="text-recife-gray-700"><?php echo esc_html($label); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Filtro de Localização (se aplicável) -->
                            <?php if (in_array($post_type, ['eventos_festivais', 'lugares', 'organizadores'])) : ?>
                                <div class="filter-group">
                                    <label class="block text-sm font-medium text-recife-gray-700 mb-2">
                                        <span class="mr-2">🌍</span>
                                        Localização
                                    </label>
                                    <select name="localizacao" 
                                            class="w-full px-3 py-2 border border-recife-gray-300 rounded-lg focus:ring-2 focus:ring-<?php echo $color_class; ?> focus:border-transparent text-sm">
                                        <option value="">Todas as localizações</option>
                                        <?php
                                        $locations = get_terms(array(
                                            'taxonomy' => 'localizacao',
                                            'hide_empty' => true,
                                            'meta_query' => array(
                                                array(
                                                    'key' => 'nivel_hierarquia',
                                                    'value' => 'cidade',
                                                    'compare' => '='
                                                )
                                            )
                                        ));
                                        
                                        foreach ($locations as $location) :
                                        ?>
                                            <option value="<?php echo esc_attr($location->slug); ?>">
                                                <?php echo esc_html($location->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Ações dos Filtros -->
                        <div class="flex items-center justify-between mt-4">
                            <button type="button" 
                                    id="clear-filters" 
                                    class="px-4 py-2 text-recife-gray-600 hover:text-recife-gray-900 font-medium text-sm">
                                Limpar Filtros
                            </button>
                            
                            <div class="flex gap-2">
                                <button type="button" 
                                        id="apply-filters" 
                                        class="px-6 py-2 bg-<?php echo $color_class; ?> text-white rounded-lg font-medium hover:opacity-90 transition-all text-sm">
                                    Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Loading State -->
        <div id="filters-loading" class="hidden">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-<?php echo $color_class; ?>"></div>
                <span class="ml-3 text-recife-gray-600">Aplicando filtros...</span>
            </div>
        </div>
        
        <!-- Filtros Ativos -->
        <div id="active-filters" class="hidden mt-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-recife-gray-700">Filtros ativos:</span>
                <div id="active-filters-list" class="flex gap-2 flex-wrap"></div>
            </div>
        </div>
    </div>
</section>

<?php
/**
 * Função auxiliar para obter opções de meta fields
 * Esta função deveria estar no plugin, mas vamos incluir aqui para funcionar
 */
if (!function_exists('recifemais_get_meta_field_options')) {
    function recifemais_get_meta_field_options($meta_key) {
        // Configuração das opções baseada no Meta Fields Manager
        $options_config = array(
            '_tipo_evento' => array(
                'show' => 'Show/Apresentação',
                'festival' => 'Festival',
                'exposicao' => 'Exposição',
                'workshop' => 'Workshop/Oficina',
                'palestra' => 'Palestra/Debate',
                'lancamento' => 'Lançamento',
                'feira' => 'Feira/Mercado',
                'celebracao' => 'Celebração Religiosa'
            ),
            '_publico_alvo' => array(
                'criancas' => 'Crianças',
                'jovens' => 'Jovens',
                'adultos' => 'Adultos',
                'idosos' => 'Idosos',
                'familias' => 'Famílias',
                'profissionais' => 'Profissionais da área'
            ),
            '_faixa_preco' => array(
                'gratuito' => 'Gratuito',
                'baixo' => 'Até R$ 20',
                'medio' => 'R$ 21 a R$ 50',
                'alto' => 'R$ 51 a R$ 100',
                'premium' => 'Acima de R$ 100'
            ),
            '_tipo_lugar' => array(
                'restaurante' => 'Restaurante',
                'bar' => 'Bar/Boteco',
                'cafeteria' => 'Cafeteria',
                'museu' => 'Museu',
                'galeria' => 'Galeria',
                'teatro' => 'Teatro',
                'cinema' => 'Cinema',
                'parque' => 'Parque',
                'praca' => 'Praça',
                'centro_cultural' => 'Centro Cultural',
                'biblioteca' => 'Biblioteca',
                'mercado' => 'Mercado',
                'shopping' => 'Shopping'
            ),
            '_especialidades' => array(
                'regional' => 'Culinária Regional',
                'internacional' => 'Culinária Internacional',
                'vegetariana' => 'Vegetariana/Vegana',
                'frutos_mar' => 'Frutos do Mar',
                'churrasco' => 'Churrasco',
                'doces' => 'Doces e Sobremesas',
                'bebidas' => 'Bebidas Especiais'
            ),
            '_manifestacoes' => array(
                'musica_popular' => 'Música Popular',
                'musica_erudita' => 'Música Erudita',
                'danca_folclorica' => 'Dança Folclórica',
                'danca_contemporanea' => 'Dança Contemporânea',
                'teatro' => 'Teatro',
                'literatura' => 'Literatura',
                'artes_visuais' => 'Artes Visuais',
                'artesanato' => 'Artesanato',
                'cinema' => 'Cinema/Audiovisual'
            ),
            '_tipo_artista' => array(
                'musico' => 'Músico/Banda',
                'cantor' => 'Cantor/Cantora',
                'dançarino' => 'Dançarino/Grupo de Dança',
                'ator' => 'Ator/Atriz',
                'escritor' => 'Escritor/Poeta',
                'artista_visual' => 'Artista Visual',
                'artesao' => 'Artesão',
                'cineasta' => 'Cineasta/Diretor'
            ),
            '_tipo_historia' => array(
                'lenda' => 'Lenda/Mito',
                'historia_real' => 'História Real',
                'biografia' => 'Biografia',
                'cronica' => 'Crônica Urbana',
                'memoria' => 'Memória Oral',
                'folclore' => 'Folclore Popular'
            ),
            '_periodo' => array(
                'colonial' => 'Período Colonial',
                'imperio' => 'Período Imperial',
                'republica_velha' => 'República Velha',
                'era_vargas' => 'Era Vargas',
                'democratico' => 'Período Democrático',
                'contemporaneo' => 'Contemporâneo'
            ),
            '_tema_guia' => array(
                'historico' => 'Histórico/Patrimônial',
                'cultural' => 'Cultural/Artístico',
                'gastronomico' => 'Gastronômico',
                'religioso' => 'Religioso/Espiritual',
                'natureza' => 'Natureza/Ecológico',
                'arquitetonico' => 'Arquitetônico',
                'noturno' => 'Vida Noturna'
            ),
            '_dificuldade' => array(
                'facil' => 'Fácil',
                'moderada' => 'Moderada',
                'dificil' => 'Difícil'
            ),
            '_duracao' => array(
                'ate_2h' => 'Até 2 horas',
                '2h_4h' => '2 a 4 horas',
                '4h_6h' => '4 a 6 horas',
                'dia_inteiro' => 'Dia inteiro',
                'multiplos_dias' => 'Múltiplos dias'
            ),
            '_tipo_roteiro' => array(
                'a_pe' => 'A pé',
                'bicicleta' => 'De bicicleta',
                'carro' => 'De carro',
                'transporte_publico' => 'Transporte público',
                'barco' => 'De barco',
                'misto' => 'Misto'
            ),
            '_tipo_organizador' => array(
                'empresa_eventos' => 'Empresa de Eventos',
                'produtora_cultural' => 'Produtora Cultural',
                'ong' => 'ONG/Associação',
                'governo' => 'Órgão Governamental',
                'instituicao_ensino' => 'Instituição de Ensino',
                'centro_cultural' => 'Centro Cultural',
                'grupo_artistico' => 'Grupo Artístico'
            ),
            '_especialidades_organizacao' => array(
                'festivais' => 'Festivais',
                'shows' => 'Shows e Concertos',
                'teatro' => 'Teatro',
                'exposicoes' => 'Exposições',
                'workshops' => 'Workshops/Cursos',
                'eventos_corporativos' => 'Eventos Corporativos',
                'casamentos' => 'Casamentos/Sociais'
            )
        );
        
        return isset($options_config[$meta_key]) ? $options_config[$meta_key] : array();
    }
}
?> 