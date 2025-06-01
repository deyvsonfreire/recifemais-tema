<?php
/**
 * Componente: Card Roteiro
 * 
 * Componente reutilizável para exibir roteiros em diferentes contextos
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
 * @param int    $post_id     ID do post do roteiro (obrigatório)
 * @param string $variant     Variação do card: 'hero', 'standard', 'horizontal', 'mini'
 * @param string $size        Tamanho: 'sm', 'md', 'lg'
 * @param bool   $show_meta   Exibir metadados (duração, dificuldade, custo)
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_points Exibir pontos de interesse
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
    'show_points' => false,
    'link_target' => '_self',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Validação
if (!$post_id || get_post_type($post_id) !== 'roteiros') {
    return;
}

// Dados do roteiro
$roteiro = get_post($post_id);
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'large');

// Meta fields
$duracao = get_post_meta($post_id, 'roteiro_duracao', true);
$dificuldade = get_post_meta($post_id, 'roteiro_dificuldade', true);
$pontos_interesse = get_post_meta($post_id, 'roteiro_pontos_interesse', true);
$transporte = get_post_meta($post_id, 'roteiro_transporte', true);
$custo_estimado = get_post_meta($post_id, 'roteiro_custo_estimado', true);
$melhor_epoca = get_post_meta($post_id, 'roteiro_melhor_epoca', true);

// Taxonomias
$tipos_roteiros = get_the_terms($post_id, 'tipos_roteiros');
$duracao_roteiros = get_the_terms($post_id, 'duracao_roteiros');
$publico_alvo = get_the_terms($post_id, 'publico_alvo');

// Ícones por duração
$duracao_icons = [
    'meio dia' => '⏰',
    'dia inteiro' => '🌅',
    '2 dias' => '📅',
    '3 dias' => '🗓️',
    'fim de semana' => '🏖️',
    'semana' => '🌴'
];
$duracao_icon = $duracao ? ($duracao_icons[strtolower($duracao)] ?? '🗺️') : '🗺️';

// Ícones por dificuldade
$dificuldade_colors = [
    'fácil' => 'bg-green-100 text-green-800',
    'moderado' => 'bg-yellow-100 text-yellow-800',
    'difícil' => 'bg-red-100 text-red-800'
];
$dificuldade_color = $dificuldade ? ($dificuldade_colors[strtolower($dificuldade)] ?? 'bg-gray-100 text-gray-800') : 'bg-gray-100 text-gray-800';

// Classes CSS
$card_classes = ['card', 'card-roteiro', "card-{$variant}", "card-{$size}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-roteiro.jpg';
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
                <?php if ($show_badge && $tipos_roteiros): ?>
                    <div class="mb-3">
                        <span class="badge badge-roteiros text-xs font-semibold">
                            <?php echo esc_html($tipos_roteiros[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h2 class="card-hero-title text-white mb-2 group-hover:text-recife-secondary transition-colors">
                    <?php echo esc_html($title); ?>
                </h2>
                
                <?php if ($show_meta): ?>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-200">
                        <?php if ($duracao): ?>
                            <span class="flex items-center gap-1">
                                <span><?php echo esc_html($duracao_icon); ?></span>
                                <?php echo esc_html($duracao); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($dificuldade): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($dificuldade); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($custo_estimado): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($custo_estimado); ?>
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
            <div class="card-horizontal-image bg-recife-gray-100">
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($title); ?>"
                     class="w-full h-full object-cover"
                     loading="lazy">
            </div>
            
            <!-- Conteúdo -->
            <div class="flex-1 min-w-0">
                <?php if ($show_badge && $tipos_roteiros): ?>
                    <div class="mb-1">
                        <span class="badge badge-roteiros text-xs">
                            <?php echo esc_html($tipos_roteiros[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-secondary transition-colors line-clamp-2 mb-1">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_meta): ?>
                    <div class="text-sm text-recife-gray-600 space-y-1">
                        <?php if ($duracao): ?>
                            <div class="flex items-center gap-1">
                                <span class="text-xs"><?php echo esc_html($duracao_icon); ?></span>
                                <span><?php echo esc_html($duracao); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($dificuldade): ?>
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo esc_attr($dificuldade_color); ?>">
                                    <?php echo esc_html($dificuldade); ?>
                                </span>
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
                <!-- Ícone de duração -->
                <?php if ($duracao): ?>
                    <div class="flex-shrink-0">
                        <div class="bg-recife-secondary text-white rounded-lg p-2 w-10 h-10 flex items-center justify-center">
                            <span class="text-sm"><?php echo esc_html($duracao_icon); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-secondary transition-colors line-clamp-2 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    
                    <?php if ($duracao): ?>
                        <p class="text-xs text-recife-gray-600 truncate">
                            <?php echo esc_html($duracao); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($dificuldade): ?>
                        <p class="text-xs text-recife-gray-500 mt-1">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium <?php echo esc_attr($dificuldade_color); ?>">
                                <?php echo esc_html($dificuldade); ?>
                            </span>
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
                
                <?php if ($show_badge && $tipos_roteiros): ?>
                    <div class="absolute top-3 left-3">
                        <span class="badge badge-roteiros text-xs font-semibold">
                            <?php echo esc_html($tipos_roteiros[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($dificuldade): ?>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo esc_attr($dificuldade_color); ?>">
                            <?php echo esc_html($dificuldade); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-4">
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-secondary transition-colors line-clamp-2 mb-2">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-sm text-recife-gray-600 line-clamp-2 mb-3">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="space-y-2 text-sm text-recife-gray-600">
                        <?php if ($duracao): ?>
                            <div class="flex items-center gap-2">
                                <span class="text-recife-secondary"><?php echo esc_html($duracao_icon); ?></span>
                                <span><?php echo esc_html($duracao); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($custo_estimado): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-secondary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo esc_html($custo_estimado); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($transporte && is_array($transporte)): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-secondary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html(implode(', ', array_slice($transporte, 0, 2))); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_points && $pontos_interesse && is_array($pontos_interesse)): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-secondary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo count($pontos_interesse); ?> pontos</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
<?php endif; ?> 