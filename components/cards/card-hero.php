<?php
/**
 * Componente: Card Hero
 * 
 * Card genérico para destaques principais - adaptável a qualquer tipo de conteúdo
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
 * @param string $size        Tamanho: 'sm', 'md', 'lg', 'xl'
 * @param string $aspect      Aspect ratio: 'hero' (16:9), 'square' (1:1), 'portrait' (3:4)
 * @param bool   $show_meta   Exibir metadados
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_author Exibir autor (para posts)
 * @param bool   $show_date   Exibir data
 * @param string $overlay     Intensidade do overlay: 'light', 'medium', 'dark'
 * @param string $text_position Posição do texto: 'bottom', 'center', 'top'
 * @param string $link_target Target do link: '_self', '_blank'
 * @param array  $classes     Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'post_id' => get_the_ID(),
    'size' => 'lg',
    'aspect' => 'hero',
    'show_meta' => true,
    'show_badge' => true,
    'show_excerpt' => false,
    'show_author' => false,
    'show_date' => true,
    'overlay' => 'medium',
    'text_position' => 'bottom',
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
$featured_image = get_the_post_thumbnail_url($post_id, 'large');
$date = get_the_date('d/m/Y', $post_id);

// Dados do autor (para posts)
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id);
$author_avatar = get_avatar_url($author_id, ['size' => 32]);

// Detectar tipo de conteúdo e configurar badge
$badge_info = recifemais_get_post_badge_info($post_id, $post_type);

// Classes de overlay
$overlay_classes = [
    'light' => 'from-black/40 via-black/10 to-transparent',
    'medium' => 'from-black/60 via-black/20 to-transparent',
    'dark' => 'from-black/80 via-black/40 to-transparent'
];
$overlay_class = $overlay_classes[$overlay] ?? $overlay_classes['medium'];

// Classes de posição do texto
$text_position_classes = [
    'bottom' => 'absolute bottom-0 left-0 right-0',
    'center' => 'absolute inset-0 flex items-center',
    'top' => 'absolute top-0 left-0 right-0'
];
$text_position_class = $text_position_classes[$text_position] ?? $text_position_classes['bottom'];

// Classes CSS
$card_classes = ['card-hero', "card-hero-{$size}", "aspect-{$aspect}"];
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-hero.jpg';
$image_url = $featured_image ?: $placeholder_image;

/**
 * Função auxiliar para obter informações de badge por tipo de post
 */
function recifemais_get_post_badge_info($post_id, $post_type) {
    $badge_info = [
        'text' => '',
        'class' => 'badge-primary',
        'color' => 'var(--recife-primary)'
    ];
    
    switch ($post_type) {
        case 'eventos_festivais':
            $terms = get_the_terms($post_id, 'tipos_eventos');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Evento';
            $badge_info['class'] = 'badge-eventos';
            break;
            
        case 'lugares':
            $terms = get_the_terms($post_id, 'tipos_lugares');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Local';
            $badge_info['class'] = 'badge-lugares';
            break;
            
        case 'artistas':
            $terms = get_the_terms($post_id, 'generos_musicais');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Artista';
            $badge_info['class'] = 'badge-artistas';
            break;
            
        case 'roteiros':
            $terms = get_the_terms($post_id, 'tipos_roteiros');
            $badge_info['text'] = $terms ? $terms[0]->name : 'Roteiro';
            $badge_info['class'] = 'badge-roteiros';
            break;
            
        case 'post':
            $terms = get_the_category($post_id);
            $badge_info['text'] = $terms ? $terms[0]->name : 'Notícia';
            $badge_info['class'] = 'badge-noticias';
            break;
            
        default:
            $badge_info['text'] = ucfirst($post_type);
            $badge_info['class'] = 'badge-default';
    }
    
    return $badge_info;
}

?>

<article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group relative overflow-hidden rounded-xl">
    <a href="<?php echo esc_url($permalink); ?>" 
       target="<?php echo esc_attr($link_target); ?>"
       class="block relative w-full h-full">
        
        <!-- Imagem de fundo -->
        <div class="absolute inset-0 bg-gradient-to-t <?php echo esc_attr($overlay_class); ?>">
            <img src="<?php echo esc_url($image_url); ?>" 
                 alt="<?php echo esc_attr($title); ?>"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 loading="lazy">
        </div>
        
        <!-- Conteúdo sobreposto -->
        <div class="<?php echo esc_attr($text_position_class); ?> p-6 text-white z-10">
            <?php if ($text_position === 'center'): ?>
                <div class="text-center max-w-2xl mx-auto">
            <?php endif; ?>
            
            <!-- Badge de categoria -->
            <?php if ($show_badge && $badge_info['text']): ?>
                <div class="mb-3">
                    <span class="<?php echo esc_attr($badge_info['class']); ?> text-xs font-semibold px-3 py-1 rounded-full">
                        <?php echo esc_html($badge_info['text']); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Título principal -->
            <h2 class="card-hero-title text-white mb-3 group-hover:text-opacity-90 transition-all duration-300">
                <?php echo esc_html($title); ?>
            </h2>
            
            <!-- Resumo (se habilitado) -->
            <?php if ($show_excerpt && $excerpt): ?>
                <p class="text-gray-100 text-lg leading-relaxed mb-4 line-clamp-2">
                    <?php echo esc_html($excerpt); ?>
                </p>
            <?php endif; ?>
            
            <!-- Meta informações -->
            <?php if ($show_meta): ?>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-200">
                    
                    <!-- Data -->
                    <?php if ($show_date): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo esc_html($date); ?>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Autor (para posts) -->
                    <?php if ($show_author && $post_type === 'post'): ?>
                        <span class="flex items-center gap-2">
                            <img src="<?php echo esc_url($author_avatar); ?>" 
                                 alt="<?php echo esc_attr($author_name); ?>"
                                 class="w-5 h-5 rounded-full">
                            <span><?php echo esc_html($author_name); ?></span>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Meta específico por tipo de post -->
                    <?php
                    switch ($post_type) {
                        case 'eventos_festivais':
                            $local_id = get_post_meta($post_id, 'evento_local', true);
                            if ($local_id):
                                $local_nome = get_the_title($local_id);
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo esc_html($local_nome); ?>
                                </span>
                                <?php
                            endif;
                            break;
                            
                        case 'lugares':
                            $bairros = get_the_terms($post_id, 'bairros_recife');
                            if ($bairros):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo esc_html($bairros[0]->name); ?>
                                </span>
                                <?php
                            endif;
                            break;
                            
                        case 'artistas':
                            $origem = get_post_meta($post_id, 'artista_origem', true);
                            if ($origem):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo esc_html($origem); ?>
                                </span>
                                <?php
                            endif;
                            break;
                            
                        case 'roteiros':
                            $duracao = get_post_meta($post_id, 'roteiro_duracao', true);
                            if ($duracao):
                                ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo esc_html($duracao); ?>
                                </span>
                                <?php
                            endif;
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if ($text_position === 'center'): ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Indicador de hover -->
        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </a>
</article> 