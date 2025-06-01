<?php
/**
 * Template para Single de Roteiros - RecifeMais V2
 * 
 * Template moderno para exibir roteiros turísticos:
 * - Uso dos dicionários inteligentes
 * - Meta fields específicos de roteiros
 * - Sistema de relacionamentos
 * - Busca integrada
 * - Schema markup para TouristTrip
 * - Design responsivo e clean
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) :
    the_post();
    
    $roteiro_id = get_the_ID();
    
    // Meta dados modernos do plugin v2
    $tipo_roteiro = get_post_meta($roteiro_id, 'tipo_roteiro', true);
    $duracao_estimada = get_post_meta($roteiro_id, 'duracao_estimada', true);
    $numero_paradas = get_post_meta($roteiro_id, 'numero_paradas', true);
    $meio_transporte = get_post_meta($roteiro_id, 'meio_transporte', true);
    $custo_estimado = get_post_meta($roteiro_id, 'custo_estimado', true);
    $nivel_dificuldade = get_post_meta($roteiro_id, 'nivel_dificuldade', true);
    $epoca_ideal = get_post_meta($roteiro_id, 'epoca_ideal', true);
    $publico_alvo = get_post_meta($roteiro_id, 'publico_alvo', true);
    $pontos_interesse = get_post_meta($roteiro_id, 'pontos_interesse', true);
    $dicas_importantes = get_post_meta($roteiro_id, 'dicas_importantes', true);
    $roteiro_detalhado = get_post_meta($roteiro_id, 'roteiro_detalhado', true);
    $o_que_levar = get_post_meta($roteiro_id, 'o_que_levar', true);
    $ponto_partida = get_post_meta($roteiro_id, 'ponto_partida', true);
    $ponto_chegada = get_post_meta($roteiro_id, 'ponto_chegada', true);
    
    // Taxonomias
    $bairros = get_the_terms($roteiro_id, 'bairros_recife');
    $cidades = get_the_terms($roteiro_id, 'cidades_pernambuco');
    $categorias = get_the_terms($roteiro_id, 'categorias_recifemais');
    
    // Inicializar dicionários
    $dicionarios = null;
    if (class_exists('RecifeMais_V2_Dicionarios')) {
        $dicionarios = new RecifeMais_V2_Dicionarios();
    }
    
    // Schema markup para SEO (TouristTrip)
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristTrip',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt() ?: get_the_content()),
        'url' => get_permalink(),
        'image' => get_the_post_thumbnail_url($roteiro_id, 'large'),
        'touristType' => 'Cultural Tourism'
    ];
    
    if ($duracao_estimada) {
        $schema['duration'] = $duracao_estimada;
    }
    
    if ($ponto_partida) {
        $schema['departure'] = $ponto_partida;
    }
    
    if ($ponto_chegada) {
        $schema['arrival'] = $ponto_chegada;
    }
    
    if ($custo_estimado) {
        $schema['offers'] = [
            '@type' => 'Offer',
            'price' => $custo_estimado,
            'priceCurrency' => 'BRL'
        ];
    }
?>

<!-- Schema Markup -->
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="main" class="site-main">
    
    <!-- ===== HERO DO ROTEIRO ===== -->
    <section class="relative overflow-hidden min-h-[70vh] flex items-center">
        <?php if (has_post_thumbnail()) : ?>
            <div class="absolute inset-0">
                <img src="<?php echo get_the_post_thumbnail_url($roteiro_id, 'full'); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            </div>
        <?php else : ?>
            <div class="absolute inset-0 bg-gradient-to-br from-cpt-roteiros via-blue-600 to-green-500"></div>
        <?php endif; ?>
        
        <div class="relative container mx-auto px-4 py-20">
            <div class="max-w-4xl">
                
                <!-- Breadcrumb -->
                <nav class="mb-6" aria-label="Breadcrumb">
                    <div class="flex items-center gap-2 text-white/75 text-sm font-medium">
                        <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                            <?php echo recifemais_get_icon_svg('home', '16'); ?>
                            <span class="ml-2">Início</span>
                        </a>
                        <span>→</span>
                        <a href="<?php echo get_post_type_archive_link('roteiros'); ?>" class="hover:text-white transition-colors">Roteiros</a>
                        <span>→</span>
                        <span class="text-white"><?php the_title(); ?></span>
                    </div>
                </nav>
                
                <!-- Status e Categoria -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 bg-cpt-roteiros text-white px-4 py-2 rounded-full font-medium text-sm">
                        🗺️ Roteiro
                    </span>
                    
                    <?php if ($tipo_roteiro && $dicionarios) : ?>
                        <span class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm">
                            <?php echo recifemais_get_icon_svg('route', '16'); ?>
                            <?php echo esc_html($dicionarios->get_label_by_value('tipos_roteiros', $tipo_roteiro)); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($nivel_dificuldade) : ?>
                        <span class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-full text-sm">
                            <?php echo recifemais_get_icon_svg('trending-up', '16'); ?>
                            <?php echo esc_html($nivel_dificuldade); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($publico_alvo && $dicionarios) : ?>
                        <span class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-full text-sm">
                            <?php echo recifemais_get_icon_svg('users', '16'); ?>
                            <?php echo esc_html($dicionarios->get_label_by_value('publico_alvo', $publico_alvo)); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Nome do Roteiro -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Resumo -->
                <?php if (has_excerpt()) : ?>
                    <p class="text-lg lg:text-xl text-white/90 leading-relaxed mb-8 max-w-3xl font-light">
                        <?php the_excerpt(); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Cards de Informações Principais -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                    
                    <!-- Duração -->
                    <?php if ($duracao_estimada) : ?>
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-2xl mb-2">⏱️</div>
                            <div class="text-white font-medium text-sm">
                                <?php echo esc_html($duracao_estimada); ?>
                            </div>
                            <div class="text-white/70 text-xs mt-1">
                                duração
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Paradas -->
                    <?php if ($numero_paradas) : ?>
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-2xl mb-2">📍</div>
                            <div class="text-white font-medium text-sm">
                                <?php echo esc_html($numero_paradas); ?>
                            </div>
                            <div class="text-white/70 text-xs mt-1">
                                paradas
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Transporte -->
                    <?php if ($meio_transporte) : ?>
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-2xl mb-2">🚶</div>
                            <div class="text-white font-medium text-sm truncate">
                                <?php echo esc_html(wp_trim_words($meio_transporte, 2, '...')); ?>
                            </div>
                            <div class="text-white/70 text-xs mt-1">
                                transporte
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Custo -->
                    <?php if ($custo_estimado) : ?>
                        <div class="bg-white/15 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-2xl mb-2">💰</div>
                            <div class="text-white font-medium text-sm">
                                <?php echo (strtolower($custo_estimado) === 'gratuito' || strtolower($custo_estimado) === 'grátis') ? 'Gratuito' : esc_html($custo_estimado); ?>
                            </div>
                            <div class="text-white/70 text-xs mt-1">
                                custo
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            class="btn bg-white text-cpt-roteiros hover:bg-gray-100 font-medium inline-flex items-center justify-center gap-2"
                            onclick="window.print()">
                        <?php echo recifemais_get_icon_svg('printer', '20'); ?>
                        <span>Imprimir Roteiro</span>
                    </button>
                    
                    <button type="button" 
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-roteiros inline-flex items-center justify-center gap-2"
                            onclick="downloadPDF()">
                        <?php echo recifemais_get_icon_svg('download', '20'); ?>
                        <span>Baixar PDF</span>
                    </button>
                    
                    <button type="button" 
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-roteiros inline-flex items-center justify-center gap-2"
                            onclick="addToFavorites(<?php echo $roteiro_id; ?>)">
                        <?php echo recifemais_get_icon_svg('heart', '20'); ?>
                        <span>Favoritar</span>
                    </button>
                    
                    <button type="button" 
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-roteiros inline-flex items-center justify-center gap-2"
                            onclick="shareRoteiro()">
                        <?php echo recifemais_get_icon_svg('share', '20'); ?>
                        <span>Compartilhar</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Descrição do Roteiro -->
                <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('file-text', '28', '#667eea'); ?>
                            Sobre o Roteiro
                        </h2>
                        
                        <div class="prose prose-lg max-w-none prose-gray text-gray-700 leading-relaxed">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>

                <!-- Roteiro Detalhado -->
                <?php if ($roteiro_detalhado) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('map', '28', '#10b981'); ?>
                                Itinerário Detalhado
                            </h2>
                            
                            <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                                <div class="prose max-w-none text-green-800">
                                    <?php echo wp_kses_post(wpautop($roteiro_detalhado)); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Pontos de Interesse -->
                <?php if ($pontos_interesse) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('star', '28', '#f59e0b'); ?>
                                Pontos de Interesse
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <?php 
                                $pontos_array = explode(',', $pontos_interesse);
                                foreach ($pontos_array as $ponto) : 
                                    $ponto = trim($ponto);
                                    if ($ponto) :
                                ?>
                                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                                ⭐
                                            </div>
                                            <span class="font-medium text-amber-900"><?php echo esc_html($ponto); ?></span>
                                        </div>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Pontos de Partida e Chegada -->
                <?php if ($ponto_partida || $ponto_chegada) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('navigation', '28', '#8b5cf6'); ?>
                                Trajeto
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <?php if ($ponto_partida) : ?>
                                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-100">
                                        <h3 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                                            <?php echo recifemais_get_icon_svg('play-circle', '20', '#7c3aed'); ?>
                                            Ponto de Partida
                                        </h3>
                                        <p class="text-purple-800"><?php echo esc_html($ponto_partida); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($ponto_chegada) : ?>
                                    <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
                                        <h3 class="font-bold text-indigo-900 mb-3 flex items-center gap-2">
                                            <?php echo recifemais_get_icon_svg('stop-circle', '20', '#4f46e5'); ?>
                                            Ponto de Chegada
                                        </h3>
                                        <p class="text-indigo-800"><?php echo esc_html($ponto_chegada); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Dicas Importantes -->
                <?php if ($dicas_importantes) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('lightbulb', '28', '#ef4444'); ?>
                                Dicas Importantes
                            </h2>
                            
                            <div class="bg-red-50 rounded-xl p-6 border border-red-100">
                                <div class="prose max-w-none text-red-800">
                                    <?php echo wp_kses_post(wpautop($dicas_importantes)); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- O que Levar -->
                <?php if ($o_que_levar) : ?>
                    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('package', '28', '#06b6d4'); ?>
                                O que Levar
                            </h2>
                            
                            <div class="bg-cyan-50 rounded-xl p-6 border border-cyan-100">
                                <div class="prose max-w-none text-cyan-800">
                                    <?php echo wp_kses_post(wpautop($o_que_levar)); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Roteiros Relacionados -->
                <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('compass', '28', '#f472b6'); ?>
                            Outros Roteiros
                        </h2>
                        
                        <?php
                        // Busca inteligente por roteiros similares
                        $roteiros_relacionados_args = [
                            'post_type' => 'roteiros',
                            'posts_per_page' => 3,
                            'post__not_in' => [$roteiro_id],
                            'meta_query' => ['relation' => 'OR']
                        ];
                        
                        // Filtros baseados em meta fields
                        if ($tipo_roteiro) {
                            $roteiros_relacionados_args['meta_query'][] = [
                                'key' => 'tipo_roteiro',
                                'value' => $tipo_roteiro,
                                'compare' => '='
                            ];
                        }
                        
                        if ($publico_alvo) {
                            $roteiros_relacionados_args['meta_query'][] = [
                                'key' => 'publico_alvo',
                                'value' => $publico_alvo,
                                'compare' => '='
                            ];
                        }
                        
                        // Filtro por localização
                        if ($bairros && !is_wp_error($bairros)) {
                            $roteiros_relacionados_args['tax_query'] = [
                                [
                                    'taxonomy' => 'bairros_recife',
                                    'field' => 'term_id',
                                    'terms' => wp_list_pluck($bairros, 'term_id')
                                ]
                            ];
                        }
                        
                        $roteiros_relacionados = new WP_Query($roteiros_relacionados_args);
                        
                        if ($roteiros_relacionados->have_posts()) :
                            ?>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php while ($roteiros_relacionados->have_posts()) : $roteiros_relacionados->the_post(); 
                                    $duracao_rel = get_post_meta(get_the_ID(), 'duracao_estimada', true);
                                    $tipo_rel = get_post_meta(get_the_ID(), 'tipo_roteiro', true);
                                    $custo_rel = get_post_meta(get_the_ID(), 'custo_estimado', true);
                                    ?>
                                    <article class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                                 alt="<?php the_title_attribute(); ?>"
                                                 class="w-full h-32 object-cover rounded-lg mb-3">
                                        <?php endif; ?>
                                        
                                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-cpt-roteiros transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <?php if ($duracao_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('clock', '14'); ?>
                                                    <span><?php echo esc_html($duracao_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($tipo_rel && $dicionarios) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('route', '14'); ?>
                                                    <span class="truncate"><?php echo esc_html($dicionarios->get_label_by_value('tipos_roteiros', $tipo_rel)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($custo_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('dollar-sign', '14'); ?>
                                                    <span><?php echo (strtolower($custo_rel) === 'gratuito' || strtolower($custo_rel) === 'grátis') ? 'Gratuito' : esc_html($custo_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p class="text-gray-500 text-center py-8">
                                Nenhum roteiro relacionado encontrado no momento.
                            </p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="space-y-8">
                
                <!-- Informações Práticas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-cpt-roteiros to-blue-600 text-white p-6">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('info', '24'); ?>
                            Informações do Roteiro
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <?php if ($duracao_estimada) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('clock', '24', '#3b82f6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?php echo esc_html($duracao_estimada); ?>
                                    </div>
                                    <div class="text-sm text-gray-600">Duração estimada</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($numero_paradas) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('map-pin', '24', '#10b981'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?php echo esc_html($numero_paradas); ?> paradas
                                    </div>
                                    <div class="text-sm text-gray-600">Pontos do roteiro</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($custo_estimado) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('dollar-sign', '24', '#f59e0b'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?php echo (strtolower($custo_estimado) === 'gratuito' || strtolower($custo_estimado) === 'grátis') ? 'Gratuito' : esc_html($custo_estimado); ?>
                                    </div>
                                    <div class="text-sm text-gray-600">Custo estimado</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($meio_transporte) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('truck', '24', '#8b5cf6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?php echo esc_html($meio_transporte); ?>
                                    </div>
                                    <div class="text-sm text-gray-600">Transporte recomendado</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($epoca_ideal) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('sun', '24', '#f97316'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?php echo esc_html($epoca_ideal); ?>
                                    </div>
                                    <div class="text-sm text-gray-600">Melhor época</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Busca Relacionada -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl border border-blue-200 p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('search', '20', '#1e40af'); ?>
                        Buscar Roteiros Similares
                    </h3>
                    
                    <div class="space-y-3">
                        <?php if ($tipo_roteiro && $dicionarios) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=roteiros&tipo_roteiro=' . urlencode($tipo_roteiro)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200">
                                <strong>Tipo:</strong> <?php echo esc_html($dicionarios->get_label_by_value('tipos_roteiros', $tipo_roteiro)); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($nivel_dificuldade) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=roteiros&nivel_dificuldade=' . urlencode($nivel_dificuldade)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200">
                                <strong>Dificuldade:</strong> <?php echo esc_html($nivel_dificuldade); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($publico_alvo && $dicionarios) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=roteiros&publico_alvo=' . urlencode($publico_alvo)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200">
                                <strong>Público:</strong> <?php echo esc_html($dicionarios->get_label_by_value('publico_alvo', $publico_alvo)); ?>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo home_url('/busca'); ?>" 
                           class="block w-full text-center p-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                            Busca Avançada
                        </a>
                    </div>
                </div>

                <!-- Ações do Roteiro -->
                <div class="bg-gradient-to-br from-cpt-roteiros to-blue-600 text-white rounded-2xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-center">Ações do Roteiro</h3>
                    <p class="text-white/90 mb-6 text-center text-sm">
                        Organize sua visita a Pernambuco
                    </p>
                    
                    <div class="space-y-3">
                        <button onclick="window.print()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                            <?php echo recifemais_get_icon_svg('printer', '20'); ?>
                            <span>Imprimir Roteiro</span>
                        </button>
                        
                        <button onclick="downloadPDF()" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                            <?php echo recifemais_get_icon_svg('download', '20'); ?>
                            <span>Baixar PDF</span>
                        </button>
                        
                        <button onclick="addToFavorites(<?php echo $roteiro_id; ?>)" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white/20 rounded-lg hover:bg-white/30 transition-colors">
                            <?php echo recifemais_get_icon_svg('heart', '20'); ?>
                            <span>Favoritar</span>
                        </button>
                    </div>
                </div>

                <!-- Compartilhar -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-600 text-white rounded-2xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-center">Compartilhe este Roteiro</h3>
                    <p class="text-white/90 mb-6 text-center text-sm">
                        Ajude outros a descobrir Pernambuco
                    </p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="shareWhatsApp()" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg font-medium transition-colors">
                            WhatsApp
                        </button>
                        <button onclick="shareFacebook()" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-medium transition-colors">
                            Facebook
                        </button>
                        <button onclick="shareTwitter()" class="bg-sky-500 hover:bg-sky-600 text-white p-3 rounded-lg font-medium transition-colors">
                            Twitter
                        </button>
                        <button onclick="copyLink()" class="bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-lg font-medium transition-colors">
                            Copiar Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Funções JavaScript para interatividade
function shareRoteiro() {
    if (navigator.share) {
        navigator.share({
            title: <?php echo json_encode(get_the_title()); ?>,
            text: <?php echo json_encode(wp_strip_all_tags(get_the_excerpt())); ?>,
            url: window.location.href
        });
    } else {
        copyLink();
    }
}

function downloadPDF() {
    // Implementação futura para gerar PDF do roteiro
    window.print();
}

function addToFavorites(roteiroId) {
    const key = 'roteiro_favorito_' + roteiroId;
    if (localStorage.getItem(key)) {
        localStorage.removeItem(key);
        alert('Roteiro removido dos favoritos');
    } else {
        localStorage.setItem(key, JSON.stringify({
            title: <?php echo json_encode(get_the_title()); ?>,
            url: window.location.href,
            saved_at: new Date().toISOString()
        }));
        alert('Roteiro adicionado aos favoritos!');
    }
}

function shareWhatsApp() {
    const text = encodeURIComponent(`${<?php echo json_encode(get_the_title()); ?>} - Roteiro Turístico em Pernambuco - ${window.location.href}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareTwitter() {
    const text = encodeURIComponent(<?php echo json_encode(get_the_title()); ?>);
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}&hashtags=RecifeMais,RoteiroPE`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copiado para a área de transferência!');
    });
}
</script>

<style>
/* Design Clean e Harmonioso */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose-gray {
    --tw-prose-body: rgb(75 85 99);
    --tw-prose-headings: rgb(31 41 55);
    --tw-prose-links: #667eea;
    --tw-prose-bold: rgb(31 41 55);
    --tw-prose-counters: rgb(107 114 128);
    --tw-prose-bullets: rgb(209 213 219);
}

.btn {
    @apply px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-sm;
}

/* Tipografia elegante */
h1 { 
    font-weight: 700; 
    letter-spacing: -0.025em; 
}

h2 { 
    font-weight: 600; 
    letter-spacing: -0.015em; 
}

h3 { 
    font-weight: 600; 
}

/* Cores suaves e linhas finas */
.border {
    border-width: 1px;
}

.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Responsividade móvel aprimorada */
@media (max-width: 640px) {
    .grid.grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    
    .text-3xl.md\\:text-4xl.lg\\:text-5xl {
        font-size: 1.875rem;
        line-height: 1.2;
    }
    
    .text-lg.lg\\:text-xl {
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .p-8 {
        padding: 1.5rem;
    }
    
    .space-y-12 > * + * {
        margin-top: 2rem;
    }
}

/* Efeitos suaves de hover */
.hover\\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.transition-shadow {
    transition: box-shadow 0.3s ease;
}

.transition-colors {
    transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

/* Gradientes suaves */
.bg-gradient-to-br {
    background: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.bg-gradient-to-r {
    background: linear-gradient(to right, var(--tw-gradient-stops));
}

.bg-gradient-to-t {
    background: linear-gradient(to top, var(--tw-gradient-stops));
}

/* Backdrop blur suave */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .bg-gradient-to-r,
    .bg-gradient-to-t {
        background: white !important;
        color: black !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .shadow-sm,
    .shadow-lg {
        box-shadow: none !important;
    }
    
    button {
        display: none !important;
    }
}
</style>

<?php endwhile;
get_footer();
?>