<?php
/**
 * Componente: Card Post/Notícia
 * 
 * Componente reutilizável para exibir posts e notícias em diferentes contextos
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
 * @param string $variant     Variação do card: 'hero', 'standard', 'horizontal', 'mini', 'breaking'
 * @param string $size        Tamanho: 'sm', 'md', 'lg'
 * @param bool   $show_meta   Exibir metadados (data, autor, categoria)
 * @param bool   $show_badge  Exibir badge de categoria
 * @param bool   $show_excerpt Exibir resumo
 * @param bool   $show_author Exibir autor
 * @param bool   $show_date   Exibir data
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
    'show_excerpt' => true,
    'show_author' => true,
    'show_date' => true,
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
$permalink = get_permalink($post_id);
$title = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$featured_image = get_the_post_thumbnail_url($post_id, 'large');
$post_type = get_post_type($post_id);

// Meta dados
$author_id = $post->post_author;
$author_name = get_the_author_meta('display_name', $author_id);
$author_avatar = get_avatar_url($author_id, ['size' => 32]);
$post_date = get_the_date('d/m/Y', $post_id);
$post_time = get_the_time('H:i', $post_id);
$reading_time = recifemais_estimate_reading_time($post->post_content);

// Categorias
$categories = get_the_category($post_id);
$primary_category = !empty($categories) ? $categories[0] : null;

// Tags
$tags = get_the_tags($post_id);

// Verificar se é breaking news (categoria especial ou meta field)
$is_breaking = false;
if ($primary_category && in_array($primary_category->slug, ['breaking', 'urgente', 'destaque'])) {
    $is_breaking = true;
}

// Classes CSS
$card_classes = ['card', 'card-post', "card-{$variant}", "card-{$size}"];
if ($is_breaking) {
    $card_classes[] = 'card-breaking';
}
$card_classes = array_merge($card_classes, $classes);

// Placeholder se não houver imagem
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-post.jpg';
$image_url = $featured_image ?: $placeholder_image;

?>

<?php if ($variant === 'breaking'): ?>
    <!-- Card Breaking News - Para notícias urgentes -->
    <article class="<?php echo esc_attr(implode(' ', $card_classes)); ?> group border-l-4 border-recife-primary bg-gradient-to-r from-recife-primary/5 to-transparent">
        <div class="p-4">
            <div class="flex items-start gap-4">
                <!-- Badge Breaking -->
                <div class="flex-shrink-0">
                    <span class="bg-recife-primary text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide animate-pulse">
                        🚨 Breaking
                    </span>
                </div>
                
                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <a href="<?php echo esc_url($permalink); ?>" 
                       target="<?php echo esc_attr($link_target); ?>"
                       class="block group-hover:text-recife-primary transition-colors">
                        
                        <h3 class="font-bold text-recife-gray-900 line-clamp-2 mb-2 text-lg">
                            <?php echo esc_html($title); ?>
                        </h3>
                        
                        <?php if ($show_excerpt && $excerpt): ?>
                            <p class="text-recife-gray-700 line-clamp-2 mb-2">
                                <?php echo esc_html($excerpt); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($show_meta): ?>
                            <div class="flex items-center gap-3 text-sm text-recife-gray-600">
                                <?php if ($show_date): ?>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?php echo esc_html($post_time); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($reading_time): ?>
                                    <span><?php echo esc_html($reading_time); ?> min</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </article>

<?php elseif ($variant === 'hero'): ?>
    <!-- Card Hero - Para manchetes principais -->
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
                <?php if ($show_badge && $primary_category): ?>
                    <div class="mb-3">
                        <span class="bg-recife-primary text-white px-3 py-1 rounded-full text-xs font-semibold uppercase">
                            <?php echo esc_html($primary_category->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h2 class="card-hero-title text-white mb-3 group-hover:text-recife-secondary transition-colors">
                    <?php echo esc_html($title); ?>
                </h2>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-gray-200 line-clamp-2 mb-3 text-lg">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                        <?php if ($show_author): ?>
                            <span class="flex items-center gap-2">
                                <img src="<?php echo esc_url($author_avatar); ?>" 
                                     alt="<?php echo esc_attr($author_name); ?>"
                                     class="w-6 h-6 rounded-full">
                                <?php echo esc_html($author_name); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($show_date): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($post_date); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($reading_time): ?>
                            <span><?php echo esc_html($reading_time); ?> min de leitura</span>
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
                <?php if ($show_badge && $primary_category): ?>
                    <div class="mb-1">
                        <span class="text-recife-primary text-xs font-semibold uppercase">
                            <?php echo esc_html($primary_category->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-2 mb-1">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-sm text-recife-gray-600 line-clamp-2 mb-2">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="text-xs text-recife-gray-500 space-y-1">
                        <div class="flex items-center gap-3">
                            <?php if ($show_author): ?>
                                <span><?php echo esc_html($author_name); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($show_date): ?>
                                <span><?php echo esc_html($post_date); ?></span>
                            <?php endif; ?>
                            
                            <?php if ($reading_time): ?>
                                <span><?php echo esc_html($reading_time); ?>min</span>
                            <?php endif; ?>
                        </div>
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
                <!-- Número ou ícone -->
                <div class="flex-shrink-0">
                    <div class="bg-recife-gray-200 text-recife-gray-700 rounded-lg p-2 w-8 h-8 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V9a1 1 0 00-1-1h-1v-1z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Conteúdo -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-sm text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-3 mb-1">
                        <?php echo esc_html($title); ?>
                    </h4>
                    
                    <?php if ($show_date): ?>
                        <p class="text-xs text-recife-gray-500">
                            <?php echo esc_html($post_date); ?>
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
                
                <?php if ($show_badge && $primary_category): ?>
                    <div class="absolute top-3 left-3">
                        <span class="bg-recife-primary text-white px-2 py-1 rounded-full text-xs font-semibold">
                            <?php echo esc_html($primary_category->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ($reading_time): ?>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 backdrop-blur-sm text-recife-gray-900 px-2 py-1 rounded-full text-xs font-medium">
                            <?php echo esc_html($reading_time); ?> min
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Conteúdo -->
            <div class="p-4">
                <h3 class="font-semibold text-recife-gray-900 group-hover:text-recife-primary transition-colors line-clamp-2 mb-2">
                    <?php echo esc_html($title); ?>
                </h3>
                
                <?php if ($show_excerpt && $excerpt): ?>
                    <p class="text-sm text-recife-gray-600 line-clamp-3 mb-3">
                        <?php echo esc_html($excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($show_meta): ?>
                    <div class="flex items-center justify-between text-sm text-recife-gray-500">
                        <div class="flex items-center gap-2">
                            <?php if ($show_author): ?>
                                <img src="<?php echo esc_url($author_avatar); ?>" 
                                     alt="<?php echo esc_attr($author_name); ?>"
                                     class="w-5 h-5 rounded-full">
                                <span class="truncate"><?php echo esc_html($author_name); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($show_date): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo esc_html($post_date); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($tags && count($tags) > 0): ?>
                    <div class="mt-3 flex flex-wrap gap-1">
                        <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                            <span class="bg-recife-gray-100 text-recife-gray-600 px-2 py-1 rounded text-xs">
                                #<?php echo esc_html($tag->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </article>
<?php endif; ?>

<?php
/**
 * Função auxiliar para estimar tempo de leitura
 */
if (!function_exists('recifemais_estimate_reading_time')) {
    function recifemais_estimate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 palavras por minuto
        return max(1, $reading_time);
    }
}
?> 