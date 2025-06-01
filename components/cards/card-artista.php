<?php
/**
 * Componente: Card Artista
 * 
 * Componente reutilizável para exibir artistas em diferentes contextos
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
 * @param int    $post_id     ID do post do artista (obrigatório)
 * @param string $variant     Variação do card: 'hero', 'standard', 'horizontal', 'mini'
 * @param string $size        Tamanho: 'sm', 'md', 'lg'
 * @param bool   $show_meta   Exibir metadados (origem, gênero, tipo)
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_social Exibir links sociais
 * @param string $link_target Target do link: '_self', '_blank'
 * @param array  $classes     Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'post_id' => get_the_ID(),
    'variant' => 'standard',
    'size' => 'md',
    'show_meta' => true,
    'show_badge' => true,
    'show_excerpt' => false,
    'show_social' => false,
    'link_target' => '_self',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Validação
if (!$post_id || get_post_type($post_id) !== 'artistas') {
    return;
}

// Dados do artista
$artista = get_post($post_id);
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'large');

// Meta fields (usando nomes corretos do plugin)
$tipo_grupo = get_post_meta($post_id, 'artista_tipo_grupo', true);
$origem = get_post_meta($post_id, 'artista_origem', true);
$ano_formacao = get_post_meta($post_id, 'artista_ano_formacao', true);
$integrantes = get_post_meta($post_id, 'artista_integrantes', true);
$biografia = get_post_meta($post_id, 'artista_biografia', true);
$site_oficial = get_post_meta($post_id, 'artista_site_oficial', true);
$redes_sociais = get_post_meta($post_id, 'artista_redes_sociais', true);
$ritmos = get_post_meta($post_id, 'artista_ritmos', true);
$generos = get_post_meta($post_id, 'artista_generos', true);
$publico_alvo = get_post_meta($post_id, 'artista_publico_alvo', true);

// Taxonomias
$generos_musicais = get_the_terms($post_id, 'generos_musicais');
$tipos_artistas = get_the_terms($post_id, 'tipos_artistas');
$bairros = get_the_terms($post_id, 'bairros_recife');

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

// Classes CSS
$card_classes = ['card', 'card-artista', "card-{$variant}", "card-{$size}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-artista.jpg';
$image_url = $featured_image ?: $placeholder_image;

?>

<?php if ($variant === 'hero'): ?>
    <!-- Card Hero - Para destaques principais -->
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
        <a href="<?php echo esc_url($permalink); ?>" 
           target="<?php echo esc_attr($link_target); ?>"
           class="block relative overflow-hidden rounded-xl aspect-hero">
            
            <!-- Imagem de fundo -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($title); ?>"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy">
            </div>
            
            <!-- Conteúdo sobreposto -->
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                <?php if ($show_badge && $generos_musicais): ?>
                    <div class="mb-3">
                        <span class="badge badge-artistas text-xs font-semibold">
                            <?php echo esc_html($generos_musicais[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h2 class="card-hero-title text-white mb-2 group-hover:text-recife-creative transition-colors">
                    <?php echo esc_html($title); ?>
                </h2>
                
                <?php if ($show_meta): ?>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-200">
                        <?php if ($tipo_grupo): ?>
                            <span class="flex items-center gap-1">
                                <span><?php echo esc_html($tipo_icon); ?></span>
                                <?php echo esc_html($tipo_grupo); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($origem): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($origem); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($ano_formacao): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($ano_formacao); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>

<?php elseif ($variant === 'horizontal'): ?>
    <!-- Card Horizontal - Para listas -->
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
        <a href="<?php echo esc_url($permalink); ?>" 
           target="<?php echo esc_attr($link_target); ?>"
           class="card-horizontal">
            
            <!-- Imagem -->
            <div class="card-horizontal-image bg-recife-gray-100 rounded-full overflow-hidden">
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($title); ?>"
                     class="w-full h-full object-cover"
                     loading="lazy">
            </div>
            
            <!-- Conteúdo -->
            <div class="flex-1 min-w-0">
                <?php if ($show_badge && $generos_musicais): ?>
                    <div class="mb-1">
                        <span class="badge badge-artistas text-xs">
                            <?php echo esc_html($generos_musicais[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-creative transition-colors line-clamp-2 mb-1">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_meta): ?>
                    <div class="text-sm text-recife-gray-600 space-y-1">
                        <?php if ($tipo_grupo): ?>
                            <div class="flex items-center gap-1">
                                <span class="text-xs"><?php echo esc_html($tipo_icon); ?></span>
                                <span><?php echo esc_html($tipo_grupo); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($origem): ?>
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html($origem); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>

<?php elseif ($variant === 'mini'): ?>
    <!-- Card Mini - Para widgets e sidebars -->
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
        <a href="<?php echo esc_url($permalink); ?>" 
           target="<?php echo esc_attr($link_target); ?>"
           class="block p-3 rounded-lg hover:bg-recife-gray-50 transition-colors">
            
            <div class="flex items-start gap-3">
                <!-- Avatar circular -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-recife-gray-200">
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($title); ?>"
                             class="w-full h-full object-cover"
                             loading="lazy">
                    </div>
                </div>
                
                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-creative transition-colors line-clamp-2 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    
                    <?php if ($generos_musicais): ?>
                        <p class="text-xs text-recife-gray-600 truncate">
                            <?php echo esc_html($generos_musicais[0]->name); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($origem): ?>
                        <p class="text-xs text-recife-gray-500 mt-1 truncate">
                            <?php echo esc_html($origem); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </article>

<?php else: ?>
    <!-- Card Standard - Padrão para grids -->
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
        <a href="<?php echo esc_url($permalink); ?>" 
           target="<?php echo esc_attr($link_target); ?>"
           class="block">
            
            <!-- Imagem -->
            <div class="relative aspect-card bg-recife-gray-100 rounded-t-lg overflow-hidden">
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($title); ?>"
                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                     loading="lazy">
                
                <?php if ($show_badge && $generos_musicais): ?>
                    <div class="absolute top-3 left-3">
                        <span class="badge badge-artistas text-xs font-semibold">
                            <?php echo esc_html($generos_musicais[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($tipo_grupo): ?>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 backdrop-blur-sm text-recife-gray-900 px-2 py-1 rounded-full text-xs font-medium">
                            <?php echo esc_html($tipo_icon . ' ' . $tipo_grupo); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-4">
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-creative transition-colors line-clamp-2 mb-2">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-sm text-recife-gray-600 line-clamp-2 mb-3">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="space-y-2 text-sm text-recife-gray-600">
                        <?php if ($origem): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-creative" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html($origem); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($ano_formacao): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-creative" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Desde <?php echo esc_html($ano_formacao); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($integrantes): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-creative" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html(wp_trim_words($integrantes, 3)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_social && $site_oficial): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-creative" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate">Site oficial</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
<?php endif; ?> 