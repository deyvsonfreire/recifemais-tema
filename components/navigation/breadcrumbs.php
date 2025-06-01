<?php
/**
 * Componente: Breadcrumbs
 * 
 * Navegação estrutural inteligente para o RecifeMais
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
 * @param string $separator   Separador entre itens (padrão: '›')
 * @param bool   $show_home   Exibir link para home (padrão: true)
 * @param string $home_text   Texto do link home (padrão: 'Início')
 * @param array  $classes     Classes CSS adicionais
 */

// Valores padrão
$defaults = [
    'separator' => '›',
    'show_home' => true,
    'home_text' => 'Início',
    'classes' => []
];

$args = wp_parse_args($args ?? [], $defaults);
extract($args);

// Não exibir na homepage
if (is_front_page()) {
    return;
}

// Classes CSS
$breadcrumb_classes = ['breadcrumb', 'flex', 'items-center', 'gap-2', 'text-sm', 'text-recife-gray-600'];
$breadcrumb_classes = array_merge($breadcrumb_classes, $classes);

// Array para armazenar os itens do breadcrumb
$breadcrumb_items = [];

// Link para home
if ($show_home) {
    $breadcrumb_items[] = [
        'title' => $home_text,
        'url' => home_url('/'),
        'is_current' => false
    ];
}

// Lógica específica por tipo de página
if (is_single()) {
    $post_type = get_post_type();
    $post_id = get_the_ID();
    
    // CPTs do RecifeMais
    $recifemais_cpts = [
        'eventos_festivais' => 'Eventos',
        'lugares' => 'Lugares',
        'artistas' => 'Artistas',
        'roteiros' => 'Roteiros',
        'organizadores' => 'Organizadores',
        'agremiacoes' => 'Agremiações',
        'historias' => 'Histórias',
        'guias_tematicos' => 'Guias'
    ];
    
    if (array_key_exists($post_type, $recifemais_cpts)) {
        // Link para o archive do CPT
        $breadcrumb_items[] = [
            'title' => $recifemais_cpts[$post_type],
            'url' => get_post_type_archive_link($post_type),
            'is_current' => false
        ];
        
        // Adicionar taxonomia principal se existir
        $main_taxonomy = '';
        switch ($post_type) {
            case 'eventos_festivais':
                $main_taxonomy = 'tipos_eventos';
                break;
            case 'lugares':
                $main_taxonomy = 'tipos_lugares';
                break;
            case 'artistas':
                $main_taxonomy = 'generos_musicais';
                break;
            case 'agremiacoes':
                $main_taxonomy = 'modalidades_agremiacoes';
                break;
        }
        
        if ($main_taxonomy) {
            $terms = get_the_terms($post_id, $main_taxonomy);
            if ($terms && !is_wp_error($terms)) {
                $primary_term = $terms[0];
                $breadcrumb_items[] = [
                    'title' => $primary_term->name,
                    'url' => get_term_link($primary_term),
                    'is_current' => false
                ];
            }
        }
        
        // Bairro se aplicável
        if (in_array($post_type, ['eventos_festivais', 'lugares', 'artistas', 'agremiacoes'])) {
            $bairros = get_the_terms($post_id, 'bairros_recife');
            if ($bairros && !is_wp_error($bairros)) {
                $bairro = $bairros[0];
                $breadcrumb_items[] = [
                    'title' => $bairro->name,
                    'url' => get_term_link($bairro),
                    'is_current' => false
                ];
            }
        }
        
    } else {
        // Posts normais - adicionar categoria
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            $breadcrumb_items[] = [
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
                'is_current' => false
            ];
        }
    }
    
    // Título do post atual
    $breadcrumb_items[] = [
        'title' => get_the_title(),
        'url' => '',
        'is_current' => true
    ];
    
} elseif (is_archive()) {
    
    if (is_category()) {
        // Arquivo de categoria
        $category = get_queried_object();
        $breadcrumb_items[] = [
            'title' => 'Notícias',
            'url' => get_permalink(get_option('page_for_posts')),
            'is_current' => false
        ];
        
        $breadcrumb_items[] = [
            'title' => $category->name,
            'url' => '',
            'is_current' => true
        ];
        
    } elseif (is_tag()) {
        // Arquivo de tag
        $tag = get_queried_object();
        $breadcrumb_items[] = [
            'title' => 'Tags',
            'url' => '',
            'is_current' => false
        ];
        
        $breadcrumb_items[] = [
            'title' => $tag->name,
            'url' => '',
            'is_current' => true
        ];
        
    } elseif (is_post_type_archive()) {
        // Arquivo de CPT
        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);
        
        $breadcrumb_items[] = [
            'title' => $post_type_object->labels->name,
            'url' => '',
            'is_current' => true
        ];
        
    } elseif (is_tax()) {
        // Arquivo de taxonomia
        $term = get_queried_object();
        $taxonomy = get_taxonomy($term->taxonomy);
        
        // Link para o post type relacionado se possível
        $related_post_types = $taxonomy->object_type;
        if (!empty($related_post_types)) {
            $post_type = $related_post_types[0];
            $post_type_object = get_post_type_object($post_type);
            
            if ($post_type_object) {
                $breadcrumb_items[] = [
                    'title' => $post_type_object->labels->name,
                    'url' => get_post_type_archive_link($post_type),
                    'is_current' => false
                ];
            }
        }
        
        $breadcrumb_items[] = [
            'title' => $term->name,
            'url' => '',
            'is_current' => true
        ];
        
    } elseif (is_author()) {
        // Arquivo de autor
        $author = get_queried_object();
        $breadcrumb_items[] = [
            'title' => 'Autores',
            'url' => '',
            'is_current' => false
        ];
        
        $breadcrumb_items[] = [
            'title' => $author->display_name,
            'url' => '',
            'is_current' => true
        ];
        
    } elseif (is_date()) {
        // Arquivo de data
        $breadcrumb_items[] = [
            'title' => 'Arquivo',
            'url' => '',
            'is_current' => false
        ];
        
        if (is_year()) {
            $breadcrumb_items[] = [
                'title' => get_the_date('Y'),
                'url' => '',
                'is_current' => true
            ];
        } elseif (is_month()) {
            $breadcrumb_items[] = [
                'title' => get_the_date('Y'),
                'url' => get_year_link(get_the_date('Y')),
                'is_current' => false
            ];
            $breadcrumb_items[] = [
                'title' => get_the_date('F'),
                'url' => '',
                'is_current' => true
            ];
        } elseif (is_day()) {
            $breadcrumb_items[] = [
                'title' => get_the_date('Y'),
                'url' => get_year_link(get_the_date('Y')),
                'is_current' => false
            ];
            $breadcrumb_items[] = [
                'title' => get_the_date('F'),
                'url' => get_month_link(get_the_date('Y'), get_the_date('m')),
                'is_current' => false
            ];
            $breadcrumb_items[] = [
                'title' => get_the_date('d'),
                'url' => '',
                'is_current' => true
            ];
        }
    }
    
} elseif (is_page()) {
    // Páginas
    $page_id = get_the_ID();
    $ancestors = get_post_ancestors($page_id);
    
    // Adicionar páginas pai
    if ($ancestors) {
        $ancestors = array_reverse($ancestors);
        foreach ($ancestors as $ancestor_id) {
            $breadcrumb_items[] = [
                'title' => get_the_title($ancestor_id),
                'url' => get_permalink($ancestor_id),
                'is_current' => false
            ];
        }
    }
    
    // Página atual
    $breadcrumb_items[] = [
        'title' => get_the_title(),
        'url' => '',
        'is_current' => true
    ];
    
} elseif (is_search()) {
    // Página de busca
    $breadcrumb_items[] = [
        'title' => 'Busca',
        'url' => '',
        'is_current' => false
    ];
    
    $breadcrumb_items[] = [
        'title' => 'Resultados para: "' . get_search_query() . '"',
        'url' => '',
        'is_current' => true
    ];
    
} elseif (is_404()) {
    // Página 404
    $breadcrumb_items[] = [
        'title' => 'Página não encontrada',
        'url' => '',
        'is_current' => true
    ];
}

// Não exibir se não houver itens suficientes
if (count($breadcrumb_items) < 2) {
    return;
}

?>

<nav class="<?php echo esc_attr(implode(' ', $breadcrumb_classes)); ?>" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumb_items as $index => $item): ?>
            <li class="flex items-center gap-2" 
                itemprop="itemListElement" 
                itemscope 
                itemtype="https://schema.org/ListItem">
                
                <?php if (!$item['is_current'] && !empty($item['url'])): ?>
                    <a href="<?php echo esc_url($item['url']); ?>" 
                       class="text-recife-gray-600 hover:text-recife-primary transition-colors"
                       itemprop="item">
                        <span itemprop="name"><?php echo esc_html($item['title']); ?></span>
                    </a>
                <?php else: ?>
                    <span class="text-recife-gray-900 font-medium" 
                          itemprop="name" 
                          aria-current="page">
                        <?php echo esc_html($item['title']); ?>
                    </span>
                <?php endif; ?>
                
                <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                
                <?php if ($index < count($breadcrumb_items) - 1): ?>
                    <span class="text-recife-gray-400" aria-hidden="true">
                        <?php echo esc_html($separator); ?>
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav> 