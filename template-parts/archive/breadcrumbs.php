<?php
/**
 * Template Part: Archive Breadcrumbs
 * 
 * Breadcrumbs de navegação para páginas de arquivo com:
 * - Estrutura semântica acessível
 * - Schema.org structured data
 * - Ícones e separadores visuais
 * - Responsivo e mobile-friendly
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Não exibir breadcrumbs na homepage
if (is_front_page()) {
    return;
}

// Array para armazenar os breadcrumbs
$breadcrumbs = [];

// Sempre começar com Home
$breadcrumbs[] = [
    'title' => 'Home',
    'url' => home_url('/'),
    'icon' => '🏠'
];

// Detectar contexto atual e construir breadcrumbs
if (is_category()) {
    // Para categorias
    $category = get_queried_object();
    
    // Adicionar categorias pai se existirem
    if ($category->parent) {
        $parent_cats = get_category_parents($category->term_id, true, '|||');
        $parent_cats = explode('|||', $parent_cats);
        array_pop($parent_cats); // Remove último elemento vazio
        
        foreach ($parent_cats as $parent_cat) {
            if (!empty(trim($parent_cat))) {
                // Extrair URL e título do link
                preg_match('/<a href="([^"]*)"[^>]*>([^<]*)<\/a>/', $parent_cat, $matches);
                if (isset($matches[1]) && isset($matches[2])) {
                    $breadcrumbs[] = [
                        'title' => $matches[2],
                        'url' => $matches[1],
                        'icon' => '📂'
                    ];
                }
            }
        }
    } else {
        // Categoria atual (sem link se for a última)
        $breadcrumbs[] = [
            'title' => $category->name,
            'url' => null,
            'icon' => '📂'
        ];
    }
    
} elseif (is_tag()) {
    // Para tags
    $tag = get_queried_object();
    $breadcrumbs[] = [
        'title' => 'Tags',
        'url' => get_bloginfo('url') . '/tag/',
        'icon' => '🏷️'
    ];
    $breadcrumbs[] = [
        'title' => $tag->name,
        'url' => null,
        'icon' => '🏷️'
    ];
    
} elseif (is_author()) {
    // Para páginas de autor
    $author = get_queried_object();
    $breadcrumbs[] = [
        'title' => 'Autores',
        'url' => get_bloginfo('url') . '/author/',
        'icon' => '👤'
    ];
    $breadcrumbs[] = [
        'title' => $author->display_name,
        'url' => null,
        'icon' => '👤'
    ];
    
} elseif (is_date()) {
    // Para archives de data
    $breadcrumbs[] = [
        'title' => 'Arquivo',
        'url' => get_bloginfo('url') . '/date/',
        'icon' => '📅'
    ];
    
    if (is_year()) {
        $breadcrumbs[] = [
            'title' => get_the_time('Y'),
            'url' => null,
            'icon' => '📅'
        ];
    } elseif (is_month()) {
        $breadcrumbs[] = [
            'title' => get_the_time('Y'),
            'url' => get_year_link(get_the_time('Y')),
            'icon' => '📅'
        ];
        $breadcrumbs[] = [
            'title' => get_the_time('F'),
            'url' => null,
            'icon' => '📅'
        ];
    } elseif (is_day()) {
        $breadcrumbs[] = [
            'title' => get_the_time('Y'),
            'url' => get_year_link(get_the_time('Y')),
            'icon' => '📅'
        ];
        $breadcrumbs[] = [
            'title' => get_the_time('F'),
            'url' => get_month_link(get_the_time('Y'), get_the_time('m')),
            'icon' => '📅'
        ];
        $breadcrumbs[] = [
            'title' => get_the_time('d'),
            'url' => null,
            'icon' => '📅'
        ];
    }
    
} elseif (is_post_type_archive()) {
    // Para archives de CPT
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object($post_type);
    
    // Mapear ícones por tipo de conteúdo
    $cpt_icons = [
        'eventos_festivais' => '🎭',
        'lugares' => '📍',
        'artistas' => '🎨',
        'roteiros' => '🗺️',
        'organizadores' => '🏢',
        'agremiacoes' => '🎪',
        'historias' => '📖',
        'guias_tematicos' => '📚'
    ];
    
    $icon = $cpt_icons[$post_type] ?? '📄';
    
    $breadcrumbs[] = [
        'title' => $post_type_obj->labels->name ?? $post_type,
        'url' => null,
        'icon' => $icon
    ];
    
} elseif (is_search()) {
    // Para resultados de busca
    $breadcrumbs[] = [
        'title' => 'Busca',
        'url' => null,
        'icon' => '🔍'
    ];
    
} elseif (is_404()) {
    // Para página 404
    $breadcrumbs[] = [
        'title' => 'Página não encontrada',
        'url' => null,
        'icon' => '❌'
    ];
}

// Se não há breadcrumbs além do home, não exibir
if (count($breadcrumbs) <= 1) {
    return;
}
?>

<nav class="breadcrumbs" aria-label="Navegação estrutural" role="navigation">
    <ol class="flex flex-wrap items-center gap-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
            <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if ($breadcrumb['url']): ?>
                    <!-- Link ativo -->
                    <a href="<?php echo esc_url($breadcrumb['url']); ?>" 
                       class="flex items-center gap-1 text-gray-600 hover:text-recife-primary transition-colors duration-200 hover:underline"
                       itemprop="item">
                        <span class="text-xs opacity-75"><?php echo $breadcrumb['icon']; ?></span>
                        <span itemprop="name"><?php echo esc_html($breadcrumb['title']); ?></span>
                    </a>
                    <meta itemprop="position" content="<?php echo $index + 1; ?>">
                <?php else: ?>
                    <!-- Item atual (sem link) -->
                    <span class="flex items-center gap-1 text-gray-900 font-medium" itemprop="name">
                        <span class="text-xs opacity-75"><?php echo $breadcrumb['icon']; ?></span>
                        <?php echo esc_html($breadcrumb['title']); ?>
                    </span>
                    <meta itemprop="position" content="<?php echo $index + 1; ?>">
                <?php endif; ?>
                
                <!-- Separador (não no último item) -->
                <?php if ($index < count($breadcrumbs) - 1): ?>
                    <span class="mx-2 text-gray-400 select-none" aria-hidden="true">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
    
    <!-- Informações adicionais para screen readers -->
    <div class="sr-only">
        Você está em: <?php echo esc_html(end($breadcrumbs)['title']); ?>
    </div>
</nav>

<!-- CSS específico para breadcrumbs -->
<style>
.breadcrumbs {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.breadcrumbs ol {
    margin: 0;
    padding: 0;
    list-style: none;
}

.breadcrumbs li {
    display: inline-flex;
    align-items: center;
}

.breadcrumbs a {
    text-decoration: none;
    transition: all 0.2s ease;
}

.breadcrumbs a:hover {
    text-decoration: underline;
}

.breadcrumbs a:focus {
    outline: 2px solid var(--recife-primary);
    outline-offset: 2px;
    border-radius: 2px;
}

/* Responsivo */
@media (max-width: 640px) {
    .breadcrumbs {
        font-size: 0.75rem;
    }
    
    .breadcrumbs ol {
        gap: 0.25rem;
    }
    
    .breadcrumbs .mx-2 {
        margin-left: 0.25rem;
        margin-right: 0.25rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .breadcrumbs a {
        color: #9ca3af;
    }
    
    .breadcrumbs a:hover {
        color: #f43f5e;
    }
    
    .breadcrumbs span {
        color: #f9fafb;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .breadcrumbs a {
        color: #000;
        font-weight: 600;
    }
    
    .breadcrumbs a:hover {
        background-color: #000;
        color: #fff;
        padding: 2px 4px;
        border-radius: 2px;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .breadcrumbs a {
        transition: none;
    }
}
</style>

<!-- JavaScript para funcionalidades avançadas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const breadcrumbs = document.querySelector('.breadcrumbs');
    
    if (!breadcrumbs) return;
    
    // Adicionar funcionalidade de keyboard navigation
    const links = breadcrumbs.querySelectorAll('a');
    
    links.forEach((link, index) => {
        link.addEventListener('keydown', function(e) {
            // Navegação com setas
            if (e.key === 'ArrowRight' && index < links.length - 1) {
                e.preventDefault();
                links[index + 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                e.preventDefault();
                links[index - 1].focus();
            }
        });
    });
    
    // Analytics tracking
    links.forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'breadcrumb_click', {
                    'breadcrumb_text': this.textContent.trim(),
                    'breadcrumb_url': this.href,
                    'page_location': window.location.href
                });
            }
        });
    });
    
    // Adicionar microdata adicional se necessário
    const breadcrumbList = breadcrumbs.querySelector('ol');
    if (breadcrumbList && !breadcrumbList.hasAttribute('itemscope')) {
        breadcrumbList.setAttribute('itemscope', '');
        breadcrumbList.setAttribute('itemtype', 'https://schema.org/BreadcrumbList');
    }
});
</script> 