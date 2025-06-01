<?php
/**
 * Template para Single de Guias Temáticos - RecifeMais V2
 * 
 * Template informativo para guias temáticos:
 * - Layout prático e funcional
 * - Meta fields específicos de guias
 * - Roteiro detalhado
 * - Informações de planejamento
 * - Schema markup
 * - Integração com dicionários
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) :
    the_post();
    
    $guia_id = get_the_ID();
    
    // Meta dados modernos do plugin v2
    $tipo_guia = get_post_meta($guia_id, 'tipo_guia', true);
    $duracao_estimada = get_post_meta($guia_id, 'duracao_estimada', true);
    $numero_paradas = get_post_meta($guia_id, 'numero_paradas', true);
    $meio_transporte = get_post_meta($guia_id, 'meio_transporte', true);
    $custo_estimado = get_post_meta($guia_id, 'custo_estimado', true);
    $nivel_dificuldade = get_post_meta($guia_id, 'nivel_dificuldade', true);
    $epoca_ideal = get_post_meta($guia_id, 'epoca_ideal', true);
    $publico_alvo = get_post_meta($guia_id, 'publico_alvo', true);
    $pontos_interesse = get_post_meta($guia_id, 'pontos_interesse', true);
    $dicas_importantes = get_post_meta($guia_id, 'dicas_importantes', true);
    $roteiro_detalhado = get_post_meta($guia_id, 'roteiro_detalhado', true);
    $o_que_levar = get_post_meta($guia_id, 'o_que_levar', true);
    
    // Taxonomias
    $bairros = get_the_terms($guia_id, 'bairros_recife');
    $cidades = get_the_terms($guia_id, 'cidades_pernambuco');
    
    // Inicializar dicionários
    $dicionarios = null;
    if (class_exists('RecifeMais_V2_Dicionarios')) {
        $dicionarios = new RecifeMais_V2_Dicionarios();
    }
    
    // Schema markup para SEO
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristAttraction',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt() ?: get_the_content()),
        'url' => get_permalink(),
        'image' => get_the_post_thumbnail_url($guia_id, 'large'),
    ];
    
    if ($duracao_estimada) {
        $schema['estimatedDuration'] = $duracao_estimada;
    }
    
    if ($custo_estimado) {
        $schema['priceRange'] = $custo_estimado;
    }
    
    if ($bairros && !is_wp_error($bairros)) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'addressLocality' => $bairros[0]->name,
            'addressRegion' => ($cidades && !is_wp_error($cidades)) ? $cidades[0]->name : 'Pernambuco'
        ];
    }
?>

<!-- Schema Markup -->
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="main" class="site-main">
    
    <!-- ===== HERO DO GUIA ===== -->
    <section class="relative overflow-hidden min-h-[70vh] flex items-center">
        <?php if (has_post_thumbnail()) : ?>
            <div class="absolute inset-0">
                <img src="<?php echo get_the_post_thumbnail_url($guia_id, 'full'); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
            </div>
        <?php else : ?>
            <div class="absolute inset-0 bg-gradient-to-br from-cpt-roteiros via-blue-600 to-indigo-500"></div>
        <?php endif; ?>
        
        <div class="relative container mx-auto px-4 py-20">
            <div class="max-w-4xl">
                
                <!-- Breadcrumb -->
                <nav class="mb-8" aria-label="Breadcrumb">
                    <div class="flex items-center gap-2 text-white/75 text-sm">
                        <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                            <?php echo recifemais_get_icon_svg('home', '16'); ?>
                            <span class="ml-2">Início</span>
                        </a>
                        <span>→</span>
                        <a href="<?php echo get_post_type_archive_link('guias_tematicos'); ?>" class="hover:text-white transition-colors">Guias Temáticos</a>
                        <span>→</span>
                        <span class="text-white"><?php the_title(); ?></span>
                    </div>
                </nav>
                
                <!-- Status e Categoria -->
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="inline-flex items-center gap-2 bg-cpt-roteiros text-white px-4 py-2 rounded-full font-semibold">
                        🗺️ Guia Temático
                    </span>
                    
                    <?php if ($tipo_guia && $dicionarios) : ?>
                        <span class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full">
                            <?php echo recifemais_get_icon_svg('tag', '16'); ?>
                            <?php echo esc_html($dicionarios->get_label_by_value('tipos_guias', $tipo_guia)); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($nivel_dificuldade) : ?>
                        <span class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-full">
                            <?php echo recifemais_get_icon_svg('trending-up', '16'); ?>
                            <?php echo esc_html($nivel_dificuldade); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Título -->
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Resumo -->
                <?php if (has_excerpt()) : ?>
                    <p class="text-xl lg:text-2xl text-white/90 leading-relaxed mb-8 max-w-3xl">
                        <?php the_excerpt(); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Cards de Informações Principais -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    
                    <!-- Duração -->
                    <?php if ($duracao_estimada) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">⏱️</div>
                            <div class="text-white font-semibold text-sm">
                                <?php echo esc_html($duracao_estimada); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                duração
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Paradas -->
                    <?php if ($numero_paradas) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">📍</div>
                            <div class="text-white font-semibold text-sm">
                                <?php echo esc_html($numero_paradas); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                paradas
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Transporte -->
                    <?php if ($meio_transporte) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">🚌</div>
                            <div class="text-white font-semibold text-sm truncate">
                                <?php echo esc_html($meio_transporte); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                transporte
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Custo -->
                    <?php if ($custo_estimado) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">💰</div>
                            <div class="text-white font-semibold text-sm">
                                <?php echo esc_html($custo_estimado); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                custo
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="button" 
                            class="btn bg-white text-cpt-roteiros hover:bg-recife-gray-100 font-bold inline-flex items-center justify-center gap-2"
                            onclick="window.print()">
                        <?php echo recifemais_get_icon_svg('printer', '20'); ?>
                        <span>Imprimir Guia</span>
                    </button>
                    
                    <button type="button" 
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-roteiros inline-flex items-center justify-center gap-2"
                            onclick="shareGuia()">
                        <?php echo recifemais_get_icon_svg('share', '20'); ?>
                        <span>Compartilhar</span>
                    </button>
                    
                    <button type="button"
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-roteiros inline-flex items-center justify-center gap-2"
                            onclick="downloadPDF()">
                        <?php echo recifemais_get_icon_svg('download', '20'); ?>
                        <span>Baixar PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Descrição do Guia -->
                <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('file-text', '32', '#667eea'); ?>
                            Sobre este Guia
                        </h2>
                        
                        <div class="prose prose-lg max-w-none prose-blue">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>

                <!-- Roteiro Detalhado -->
                <?php if ($roteiro_detalhado) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('map', '32', '#10b981'); ?>
                                Roteiro Detalhado
                            </h2>
                            
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post(wpautop($roteiro_detalhado)); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Pontos de Interesse -->
                <?php if ($pontos_interesse) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('star', '32', '#f59e0b'); ?>
                                Pontos de Interesse
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <?php 
                                $pontos_array = explode(',', $pontos_interesse);
                                foreach ($pontos_array as $ponto) : 
                                    $ponto = trim($ponto);
                                    if ($ponto) :
                                ?>
                                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                                <?php echo recifemais_get_icon_svg('map-pin', '20', '#f59e0b'); ?>
                                            </div>
                                            <span class="font-semibold text-amber-900"><?php echo esc_html($ponto); ?></span>
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

                <!-- Dicas Importantes -->
                <?php if ($dicas_importantes) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('lightbulb', '32', '#8b5cf6'); ?>
                                Dicas Importantes
                            </h2>
                            
                            <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                                <div class="prose prose-lg max-w-none text-purple-800">
                                    <?php echo wp_kses_post(wpautop($dicas_importantes)); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- O que levar -->
                <?php if ($o_que_levar) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('package', '32', '#059669'); ?>
                                O que Levar
                            </h2>
                            
                            <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <?php 
                                    $itens_array = explode(',', $o_que_levar);
                                    foreach ($itens_array as $item) : 
                                        $item = trim($item);
                                        if ($item) :
                                    ?>
                                        <div class="flex items-center gap-3">
                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <?php echo recifemais_get_icon_svg('check', '14', '#ffffff'); ?>
                                            </div>
                                            <span class="text-green-800"><?php echo esc_html($item); ?></span>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Guias Relacionados -->
                <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('compass', '32', '#06b6d4'); ?>
                            Outros Guias
                        </h2>
                        
                        <?php
                        // Busca inteligente por guias relacionados
                        $guias_relacionados_args = [
                            'post_type' => 'guias_tematicos',
                            'posts_per_page' => 3,
                            'post__not_in' => [$guia_id],
                            'meta_query' => ['relation' => 'OR']
                        ];
                        
                        // Filtros baseados em meta fields
                        if ($tipo_guia) {
                            $guias_relacionados_args['meta_query'][] = [
                                'key' => 'tipo_guia',
                                'value' => $tipo_guia,
                                'compare' => '='
                            ];
                        }
                        
                        if ($publico_alvo) {
                            $guias_relacionados_args['meta_query'][] = [
                                'key' => 'publico_alvo',
                                'value' => $publico_alvo,
                                'compare' => '='
                            ];
                        }
                        
                        // Filtro por localização
                        if ($bairros && !is_wp_error($bairros)) {
                            $guias_relacionados_args['tax_query'] = [
                                [
                                    'taxonomy' => 'bairros_recife',
                                    'field' => 'term_id',
                                    'terms' => wp_list_pluck($bairros, 'term_id')
                                ]
                            ];
                        }
                        
                        $guias_relacionados = new WP_Query($guias_relacionados_args);
                        
                        if ($guias_relacionados->have_posts()) :
                            ?>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php while ($guias_relacionados->have_posts()) : $guias_relacionados->the_post(); 
                                    $duracao_rel = get_post_meta(get_the_ID(), 'duracao_estimada', true);
                                    $paradas_rel = get_post_meta(get_the_ID(), 'numero_paradas', true);
                                    $custo_rel = get_post_meta(get_the_ID(), 'custo_estimado', true);
                                    ?>
                                    <article class="border border-recife-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                                 alt="<?php the_title_attribute(); ?>"
                                                 class="w-full h-32 object-cover rounded-lg mb-3">
                                        <?php endif; ?>
                                        
                                        <h3 class="font-semibold text-recife-gray-900 mb-2 line-clamp-2">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-cpt-roteiros transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="space-y-1 text-sm text-recife-gray-600">
                                            <?php if ($duracao_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('clock', '14'); ?>
                                                    <span><?php echo esc_html($duracao_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($paradas_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('map-pin', '14'); ?>
                                                    <span><?php echo esc_html($paradas_rel); ?> paradas</span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($custo_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('dollar-sign', '14'); ?>
                                                    <span><?php echo esc_html($custo_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p class="text-recife-gray-500 text-center py-8">
                                Nenhum guia relacionado encontrado no momento.
                            </p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="space-y-8">
                
                <!-- Informações do Guia -->
                <div class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-cpt-roteiros to-blue-600 text-white p-6">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('info', '24'); ?>
                            Informações do Guia
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <?php if ($duracao_estimada) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('clock', '24', '#3b82f6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($duracao_estimada); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Duração estimada</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($nivel_dificuldade) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('trending-up', '24', '#10b981'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($nivel_dificuldade); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Nível de dificuldade</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($custo_estimado) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('dollar-sign', '24', '#f59e0b'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($custo_estimado); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Custo estimado</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($epoca_ideal) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('sun', '24', '#8b5cf6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($epoca_ideal); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Época ideal</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('zap', '20', '#1e40af'); ?>
                        Ações Rápidas
                    </h3>
                    
                    <div class="space-y-3">
                        <button onclick="window.print()" class="w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('printer', '16', '#3b82f6'); ?>
                            <span>Imprimir este guia</span>
                        </button>
                        
                        <button onclick="saveToFavorites()" class="w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('heart', '16', '#ef4444'); ?>
                            <span>Salvar nos favoritos</span>
                        </button>
                        
                        <button onclick="downloadPDF()" class="w-full text-left p-3 bg-white rounded-lg hover:bg-blue-50 transition-colors text-sm border border-blue-200 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('download', '16', '#10b981'); ?>
                            <span>Baixar em PDF</span>
                        </button>
                        
                        <a href="<?php echo home_url('/busca'); ?>" class="block w-full text-center p-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                            Buscar Mais Guias
                        </a>
                    </div>
                </div>

                <!-- Compartilhar -->
                <div class="bg-gradient-to-br from-cpt-roteiros to-blue-600 text-white rounded-2xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-center">Compartilhe este Guia</h3>
                    <p class="text-white/90 mb-6 text-center text-sm">
                        Ajude outros viajantes a descobrir Pernambuco
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
function shareGuia() {
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

function shareWhatsApp() {
    const text = encodeURIComponent(`${<?php echo json_encode(get_the_title()); ?>} - Guia Temático de Pernambuco - ${window.location.href}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareTwitter() {
    const text = encodeURIComponent(<?php echo json_encode(get_the_title()); ?>);
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}&hashtags=RecifeMais,GuiaPE`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copiado para a área de transferência!');
    });
}

function downloadPDF() {
    // Funcionalidade de download seria implementada no futuro
    alert('Funcionalidade de download em PDF será implementada em breve!');
}

function saveToFavorites() {
    // Funcionalidade de favoritos seria implementada no futuro
    alert('Guia salvo nos favoritos! (Funcionalidade será implementada em breve)');
}

// Função de impressão otimizada
window.addEventListener('beforeprint', function() {
    // Ocultar elementos desnecessários para impressão
    const elementsToHide = document.querySelectorAll('.btn, .bg-gradient, button');
    elementsToHide.forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Restaurar elementos após impressão
    const elementsToShow = document.querySelectorAll('.btn, .bg-gradient, button');
    elementsToShow.forEach(el => {
        el.style.display = '';
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose-blue {
    --tw-prose-links: #667eea;
    --tw-prose-headings: #1f2937;
}

.btn {
    @apply px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105;
}

/* Estilos específicos para impressão */
@media print {
    .btn, button, .bg-gradient-to-r, .bg-gradient-to-br {
        display: none !important;
    }
    
    .bg-white {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .text-white {
        color: #1f2937 !important;
    }
    
    .prose {
        max-width: none !important;
    }
}

@media (max-width: 640px) {
    .grid.grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    
    .text-4xl.lg\\:text-6xl {
        font-size: 2rem;
        line-height: 1.1;
    }
}
</style>

<?php
endwhile;
get_footer();
?> 