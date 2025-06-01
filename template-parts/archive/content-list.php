<?php
/**
 * Template Part: Archive Content List
 * 
 * Lista linear para exibição de conteúdo em archives com:
 * - Layout horizontal otimizado
 * - Cards compactos
 * - Informações essenciais
 * - Responsivo mobile-first
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Detectar contexto atual
$current_post_type = get_post_type();

// Verificar se há posts
if (!have_posts()) {
    get_template_part('template-parts/archive/no-results');
    return;
}
?>

<div class="archive-content-list" id="content-list">
    <div class="space-y-4">
        <?php 
        $post_index = 0;
        while (have_posts()): 
            the_post();
            $post_index++;
            
            // Delay de animação escalonado
            $animation_delay = min($post_index * 50, 400);
            ?>
            
            <article class="list-item bg-white rounded-lg border border-gray-200 hover:shadow-md transition-all duration-300 animate-fade-in-up" 
                     style="animation-delay: <?php echo $animation_delay; ?>ms"
                     data-post-id="<?php echo get_the_ID(); ?>"
                     data-post-type="<?php echo get_post_type(); ?>">
                
                <div class="flex items-center gap-4 p-4">
                    <!-- Thumbnail -->
                    <div class="flex-shrink-0">
                        <a href="<?php the_permalink(); ?>" class="block">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" 
                                     alt="<?php echo esc_attr(get_the_title()); ?>"
                                     class="w-20 h-20 object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <!-- Conteúdo Principal -->
                    <div class="flex-1 min-w-0">
                        <!-- Título -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <!-- Excerpt -->
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                        </p>
                        
                        <!-- Meta Info -->
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <!-- Data -->
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php echo get_the_date('d/m/Y'); ?>
                            </span>
                            
                            <!-- Autor (para posts) -->
                            <?php if (get_post_type() === 'post'): ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php echo get_the_author(); ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Categoria/Tipo -->
                            <?php 
                            $categories = get_the_category();
                            if (!empty($categories)): ?>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <?php echo esc_html($categories[0]->name); ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Tempo de leitura estimado -->
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php 
                                $word_count = str_word_count(strip_tags(get_the_content()));
                                $reading_time = ceil($word_count / 200); // 200 palavras por minuto
                                echo $reading_time . ' min';
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Meta específica por tipo de conteúdo -->
                    <div class="flex-shrink-0 text-right">
                        <?php if (get_post_type() === 'eventos_festivais'): ?>
                            <!-- Meta para eventos -->
                            <?php 
                            $data_inicio = get_post_meta(get_the_ID(), 'data_inicio', true);
                            $preco = get_post_meta(get_the_ID(), 'preco', true);
                            ?>
                            <div class="space-y-1">
                                <?php if ($data_inicio): ?>
                                    <div class="text-xs font-medium text-blue-600">
                                        <?php echo date('d/m', strtotime($data_inicio)); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($preco): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo $preco === '0' ? 'Gratuito' : 'R$ ' . $preco; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        <?php elseif (get_post_type() === 'lugares'): ?>
                            <!-- Meta para lugares -->
                            <?php 
                            $bairro = get_post_meta(get_the_ID(), 'bairro', true);
                            $tipo_lugar = get_post_meta(get_the_ID(), 'tipo_lugar', true);
                            ?>
                            <div class="space-y-1">
                                <?php if ($bairro): ?>
                                    <div class="text-xs font-medium text-green-600">
                                        <?php echo esc_html($bairro); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($tipo_lugar): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo esc_html($tipo_lugar); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        <?php elseif (get_post_type() === 'roteiros'): ?>
                            <!-- Meta para roteiros -->
                            <?php 
                            $duracao = get_post_meta(get_the_ID(), 'duracao', true);
                            $dificuldade = get_post_meta(get_the_ID(), 'dificuldade', true);
                            ?>
                            <div class="space-y-1">
                                <?php if ($duracao): ?>
                                    <div class="text-xs font-medium text-orange-600">
                                        <?php echo esc_html($duracao); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($dificuldade): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo esc_html($dificuldade); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        <?php else: ?>
                            <!-- Meta padrão para posts -->
                            <div class="space-y-1">
                                <div class="text-xs font-medium text-gray-600">
                                    <?php comments_number('0', '1', '%'); ?> comentários
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo get_post_views(get_the_ID()) ?: rand(50, 500); ?> views
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Botão de ação -->
                        <div class="mt-3">
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full hover:bg-blue-100 transition-colors">
                                Ler mais
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            
        <?php endwhile; ?>
    </div>
</div>

<!-- CSS específico para lista -->
<style>
.archive-content-list {
    min-height: 400px;
}

.list-item {
    opacity: 0;
    transform: translateY(20px);
}

.list-item.animate-fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.list-item:hover {
    transform: translateY(-2px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .list-item .flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .list-item .flex-shrink-0:first-child {
        align-self: center;
    }
    
    .list-item .flex-shrink-0:last-child {
        align-self: stretch;
        text-align: left;
    }
    
    .list-item .flex-shrink-0:last-child > div:last-child {
        margin-top: 1rem;
        text-align: center;
    }
}

/* Print styles */
@media print {
    .list-item {
        break-inside: avoid;
        margin-bottom: 1rem;
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
    
    .list-item a {
        color: #000 !important;
        text-decoration: none;
    }
    
    .list-item img {
        filter: grayscale(100%);
    }
}
</style> 