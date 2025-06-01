<?php
/**
 * Single Template: Artistas
 * Inspirado no layout de referência com hero fullscreen
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

// Meta fields do artista
$tipo_grupo = get_post_meta(get_the_ID(), 'artista_tipo_grupo', true);
$origem = get_post_meta(get_the_ID(), 'artista_origem', true);
$ano_formacao = get_post_meta(get_the_ID(), 'artista_ano_formacao', true);
$site_oficial = get_post_meta(get_the_ID(), 'artista_site_oficial', true);
$redes_sociais = get_post_meta(get_the_ID(), 'artista_redes_sociais', true);

// Taxonomias
$generos_musicais = get_the_terms(get_the_ID(), 'generos_musicais');
$tipos_artistas = get_the_terms(get_the_ID(), 'tipos_artistas');

// Ícones por tipo de grupo
$tipo_icons = [
    'solo' => '🎤',
    'banda' => '🎸',
    'grupo' => '👥',
    'coletivo' => '🎭',
    'orquestra' => '🎼',
    'coral' => '🎵'
];
$tipo_icon = $tipo_grupo ? ($tipo_icons[strtolower($tipo_grupo)] ?? '🎨') : '🎨';

// Status badge baseado no tipo
$status_badge = '';
$status_color = '';
if ($tipo_grupo) {
    switch (strtolower($tipo_grupo)) {
        case 'solo':
            $status_badge = 'Artista Solo';
            $status_color = 'bg-purple-500';
            break;
        case 'banda':
            $status_badge = 'Banda';
            $status_color = 'bg-blue-500';
            break;
        case 'grupo':
            $status_badge = 'Grupo Cultural';
            $status_color = 'bg-green-500';
            break;
        case 'orquestra':
            $status_badge = 'Orquestra';
            $status_color = 'bg-indigo-500';
            break;
        case 'coral':
            $status_badge = 'Coral';
            $status_color = 'bg-pink-500';
            break;
        default:
            $status_badge = 'Artista';
            $status_color = 'bg-purple-500';
    }
}

?>

<main class="single-artista bg-gray-50 min-h-screen">
    
    <!-- Hero Section Fullscreen -->
    <section class="hero-artista relative h-screen flex items-end overflow-hidden">
        
        <!-- Imagem de fundo -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="absolute inset-0">
                <?php the_post_thumbnail('full', [
                    'class' => 'w-full h-full object-cover transition-transform duration-700 hover:scale-105',
                    'alt' => get_the_title()
                ]); ?>
            </div>
        <?php else : ?>
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800"></div>
        <?php endif; ?>
        
        <!-- Overlay gradiente -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        
        <!-- Conteúdo do hero -->
        <div class="relative z-10 w-full">
            <div class="container mx-auto px-4 pb-16">
                
                <!-- Breadcrumbs -->
                <nav class="mb-8" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm text-white/80">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                Início
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <a href="<?php echo get_post_type_archive_link('artistas'); ?>" class="hover:text-white transition-colors">
                                Artistas
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <span class="text-white font-medium"><?php the_title(); ?></span>
                        </li>
                    </ol>
                </nav>
                
                <!-- Status Badge -->
                <?php if ($status_badge) : ?>
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 <?php echo esc_attr($status_color); ?> text-white px-4 py-2 rounded-full text-sm font-semibold animate-pulse">
                            <span><?php echo esc_html($tipo_icon); ?></span>
                            <?php echo esc_html($status_badge); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <!-- Título principal -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Meta informações rápidas -->
                <div class="meta-rapida mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl">
                        
                        <?php if ($origem) : ?>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <?php echo recifemais_get_icon_svg('map-pin', '20', '#ffffff'); ?>
                                    </div>
                                    <div>
                                        <div class="text-white/70 text-sm">Origem</div>
                                        <div class="text-white font-semibold"><?php echo esc_html($origem); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($ano_formacao) : ?>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <?php echo recifemais_get_icon_svg('calendar', '20', '#ffffff'); ?>
                                    </div>
                                    <div>
                                        <div class="text-white/70 text-sm">
                                            <?php echo $tipo_grupo === 'solo' ? 'Carreira desde' : 'Formado em'; ?>
                                        </div>
                                        <div class="text-white font-semibold"><?php echo esc_html($ano_formacao); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($generos_musicais && !empty($generos_musicais)) : ?>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <?php echo recifemais_get_icon_svg('music', '20', '#ffffff'); ?>
                                    </div>
                                    <div>
                                        <div class="text-white/70 text-sm">Gênero</div>
                                        <div class="text-white font-semibold"><?php echo esc_html($generos_musicais[0]->name); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- Botões de ação -->
                <div class="acoes-artista">
                    <div class="flex flex-wrap gap-4">
                        
                        <?php if ($site_oficial) : ?>
                            <a href="<?php echo esc_url($site_oficial); ?>" 
                               target="_blank"
                               class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <?php echo recifemais_get_icon_svg('globe', '20', '#ffffff'); ?>
                                Visitar Site
                            </a>
                        <?php endif; ?>
                        
                        <!-- Botão Compartilhar -->
                        <button id="btn-compartilhar-artista" 
                                class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 backdrop-blur-sm border border-white/30">
                            <?php echo recifemais_get_icon_svg('share', '20', '#ffffff'); ?>
                            Compartilhar
                        </button>
                        
                        <!-- Botão Redes Sociais -->
                        <?php if ($redes_sociais) : ?>
                            <div class="relative">
                                <button id="btn-redes-sociais" 
                                        class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 backdrop-blur-sm border border-white/30">
                                    <?php echo recifemais_get_icon_svg('users', '20', '#ffffff'); ?>
                                    Redes Sociais
                                    <?php echo recifemais_get_icon_svg('chevron-down', '16', '#ffffff'); ?>
                                </button>
                                
                                <!-- Dropdown Redes Sociais -->
                                <div id="dropdown-redes" class="absolute top-full left-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 min-w-48 z-50 opacity-0 invisible transition-all duration-300">
                                    <div class="p-2">
                                        <?php
                                        $redes_lines = explode("\n", $redes_sociais);
                                        foreach ($redes_lines as $linha) {
                                            $linha = trim($linha);
                                            if (!empty($linha)) {
                                                // Detectar tipo de rede social
                                                $icon = 'link';
                                                $nome = 'Link';
                                                $cor = 'text-gray-600';
                                                
                                                if (strpos($linha, 'instagram') !== false || strpos($linha, '@') !== false) {
                                                    $icon = 'camera';
                                                    $nome = 'Instagram';
                                                    $cor = 'text-pink-600';
                                                } elseif (strpos($linha, 'facebook') !== false) {
                                                    $icon = 'facebook';
                                                    $nome = 'Facebook';
                                                    $cor = 'text-blue-600';
                                                } elseif (strpos($linha, 'youtube') !== false) {
                                                    $icon = 'play';
                                                    $nome = 'YouTube';
                                                    $cor = 'text-red-600';
                                                } elseif (strpos($linha, 'spotify') !== false) {
                                                    $icon = 'music';
                                                    $nome = 'Spotify';
                                                    $cor = 'text-green-600';
                                                }
                                                ?>
                                                <a href="<?php echo esc_url($linha); ?>" 
                                                   target="_blank"
                                                   class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors <?php echo esc_attr($cor); ?>">
                                                    <?php echo recifemais_get_icon_svg($icon, '16', 'currentColor'); ?>
                                                    <span class="font-medium"><?php echo esc_html($nome); ?></span>
                                                    <?php echo recifemais_get_icon_svg('external-link', '14', 'currentColor'); ?>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
    </section>
    
    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Conteúdo do Post -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <div class="prose prose-lg max-w-none">
                            <?php
                            if (have_posts()) :
                                while (have_posts()) : the_post();
                                    the_content();
                                endwhile;
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Meta Informações Específicas do Artista -->
                    <?php get_template_part('template-parts/single/meta-artista'); ?>
                    
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Widget de Informações Rápidas -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('info', '20', '#7c3aed'); ?>
                            Informações Rápidas
                        </h3>
                        
                        <div class="space-y-3">
                            
                            <?php if ($tipo_grupo) : ?>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Tipo</span>
                                    <span class="font-semibold text-purple-600"><?php echo esc_html(ucfirst($tipo_grupo)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($origem) : ?>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Origem</span>
                                    <span class="font-semibold text-gray-900"><?php echo esc_html($origem); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($ano_formacao) : ?>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">
                                        <?php echo $tipo_grupo === 'solo' ? 'Carreira desde' : 'Formado em'; ?>
                                    </span>
                                    <span class="font-semibold text-gray-900"><?php echo esc_html($ano_formacao); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($generos_musicais && !empty($generos_musicais)) : ?>
                                <div class="py-2">
                                    <span class="text-gray-600 block mb-2">Gêneros</span>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach ($generos_musicais as $genero) : ?>
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">
                                                <?php echo esc_html($genero->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <?php get_template_part('template-parts/homepage/newsletter-signup'); ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Artistas Relacionados -->
    <section class="artistas-relacionados bg-white py-16 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Outros artistas que você pode gostar
                    </h2>
                    <p class="text-xl text-gray-600">
                        Descubra mais talentos da música pernambucana
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    // Query para artistas relacionados
                    $related_args = [
                        'post_type' => 'artistas',
                        'posts_per_page' => 6,
                        'post__not_in' => [get_the_ID()],
                        'orderby' => 'rand'
                    ];
                    
                    // Se tem gênero musical, buscar artistas do mesmo gênero
                    if ($generos_musicais && !empty($generos_musicais)) {
                        $related_args['tax_query'] = [
                            [
                                'taxonomy' => 'generos_musicais',
                                'field' => 'term_id',
                                'terms' => wp_list_pluck($generos_musicais, 'term_id')
                            ]
                        ];
                    }
                    
                    $related_query = new WP_Query($related_args);
                    
                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) : $related_query->the_post();
                    ?>
                        <div class="artista-relacionado">
                            <?php
                            get_template_part('components/cards/card-artista', null, [
                                'post_id' => get_the_ID(),
                                'variant' => 'standard',
                                'size' => 'md',
                                'show_meta' => true,
                                'show_badge' => true,
                                'show_excerpt' => false,
                                'show_social' => false
                            ]);
                            ?>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                
                <div class="text-center mt-12">
                    <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
                       class="inline-flex items-center gap-2 bg-purple-600 text-white px-8 py-4 rounded-lg hover:bg-purple-700 transition-colors font-semibold text-lg">
                        Ver todos os artistas
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