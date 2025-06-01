<?php
/**
 * Archive Template: Artistas
 * Inspirado no Agenda Viva SP - Layout moderno e funcional
 * 
 * Utiliza todos os meta fields do plugin RecifeMais Core V2:
 * - artista_tipo_grupo, artista_origem, artista_ano_formacao
 * - artista_integrantes, artista_biografia, artista_redes_sociais
 * - artista_ritmos, artista_generos, artista_publico_alvo
 * - artista_site_oficial
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

// Configurações da página
$total_artistas = wp_count_posts('artistas')->publish;
$artistas_com_origem = new WP_Query([
    'post_type' => 'artistas',
    'meta_query' => [
        [
            'key' => 'artista_origem',
            'value' => '',
            'compare' => '!='
        ]
    ],
    'posts_per_page' => 1
]);
$artistas_localizados = $artistas_com_origem->found_posts;
wp_reset_postdata();

// Filtros ativos
$filtro_tipo = isset($_GET['tipo']) ? sanitize_text_field($_GET['tipo']) : '';
$filtro_genero = isset($_GET['genero']) ? sanitize_text_field($_GET['genero']) : '';
$filtro_ritmo = isset($_GET['ritmo']) ? sanitize_text_field($_GET['ritmo']) : '';
$filtro_origem = isset($_GET['origem']) ? sanitize_text_field($_GET['origem']) : '';
$busca = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

?>

<main class="archive-artistas bg-gray-50 min-h-screen">
    
    <!-- Header Principal (Inspirado Agenda Viva SP) -->
    <section class="hero-artistas bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0 11.046 8.954 20 20 20s20-8.954 20-20-8.954-20-20-20-20 8.954-20 20z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <div class="container mx-auto px-4 py-16 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                
                <!-- Breadcrumbs -->
                <nav class="mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center justify-center gap-2 text-sm text-purple-100">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                Início
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <span class="text-white font-medium">Artistas</span>
                        </li>
                    </ol>
                </nav>
                
                <!-- Título Principal -->
                <div class="mb-8">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-4">
                        Artistas de Pernambuco
                        <span class="block text-2xl lg:text-3xl font-normal text-purple-100 mt-2">
                            Talentos que fazem nossa cultura brilhar
                        </span>
                    </h1>
                    <p class="text-xl text-purple-100 max-w-2xl mx-auto leading-relaxed">
                        Conheça os artistas, bandas e grupos culturais que representam a riqueza musical e artística de Pernambuco. 
                        Descubra novos talentos e acompanhe seus trabalhos.
                    </p>
                </div>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold"><?php echo number_format_i18n($total_artistas); ?></div>
                        <div class="text-sm text-purple-100">Artistas Cadastrados</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold"><?php echo number_format_i18n($artistas_localizados); ?></div>
                        <div class="text-sm text-purple-100">Com Origem Definida</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold">
                            <?php echo number_format_i18n(wp_count_terms('generos_musicais')); ?>
                        </div>
                        <div class="text-sm text-purple-100">Gêneros Musicais</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-2xl lg:text-3xl font-bold">
                            <?php echo number_format_i18n(wp_count_terms('tipos_artistas')); ?>
                        </div>
                        <div class="text-sm text-purple-100">Tipos de Artistas</div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Barra de Busca e Filtros (Estilo Agenda Viva SP) -->
    <section class="filtros-artistas bg-white shadow-lg border-b border-gray-200 sticky top-0 z-40">
        <div class="container mx-auto px-4 py-6">
            <form method="GET" class="max-w-6xl mx-auto">
                
                <!-- Busca Principal -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" 
                               name="s" 
                               value="<?php echo esc_attr($busca); ?>"
                               placeholder="Buscar artistas, bandas, grupos culturais..."
                               class="w-full px-6 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pl-14">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <?php echo recifemais_get_icon_svg('search', '24', '#6b7280'); ?>
                        </div>
                        <button type="submit" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                            Buscar
                        </button>
                    </div>
                </div>
                
                <!-- Filtros Rápidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    <!-- Filtro de Tipo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Artista</label>
                        <select name="tipo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Todos os tipos</option>
                            <?php
                            $tipos_artistas = get_terms([
                                'taxonomy' => 'tipos_artistas',
                                'hide_empty' => true,
                                'orderby' => 'count',
                                'order' => 'DESC'
                            ]);
                            foreach ($tipos_artistas as $tipo) :
                            ?>
                                <option value="<?php echo esc_attr($tipo->slug); ?>" <?php selected($filtro_tipo, $tipo->slug); ?>>
                                    <?php echo esc_html($tipo->name); ?> (<?php echo $tipo->count; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtro de Gênero Musical -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gênero Musical</label>
                        <select name="genero" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Todos os gêneros</option>
                            <?php
                            $generos_musicais = get_terms([
                                'taxonomy' => 'generos_musicais',
                                'hide_empty' => true,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ]);
                            foreach ($generos_musicais as $genero) :
                            ?>
                                <option value="<?php echo esc_attr($genero->slug); ?>" <?php selected($filtro_genero, $genero->slug); ?>>
                                    <?php echo esc_html($genero->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtro de Ritmo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ritmo</label>
                        <select name="ritmo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Todos os ritmos</option>
                            <?php
                            // Buscar ritmos do dicionário
                            if (class_exists('RecifeMais_V2_Dicionarios')) {
                                $dicionarios = new RecifeMais_V2_Dicionarios();
                                $ritmos = $dicionarios->get_valores('ritmos_musicais');
                                foreach ($ritmos as $ritmo) :
                            ?>
                                <option value="<?php echo esc_attr($ritmo['valor']); ?>" <?php selected($filtro_ritmo, $ritmo['valor']); ?>>
                                    <?php echo esc_html($ritmo['label']); ?>
                                </option>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro de Origem -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Origem</label>
                        <select name="origem" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Todas as origens</option>
                            <option value="recife" <?php selected($filtro_origem, 'recife'); ?>>Recife</option>
                            <option value="olinda" <?php selected($filtro_origem, 'olinda'); ?>>Olinda</option>
                            <option value="caruaru" <?php selected($filtro_origem, 'caruaru'); ?>>Caruaru</option>
                            <option value="petrolina" <?php selected($filtro_origem, 'petrolina'); ?>>Petrolina</option>
                            <option value="garanhuns" <?php selected($filtro_origem, 'garanhuns'); ?>>Garanhuns</option>
                            <option value="outras" <?php selected($filtro_origem, 'outras'); ?>>Outras cidades</option>
                        </select>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="flex flex-col gap-2">
                        <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                            Filtrar
                        </button>
                        <?php if ($filtro_tipo || $filtro_genero || $filtro_ritmo || $filtro_origem || $busca) : ?>
                            <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
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
                <?php if ($filtro_tipo || $filtro_genero || $filtro_ritmo || $filtro_origem || $busca) : ?>
                    <div class="mb-8">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-purple-900 mb-2">Filtros ativos:</h3>
                                    <div class="flex flex-wrap gap-2">
                                        <?php if ($busca) : ?>
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                                Busca: "<?php echo esc_html($busca); ?>"
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_tipo) : ?>
                                            <?php $tipo_term = get_term_by('slug', $filtro_tipo, 'tipos_artistas'); ?>
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                                Tipo: <?php echo esc_html($tipo_term->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_genero) : ?>
                                            <?php $genero_term = get_term_by('slug', $filtro_genero, 'generos_musicais'); ?>
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                                Gênero: <?php echo esc_html($genero_term->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_ritmo) : ?>
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                                Ritmo: <?php echo esc_html(ucfirst(str_replace('-', ' ', $filtro_ritmo))); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($filtro_origem) : ?>
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                                Origem: <?php echo esc_html(ucfirst($filtro_origem)); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-sm text-purple-700">
                                    <?php echo $wp_query->found_posts; ?> artista(s) encontrado(s)
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Grid de Artistas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <?php
                        // Usar o component card-artista existente
                        get_template_part('components/cards/card-artista', null, [
                            'post_id' => get_the_ID(),
                            'variant' => 'standard',
                            'size' => 'lg',
                            'show_meta' => true,
                            'show_badge' => true,
                            'show_excerpt' => true,
                            'show_social' => false,
                            'classes' => ['artista-card-archive']
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
                        'class' => 'pagination-artistas'
                    ]);
                    ?>
                </div>
                
            <?php else : ?>
                
                <!-- Estado Vazio -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                        <?php echo recifemais_get_icon_svg('users', '64', '#9ca3af'); ?>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Nenhum artista encontrado
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                        <?php if ($busca || $filtro_tipo || $filtro_genero || $filtro_ritmo || $filtro_origem) : ?>
                            Não encontramos artistas que correspondam aos seus critérios de busca. 
                            Tente ajustar os filtros ou fazer uma nova busca.
                        <?php else : ?>
                            Ainda não temos artistas cadastrados. Volte em breve para descobrir os talentos de Pernambuco!
                        <?php endif; ?>
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <?php if ($busca || $filtro_tipo || $filtro_genero || $filtro_ritmo || $filtro_origem) : ?>
                            <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
                               class="inline-flex items-center gap-2 px-8 py-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                                Ver todos os artistas
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            Explorar eventos
                        </a>
                        
                        <a href="<?php echo get_post_type_archive_link('agremiacoes'); ?>" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            Ver agremiações
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
    
    <!-- Seção de Gêneros Musicais (Inspirado Agenda Viva SP) -->
    <section class="generos-musicais bg-white py-16 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Artistas por gênero musical
                    </h2>
                    <p class="text-xl text-gray-600">
                        Explore nossos artistas por estilo musical
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php
                    $generos_principais = get_terms([
                        'taxonomy' => 'generos_musicais',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 8
                    ]);
                    
                    $genero_icons = [
                        'frevo' => 'music',
                        'maracatu' => 'drum',
                        'forro' => 'guitar',
                        'mpb' => 'mic',
                        'rock' => 'zap',
                        'pop' => 'star',
                        'eletronica' => 'radio',
                        'jazz' => 'headphones'
                    ];
                    
                    foreach ($generos_principais as $genero) :
                        $icon = $genero_icons[$genero->slug] ?? 'music';
                        $archive_url = add_query_arg('genero', $genero->slug, get_post_type_archive_link('artistas'));
                    ?>
                        <a href="<?php echo esc_url($archive_url); ?>" 
                           class="group bg-gray-50 hover:bg-purple-50 border border-gray-200 hover:border-purple-200 rounded-xl p-6 transition-all duration-300 text-center">
                            <div class="w-16 h-16 bg-purple-100 group-hover:bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors">
                                <?php echo recifemais_get_icon_svg($icon, '32', '#7c3aed'); ?>
                            </div>
                            <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors mb-2">
                                <?php echo esc_html($genero->name); ?>
                            </h3>
                            <p class="text-sm text-gray-600">
                                <?php echo number_format_i18n($genero->count); ?> artista(s)
                            </p>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
                       class="inline-flex items-center gap-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                        Ver todos os gêneros
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