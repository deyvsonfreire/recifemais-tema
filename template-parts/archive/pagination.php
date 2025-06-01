<?php
/**
 * Template Part: Archive Pagination
 * 
 * Paginação moderna para páginas de arquivo com:
 * - Navegação acessível
 * - Design responsivo
 * - Informações contextuais
 * - Preload de páginas
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Verificar se há necessidade de paginação
global $wp_query;

$total_pages = $wp_query->max_num_pages;
$current_page = max(1, get_query_var('paged'));

if ($total_pages <= 1) {
    return; // Não exibir se há apenas uma página
}

// Configurações de paginação
$range = 2; // Quantas páginas mostrar de cada lado da atual
$show_all = false; // Mostrar todas as páginas se total for pequeno

// Calcular páginas para exibir
$pages_to_show = [];

if ($total_pages <= 7 || $show_all) {
    // Mostrar todas as páginas se forem poucas
    for ($i = 1; $i <= $total_pages; $i++) {
        $pages_to_show[] = $i;
    }
} else {
    // Lógica complexa para páginas
    $pages_to_show[] = 1; // Sempre mostrar primeira página
    
    if ($current_page > $range + 2) {
        $pages_to_show[] = '...'; // Reticências antes
    }
    
    // Páginas ao redor da atual
    for ($i = max(2, $current_page - $range); $i <= min($total_pages - 1, $current_page + $range); $i++) {
        $pages_to_show[] = $i;
    }
    
    if ($current_page < $total_pages - $range - 1) {
        $pages_to_show[] = '...'; // Reticências depois
    }
    
    if ($total_pages > 1) {
        $pages_to_show[] = $total_pages; // Sempre mostrar última página
    }
}

// URLs de navegação
$prev_url = $current_page > 1 ? get_pagenum_link($current_page - 1) : null;
$next_url = $current_page < $total_pages ? get_pagenum_link($current_page + 1) : null;

// Informações contextuais
$posts_per_page = get_query_var('posts_per_page') ?: get_option('posts_per_page');
$total_posts = $wp_query->found_posts;
$showing_start = (($current_page - 1) * $posts_per_page) + 1;
$showing_end = min($current_page * $posts_per_page, $total_posts);
?>

<nav class="archive-pagination" role="navigation" aria-label="Navegação de páginas">
    <!-- Informações contextuais -->
    <div class="pagination-info text-center mb-6">
        <p class="text-sm text-gray-600">
            Exibindo <strong><?php echo number_format($showing_start); ?>-<?php echo number_format($showing_end); ?></strong> 
            de <strong><?php echo number_format($total_posts); ?></strong> resultados
            <span class="hidden sm:inline">
                (Página <?php echo $current_page; ?> de <?php echo $total_pages; ?>)
            </span>
        </p>
    </div>
    
    <!-- Navegação principal -->
    <div class="pagination-nav flex items-center justify-center">
        <div class="flex items-center space-x-1">
            
            <!-- Botão Primeira Página -->
            <?php if ($current_page > 2): ?>
                <a href="<?php echo get_pagenum_link(1); ?>" 
                   class="pagination-btn pagination-first hidden sm:flex"
                   title="Primeira página"
                   aria-label="Ir para a primeira página">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                    <span class="ml-1 hidden md:inline">Primeira</span>
                </a>
            <?php endif; ?>
            
            <!-- Botão Anterior -->
            <?php if ($prev_url): ?>
                <a href="<?php echo esc_url($prev_url); ?>" 
                   class="pagination-btn pagination-prev"
                   title="Página anterior"
                   aria-label="Ir para a página anterior"
                   rel="prev">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="ml-1 hidden sm:inline">Anterior</span>
                </a>
            <?php else: ?>
                <span class="pagination-btn pagination-prev pagination-disabled" aria-hidden="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="ml-1 hidden sm:inline">Anterior</span>
                </span>
            <?php endif; ?>
            
            <!-- Números das páginas -->
            <div class="flex items-center space-x-1">
                <?php foreach ($pages_to_show as $page): ?>
                    <?php if ($page === '...'): ?>
                        <span class="pagination-ellipsis" aria-hidden="true">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </span>
                    <?php elseif ($page == $current_page): ?>
                        <span class="pagination-btn pagination-current" aria-current="page" aria-label="Página atual, página <?php echo $page; ?>">
                            <?php echo $page; ?>
                        </span>
                    <?php else: ?>
                        <a href="<?php echo get_pagenum_link($page); ?>" 
                           class="pagination-btn pagination-number"
                           aria-label="Ir para a página <?php echo $page; ?>">
                            <?php echo $page; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Botão Próximo -->
            <?php if ($next_url): ?>
                <a href="<?php echo esc_url($next_url); ?>" 
                   class="pagination-btn pagination-next"
                   title="Próxima página"
                   aria-label="Ir para a próxima página"
                   rel="next">
                    <span class="mr-1 hidden sm:inline">Próxima</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            <?php else: ?>
                <span class="pagination-btn pagination-next pagination-disabled" aria-hidden="true">
                    <span class="mr-1 hidden sm:inline">Próxima</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            <?php endif; ?>
            
            <!-- Botão Última Página -->
            <?php if ($current_page < $total_pages - 1): ?>
                <a href="<?php echo get_pagenum_link($total_pages); ?>" 
                   class="pagination-btn pagination-last hidden sm:flex"
                   title="Última página"
                   aria-label="Ir para a última página">
                    <span class="mr-1 hidden md:inline">Última</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Navegação rápida (mobile) -->
    <div class="pagination-mobile sm:hidden mt-4">
        <div class="flex justify-between items-center">
            <?php if ($prev_url): ?>
                <a href="<?php echo esc_url($prev_url); ?>" 
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            
            <span class="text-sm text-gray-600 font-medium">
                <?php echo $current_page; ?> de <?php echo $total_pages; ?>
            </span>
            
            <?php if ($next_url): ?>
                <a href="<?php echo esc_url($next_url); ?>" 
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Próxima
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Jump to page (para muitas páginas) -->
    <?php if ($total_pages > 10): ?>
        <div class="pagination-jump mt-6 text-center">
            <form class="inline-flex items-center gap-2" onsubmit="jumpToPage(event)">
                <label for="jump-page" class="text-sm text-gray-600">Ir para página:</label>
                <input type="number" 
                       id="jump-page"
                       min="1" 
                       max="<?php echo $total_pages; ?>" 
                       value="<?php echo $current_page; ?>"
                       class="w-16 px-2 py-1 text-sm border border-gray-300 rounded text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" 
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Ir
                </button>
            </form>
        </div>
    <?php endif; ?>
</nav>

<!-- CSS específico para paginação -->
<style>
.archive-pagination {
    margin: 2rem 0;
}

.pagination-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.75rem;
    border: 1px solid #d1d5db;
    background-color: white;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.pagination-btn:hover:not(.pagination-disabled):not(.pagination-current) {
    background-color: #f3f4f6;
    border-color: #9ca3af;
    color: #111827;
}

.pagination-btn:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    ring-offset: 2px;
}

.pagination-current {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
    font-weight: 600;
}

.pagination-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    color: #9ca3af;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .pagination-btn {
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        font-size: 0.75rem;
    }
    
    .pagination-nav {
        overflow-x: auto;
        padding: 0 1rem;
    }
    
    .pagination-nav > div {
        min-width: max-content;
    }
}

/* Print styles */
@media print {
    .archive-pagination {
        display: none !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .pagination-btn {
        background-color: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    .pagination-btn:hover:not(.pagination-disabled):not(.pagination-current) {
        background-color: #4b5563;
        border-color: #6b7280;
    }
    
    .pagination-current {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .pagination-btn {
        border-width: 2px;
        font-weight: 600;
    }
    
    .pagination-current {
        background-color: #000;
        border-color: #000;
        color: #fff;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .pagination-btn {
        transition: none;
    }
}
</style>

<!-- JavaScript para funcionalidades da paginação -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preload de páginas adjacentes
    const currentPage = <?php echo $current_page; ?>;
    const totalPages = <?php echo $total_pages; ?>;
    
    // Preload página anterior
    if (currentPage > 1) {
        const prevLink = document.createElement('link');
        prevLink.rel = 'prefetch';
        prevLink.href = '<?php echo $prev_url ? esc_js($prev_url) : ''; ?>';
        if (prevLink.href) document.head.appendChild(prevLink);
    }
    
    // Preload próxima página
    if (currentPage < totalPages) {
        const nextLink = document.createElement('link');
        nextLink.rel = 'prefetch';
        nextLink.href = '<?php echo $next_url ? esc_js($next_url) : ''; ?>';
        if (nextLink.href) document.head.appendChild(nextLink);
    }
    
    // Analytics para cliques na paginação
    document.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'pagination_click', {
                    'page_number': this.textContent.trim() || 'navigation',
                    'current_page': currentPage,
                    'total_pages': totalPages,
                    'page_location': window.location.href
                });
            }
        });
    });
});

// Função para pular para página específica
function jumpToPage(event) {
    event.preventDefault();
    
    const input = event.target.querySelector('#jump-page');
    const pageNumber = parseInt(input.value);
    const totalPages = <?php echo $total_pages; ?>;
    
    if (pageNumber >= 1 && pageNumber <= totalPages) {
        const currentUrl = new URL(window.location);
        
        if (pageNumber === 1) {
            currentUrl.searchParams.delete('paged');
        } else {
            currentUrl.searchParams.set('paged', pageNumber);
        }
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pagination_jump', {
                'target_page': pageNumber,
                'current_page': <?php echo $current_page; ?>,
                'total_pages': totalPages
            });
        }
        
        window.location.href = currentUrl.toString();
    } else {
        alert('Por favor, insira um número de página válido (1-' + totalPages + ')');
        input.focus();
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    // Não interferir se o usuário está digitando
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    
    const currentPage = <?php echo $current_page; ?>;
    const totalPages = <?php echo $total_pages; ?>;
    
    // Seta esquerda = página anterior
    if (e.key === 'ArrowLeft' && currentPage > 1) {
        e.preventDefault();
        const prevBtn = document.querySelector('.pagination-prev');
        if (prevBtn && !prevBtn.classList.contains('pagination-disabled')) {
            prevBtn.click();
        }
    }
    
    // Seta direita = próxima página
    if (e.key === 'ArrowRight' && currentPage < totalPages) {
        e.preventDefault();
        const nextBtn = document.querySelector('.pagination-next');
        if (nextBtn && !nextBtn.classList.contains('pagination-disabled')) {
            nextBtn.click();
        }
    }
});
</script> 