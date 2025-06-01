<?php
/**
 * Template para Archive de Agremiações
 * RecifeMais - Tema Oficial
 */

get_header(); 
?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header da página -->
        <div class="hero-archive bg-gradient-to-r from-recife-primary to-recife-secondary text-white py-16 rounded-2xl mb-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🎪</span>
                </div>
                <h1 class="text-4xl font-bold mb-4">Agremiações Culturais</h1>
                <p class="text-xl text-recife-gray-100 max-w-2xl mx-auto">
                    Descubra as tradicionais agremiações que mantêm viva a cultura pernambucana
                </p>
                
                <!-- Contador de resultados -->
                <?php
                $total_posts = $wp_query->found_posts;
                ?>
                <div class="mt-6 inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                    <span class="text-sm"><?php echo $total_posts; ?> agremiações encontradas</span>
                </div>
            </div>
        </div>

        <!-- Incluir sistema de busca avançada -->
        <?php if (file_exists(get_template_directory() . '/components/busca-avancada.php')): ?>
            <?php include get_template_directory() . '/components/busca-avancada.php'; ?>
        <?php endif; ?>

        <!-- Filtros específicos para agremiações -->
        <div class="mb-8">
            <?php include get_template_directory() . '/components/filtros-meta-fields.php'; ?>
        </div>

        <!-- Grid de agremiações -->
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card-agremiacoes group hover:scale-105 transform transition-all duration-300'); ?>>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-recife-gray-100 h-full flex flex-col">
                            
                            <!-- Imagem -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="relative h-48 overflow-hidden">
                                    <?php the_post_thumbnail('medium', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-300',
                                        'alt' => get_the_title()
                                    ]); ?>
                                    
                                    <!-- Badge do tipo -->
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-cpt-artistas text-white px-3 py-1 text-xs font-semibold rounded-full">
                                            🎪 Agremiação
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Conteúdo -->
                            <div class="p-6 flex-1 flex flex-col">
                                
                                <!-- Título -->
                                <h3 class="text-xl font-bold text-recife-gray-900 mb-3 group-hover:text-recife-primary transition-colors">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <!-- Meta informações -->
                                <div class="space-y-2 mb-4">
                                    
                                    <!-- Tipo de agremiação -->
                                    <?php $tipo_agremiacoes = get_post_meta(get_the_ID(), 'tipo_agremiacoes', true); ?>
                                    <?php if ($tipo_agremiacoes) : ?>
                                        <div class="flex items-center gap-2 text-sm text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('star', '16'); ?>
                                            <span><?php echo esc_html($tipo_agremiacoes); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Localização -->
                                    <?php 
                                    $local = get_post_meta(get_the_ID(), 'local', true);
                                    $bairros = get_the_terms(get_the_ID(), 'bairros_recife');
                                    $location = $local ?: ($bairros ? $bairros[0]->name : '');
                                    ?>
                                    <?php if ($location) : ?>
                                        <div class="flex items-center gap-2 text-sm text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('map-pin', '16'); ?>
                                            <span><?php echo esc_html($location); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Ano de fundação -->
                                    <?php $ano_fundacao = get_post_meta(get_the_ID(), 'ano_fundacao', true); ?>
                                    <?php if ($ano_fundacao) : ?>
                                        <div class="flex items-center gap-2 text-sm text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('calendar', '16'); ?>
                                            <span>Fundada em <?php echo esc_html($ano_fundacao); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Excerpt -->
                                <?php if (has_excerpt()) : ?>
                                    <p class="text-recife-gray-600 text-sm line-clamp-3 flex-1 mb-4">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Taxonomias -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php
                                    $taxonomies = ['bairros_recife', 'cidades_pernambuco'];
                                    foreach ($taxonomies as $taxonomy) {
                                        $terms = get_the_terms(get_the_ID(), $taxonomy);
                                        if ($terms && !is_wp_error($terms)) {
                                            foreach (array_slice($terms, 0, 2) as $term) {
                                                echo '<span class="inline-block bg-recife-gray-100 text-recife-gray-700 px-2 py-1 text-xs rounded-full">' . esc_html($term->name) . '</span>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                
                                <!-- Botão de ação -->
                                <div class="mt-auto">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="inline-flex items-center gap-2 text-recife-primary hover:text-recife-primary-dark font-semibold text-sm group-hover:gap-3 transition-all">
                                        <span>Conhecer agremiação</span>
                                        <?php echo recifemais_get_icon_svg('arrow-right', '16'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
            </div>

            <!-- Paginação -->
            <div class="flex justify-center">
                <?php
                echo paginate_links([
                    'prev_text' => '← Anterior',
                    'next_text' => 'Próximo →',
                    'mid_size' => 2,
                    'before_page_number' => '<span class="sr-only">Página </span>',
                    'class' => 'pagination-recifemais'
                ]);
                ?>
            </div>

        <?php else : ?>
            
            <!-- Nenhum resultado -->
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🎪</div>
                <h2 class="text-2xl font-bold text-recife-gray-900 mb-4">Nenhuma agremiação encontrada</h2>
                <p class="text-recife-gray-600 mb-8 max-w-md mx-auto">
                    Não encontramos agremiações culturais no momento. Que tal explorar outros conteúdos?
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/eventos_festivais')); ?>" 
                       class="btn btn-primary">
                        🎭 Ver Eventos
                    </a>
                    <a href="<?php echo esc_url(home_url('/artistas')); ?>" 
                       class="btn btn-outline">
                        🎨 Ver Artistas
                    </a>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<style>
.card-agremiacoes {
    @apply hover:shadow-xl;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hero-archive {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.pagination-recifemais a,
.pagination-recifemais span {
    @apply px-4 py-2 mx-1 border border-recife-gray-300 rounded-lg text-recife-gray-700 hover:bg-recife-primary hover:text-white hover:border-recife-primary transition-colors;
}

.pagination-recifemais .current {
    @apply bg-recife-primary text-white border-recife-primary;
}
</style>

<?php get_footer(); ?> 