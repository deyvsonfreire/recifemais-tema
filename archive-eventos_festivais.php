<?php
/**
 * Archive Template: Eventos e Festivais
 * Inspirado no Agenda Viva SP - Layout moderno e funcional
 * 
 * Utiliza todos os meta fields do plugin RecifeMais Core V2:
 * - evento_data_inicio, evento_data_fim
 * - evento_horario_inicio, evento_horario_fim  
 * - evento_preco, evento_local, evento_organizador
 * - evento_atracoes, evento_link_inscricao, evento_contato
 * - evento_tipos, evento_publico_alvo
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Configurações da página
$total_eventos = wp_count_posts('eventos_festivais')->publish;
$eventos_hoje = new WP_Query([
    'post_type' => 'eventos_festivais',
    'meta_query' => [
        [
            'key' => 'evento_data_inicio',
            'value' => date('Y-m-d'),
            'compare' => '='
        ]
    ],
    'posts_per_page' => 1
]);
$eventos_hoje_count = $eventos_hoje->found_posts;
wp_reset_postdata();

// Filtros ativos
$filtro_data = isset($_GET['data_filter']) ? sanitize_text_field($_GET['data_filter']) : '';
$filtro_tipo = isset($_GET['tipo']) ? sanitize_text_field($_GET['tipo']) : '';
$filtro_bairro = isset($_GET['bairro']) ? sanitize_text_field($_GET['bairro']) : '';
$filtro_preco = isset($_GET['preco']) ? sanitize_text_field($_GET['preco']) : '';
$busca = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

?>

<main class="archive-eventos bg-gray-50 min-h-screen">
    
    <!-- Header Principal (Inspirado Agenda Viva SP) -->
    <section class="hero-eventos bg-gradient-to-br from-red-600 via-red-700 to-red-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <div class="container mx-auto px-4 py-16 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                
                <!-- Breadcrumbs -->
                <nav class="mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center justify-center gap-2 text-sm text-red-100">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                Início
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <span class="text-white font-medium">Agenda Cultural</span>
                        </li>
                    </ol>
                </nav>
                
                <!-- Título Principal -->
                <div class="mb-8">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-4">
                        Agenda Cultural
                        <span class="block text-2xl lg:text-3xl font-normal text-red-100 mt-2">
                            Recife & Pernambuco
                        </span>
                    </h1>
                    <p class="text-xl text-red-100 max-w-2xl mx-auto leading-relaxed">
                        Descubra os melhores eventos, festivais e apresentações culturais de Pernambuco. 
                        Sua agenda cultural completa em um só lugar.
                    </p>
                </div>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold"><?php echo number_format_i18n($total_eventos); ?></div>
                        <div class="text-sm text-red-100">Eventos Cadastrados</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold"><?php echo number_format_i18n($eventos_hoje_count); ?></div>
                        <div class="text-sm text-red-100">Acontecem Hoje</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold">
                            <?php echo number_format_i18n(wp_count_terms('tipos_eventos')); ?>
                        </div>
                        <div class="text-sm text-red-100">Categorias</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold">
                            <?php echo number_format_i18n(wp_count_terms('bairros_recife')); ?>
                        </div>
                        <div class="text-sm text-red-100">Bairros</div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Barra de Busca e Filtros (Estilo Agenda Viva SP) -->
    <section class="filtros-eventos bg-white shadow-lg border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" class="max-w-6xl mx-auto">
                
                <!-- Busca Principal -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               name="s" 
                               value="<?php echo esc_attr($busca); ?>"
                               placeholder="Buscar eventos por nome, artista, local..."
                               class="w-full px-6 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 pl-14">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <?php echo recifemais_get_icon_svg('search', '24', '#6b7280'); ?>
                        </div>
                        <button type="submit" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            Buscar
                        </button>
                    </div>
                </div>
                
                <!-- Filtros Rápidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    <!-- Filtro de Data -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quando</label>
                        <select name="data_filter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Todas as datas</option>
                            <option value="hoje" <?php selected($filtro_data, 'hoje'); ?>>Hoje</option>
                            <option value="amanha" <?php selected($filtro_data, 'amanha'); ?>>Amanhã</option>
                            <option value="fim_semana" <?php selected($filtro_data, 'fim_semana'); ?>>Fim de semana</option>
                            <option value="proxima_semana" <?php selected($filtro_data, 'proxima_semana'); ?>>Próxima semana</option>
                            <option value="proximo_mes" <?php selected($filtro_data, 'proximo_mes'); ?>>Próximo mês</option>
                        </select>
                    </div>
                    
                    <!-- Filtro de Tipo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                        <select name="tipo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Todas as categorias</option>
                            <?php
                            $tipos_eventos = get_terms([
                                'taxonomy' => 'tipos_eventos',
                                'hide_empty' => true,
                                'orderby' => 'count',
                                'order' => 'DESC'
                            ]);
                            foreach ($tipos_eventos as $tipo) :
                            ?>
                                <option value="<?php echo esc_attr($tipo->slug); ?>" <?php selected($filtro_tipo, $tipo->slug); ?>>
                                    <?php echo esc_html($tipo->name); ?> (<?php echo $tipo->count; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtro de Bairro -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bairro</label>
                        <select name="bairro" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Todos os bairros</option>
                            <?php
                            $bairros = get_terms([
                                'taxonomy' => 'bairros_recife',
                                'hide_empty' => true,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ]);
                            foreach ($bairros as $bairro) :
                            ?>
                                <option value="<?php echo esc_attr($bairro->slug); ?>" <?php selected($filtro_bairro, $bairro->slug); ?>>
                                    <?php echo esc_html($bairro->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtro de Preço -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Entrada</label>
                        <select name="preco" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Todos os preços</option>
                            <option value="gratuito" <?php selected($filtro_preco, 'gratuito'); ?>>Gratuito</option>
                            <option value="pago" <?php selected($filtro_preco, 'pago'); ?>>Pago</option>
                        </select>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="flex flex-col gap-2">
                        <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            Filtrar
                        </button>
                        <?php if ($filtro_data || $filtro_tipo || $filtro_bairro || $filtro_preco || $busca) : ?>
                            <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                               class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-center">
                                Limpar
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>
                
            </form>
        </div>
    </section>
    
    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            
            <?php if (have_posts()) : ?>
                
                <!-- Filtros Ativos -->
                <?php if ($filtro_data || $filtro_tipo || $filtro_bairro || $filtro_preco || $busca) : ?>
                    <div class="mb-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-blue-900 mb-2">Filtros ativos:</h3>
                                    <div class="flex flex-wrap gap-2">
                                        <?php if ($busca) : ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                Busca: "<?php echo esc_html($busca); ?>"
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_data) : ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                Data: <?php echo esc_html(ucfirst(str_replace('_', ' ', $filtro_data))); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_tipo) : ?>
                                            <?php $tipo_term = get_term_by('slug', $filtro_tipo, 'tipos_eventos'); ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                Categoria: <?php echo esc_html($tipo_term->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_bairro) : ?>
                                            <?php $bairro_term = get_term_by('slug', $filtro_bairro, 'bairros_recife'); ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                Bairro: <?php echo esc_html($bairro_term->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_preco) : ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                Entrada: <?php echo esc_html(ucfirst($filtro_preco)); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-sm text-blue-700">
                                    <?php echo $wp_query->found_posts; ?> evento(s) encontrado(s)
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Grid de Eventos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <?php
                        // Usar o component card-evento existente
                        get_template_part('components/cards/card-evento', null, [
                            'post_id' => get_the_ID(),
                            'variant' => 'standard',
                            'size' => 'lg',
                            'show_meta' => true,
                            'show_badge' => true,
                            'show_excerpt' => true,
                            'classes' => ['evento-card-archive']
                        ]);
                        ?>
                        
                    <?php endwhile; ?>
                </div>
                
                <!-- Paginação -->
                <div class="flex justify-center">
                    <?php
                    the_posts_pagination([
                        'mid_size' => 2,
                        'prev_text' => '← Anterior',
                        'next_text' => 'Próxima →',
                        'before_page_number' => '<span class="sr-only">Página </span>',
                        'class' => 'pagination-eventos'
                    ]);
                    ?>
                </div>
                
            <?php else : ?>
                
                <!-- Estado Vazio -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                        <?php echo recifemais_get_icon_svg('calendar', '64', '#9ca3af'); ?>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Nenhum evento encontrado
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                        <?php if ($busca || $filtro_data || $filtro_tipo || $filtro_bairro || $filtro_preco) : ?>
                            Não encontramos eventos que correspondam aos seus critérios de busca. 
                            Tente ajustar os filtros ou fazer uma nova busca.
                        <?php else : ?>
                            Ainda não temos eventos cadastrados. Volte em breve para conferir nossa agenda cultural!
                        <?php endif; ?>
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <?php if ($busca || $filtro_data || $filtro_tipo || $filtro_bairro || $filtro_preco) : ?>
                            <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                               class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                Ver todos os eventos
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_post_type_archive_link('lugares'); ?>" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            Explorar lugares
                        </a>
                        
                        <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                            Conhecer artistas
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
    
    <!-- Seção de Categorias (Inspirado Agenda Viva SP) -->
    <section class="categorias-eventos bg-white py-16 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Eventos por categoria
                    </h2>
                    <p class="text-xl text-gray-600">
                        Explore nossa agenda cultural por tipo de evento
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php
                    $categorias_eventos = get_terms([
                        'taxonomy' => 'tipos_eventos',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 8
                    ]);
                    
                    $categoria_icons = [
                        'show' => 'music',
                        'teatro' => 'theater',
                        'festival' => 'star',
                        'exposicao' => 'image',
                        'workshop' => 'users',
                        'palestra' => 'mic',
                        'danca' => 'heart',
                        'cinema' => 'film'
                    ];
                    
                    foreach ($categorias_eventos as $categoria) :
                        $icon = $categoria_icons[$categoria->slug] ?? 'calendar';
                        $archive_url = add_query_arg('tipo', $categoria->slug, get_post_type_archive_link('eventos_festivais'));
                    ?>
                        <a href="<?php echo esc_url($archive_url); ?>" 
                           class="group bg-gray-50 hover:bg-red-50 border border-gray-200 hover:border-red-200 rounded-xl p-6 transition-all duration-300 text-center">
                            <div class="w-16 h-16 bg-red-100 group-hover:bg-red-200 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors">
                                <?php echo recifemais_get_icon_svg($icon, '32', '#dc2626'); ?>
                            </div>
                            <h3 class="font-bold text-gray-900 group-hover:text-red-600 transition-colors mb-2">
                                <?php echo esc_html($categoria->name); ?>
                            </h3>
                            <p class="text-sm text-gray-600">
                                <?php echo number_format_i18n($categoria->count); ?> evento(s)
                            </p>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                       class="inline-flex items-center gap-2 text-red-600 font-semibold hover:text-red-700 transition-colors">
                        Ver todas as categorias
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
                
            </div>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>