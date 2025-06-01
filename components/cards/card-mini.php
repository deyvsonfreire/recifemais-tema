<?php
/**
 * Componente: Card Mini
 * 
 * Card compacto para widgets, sidebars e listas pequenas
 * Design minimalista com informações essenciais
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
 * @param int    $post_id     ID do post (obrigatório)
 * @param string $variant     Variação: 'default', 'icon', 'number', 'date'
 * @param bool   $show_image  Exibir imagem/ícone
 * @param bool   $show_meta   Exibir metadados
 * @param bool   $show_date   Exibir data
 * @param bool   $show_badge  Exibir badge pequeno
 * @param string $date_format Formato da data: 'relative', 'short', 'full'
 * @param string $link_target Target do link: '_self', '_blank'
 * @param array  $classes     Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'post_id' => get_the_ID(),
    'variant' => 'default',
    'show_image' => true,
    'show_meta' => true,
    'show_date' => true,
    'show_badge' => false,
    'date_format' => 'relative',
    'link_target' => '_self',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Validação
if (!$post_id) {
    return;
}

// Dados do post
$post = get_post($post_id);
$post_type = get_post_type($post_id);
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'thumbnail');
$date = get_the_date('d/m/Y', $post_id);
$time_ago = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) . ' atrás';

// Formatação de data
$formatted_date = '';
switch ($date_format) {
    case 'relative':
        $formatted_date = $time_ago;
        break;
    case 'short':
        $formatted_date = get_the_date('d/m', $post_id);
        break;
    case 'full':
        $formatted_date = $date;
        break;
}

// Detectar tipo de conteúdo e configurar ícone/badge
$content_info = recifemais_get_mini_content_info($post_id, $post_type);

// Classes CSS
$card_classes = ['card-mini', "card-mini-{$variant}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-mini.jpg';
$image_url = $featured_image ?: $placeholder_image;

/**
 * Função auxiliar para obter informações específicas do conteúdo
 */
function recifemais_get_mini_content_info($post_id, $post_type) {
    $info = [
        'icon' => '📄',
        'color' => 'bg-recife-gray-500',
        'meta' => '',
        'badge' => ''
    ];
    
    switch ($post_type) {
        case 'eventos_festivais':
            $info['icon'] = '🎭';
            $info['color'] = 'bg-recife-primary';
            $data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
            if ($data_inicio) {
                $data_obj = DateTime::createFromFormat('Y-m-d', $data_inicio);
                if ($data_obj) {
                    $info['meta'] = $data_obj->format('d/m');
                }
            }
            $terms = get_the_terms($post_id, 'tipos_eventos');
            $info['badge'] = $terms ? $terms[0]->name : 'Evento';
            break;
            
        case 'lugares':
            $info['icon'] = '📍';
            $info['color'] = 'bg-recife-accent';
            $bairros = get_the_terms($post_id, 'bairros_recife');
            $info['meta'] = $bairros ? $bairros[0]->name : '';
            $terms = get_the_terms($post_id, 'tipos_lugares');
            $info['badge'] = $terms ? $terms[0]->name : 'Local';
            break;
            
        case 'artistas':
            $info['icon'] = '🎨';
            $info['color'] = 'bg-recife-creative';
            $origem = get_post_meta($post_id, 'artista_origem', true);
            $info['meta'] = $origem ?: '';
            $terms = get_the_terms($post_id, 'generos_musicais');
            $info['badge'] = $terms ? $terms[0]->name : 'Artista';
            break;
            
        case 'roteiros':
            $info['icon'] = '🗺️';
            $info['color'] = 'bg-recife-secondary';
            $duracao = get_post_meta($post_id, 'roteiro_duracao', true);
            $info['meta'] = $duracao ?: '';
            $terms = get_the_terms($post_id, 'tipos_roteiros');
            $info['badge'] = $terms ? $terms[0]->name : 'Roteiro';
            break;
            
        case 'post':
            $info['icon'] = '📰';
            $info['color'] = 'bg-recife-accent';
            $author_name = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
            $info['meta'] = $author_name;
            $terms = get_the_category($post_id);
            $info['badge'] = $terms ? $terms[0]->name : 'Notícia';
            break;
            
        default:
            $info['badge'] = ucfirst($post_type);
    }
    
    return $info;
}

?>

<article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
    <a href="<?php echo esc_url($permalink); ?>" 
       target="<?php echo esc_attr($link_target); ?>"
       class="block p-3 rounded-lg hover:bg-recife-gray-50 transition-all duration-200">
        
        <?php if ($variant === 'icon'): ?>
            <!-- Variação com ícone -->
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 <?php echo esc_attr($content_info['color']); ?> text-white rounded-lg flex items-center justify-center">
                        <span class="text-sm"><?php echo esc_html($content_info['icon']); ?></span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    <?php if ($show_meta && $content_info['meta']): ?>
                        <p class="text-xs text-recife-gray-500 truncate">
                            <?php echo esc_html($content_info['meta']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php elseif ($variant === 'number'): ?>
            <!-- Variação com número (para rankings, listas numeradas) -->
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-recife-primary text-white rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold"><?php echo esc_html($args['number'] ?? '1'); ?></span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-2">
                        <?php echo esc_html($title); ?>
                    </h4>
                    <?php if ($show_date): ?>
                        <p class="text-xs text-recife-gray-500 mt-1">
                            <?php echo esc_html($formatted_date); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php elseif ($variant === 'date'): ?>
            <!-- Variação com data destacada -->
            <div class="flex items-start gap-3">
                <?php if ($post_type === 'eventos_festivais'): ?>
                    <?php
                    $data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
                    if ($data_inicio):
                        $data_obj = DateTime::createFromFormat('Y-m-d', $data_inicio);
                        if ($data_obj):
                            ?>
                            <div class="flex-shrink-0 text-center">
                                <div class="bg-recife-primary text-white rounded-lg p-2 min-w-[3rem]">
                                    <div class="text-xs font-semibold leading-none">
                                        <?php echo esc_html($data_obj->format('M')); ?>
                                    </div>
                                    <div class="text-lg font-bold leading-none mt-1">
                                        <?php echo esc_html($data_obj->format('d')); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    endif;
                else: ?>
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-recife-gray-100 rounded-lg overflow-hidden">
                            <img src="<?php echo esc_url($image_url); ?>" 
                                 alt="<?php echo esc_attr($title); ?>"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-2 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    <?php if ($show_meta && $content_info['meta']): ?>
                        <p class="text-xs text-recife-gray-500 truncate">
                            <?php echo esc_html($content_info['meta']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Variação padrão -->
            <div class="flex items-start gap-3">
                <?php if ($show_image): ?>
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-recife-gray-100 rounded-lg overflow-hidden">
                            <img src="<?php echo esc_url($image_url); ?>" 
                                 alt="<?php echo esc_attr($title); ?>"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                 loading="lazy">
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="flex-1 min-w-0">
                    <?php if ($show_badge && $content_info['badge']): ?>
                        <div class="mb-1">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-recife-gray-100 text-recife-gray-700">
                                <?php echo esc_html($content_info['badge']); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-2 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    
                    <?php if ($show_meta): ?>
                        <div class="flex items-center gap-2 text-xs text-recife-gray-500">
                            <?php if ($show_date): ?>
                                <span><?php echo esc_html($formatted_date); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($content_info['meta']): ?>
                                <?php if ($show_date): ?>
                                    <span>•</span>
                                <?php endif; ?>
                                <span class="truncate"><?php echo esc_html($content_info['meta']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </a>
</article> 