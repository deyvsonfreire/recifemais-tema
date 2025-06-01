<?php
/**
 * Template Part: Related Posts
 * 
 * Seção de posts relacionados com:
 * - Algoritmo inteligente de relacionamento
 * - Layout responsivo em grid
 * - Múltiplos critérios de busca
 * - Fallback para posts populares
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do post atual
$post_id = get_the_ID();
$post_categories = get_the_category($post_id);
$post_tags = get_the_tags($post_id);

// Configurações
$args = wp_parse_args($args ?? [], [
    'posts_count' => 6,
    'show_category_filter' => true,
    'show_excerpt' => true,
    'show_author' => false,
    'show_date' => true,
    'show_views' => true,
    'layout' => 'grid', // grid, list, carousel
    'title' => 'Você também pode gostar'
]);

// Algoritmo de posts relacionados
$related_posts = [];

// 1. Primeiro: Posts da mesma categoria
if (!empty($post_categories)) {
    $category_ids = wp_list_pluck($post_categories, 'term_id');
    
    $category_posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => $args['posts_count'],
        'post__not_in' => [$post_id],
        'category__in' => $category_ids,
        'orderby' => 'rand',
        'meta_query' => [
            [
                'key' => 'post_views',
                'compare' => 'EXISTS'
            ]
        ]
    ]);
    
    $related_posts = array_merge($related_posts, $category_posts);
}

// 2. Se não tiver posts suficientes, buscar por tags
if (count($related_posts) < $args['posts_count'] && !empty($post_tags)) {
    $tag_ids = wp_list_pluck($post_tags, 'term_id');
    $needed = $args['posts_count'] - count($related_posts);
    $exclude_ids = array_merge([$post_id], wp_list_pluck($related_posts, 'ID'));
    
    $tag_posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => $needed,
        'post__not_in' => $exclude_ids,
        'tag__in' => $tag_ids,
        'orderby' => 'rand'
    ]);
    
    $related_posts = array_merge($related_posts, $tag_posts);
}

// 3. Se ainda não tiver posts suficientes, buscar posts populares
if (count($related_posts) < $args['posts_count']) {
    $needed = $args['posts_count'] - count($related_posts);
    $exclude_ids = array_merge([$post_id], wp_list_pluck($related_posts, 'ID'));
    
    $popular_posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => $needed,
        'post__not_in' => $exclude_ids,
        'meta_key' => 'post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'date_query' => [
            [
                'after' => '60 days ago'
            ]
        ]
    ]);
    
    $related_posts = array_merge($related_posts, $popular_posts);
}

// 4. Fallback final: posts recentes
if (count($related_posts) < $args['posts_count']) {
    $needed = $args['posts_count'] - count($related_posts);
    $exclude_ids = array_merge([$post_id], wp_list_pluck($related_posts, 'ID'));
    
    $recent_posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => $needed,
        'post__not_in' => $exclude_ids,
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
    
    $related_posts = array_merge($related_posts, $recent_posts);
}

// Limitar ao número desejado e remover duplicatas
$related_posts = array_slice(array_unique($related_posts, SORT_REGULAR), 0, $args['posts_count']);

// Se não tiver posts relacionados, não exibir a seção
if (empty($related_posts)) {
    return;
}

// Categorias únicas dos posts relacionados (para filtro)
$related_categories = [];
if ($args['show_category_filter']) {
    foreach ($related_posts as $post) {
        $post_cats = get_the_category($post->ID);
        foreach ($post_cats as $cat) {
            if (!isset($related_categories[$cat->term_id])) {
                $related_categories[$cat->term_id] = $cat;
            }
        }
    }
}
?>

<section class="related-posts bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        
        <!-- Header da Seção -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                <?php echo esc_html($args['title']); ?>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Selecionamos estas matérias que podem interessar você, baseadas no que você está lendo.
            </p>
        </div>
        
        <?php if ($args['show_category_filter'] && count($related_categories) > 1): ?>
            <!-- Filtros por Categoria -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <button type="button" 
                        onclick="filterRelatedPosts('all')"
                        class="filter-btn active px-4 py-2 bg-recife-primary text-white rounded-full text-sm font-medium transition-colors hover:bg-recife-primary/90">
                    Todos
                </button>
                
                <?php foreach ($related_categories as $category): ?>
                    <button type="button" 
                            onclick="filterRelatedPosts('<?php echo esc_attr($category->slug); ?>')"
                            class="filter-btn px-4 py-2 bg-white text-gray-700 rounded-full text-sm font-medium border border-gray-200 transition-colors hover:bg-gray-50">
                        <?php echo esc_html($category->name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Grid de Posts -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="related-posts-grid">
            <?php foreach ($related_posts as $related_post): 
                $related_image = get_the_post_thumbnail_url($related_post->ID, 'medium_large');
                $related_categories_post = get_the_category($related_post->ID);
                $related_author = get_the_author_meta('display_name', $related_post->post_author);
                $related_date = get_the_date('d/m/Y', $related_post->ID);
                $related_time = get_the_time('H:i', $related_post->ID);
                $related_excerpt = get_the_excerpt($related_post->ID);
                $related_views = get_post_meta($related_post->ID, 'post_views', true);
                $reading_time = recifemais_reading_time($related_post->ID);
                
                // Classes para filtro
                $filter_classes = 'all';
                if (!empty($related_categories_post)) {
                    $filter_classes .= ' ' . implode(' ', wp_list_pluck($related_categories_post, 'slug'));
                }
                ?>
                
                <article class="related-post-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden group hover:shadow-md transition-shadow duration-300" 
                         data-categories="<?php echo esc_attr($filter_classes); ?>">
                    
                    <!-- Imagem -->
                    <?php if ($related_image): ?>
                        <div class="aspect-video bg-gray-200 overflow-hidden">
                            <a href="<?php echo get_permalink($related_post->ID); ?>" class="block h-full">
                                <img src="<?php echo esc_url($related_image); ?>" 
                                     alt="<?php echo esc_attr($related_post->post_title); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Conteúdo -->
                    <div class="p-6">
                        
                        <!-- Categoria -->
                        <?php if (!empty($related_categories_post)): ?>
                            <div class="mb-3">
                                <a href="<?php echo get_category_link($related_categories_post[0]); ?>" 
                                   class="inline-block text-xs font-semibold text-recife-primary uppercase tracking-wide hover:text-recife-primary/80 transition-colors">
                                    <?php echo esc_html($related_categories_post[0]->name); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Título -->
                        <h3 class="text-lg font-bold text-gray-900 leading-tight mb-3">
                            <a href="<?php echo get_permalink($related_post->ID); ?>" 
                               class="hover:text-recife-primary transition-colors">
                                <?php echo esc_html($related_post->post_title); ?>
                            </a>
                        </h3>
                        
                        <!-- Excerpt -->
                        <?php if ($args['show_excerpt'] && $related_excerpt): ?>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                <?php echo esc_html($related_excerpt); ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Meta Informações -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center gap-4">
                                
                                <?php if ($args['show_author']): ?>
                                    <!-- Autor -->
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?php echo esc_html($related_author); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($args['show_date']): ?>
                                    <!-- Data -->
                                    <time datetime="<?php echo get_the_date('c', $related_post->ID); ?>" 
                                          class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?php echo esc_html($related_date); ?>
                                    </time>
                                <?php endif; ?>
                                
                                <!-- Tempo de Leitura -->
                                <?php if ($reading_time): ?>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?php echo esc_html($reading_time); ?>min
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($args['show_views'] && $related_views): ?>
                                <!-- Visualizações -->
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo number_format($related_views); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Botão Ver Mais -->
        <div class="text-center mt-12">
            <a href="<?php echo home_url('/noticias/'); ?>" 
               class="inline-flex items-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                Ver Mais Notícias
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<script>
function filterRelatedPosts(category) {
    const posts = document.querySelectorAll('.related-post-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Atualizar botões ativos
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-recife-primary', 'text-white');
        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
    });
    
    event.target.classList.add('active', 'bg-recife-primary', 'text-white');
    event.target.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
    
    // Filtrar posts
    posts.forEach(post => {
        const categories = post.dataset.categories;
        
        if (category === 'all' || categories.includes(category)) {
            post.style.display = 'block';
            post.style.animation = 'fadeIn 0.3s ease-in-out';
        } else {
            post.style.display = 'none';
        }
    });
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'filter_related_posts', {
            'category': category,
            'post_id': '<?php echo get_the_ID(); ?>'
        });
    }
}

// Animação de entrada
document.addEventListener('DOMContentLoaded', function() {
    const posts = document.querySelectorAll('.related-post-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1
    });
    
    posts.forEach(post => {
        post.style.opacity = '0';
        post.style.transform = 'translateY(20px)';
        post.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(post);
    });
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.filter-btn.active {
    box-shadow: 0 2px 4px rgba(225, 29, 72, 0.2);
}
</style> 