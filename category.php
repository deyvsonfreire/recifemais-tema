<?php
/**
 * Template para páginas de categoria (notícias por editoria)
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

$category = get_queried_object();
$category_color = recifemais_get_category_color($category->slug);
$category_icon = recifemais_get_category_icon($category->slug);
?>

<!-- Hero Elegante - Estilo Agenda Cultural -->
<section class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-4">
        <!-- Cabeçalho da Categoria -->
        <div class="mb-8">
            <!-- Breadcrumbs Sofisticados -->
            <nav class="mb-6">
                <div class="flex items-center text-sm text-recife-gray-500">
                    <a href="<?php echo home_url(); ?>" class="flex items-center gap-1 hover:text-recife-primary transition-colors">
                        <?php echo recifemais_get_icon_svg('home', '16', 'currentColor'); ?>
                        <span>Início</span>
                    </a>
                    <?php echo recifemais_get_icon_svg('arrow-right', '16', '#9ca3af'); ?>
                    <a href="<?php echo home_url('/noticias/'); ?>" class="flex items-center gap-1 hover:text-recife-primary transition-colors">
                        <?php echo recifemais_get_icon_svg('news', '16', 'currentColor'); ?>
                        <span>Notícias</span>
                    </a>
                    <?php echo recifemais_get_icon_svg('arrow-right', '16', '#9ca3af'); ?>
                    <span class="text-recife-gray-900 font-medium"><?php echo $category->name; ?></span>
                </div>
            </nav>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="inline-flex items-center justify-center w-12 h-12 <?php echo str_replace('bg-', 'bg-', $category_color); ?> bg-opacity-10 rounded-xl">
                    <?php echo str_replace(['16', 'currentColor'], ['24', str_replace('bg-', '#', $category_color)], $category_icon); ?>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-recife-gray-900">
                    <?php echo $category->name; ?>
                </h1>
            </div>
            
            <?php if ($category->description) : ?>
                <p class="text-base text-recife-gray-600 max-w-2xl mb-4">
                    <?php echo $category->description; ?>
                </p>
            <?php endif; ?>
            
            <!-- Estatísticas da Categoria -->
            <div class="flex items-center gap-6 text-sm text-recife-gray-500">
                <div class="flex items-center gap-1">
                    <?php echo recifemais_get_icon_svg('news', '16', 'currentColor'); ?>
                    <span><?php echo $category->count; ?> notícias publicadas</span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-1">
                    <?php echo recifemais_get_icon_svg('calendar', '16', 'currentColor'); ?>
                    <span>Última atualização: <?php echo get_the_date('j/m/Y'); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Linha Divisória com Cor da Categoria -->
        <div class="flex items-center justify-between border-b-2 <?php echo str_replace('bg-', 'border-', $category_color); ?> pb-4 mb-8">
            <h2 class="text-xl font-bold <?php echo str_replace('bg-', 'text-', $category_color); ?>">
                Últimas Notícias
            </h2>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-recife-gray-500">
                    <?php
                    global $wp_query;
                    $total = $wp_query->found_posts;
                    printf(_n('%s notícia', '%s notícias', $total, 'recifemais-tema'), number_format_i18n($total));
                    ?>
                </span>
                <a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo str_replace('bg-', 'text-', $category_color); ?> hover:opacity-80 font-medium">
                    Ver todas →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Conteúdo Principal -->
<div class="container mx-auto px-4 py-8">
    <div class="grid lg:grid-cols-12 gap-8">
        
        <!-- Lista de Notícias -->
        <main class="lg:col-span-8">
            
            <?php if (have_posts()) : ?>
                
                <!-- Notícias em Destaque desta Categoria -->
                <?php
                $featured_args = array(
                    'posts_per_page' => 2,
                    'cat' => $category->term_id,
                    'meta_key' => '_featured_post',
                    'meta_value' => '1',
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                $featured_posts = new WP_Query($featured_args);
                
                if ($featured_posts->have_posts()) :
                ?>
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('star', '24', '#f59e0b'); ?>
                        Destaques de <?php echo $category->name; ?>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php while ($featured_posts->have_posts()) : $featured_posts->the_post(); ?>
                            <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="aspect-w-16 aspect-h-9">
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'recifemais-card'); ?>" 
                                             alt="<?php the_title_attribute(); ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-6">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 <?php echo $category_color; ?> text-white text-xs font-medium rounded">
                                            <span><?php echo $category_icon; ?></span>
                                            <span>DESTAQUE</span>
                                        </span>
                                        
                                        <?php
                                        $urgencia = get_post_meta(get_the_ID(), '_noticia_urgencia', true);
                                        echo recifemais_render_urgency_badge($urgencia);
                                        ?>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3 leading-tight">
                                        <a href="<?php the_permalink(); ?>" class="group-hover:text-recife-primary transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="text-recife-gray-600 mb-4 line-clamp-3">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                    </p>
                                    
                                    <div class="flex items-center justify-between text-sm text-recife-gray-500">
                                        <div class="flex items-center gap-2">
                                            <span>📅 <?php echo get_the_date('d/m/Y'); ?></span>
                                            <span>•</span>
                                            <span>⏱️ <?php echo recifemais_reading_time(); ?></span>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" 
                                           class="text-recife-primary font-medium hover:text-recife-primary-dark transition-colors">
                                            Ler notícia →
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
                <?php endif; ?>
                
                <!-- Todas as Notícias da Categoria -->
                <section>
                    <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 flex items-center gap-2">
                        <?php echo str_replace(['16', 'currentColor'], ['24', str_replace('bg-', '#', $category_color)], $category_icon); ?>
                        Todas as notícias de <?php echo $category->name; ?>
                    </h2>
                    
                    <div class="space-y-6">
                        <?php 
                        // Excluir posts em destaque da listagem principal
                        $exclude_featured = array();
                        if ($featured_posts->have_posts()) {
                            foreach ($featured_posts->posts as $featured_post) {
                                $exclude_featured[] = $featured_post->ID;
                            }
                        }
                        
                        // Query principal da categoria
                        global $wp_query;
                        $main_query = $wp_query;
                        
                        if (!empty($exclude_featured)) {
                            $main_query->set('post__not_in', $exclude_featured);
                        }
                        
                        while (have_posts()) : the_post();
                            $urgencia = get_post_meta(get_the_ID(), '_noticia_urgencia', true);
                            $fonte_primaria = get_post_meta(get_the_ID(), '_noticia_fonte_primaria', true);
                        ?>
                        
                        <article class="bg-white rounded-lg shadow-sm border border-recife-gray-200 overflow-hidden hover:shadow-lg transition-all group">
                            <div class="flex flex-col md:flex-row">
                                <!-- Imagem -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="md:w-80 flex-shrink-0">
                                        <div class="aspect-w-16 aspect-h-9 md:aspect-w-4 md:aspect-h-3">
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'recifemais-card'); ?>" 
                                                 alt="<?php the_title_attribute(); ?>"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Conteúdo -->
                                <div class="flex-1 p-6">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 <?php echo $category_color; ?> text-white text-xs font-medium rounded">
                                            <span><?php echo $category_icon; ?></span>
                                            <span><?php echo strtoupper($category->name); ?></span>
                                        </span>
                                        
                                        <?php echo recifemais_render_urgency_badge($urgencia); ?>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3 leading-tight">
                                        <a href="<?php the_permalink(); ?>" class="group-hover:text-recife-primary transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="text-recife-gray-600 mb-4 line-clamp-2">
                                        <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                                    </p>
                                    
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-recife-gray-500 mb-4">
                                        <span class="flex items-center gap-1">
                                            <?php echo recifemais_get_icon_svg('user', '16', 'currentColor'); ?>
                                            <?php the_author(); ?>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <?php echo recifemais_get_icon_svg('calendar', '16', 'currentColor'); ?>
                                            <?php echo get_the_date('j/m/Y \à\s G\hi'); ?>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <?php echo recifemais_get_icon_svg('clock', '16', 'currentColor'); ?>
                                            <?php echo recifemais_reading_time(); ?>
                                        </span>
                                        <?php if ($fonte_primaria) : ?>
                                            <span class="flex items-center gap-1">
                                                <?php echo recifemais_get_icon_svg('shield', '16', 'currentColor'); ?>
                                                <?php echo esc_html($fonte_primaria); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex gap-2">
                                            <?php
                                            $tags = get_the_tags();
                                            if ($tags && count($tags) > 0) :
                                                $displayed_tags = array_slice($tags, 0, 3);
                                                foreach ($displayed_tags as $tag) :
                                            ?>
                                                <span class="inline-block px-2 py-1 bg-recife-gray-100 text-recife-gray-600 text-xs rounded">
                                                    <?php echo $tag->name; ?>
                                                </span>
                                            <?php 
                                                endforeach;
                                                if (count($tags) > 3) :
                                            ?>
                                                <span class="text-xs text-recife-gray-500">+<?php echo count($tags) - 3; ?></span>
                                            <?php endif; endif; ?>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" 
                                           class="inline-flex items-center gap-1 text-recife-primary font-medium hover:text-recife-primary-dark transition-colors">
                                            Ler notícia <span>→</span>
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
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => '← Notícias Anteriores',
                            'next_text' => 'Próximas Notícias →',
                            'before_page_number' => '<span class="screen-reader-text">Página </span>',
                            'class' => 'pagination-news'
                        ));
                        ?>
                    </div>
                </section>
                
            <?php else : ?>
                
                <!-- Estado Vazio -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 <?php echo str_replace('bg-', 'bg-', $category_color); ?> bg-opacity-10 rounded-full mb-6">
                        <?php echo str_replace(['16', 'currentColor'], ['48', str_replace('bg-', '#', $category_color)], $category_icon); ?>
                    </div>
                    <h2 class="text-2xl font-bold text-recife-gray-900 mb-4">
                        Nenhuma notícia encontrada em <?php echo $category->name; ?>
                    </h2>
                    <p class="text-recife-gray-600 mb-8 max-w-md mx-auto">
                        Esta editoria ainda não possui notícias publicadas. Volte em breve para conferir as novidades.
                    </p>
                    <div class="flex gap-4 justify-center">
                        <a href="<?php echo home_url('/noticias/'); ?>" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-recife-primary text-white rounded-lg hover:bg-recife-primary-dark transition-colors">
                            <?php echo recifemais_get_icon_svg('news', '16', 'white'); ?>
                            Todas as notícias
                        </a>
                        <a href="<?php echo home_url(); ?>" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-recife-gray-100 text-recife-gray-700 rounded-lg hover:bg-recife-gray-200 transition-colors">
                            <?php echo recifemais_get_icon_svg('home', '16', 'currentColor'); ?>
                            Página inicial
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
            
        </main>
        
        <!-- Sidebar -->
        <aside class="lg:col-span-4">
            <div class="space-y-8 sticky top-8">
                
                <!-- Outras Editorias -->
                <div class="bg-white rounded-xl shadow-sm border border-recife-gray-200 p-6">
                    <h3 class="text-lg font-bold text-recife-gray-900 mb-6 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('folder', '20', '#dc2626'); ?>
                        Outras Editorias
                    </h3>
                    
                    <?php
                    $other_categories = get_categories(array(
                        'hide_empty' => true,
                        'exclude' => $category->term_id,
                        'number' => 6,
                        'orderby' => 'count',
                        'order' => 'DESC'
                    ));
                    
                    if ($other_categories) :
                        foreach ($other_categories as $other_cat) :
                            $other_icon = recifemais_get_category_icon($other_cat->slug);
                            $other_color = recifemais_get_category_color($other_cat->slug);
                    ?>
                        <a href="<?php echo get_category_link($other_cat->term_id); ?>" 
                           class="flex items-center justify-between p-3 hover:bg-recife-gray-50 rounded-lg transition-colors group">
                            <div class="flex items-center gap-3">
                                <span class="text-lg"><?php echo $other_icon; ?></span>
                                <span class="font-medium text-recife-gray-900 group-hover:text-recife-primary transition-colors">
                                    <?php echo $other_cat->name; ?>
                                </span>
                            </div>
                            <span class="text-sm text-recife-gray-500 bg-recife-gray-100 px-2 py-1 rounded-full">
                                <?php echo $other_cat->count; ?>
                            </span>
                        </a>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                    
                    <div class="mt-4 pt-4 border-t border-recife-gray-200">
                        <a href="<?php echo home_url('/noticias/'); ?>" 
                           class="text-recife-primary font-semibold hover:underline flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('news', '16', 'currentColor'); ?>
                            Ver todas as editorias
                            <?php echo recifemais_get_icon_svg('arrow-right', '16', 'currentColor'); ?>
                        </a>
                    </div>
                </div>

                <!-- Mais Lidas desta Categoria -->
                <div class="bg-white rounded-xl shadow-sm border border-recife-gray-200 p-6">
                    <h3 class="text-lg font-bold text-recife-gray-900 mb-6 flex items-center gap-2">
                        <?php echo recifemais_get_icon_svg('fire', '20', '#dc2626'); ?>
                        Mais Lidas em <?php echo $category->name; ?>
                    </h3>
                    
                    <?php
                    $popular_args = array(
                        'posts_per_page' => 5,
                        'cat' => $category->term_id,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'date_query' => array(
                            array(
                                'after' => '2 weeks ago'
                            )
                        )
                    );
                    
                    $popular_posts = new WP_Query($popular_args);
                    
                    if ($popular_posts->have_posts()) :
                        $counter = 1;
                        while ($popular_posts->have_posts()) : $popular_posts->the_post();
                    ?>
                        <article class="flex gap-4 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors group">
                            <div class="w-8 h-8 bg-recife-primary text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                <?php echo $counter; ?>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-recife-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-recife-primary transition-colors">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                <div class="text-xs text-recife-gray-500">
                                    📅 <?php echo get_the_date('d/m/Y'); ?>
                                </div>
                            </div>
                        </article>
                    <?php 
                        $counter++;
                        endwhile; 
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-sm text-recife-gray-500 text-center py-4">
                            📊 Dados de popularidade em construção
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Newsletter -->
                <div class="bg-gradient-to-br from-recife-primary to-recife-secondary text-white rounded-xl p-6 shadow-lg">
                    <div class="text-center">
                        <div class="text-4xl mb-4">📬</div>
                        <h3 class="text-xl font-bold mb-3">RecifeMais News</h3>
                        
                        <p class="text-white text-opacity-90 mb-6 text-sm">
                            Receba notícias de <?php echo $category->name; ?> e outras editorias
                        </p>
                        
                        <form class="space-y-4" action="#" method="post">
                            <input type="email" 
                                   placeholder="📧 Seu e-mail" 
                                   class="w-full px-4 py-3 rounded-lg text-recife-gray-900 border-0 focus:ring-2 focus:ring-white text-sm">
                            <button type="submit" 
                                    class="w-full bg-white text-recife-primary font-semibold py-3 rounded-lg hover:bg-gray-100 transition-colors">
                                Inscrever-se Grátis
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
        </aside>
        
    </div>
</div>

<?php get_footer(); ?> 