<?php
/**
 * Componente: Card Horizontal
 * 
 * Card genérico em formato horizontal - adaptável a qualquer tipo de conteúdo
 * Ideal para listas, sidebars e layouts compactos
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
 * @param string $size        Tamanho: 'sm', 'md', 'lg'
 * @param string $image_size  Tamanho da imagem: 'thumb' (60px), 'small' (80px), 'medium' (120px)
 * @param bool   $show_meta   Exibir metadados
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_date   Exibir data
 * @param bool   $show_author Exibir autor (para posts)
 * @param int    $excerpt_length Número de palavras do resumo
 * @param string $link_target Target do link: '_self', '_blank'
 * @param array  $classes     Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'post_id' => get_the_ID(),
    'size' => 'md',
    'image_size' => 'small',
    'show_meta' => true,
    'show_badge' => false,
    'show_excerpt' => true,
    'show_date' => true,
    'show_author' => false,
    'excerpt_length' => 15,
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
$excerpt = get_the_excerpt($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'medium');
$date = get_the_date('d/m/Y', $post_id);
$time_ago = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) . ' atrás';

// Dados do autor (para posts)
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id);

// Detectar tipo de conteúdo e configurar badge
$badge_info = recifemais_get_post_badge_info_horizontal($post_id, $post_type);

// Tamanhos de imagem
$image_sizes = [
    'thumb' => 'w-15 h-15', // 60px
    'small' => 'w-20 h-20', // 80px
    'medium' => 'w-30 h-30' // 120px
];
$image_size_class = $image_sizes[$image_size] ?? $image_sizes['small'];

// Classes CSS
$card_classes = ['card-horizontal', "card-horizontal-{$size}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-thumb.jpg';
$image_url = $featured_image ?: $placeholder_image;

// Truncar excerpt se necessário
if ($excerpt && $excerpt_length > 0) {
    $excerpt = wp_trim_words($excerpt, $excerpt_length, '...');
}

/**
 * Função auxiliar para obter informações de badge por tipo de post
 */
function recifemais_get_post_badge_info_horizontal($post_id, $post_type) {
    $badge_info = [
        'text' => '',
        'class' => 'badge-primary',
        'icon' => ''
    ];
    
    switch ($post_type) {
        case 'eventos_festivais':
            $terms = get_the_terms($post_id, 'tipos_eventos');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Evento';
            $badge_info['class'] = 'badge-eventos';
            $badge_info['icon'] = '🎭';
            break;
            
        case 'lugares':
            $terms = get_the_terms($post_id, 'tipos_lugares');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Local';
            $badge_info['class'] = 'badge-lugares';
            $badge_info['icon'] = '📍';
            break;
            
        case 'artistas':
            $terms = get_the_terms($post_id, 'generos_musicais');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Artista';
            $badge_info['class'] = 'badge-artistas';
            $badge_info['icon'] = '🎨';
            break;
            
        case 'roteiros':
            $terms = get_the_terms($post_id, 'tipos_roteiros');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Roteiro';
            $badge_info['class'] = 'badge-roteiros';
            $badge_info['icon'] = '🗺️';
            break;
            
        case 'post':
            $terms = get_the_category($post_id);
            $badge_info['text'] = $terms ? $terms[0]->name : 'Notícia';
            $badge_info['class'] = 'badge-noticias';
            $badge_info['icon'] = '📰';
            break;
            
        default:
            $badge_info['text'] = ucfirst($post_type);
            $badge_info['class'] = 'badge-default';
            $badge_info['icon'] = '📄';
    }
    
    return $badge_info;
}

?>

<article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group">
    <a href="<?php echo esc_url($permalink); ?>" 
       target="<?php echo esc_attr($link_target); ?>"
       class="flex items-start gap-4 p-4 rounded-lg hover:bg-recife-gray-50 transition-all duration-200">
        
        <!-- Imagem -->
        <div class="flex-shrink-0">
            <div class="<?php echo esc_attr($image_size_class); ?> bg-recife-gray-100 rounded-lg overflow-hidden">
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($title); ?>"
                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                     loading="lazy">
            </div>
        </div>
        
        <!-- Conteúdo -->
        <div class="flex-1 min-w-0">
            
            <!-- Badge de categoria -->
            <?php if ($show_badge && $badge_info['text']): ?>
                <div class="mb-2">
                    <span class="<?php echo esc_attr($badge_info['class']); ?> text-xs font-medium px-2 py-1 rounded">
                        <?php if ($badge_info['icon']): ?>
                            <span class="mr-1"><?php echo esc_html($badge_info['icon']); ?></span>
                        <?php endif; ?>
                        <?php echo esc_html($badge_info['text']); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Título -->
            <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-primary transition-colors duration-200 line-clamp-2 mb-2">
                <?php echo esc_html($title); ?>
            </h3>
            
            <!-- Resumo -->
            <?php if ($show_excerpt && $excerpt): ?>
                <p class="text-sm text-recife-gray-600 line-clamp-2 mb-3">
                    <?php echo esc_html($excerpt); ?>
                </p>
            <?php endif; ?>
            
            <!-- Meta informações -->
            <?php if ($show_meta): ?>
                <div class="flex flex-wrap items-center gap-3 text-xs text-recife-gray-500">
                    
                    <!-- Data -->
                    <?php if ($show_date): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span><?php echo esc_html($time_ago); ?></span>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Autor (para posts) -->
                    <?php if ($show_author && $post_type === 'post'): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            <span><?php echo esc_html($author_name); ?></span>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Meta específico por tipo de post -->
                    <?php
                    switch ($post_type) {
                        case 'eventos_festivais':
                            $data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
                            if ($data_inicio):
                                $data_obj = DateTime::createFromFormat('Y-m-d', $data_inicio);
                                if ($data_obj):
                                    ?>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span><?php echo esc_html($data_obj->format('d/m')); ?></span>
                                    </span>
                                    <?php
                                endif;
                            endif;
                            break;
                            
                        case 'lugares':
                            $bairros = get_the_terms($post_id, 'bairros_recife');
                            if ($bairros):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span><?php echo esc_html($bairros[0]->name); ?></span>
                                </span>
                                <?php
                            endif;
                            break;
                            
                        case 'artistas':
                            $origem = get_post_meta($post_id, 'artista_origem', true);
                            if ($origem):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span><?php echo esc_html($origem); ?></span>
                                </span>
                                <?php
                            endif;
                            break;
                            
                        case 'roteiros':
                            $duracao = get_post_meta($post_id, 'roteiro_duracao', true);
                            if ($duracao):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span><?php echo esc_html($duracao); ?></span>
                                </span>
                                <?php
                            endif;
                            break;
                    }
                    ?>
                    
                    <!-- Tempo de leitura (para posts) -->
                    <?php if ($post_type === 'post'): ?>
                        <?php
                        $content = get_post_field('post_content', $post_id);
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 200); // 200 palavras por minuto
                        ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                            </svg>
                            <span><?php echo esc_html($reading_time); ?> min</span>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Indicador de link externo (se aplicável) -->
        <?php if ($link_target === '_blank'): ?>
            <div class="flex-shrink-0 ml-2">
                <svg class="w-4 h-4 text-recife-gray-400 group-hover:text-recife-primary transition-colors" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                </svg>
            </div>
        <?php endif; ?>
    </a>
</article> 