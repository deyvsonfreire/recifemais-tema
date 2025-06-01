<?php
/**
 * Componente: Card Lugar
 * 
 * Componente reutilizável para exibir lugares em diferentes contextos
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
 * @param int    $post_id     ID do post do lugar (obrigatório)
 * @param string $variant     Variação do card: 'hero', 'standard', 'horizontal', 'mini'
 * @param string $size        Tamanho: 'sm', 'md', 'lg'
 * @param bool   $show_meta   Exibir metadados (endereço, telefone, preço)
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_rating Exibir avaliação (futuro)
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
    'show_rating' => false,
    'link_target' => '_self',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Validação
if (!$post_id || get_post_type($post_id) !== 'lugares') {
    return;
}

// Dados do lugar
$lugar = get_post($post_id);
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'large');

// Meta fields
$endereco = get_post_meta($post_id, 'lugar_endereco', true);
$telefone = get_post_meta($post_id, 'lugar_telefone', true);
$email = get_post_meta($post_id, 'lugar_email', true);
$website = get_post_meta($post_id, 'lugar_website', true);
$horario_funcionamento = get_post_meta($post_id, 'lugar_horario_funcionamento', true);
$faixa_preco = get_post_meta($post_id, 'lugar_faixa_preco', true);

// Taxonomias
$tipos_lugares = get_the_terms($post_id, 'tipos_lugares');
$bairros = get_the_terms($post_id, 'bairros_recife');
$categorias = get_the_terms($post_id, 'categorias_lugares');

// Formatação de endereço
$endereco_resumido = '';
if ($endereco) {
    $endereco_parts = explode(',', $endereco);
    $endereco_resumido = trim($endereco_parts[0]);
    if ($bairros && !empty($bairros)) {
        $endereco_resumido .= ', ' . $bairros[0]->name;
    }
}

// Ícone de preço
$preco_icons = [
    '$' => '💰',
    '$$' => '💰💰',
    '$$$' => '💰💰💰'
];
$preco_icon = $faixa_preco ? ($preco_icons[$faixa_preco] ?? '') : '';

// Classes CSS
$card_classes = ['card', 'card-lugar', "card-{$variant}", "card-{$size}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-lugar.jpg';
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
                <?php if ($show_badge && $tipos_lugares): ?>
                    <div class="mb-3">
                        <span class="badge badge-lugares text-xs font-semibold">
                            <?php echo esc_html($tipos_lugares[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h2 class="card-hero-title text-white mb-2 group-hover:text-recife-accent-light transition-colors">
                    <?php echo esc_html($title); ?>
                </h2>
                
                <?php if ($show_meta): ?>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-200">
                        <?php if ($endereco_resumido): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($endereco_resumido); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($telefone): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <?php echo esc_html($telefone); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($faixa_preco): ?>
                            <span class="flex items-center gap-1">
                                <span><?php echo esc_html($preco_icon); ?></span>
                                <span><?php echo esc_html($faixa_preco); ?></span>
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
                <?php if ($show_badge && $tipos_lugares): ?>
                    <div class="mb-1">
                        <span class="badge badge-lugares text-xs">
                            <?php echo esc_html($tipos_lugares[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-accent transition-colors line-clamp-2 mb-1">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_meta): ?>
                    <div class="text-sm text-recife-gray-600 space-y-1">
                        <?php if ($endereco_resumido): ?>
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html($endereco_resumido); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($faixa_preco): ?>
                            <div class="flex items-center gap-1">
                                <span class="text-xs"><?php echo esc_html($preco_icon); ?></span>
                                <span><?php echo esc_html($faixa_preco); ?></span>
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
                <!-- Ícone do tipo -->
                <?php if ($tipos_lugares): ?>
                    <div class="flex-shrink-0">
                        <div class="bg-recife-accent text-white rounded-lg p-2 w-10 h-10 flex items-center justify-center">
                            <?php
                            $tipo_icons = [
                                'restaurante' => '🍽️',
                                'bar' => '🍺',
                                'cafe' => '☕',
                                'teatro' => '🎭',
                                'museu' => '🏛️',
                                'cinema' => '🎬',
                                'parque' => '🌳',
                                'praia' => '🏖️'
                            ];
                            $tipo_slug = strtolower($tipos_lugares[0]->slug);
                            $icon = $tipo_icons[$tipo_slug] ?? '📍';
                            ?>
                            <span class="text-sm"><?php echo esc_html($icon); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-accent transition-colors line-clamp-2 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    
                    <?php if ($endereco_resumido): ?>
                        <p class="text-xs text-recife-gray-600 truncate">
                            <?php echo esc_html($endereco_resumido); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($faixa_preco): ?>
                        <p class="text-xs text-recife-gray-500 mt-1">
                            <?php echo esc_html($preco_icon . ' ' . $faixa_preco); ?>
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
                
                <?php if ($show_badge && $tipos_lugares): ?>
                    <div class="absolute top-3 left-3">
                        <span class="badge badge-lugares text-xs font-semibold">
                            <?php echo esc_html($tipos_lugares[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($faixa_preco): ?>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 backdrop-blur-sm text-recife-gray-900 px-2 py-1 rounded-full text-xs font-medium">
                            <?php echo esc_html($preco_icon . ' ' . $faixa_preco); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-4">
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-accent transition-colors line-clamp-2 mb-2">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-sm text-recife-gray-600 line-clamp-2 mb-3">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="space-y-2 text-sm text-recife-gray-600">
                        <?php if ($endereco_resumido): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html($endereco_resumido); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($telefone): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <span><?php echo esc_html($telefone); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($horario_funcionamento): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate"><?php echo esc_html(wp_trim_words($horario_funcionamento, 3)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($website): ?>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-recife-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="truncate">Website</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
<?php endif; ?> 