<?php
/**
 * Template para Single de Agremiações - RecifeMais V2
 * 
 * Template moderno para exibir agremiações culturais:
 * - Uso dos dicionários inteligentes
 * - Meta fields específicos de agremiações
 * - Sistema de relacionamentos
 * - Busca integrada
 * - Schema markup
 * - Design responsivo
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) :
    the_post();
    
    $agremiacoes_id = get_the_ID();
    
    // Meta dados modernos do plugin v2
    $tipo_agremiacoes = get_post_meta($agremiacoes_id, 'tipo_agremiacoes', true);
    $ano_fundacao = get_post_meta($agremiacoes_id, 'ano_fundacao', true);
    $local = get_post_meta($agremiacoes_id, 'local', true);
    $endereco = get_post_meta($agremiacoes_id, 'endereco', true);
    $telefone = get_post_meta($agremiacoes_id, 'telefone', true);
    $site = get_post_meta($agremiacoes_id, 'site', true);
    $latitude = get_post_meta($agremiacoes_id, 'latitude', true);
    $longitude = get_post_meta($agremiacoes_id, 'longitude', true);
    $numero_membros = get_post_meta($agremiacoes_id, 'numero_membros', true);
    $atividades_principais = get_post_meta($agremiacoes_id, 'atividades_principais', true);
    $eventos_anuais = get_post_meta($agremiacoes_id, 'eventos_anuais', true);
    $presidente = get_post_meta($agremiacoes_id, 'presidente', true);
    
    // Campos do dicionário
    $publico_alvo = get_post_meta($agremiacoes_id, 'publico_alvo', true);
    $ritmo_musical = get_post_meta($agremiacoes_id, 'ritmo_musical', true);
    
    // Taxonomias
    $bairros = get_the_terms($agremiacoes_id, 'bairros_recife');
    $cidades = get_the_terms($agremiacoes_id, 'cidades_pernambuco');
    
    // Inicializar dicionários
    $dicionarios = null;
    if (class_exists('RecifeMais_V2_Dicionarios')) {
        $dicionarios = new RecifeMais_V2_Dicionarios();
    }
    
    // Schema markup para SEO
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt() ?: get_the_content()),
        'url' => get_permalink(),
        'image' => get_the_post_thumbnail_url($agremiacoes_id, 'large'),
    ];
    
    if ($ano_fundacao) {
        $schema['foundingDate'] = $ano_fundacao;
    }
    
    if ($local && $endereco) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'name' => $local,
            'streetAddress' => $endereco
        ];
    }
    
    if ($telefone) {
        $schema['telephone'] = $telefone;
    }
    
    if ($site) {
        $schema['url'] = $site;
    }
?>

<!-- Schema Markup -->
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="main" class="site-main">
    
    <!-- ===== HERO DA AGREMIAÇÃO ===== -->
    <section class="relative overflow-hidden min-h-[70vh] flex items-center">
        <?php if (has_post_thumbnail()) : ?>
            <div class="absolute inset-0">
                <img src="<?php echo get_the_post_thumbnail_url($agremiacoes_id, 'full'); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
            </div>
        <?php else : ?>
            <div class="absolute inset-0 bg-gradient-to-br from-cpt-artistas via-purple-600 to-pink-500"></div>
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
                        <a href="<?php echo get_post_type_archive_link('agremiacoes'); ?>" class="hover:text-white transition-colors">Agremiações</a>
                        <span>→</span>
                        <span class="text-white"><?php the_title(); ?></span>
                    </div>
                </nav>
                
                <!-- Status e Categoria -->
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="inline-flex items-center gap-2 bg-cpt-artistas text-white px-4 py-2 rounded-full font-semibold">
                        🎪 Agremiação Cultural
                    </span>
                    
                    <?php if ($tipo_agremiacoes && $dicionarios) : ?>
                        <span class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full">
                            <?php echo recifemais_get_icon_svg('star', '16'); ?>
                            <?php echo esc_html($dicionarios->get_label_by_value('tipos_agremiacoes', $tipo_agremiacoes)); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($ano_fundacao) : ?>
                        <span class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-full">
                            <?php echo recifemais_get_icon_svg('calendar', '16'); ?>
                            Desde <?php echo esc_html($ano_fundacao); ?>
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
                    
                    <!-- Fundação -->
                    <?php if ($ano_fundacao) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">🗓️</div>
                            <div class="text-white font-semibold text-sm">
                                Fundada em
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                <?php echo esc_html($ano_fundacao); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Membros -->
                    <?php if ($numero_membros) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">👥</div>
                            <div class="text-white font-semibold text-sm">
                                <?php echo esc_html($numero_membros); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                membros
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Local -->
                    <?php if ($local) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">📍</div>
                            <div class="text-white font-semibold text-sm truncate">
                                <?php echo esc_html($local); ?>
                            </div>
                            <?php if ($endereco) : ?>
                                <div class="text-white/75 text-xs mt-1 truncate">
                                    <?php echo esc_html(wp_trim_words($endereco, 3, '...')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Presidente -->
                    <?php if ($presidente) : ?>
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30">
                            <div class="text-2xl mb-2">👑</div>
                            <div class="text-white font-semibold text-sm truncate">
                                <?php echo esc_html($presidente); ?>
                            </div>
                            <div class="text-white/75 text-xs mt-1">
                                Presidente
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <?php if ($site) : ?>
                        <a href="<?php echo esc_url($site); ?>" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn bg-white text-cpt-artistas hover:bg-recife-gray-100 font-bold inline-flex items-center justify-center gap-2">
                            <?php echo recifemais_get_icon_svg('external-link', '20'); ?>
                            <span>Site Oficial</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($telefone) : ?>
                        <a href="tel:<?php echo esc_attr($telefone); ?>" 
                           class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-artistas inline-flex items-center justify-center gap-2">
                            <?php echo recifemais_get_icon_svg('phone', '20'); ?>
                            <span>Entrar em Contato</span>
                        </a>
                    <?php endif; ?>
                    
                    <button type="button" 
                            class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-artistas inline-flex items-center justify-center gap-2"
                            onclick="shareAgremiacao()">
                        <?php echo recifemais_get_icon_svg('share', '20'); ?>
                        <span>Compartilhar</span>
                    </button>
                    
                    <?php if ($latitude && $longitude) : ?>
                        <button type="button"
                                class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-artistas inline-flex items-center justify-center gap-2"
                                onclick="openMaps(<?php echo esc_attr($latitude); ?>, <?php echo esc_attr($longitude); ?>)">
                            <?php echo recifemais_get_icon_svg('map-pin', '20'); ?>
                            <span>Ver no Mapa</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid lg:grid-cols-3 gap-12">
            
            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- História da Agremiação -->
                <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('book-open', '32', '#667eea'); ?>
                            História da Agremiação
                        </h2>
                        
                        <div class="prose prose-lg max-w-none prose-blue">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Atividades principais -->
                        <?php if ($atividades_principais) : ?>
                            <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                                <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                                    <?php echo recifemais_get_icon_svg('activity', '20', '#1e40af'); ?>
                                    Atividades Principais
                                </h3>
                                <div class="text-blue-800">
                                    <?php echo wp_kses_post(wpautop($atividades_principais)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Ritmo musical -->
                        <?php if ($ritmo_musical && $dicionarios) : ?>
                            <div class="mt-8 p-6 bg-purple-50 rounded-xl border border-purple-200">
                                <h3 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                                    <?php echo recifemais_get_icon_svg('music', '20', '#7c3aed'); ?>
                                    Estilo Musical
                                </h3>
                                <p class="text-purple-800">
                                    <?php echo esc_html($dicionarios->get_label_by_value('ritmos_musicais', $ritmo_musical)); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Eventos Anuais -->
                <?php if ($eventos_anuais) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('calendar', '32', '#f59e0b'); ?>
                                Eventos Anuais
                            </h2>
                            
                            <div class="prose prose-lg max-w-none">
                                <?php echo wp_kses_post(wpautop($eventos_anuais)); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Localização -->
                <?php if ($local && $endereco) : ?>
                    <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                                <?php echo recifemais_get_icon_svg('map-pin', '32', '#10b981'); ?>
                                Localização
                            </h2>
                            
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <?php echo recifemais_get_icon_svg('building', '24', '#10b981'); ?>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-recife-gray-900 mb-2">
                                            <?php echo esc_html($local); ?>
                                        </h3>
                                        <p class="text-recife-gray-600 mb-4">
                                            <?php echo esc_html($endereco); ?>
                                        </p>
                                        
                                        <!-- Informações de contato -->
                                        <div class="flex flex-wrap gap-4">
                                            <?php if ($telefone) : ?>
                                                <a href="tel:<?php echo esc_attr($telefone); ?>" 
                                                   class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                                                    <?php echo recifemais_get_icon_svg('phone', '16'); ?>
                                                    <span><?php echo esc_html($telefone); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($site) : ?>
                                                <a href="<?php echo esc_url($site); ?>" 
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                                                    <?php echo recifemais_get_icon_svg('external-link', '16'); ?>
                                                    <span>Site Oficial</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Mapa (placeholder) -->
                                <?php if ($latitude && $longitude) : ?>
                                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-200">
                                        <div class="text-center">
                                            <div class="text-4xl mb-4">🗺️</div>
                                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Localização no Mapa</h3>
                                            <p class="text-blue-700 mb-4">Coordenadas: <?php echo esc_html($latitude); ?>, <?php echo esc_html($longitude); ?></p>
                                            <button onclick="openMaps(<?php echo esc_attr($latitude); ?>, <?php echo esc_attr($longitude); ?>)" 
                                                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                                Abrir no Google Maps
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Agremiações Relacionadas -->
                <section class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('users', '32', '#8b5cf6'); ?>
                            Outras Agremiações
                        </h2>
                        
                        <?php
                        // Busca inteligente por agremiações similares
                        $agremiacoes_relacionadas_args = [
                            'post_type' => 'agremiacoes',
                            'posts_per_page' => 3,
                            'post__not_in' => [$agremiacoes_id],
                            'meta_query' => ['relation' => 'OR']
                        ];
                        
                        // Filtros baseados em meta fields
                        if ($tipo_agremiacoes) {
                            $agremiacoes_relacionadas_args['meta_query'][] = [
                                'key' => 'tipo_agremiacoes',
                                'value' => $tipo_agremiacoes,
                                'compare' => '='
                            ];
                        }
                        
                        if ($ritmo_musical) {
                            $agremiacoes_relacionadas_args['meta_query'][] = [
                                'key' => 'ritmo_musical',
                                'value' => $ritmo_musical,
                                'compare' => '='
                            ];
                        }
                        
                        // Filtro por localização
                        if ($bairros && !is_wp_error($bairros)) {
                            $agremiacoes_relacionadas_args['tax_query'] = [
                                [
                                    'taxonomy' => 'bairros_recife',
                                    'field' => 'term_id',
                                    'terms' => wp_list_pluck($bairros, 'term_id')
                                ]
                            ];
                        }
                        
                        $agremiacoes_relacionadas = new WP_Query($agremiacoes_relacionadas_args);
                        
                        if ($agremiacoes_relacionadas->have_posts()) :
                            ?>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php while ($agremiacoes_relacionadas->have_posts()) : $agremiacoes_relacionadas->the_post(); 
                                    $ano_rel = get_post_meta(get_the_ID(), 'ano_fundacao', true);
                                    $local_rel = get_post_meta(get_the_ID(), 'local', true);
                                    $tipo_rel = get_post_meta(get_the_ID(), 'tipo_agremiacoes', true);
                                    ?>
                                    <article class="border border-recife-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                                 alt="<?php the_title_attribute(); ?>"
                                                 class="w-full h-32 object-cover rounded-lg mb-3">
                                        <?php endif; ?>
                                        
                                        <h3 class="font-semibold text-recife-gray-900 mb-2 line-clamp-2">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-cpt-artistas transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="space-y-1 text-sm text-recife-gray-600">
                                            <?php if ($ano_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('calendar', '14'); ?>
                                                    <span>Desde <?php echo esc_html($ano_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($local_rel) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('map-pin', '14'); ?>
                                                    <span class="truncate"><?php echo esc_html($local_rel); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($tipo_rel && $dicionarios) : ?>
                                                <div class="flex items-center gap-2">
                                                    <?php echo recifemais_get_icon_svg('star', '14'); ?>
                                                    <span class="truncate"><?php echo esc_html($dicionarios->get_label_by_value('tipos_agremiacoes', $tipo_rel)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p class="text-recife-gray-500 text-center py-8">
                                Nenhuma agremiação relacionada encontrada no momento.
                            </p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- ===== SIDEBAR ===== -->
            <div class="space-y-8">
                
                <!-- Informações Rápidas -->
                <div class="bg-white rounded-2xl shadow-lg border border-recife-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-cpt-artistas to-purple-600 text-white p-6">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('info', '24'); ?>
                            Informações Rápidas
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <?php if ($ano_fundacao) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('calendar', '24', '#3b82f6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        Fundada em <?php echo esc_html($ano_fundacao); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">
                                        <?php echo date('Y') - intval($ano_fundacao); ?> anos de tradição
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($bairros && !is_wp_error($bairros)) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('map-pin', '24', '#10b981'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($bairros[0]->name); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">
                                        <?php echo ($cidades && !is_wp_error($cidades)) ? esc_html($cidades[0]->name) : 'Pernambuco'; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($numero_membros) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('users', '24', '#8b5cf6'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($numero_membros); ?> membros
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Comunidade ativa</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($presidente) : ?>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('crown', '24', '#f59e0b'); ?>
                                </div>
                                <div>
                                    <div class="font-semibold text-recife-gray-900">
                                        <?php echo esc_html($presidente); ?>
                                    </div>
                                    <div class="text-sm text-recife-gray-600">Presidente</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Busca Relacionada -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200 p-6">
                    <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('search', '20', '#7c3aed'); ?>
                        Buscar Agremiações Similares
                    </h3>
                    
                    <div class="space-y-3">
                        <?php if ($tipo_agremiacoes && $dicionarios) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=agremiacoes&tipo_agremiacoes=' . urlencode($tipo_agremiacoes)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-purple-50 transition-colors text-sm border border-purple-200">
                                <strong>Tipo:</strong> <?php echo esc_html($dicionarios->get_label_by_value('tipos_agremiacoes', $tipo_agremiacoes)); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($bairros && !is_wp_error($bairros)) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=agremiacoes&bairro=' . urlencode($bairros[0]->slug)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-purple-50 transition-colors text-sm border border-purple-200">
                                <strong>Local:</strong> <?php echo esc_html($bairros[0]->name); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($ritmo_musical && $dicionarios) : ?>
                            <a href="<?php echo home_url('/busca?post_types[]=agremiacoes&ritmo_musical=' . urlencode($ritmo_musical)); ?>" 
                               class="block w-full text-left p-3 bg-white rounded-lg hover:bg-purple-50 transition-colors text-sm border border-purple-200">
                                <strong>Estilo:</strong> <?php echo esc_html($dicionarios->get_label_by_value('ritmos_musicais', $ritmo_musical)); ?>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo home_url('/busca'); ?>" 
                           class="block w-full text-center p-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors font-medium">
                            Busca Avançada
                        </a>
                    </div>
                </div>

                <!-- Compartilhar -->
                <div class="bg-gradient-to-br from-cpt-artistas to-purple-600 text-white rounded-2xl p-6">
                    <h3 class="text-xl font-bold mb-4 text-center">Divulgue esta Agremiação</h3>
                    <p class="text-white/90 mb-6 text-center text-sm">
                        Ajude a valorizar a cultura pernambucana
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
function shareAgremiacao() {
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

function openMaps(lat, lng) {
    const url = `https://www.google.com/maps?q=${lat},${lng}`;
    window.open(url, '_blank');
}

function shareWhatsApp() {
    const text = encodeURIComponent(`${<?php echo json_encode(get_the_title()); ?>} - Agremiação Cultural - ${window.location.href}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareTwitter() {
    const text = encodeURIComponent(<?php echo json_encode(get_the_title()); ?>);
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copiado para a área de transferência!');
    });
}
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