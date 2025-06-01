<?php
/**
 * Template Part: Meta Post
 * 
 * Meta dados para posts padrão com:
 * - Informações do autor
 * - Data e hora de publicação
 * - Categoria e tags
 * - Tempo de leitura estimado
 * - Estatísticas de visualização
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do post
$post_id = get_the_ID();
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name');
$author_avatar = get_avatar_url($author_id, ['size' => 40]);
$post_date = get_the_date('d/m/Y');
$post_time = get_the_time('H:i');
$post_categories = get_the_category();
$post_tags = get_the_tags();

// Meta dados customizados
$post_views = get_post_meta($post_id, 'post_views', true) ?: 0;
$reading_time = recifemais_reading_time($post_id);
$last_updated = get_the_modified_date('d/m/Y');
$is_updated = $last_updated !== $post_date;

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_author' => true,
    'show_date' => true,
    'show_category' => true,
    'show_tags' => true,
    'show_reading_time' => true,
    'show_views' => true,
    'show_updated_date' => true,
    'show_share_count' => false,
    'layout' => 'horizontal' // horizontal, vertical, compact
]);

// Contagem de compartilhamentos (simulado)
$share_count = rand(5, 150);
?>

<div class="post-meta <?php echo esc_attr('layout-' . $args['layout']); ?> bg-gray-50 rounded-lg p-4 mb-6">
    
    <?php if ($args['layout'] === 'horizontal'): ?>
        <!-- Layout Horizontal -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            
            <!-- Informações Principais -->
            <div class="flex flex-wrap items-center gap-4">
                
                <?php if ($args['show_author']): ?>
                    <!-- Autor -->
                    <div class="flex items-center gap-3">
                        <img src="<?php echo esc_url($author_avatar); ?>" 
                             alt="<?php echo esc_attr($author_name); ?>"
                             class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                <a href="<?php echo get_author_posts_url($author_id); ?>" 
                                   class="hover:text-recife-primary transition-colors">
                                    <?php echo esc_html($author_name); ?>
                                </a>
                            </p>
                            <p class="text-xs text-gray-500">Jornalista</p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_date']): ?>
                    <!-- Data e Hora -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo esc_html($post_date); ?> às <?php echo esc_html($post_time); ?>
                        </time>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_reading_time'] && $reading_time): ?>
                    <!-- Tempo de Leitura -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($reading_time); ?> min de leitura</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Estatísticas -->
            <div class="flex items-center gap-4">
                
                <?php if ($args['show_views']): ?>
                    <!-- Visualizações -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo number_format($post_views); ?> visualizações</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_share_count']): ?>
                    <!-- Compartilhamentos -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                        </svg>
                        <span><?php echo number_format($share_count); ?> compartilhamentos</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    <?php elseif ($args['layout'] === 'vertical'): ?>
        <!-- Layout Vertical -->
        <div class="space-y-4">
            
            <?php if ($args['show_author']): ?>
                <!-- Autor -->
                <div class="flex items-center gap-3">
                    <img src="<?php echo esc_url($author_avatar); ?>" 
                         alt="<?php echo esc_attr($author_name); ?>"
                         class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <div>
                        <p class="font-semibold text-gray-900">
                            <a href="<?php echo get_author_posts_url($author_id); ?>" 
                               class="hover:text-recife-primary transition-colors">
                                <?php echo esc_html($author_name); ?>
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">Jornalista</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Meta Informações -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                
                <?php if ($args['show_date']): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($post_date); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_reading_time'] && $reading_time): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo esc_html($reading_time); ?> min</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_views']): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span><?php echo number_format($post_views); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_share_count']): ?>
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                        </svg>
                        <span><?php echo number_format($share_count); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Layout Compact -->
        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
            
            <?php if ($args['show_author']): ?>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?php echo get_author_posts_url($author_id); ?>" 
                       class="hover:text-recife-primary transition-colors">
                        <?php echo esc_html($author_name); ?>
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if ($args['show_date']): ?>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo esc_html($post_date); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($args['show_reading_time'] && $reading_time): ?>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo esc_html($reading_time); ?>min
                </span>
            <?php endif; ?>
            
            <?php if ($args['show_views']): ?>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo number_format($post_views); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Categoria e Tags -->
    <?php if (($args['show_category'] && !empty($post_categories)) || ($args['show_tags'] && !empty($post_tags))): ?>
        <div class="mt-4 pt-4 border-t border-gray-200">
            
            <?php if ($args['show_category'] && !empty($post_categories)): ?>
                <!-- Categoria -->
                <div class="mb-3">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">Categoria</span>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($post_categories as $category): ?>
                            <a href="<?php echo get_category_link($category); ?>" 
                               class="inline-flex items-center gap-1 bg-recife-primary/10 text-recife-primary px-3 py-1 rounded-full text-sm font-medium hover:bg-recife-primary/20 transition-colors">
                                📰 <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($args['show_tags'] && !empty($post_tags)): ?>
                <!-- Tags -->
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">Tags</span>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($post_tags as $tag): ?>
                            <a href="<?php echo get_tag_link($tag); ?>" 
                               class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium hover:bg-gray-200 transition-colors">
                                #<?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Data de Atualização -->
    <?php if ($args['show_updated_date'] && $is_updated): ?>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                </svg>
                <span>Última atualização: <?php echo esc_html($last_updated); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.post-meta.layout-horizontal {
    @apply flex-row;
}

.post-meta.layout-vertical {
    @apply flex-col;
}

.post-meta.layout-compact {
    @apply p-3;
}

@media (max-width: 768px) {
    .post-meta.layout-horizontal {
        @apply flex-col space-y-3;
    }
    
    .post-meta.layout-horizontal .flex:first-child {
        @apply flex-col items-start space-y-3;
    }
}
</style> 