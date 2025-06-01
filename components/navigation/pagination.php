<?php
/**
 * Componente: Paginação
 * 
 * Navegação entre páginas moderna e acessível
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
 * @param WP_Query $query        Query object (padrão: global $wp_query)
 * @param string   $type         Tipo: 'numbered', 'simple', 'load_more'
 * @param int      $mid_size     Número de páginas ao redor da atual (padrão: 2)
 * @param int      $end_size     Número de páginas no início e fim (padrão: 1)
 * @param string   $prev_text    Texto do botão anterior
 * @param string   $next_text    Texto do botão próximo
 * @param bool     $show_all     Mostrar todas as páginas
 * @param array    $classes      Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'query' => null,
    'type' => 'numbered',
    'mid_size' => 2,
    'end_size' => 1,
    'prev_text' => '‹ Anterior',
    'next_text' => 'Próximo ›',
    'show_all' => false,
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Usar query global se não especificada
if (!$query) {
    global $wp_query;
    $query = $wp_query;
}

// Verificar se há páginas suficientes
$total_pages = $query->max_num_pages;
if ($total_pages <= 1) {
    return;
}

$current_page = max(1, get_query_var('paged'));

// Classes CSS
$pagination_classes = ['pagination', 'flex', 'items-center', 'justify-center', 'gap-2', 'mt-8'];
$pagination_classes = array_merge($pagination_classes, $classes);

?>

<?php if ($type === 'simple'): ?>
    <!-- Paginação Simples - Apenas Anterior/Próximo -->
    <nav class="<?php echo esc_attr(implode(' ', $pagination_classes)); ?>" aria-label="Navegação de páginas">
        <div class="flex items-center justify-between w-full max-w-sm mx-auto">
            <?php if ($current_page > 1): ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>" 
                   class="btn btn-outline btn-sm flex items-center gap-2 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo esc_html($prev_text); ?>
                </a>
            <?php else: ?>
                <span class="btn btn-outline btn-sm opacity-50 cursor-not-allowed flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo esc_html($prev_text); ?>
                </span>
            <?php endif; ?>
            
            <span class="text-sm text-recife-gray-600">
                Página <?php echo esc_html($current_page); ?> de <?php echo esc_html($total_pages); ?>
            </span>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>" 
                   class="btn btn-outline btn-sm flex items-center gap-2 group">
                    <?php echo esc_html($next_text); ?>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            <?php else: ?>
                <span class="btn btn-outline btn-sm opacity-50 cursor-not-allowed flex items-center gap-2">
                    <?php echo esc_html($next_text); ?>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            <?php endif; ?>
        </div>
    </nav>

<?php elseif ($type === 'load_more'): ?>
    <!-- Load More Button -->
    <?php if ($current_page < $total_pages): ?>
        <div class="<?php echo esc_attr(implode(' ', $pagination_classes)); ?>">
            <button type="button" 
                    class="btn btn-primary btn-lg load-more-btn" 
                    data-page="<?php echo esc_attr($current_page + 1); ?>"
                    data-max-pages="<?php echo esc_attr($total_pages); ?>">
                <span class="load-more-text">Carregar mais conteúdo</span>
                <span class="load-more-loading hidden">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Carregando...
                </span>
            </button>
            
            <div class="text-sm text-recife-gray-600 mt-2">
                Página <?php echo esc_html($current_page); ?> de <?php echo esc_html($total_pages); ?>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <!-- Paginação Numerada - Padrão -->
    <nav class="<?php echo esc_attr(implode(' ', $pagination_classes)); ?>" aria-label="Navegação de páginas">
        <div class="flex items-center gap-1">
            
            <!-- Botão Anterior -->
            <?php if ($current_page > 1): ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>" 
                   class="pagination-btn pagination-prev group"
                   aria-label="Página anterior">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            <?php endif; ?>
            
            <?php
            // Calcular páginas a exibir
            $start_page = max(1, $current_page - $mid_size);
            $end_page = min($total_pages, $current_page + $mid_size);
            
            // Ajustar se necessário para manter o número de páginas
            if ($end_page - $start_page < $mid_size * 2) {
                if ($start_page == 1) {
                    $end_page = min($total_pages, $start_page + $mid_size * 2);
                } else {
                    $start_page = max(1, $end_page - $mid_size * 2);
                }
            }
            
            // Primeira página e reticências
            if ($start_page > 1) {
                for ($i = 1; $i <= min($end_size, $start_page - 1); $i++) {
                    ?>
                    <a href="<?php echo esc_url(get_pagenum_link($i)); ?>" 
                       class="pagination-btn"
                       aria-label="Página <?php echo esc_attr($i); ?>">
                        <?php echo esc_html($i); ?>
                    </a>
                    <?php
                }
                
                if ($start_page > $end_size + 1) {
                    ?>
                    <span class="pagination-dots">…</span>
                    <?php
                }
            }
            
            // Páginas do meio
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $current_page) {
                    ?>
                    <span class="pagination-btn pagination-current" aria-current="page">
                        <?php echo esc_html($i); ?>
                    </span>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo esc_url(get_pagenum_link($i)); ?>" 
                       class="pagination-btn"
                       aria-label="Página <?php echo esc_attr($i); ?>">
                        <?php echo esc_html($i); ?>
                    </a>
                    <?php
                }
            }
            
            // Reticências e última página
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - $end_size) {
                    ?>
                    <span class="pagination-dots">…</span>
                    <?php
                }
                
                for ($i = max($end_page + 1, $total_pages - $end_size + 1); $i <= $total_pages; $i++) {
                    ?>
                    <a href="<?php echo esc_url(get_pagenum_link($i)); ?>" 
                       class="pagination-btn"
                       aria-label="Página <?php echo esc_attr($i); ?>">
                        <?php echo esc_html($i); ?>
                    </a>
                    <?php
                }
            }
            ?>
            
            <!-- Botão Próximo -->
            <?php if ($current_page < $total_pages): ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>" 
                   class="pagination-btn pagination-next group"
                   aria-label="Próxima página">
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Info adicional -->
        <div class="text-sm text-recife-gray-600 mt-3 text-center">
            Página <?php echo esc_html($current_page); ?> de <?php echo esc_html($total_pages); ?>
            <?php
            $total_posts = $query->found_posts;
            if ($total_posts > 0) {
                $posts_per_page = get_query_var('posts_per_page');
                $start_post = ($current_page - 1) * $posts_per_page + 1;
                $end_post = min($current_page * $posts_per_page, $total_posts);
                ?>
                <span class="hidden sm:inline">
                    • Exibindo <?php echo esc_html($start_post); ?>-<?php echo esc_html($end_post); ?> de <?php echo esc_html($total_posts); ?> resultados
                </span>
                <?php
            }
            ?>
        </div>
    </nav>
<?php endif; ?>

<style>
/* Estilos específicos para paginação */
.pagination-btn {
    @apply flex items-center justify-center w-10 h-10 text-sm font-medium text-recife-gray-700 bg-white border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50 hover:text-recife-primary transition-colors;
}

.pagination-btn.pagination-current {
    @apply bg-recife-primary text-white border-recife-primary;
}

.pagination-btn.pagination-prev,
.pagination-btn.pagination-next {
    @apply w-auto px-3;
}

.pagination-dots {
    @apply flex items-center justify-center w-10 h-10 text-recife-gray-400;
}

.load-more-btn {
    @apply relative;
}

.load-more-btn:disabled {
    @apply opacity-50 cursor-not-allowed;
}

.load-more-btn.loading .load-more-text {
    @apply hidden;
}

.load-more-btn.loading .load-more-loading {
    @apply flex items-center gap-2;
}
</style>

<script>
// JavaScript para Load More (se necessário)
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.querySelector('.load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const button = this;
            const currentPage = parseInt(button.dataset.page);
            const maxPages = parseInt(button.dataset.maxPages);
            
            // Adicionar classe loading
            button.classList.add('loading');
            button.disabled = true;
            
            // Aqui você implementaria a lógica AJAX para carregar mais posts
            // Por exemplo, usando fetch() para buscar o próximo conjunto de posts
            
            // Exemplo básico (substitua pela sua implementação):
            setTimeout(() => {
                // Simular carregamento
                button.classList.remove('loading');
                button.disabled = false;
                
                // Atualizar página
                button.dataset.page = currentPage + 1;
                
                // Esconder botão se chegou ao fim
                if (currentPage >= maxPages) {
                    button.style.display = 'none';
                }
            }, 1000);
        });
    }
});
</script> 