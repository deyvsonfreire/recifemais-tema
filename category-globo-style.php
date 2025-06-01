<?php
/**
 * Template Category - RecifeMais (Estilo Globo.com)
 * Template para páginas de categoria inspirado na Globo.com
 * Layout limpo, profissional e focado no conteúdo
 *
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$category = get_queried_object();
$category_color = recifemais_get_category_color($category->slug);
$category_icon = recifemais_get_category_icon($category->slug);
$posts_count = $category->count;

?>

<main class="category-page bg-gray-50 min-h-screen">
    
    <!-- Header da Categoria (Estilo Globo) -->
    <section class="category-header bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-6xl mx-auto">
                
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm text-gray-500">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-red-600 transition-colors">
                                Início
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <a href="<?php echo home_url('/noticias/'); ?>" class="hover:text-red-600 transition-colors">
                                Notícias
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <span class="text-gray-700 font-medium"><?php echo esc_html($category->name); ?></span>
                        </li>
                    </ol>
                </nav>
                
                <!-- Título e Meta da Categoria -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <!-- Ícone da Categoria -->
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-red-600 text-xl">
                                    <?php echo recifemais_get_icon_svg('news', '24', '#dc2626'); ?>
                                </span>
                            </div>
                            
                            <!-- Título -->
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-1">
                                    <?php echo esc_html($category->name); ?>
                                </h1>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span><?php echo number_format_i18n($posts_count); ?> notícias</span>
                                    <span>•</span>
                                    <span>Atualizado <?php echo get_the_date('d/m/Y'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Descrição da Categoria -->
                        <?php if ($category->description) : ?>
                            <p class="text-lg text-gray-700 leading-relaxed max-w-3xl">
                                <?php echo esc_html($category->description); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Ações Rápidas -->
                    <div class="hidden lg:flex items-center gap-3">
                        <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-gray-100 rounded-lg transition-colors" 
                                title="Compartilhar categoria">
                            <?php echo recifemais_get_icon_svg('share', '20', 'currentColor'); ?>
                        </button>
                        <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-gray-100 rounded-lg transition-colors" 
                                title="Seguir categoria">
                            <?php echo recifemais_get_icon_svg('bell', '20', 'currentColor'); ?>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Área de Conteúdo Principal (3/4) -->
                <div class="lg:col-span-3">
                    
                    <?php if (have_posts()) : ?>
                        
                        <!-- Notícias em Destaque -->
                        <?php
                        $featured_query = new WP_Query([
                            'cat' => $category->term_id,
                            'posts_per_page' => 3,
                            'meta_key' => '_featured_post',
                            'meta_value' => '1',
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ]);
                        
                        if ($featured_query->have_posts()) :
                        ?>
                        <section class="featured-posts mb-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-red-600 rounded-full"></span>
                                    Destaques
                                </h2>
                                <span class="text-sm text-gray-600">
                                    <?php echo $featured_query->found_posts; ?> em destaque
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                                        
                                        <!-- Imagem -->
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('medium_large', [
                                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                    ]); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Conteúdo -->
                                        <div class="p-4">
                                            <!-- Badge Destaque -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded-full">
                                                    DESTAQUE
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?php echo get_the_date('d/m/Y'); ?>
                                                </span>
                                            </div>
                                            
                                            <!-- Título -->
                                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <!-- Resumo -->
                                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                            </p>
                                            
                                            <!-- Meta -->
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span><?php echo recifemais_get_reading_time(); ?> min</span>
                                                <a href="<?php the_permalink(); ?>" 
                                                   class="text-red-600 font-medium hover:text-red-700">
                                                    Ler mais →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </section>
                        <?php endif; ?>
                        
                        <!-- Todas as Notícias -->
                        <section class="all-posts">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-gray-400 rounded-full"></span>
                                    Todas as notícias
                                </h2>
                                <div class="flex items-center gap-4">
                                    <!-- Filtros rápidos -->
                                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                                        <option>Mais recentes</option>
                                        <option>Mais lidas</option>
                                        <option>Mais comentadas</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Lista de Posts -->
                            <div class="space-y-6">
                                <?php 
                                // Excluir posts em destaque da listagem principal
                                $exclude_ids = [];
                                if (isset($featured_query) && $featured_query->have_posts()) {
                                    foreach ($featured_query->posts as $featured_post) {
                                        $exclude_ids[] = $featured_post->ID;
                                    }
                                }
                                
                                // Rewind posts para a query principal
                                rewind_posts();
                                
                                while (have_posts()) : the_post();
                                    // Pular se for post em destaque
                                    if (in_array(get_the_ID(), $exclude_ids)) {
                                        continue;
                                    }
                                ?>
                                
                                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                                    <div class="flex flex-col md:flex-row">
                                        
                                        <!-- Imagem -->
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="md:w-80 flex-shrink-0">
                                                <div class="aspect-w-16 aspect-h-9 md:aspect-w-4 md:aspect-h-3 overflow-hidden">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium_large', [
                                                            'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                        ]); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Conteúdo -->
                                        <div class="flex-1 p-6">
                                            <!-- Meta superior -->
                                            <div class="flex items-center gap-3 mb-3">
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded">
                                                    <?php echo strtoupper($category->name); ?>
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?php echo get_the_date('d/m/Y \à\s H:i'); ?>
                                                </span>
                                                <?php if (recifemais_is_breaking_news()) : ?>
                                                    <span class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded animate-pulse">
                                                        URGENTE
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Título -->
                                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition-colors">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <!-- Resumo -->
                                            <p class="text-gray-600 mb-4 line-clamp-2">
                                                <?php echo wp_trim_words(get_the_excerpt(), 35); ?>
                                            </p>
                                            
                                            <!-- Meta inferior -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <?php the_author(); ?>
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <?php echo recifemais_get_reading_time(); ?> min
                                                    </span>
                                                </div>
                                                
                                                <a href="<?php the_permalink(); ?>" 
                                                   class="inline-flex items-center gap-1 text-red-600 font-medium hover:text-red-700 transition-colors">
                                                    Ler notícia
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                
                                <?php endwhile; ?>
                            </div>
                            
                            <!-- Paginação -->
                            <div class="mt-12">
                                <?php
                                the_posts_pagination([
                                    'mid_size' => 2,
                                    'prev_text' => '← Anterior',
                                    'next_text' => 'Próxima →',
                                    'before_page_number' => '<span class="sr-only">Página </span>',
                                    'class' => 'pagination-category'
                                ]);
                                ?>
                            </div>
                        </section>
                        
                    <?php else : ?>
                        
                        <!-- Estado Vazio -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <?php echo recifemais_get_icon_svg('news', '48', '#9ca3af'); ?>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                                Nenhuma notícia encontrada
                            </h2>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                Esta categoria ainda não possui notícias publicadas. Volte em breve para conferir as novidades.
                            </p>
                            <div class="flex gap-4 justify-center">
                                <a href="<?php echo home_url('/noticias/'); ?>" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Todas as notícias
                                </a>
                                <a href="<?php echo home_url(); ?>" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                    Página inicial
                                </a>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                    
                </div>
                
                <!-- Sidebar (1/4) -->
                <aside class="lg:col-span-1">
                    <div class="space-y-6 sticky top-8">
                        
                        <!-- Outras Categorias -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-4 bg-red-600 rounded-full"></span>
                                Outras editorias
                            </h3>
                            
                            <?php
                            $other_categories = get_categories([
                                'hide_empty' => true,
                                'exclude' => $category->term_id,
                                'number' => 6,
                                'orderby' => 'count',
                                'order' => 'DESC'
                            ]);
                            
                            if ($other_categories) :
                                foreach ($other_categories as $other_cat) :
                            ?>
                                <a href="<?php echo get_category_link($other_cat->term_id); ?>" 
                                   class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                        <span class="font-medium text-gray-900 group-hover:text-red-600 transition-colors">
                                            <?php echo esc_html($other_cat->name); ?>
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        <?php echo $other_cat->count; ?>
                                    </span>
                                </a>
                            <?php endforeach; endif; ?>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="<?php echo home_url('/noticias/'); ?>" 
                                   class="text-red-600 font-semibold hover:text-red-700 flex items-center gap-2">
                                    Ver todas as editorias
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Mais Lidas -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-1 h-4 bg-orange-500 rounded-full"></span>
                                Mais lidas
                            </h3>
                            
                            <?php
                            $popular_posts = new WP_Query([
                                'posts_per_page' => 5,
                                'cat' => $category->term_id,
                                'meta_key' => 'post_views_count',
                                'orderby' => 'meta_value_num',
                                'order' => 'DESC',
                                'date_query' => [
                                    ['after' => '1 week ago']
                                ]
                            ]);
                            
                            if ($popular_posts->have_posts()) :
                                $counter = 1;
                                while ($popular_posts->have_posts()) : $popular_posts->the_post();
                            ?>
                                <article class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <div class="w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?php echo $counter; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2 group-hover:text-red-600 transition-colors">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <div class="text-xs text-gray-500">
                                            <?php echo get_the_date('d/m/Y'); ?>
                                        </div>
                                    </div>
                                </article>
                            <?php 
                                $counter++;
                                endwhile; 
                                wp_reset_postdata();
                            else :
                            ?>
                                <p class="text-sm text-gray-500 text-center py-4">
                                    Dados em construção
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Newsletter -->
                        <?php get_template_part('template-parts/homepage/newsletter-signup'); ?>
                        
                    </div>
                </aside>
                
            </div>
        </div>
    </div>
    
</main>

<?php get_footer(); ?> 