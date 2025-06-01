<?php
/**
 * Template para Archive de Guias Temáticos
 * RecifeMais - Tema Oficial
 */

get_header(); 
?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header da página -->
        <div class="hero-archive bg-gradient-to-r from-green-500 to-teal-600 text-white py-16 rounded-2xl mb-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">📚</span>
                </div>
                <h1 class="text-4xl font-bold mb-4">Guias Temáticos</h1>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Roteiros organizados por tema para você explorar Pernambuco com propósito
                </p>
                
                <!-- Contador de resultados -->
                <?php
                $total_posts = $wp_query->found_posts;
                ?>
                <div class="mt-6 inline-flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                    <span class="text-sm"><?php echo $total_posts; ?> guias disponíveis</span>
                </div>
            </div>
        </div>

        <!-- Incluir sistema de busca avançada -->
        <?php if (file_exists(get_template_directory() . '/components/busca-avancada.php')): ?>
            <?php include get_template_directory() . '/components/busca-avancada.php'; ?>
        <?php endif; ?>

        <!-- Grid de guias temáticos -->
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card-guia group'); ?>>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-recife-gray-100 h-full flex flex-col hover:shadow-xl transition-shadow">
                            
                            <!-- Imagem com overlay -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="relative h-56 overflow-hidden">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                        'alt' => get_the_title()
                                    ]); ?>
                                    
                                    <!-- Overlay com badges -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20">
                                        <div class="absolute top-4 left-4 flex gap-2">
                                            <span class="bg-cpt-roteiros text-white px-3 py-1 text-xs font-semibold rounded-full">
                                                📚 Guia Temático
                                            </span>
                                            
                                            <!-- Dificuldade -->
                                            <?php $dificuldade = get_post_meta(get_the_ID(), 'dificuldade', true); ?>
                                            <?php if ($dificuldade) : ?>
                                                <span class="bg-white/20 text-white px-3 py-1 text-xs font-semibold rounded-full">
                                                    <?php echo esc_html($dificuldade); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Tema na parte inferior -->
                                        <?php $tema_guia = get_post_meta(get_the_ID(), 'tema_guia', true); ?>
                                        <?php if ($tema_guia) : ?>
                                            <div class="absolute bottom-4 left-4">
                                                <span class="bg-green-500 text-white px-4 py-2 text-sm font-bold rounded-lg">
                                                    <?php echo esc_html($tema_guia); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
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
                                
                                <!-- Meta informações rápidas -->
                                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                    
                                    <!-- Duração -->
                                    <?php $duracao_estimada = get_post_meta(get_the_ID(), 'duracao_estimada', true); ?>
                                    <?php if ($duracao_estimada) : ?>
                                        <div class="flex items-center gap-2 text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('clock', '16'); ?>
                                            <span><?php echo esc_html($duracao_estimada); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Número de paradas -->
                                    <?php $numero_paradas = get_post_meta(get_the_ID(), 'numero_paradas', true); ?>
                                    <?php if ($numero_paradas) : ?>
                                        <div class="flex items-center gap-2 text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('map-pin', '16'); ?>
                                            <span><?php echo esc_html($numero_paradas); ?> paradas</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Meio de transporte -->
                                    <?php $meio_transporte = get_post_meta(get_the_ID(), 'meio_transporte', true); ?>
                                    <?php if ($meio_transporte) : ?>
                                        <div class="flex items-center gap-2 text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('car', '16'); ?>
                                            <span><?php echo esc_html($meio_transporte); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Custo estimado -->
                                    <?php $custo_estimado = get_post_meta(get_the_ID(), 'custo_estimado', true); ?>
                                    <?php if ($custo_estimado) : ?>
                                        <div class="flex items-center gap-2 text-recife-gray-600">
                                            <?php echo recifemais_get_icon_svg('dollar-sign', '16'); ?>
                                            <span>R$ <?php echo esc_html($custo_estimado); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Excerpt -->
                                <?php if (has_excerpt()) : ?>
                                    <p class="text-recife-gray-700 text-sm leading-relaxed mb-4 flex-1">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Destaques do guia -->
                                <?php $destaques = get_post_meta(get_the_ID(), 'destaques_guia', true); ?>
                                <?php if ($destaques) : ?>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-recife-gray-900 mb-2">Destaques:</h4>
                                        <div class="flex flex-wrap gap-1">
                                            <?php 
                                            $destaques_array = explode(',', $destaques);
                                            foreach (array_slice($destaques_array, 0, 4) as $destaque) : 
                                            ?>
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 text-xs rounded-full">
                                                    <?php echo esc_html(trim($destaque)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Taxonomias -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php
                                    $taxonomies = ['bairros_recife', 'cidades_pernambuco'];
                                    foreach ($taxonomies as $taxonomy) {
                                        $terms = get_the_terms(get_the_ID(), $taxonomy);
                                        if ($terms && !is_wp_error($terms)) {
                                            foreach (array_slice($terms, 0, 2) as $term) {
                                                echo '<span class="inline-block bg-recife-gray-100 text-recife-gray-700 px-3 py-1 text-xs rounded-full">' . esc_html($term->name) . '</span>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                
                                <!-- Público-alvo -->
                                <?php $publico_alvo = get_post_meta(get_the_ID(), 'publico_alvo', true); ?>
                                <?php if ($publico_alvo) : ?>
                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center gap-2 text-sm text-blue-800">
                                            <?php echo recifemais_get_icon_svg('users', '16'); ?>
                                            <span class="font-medium">Ideal para:</span>
                                            <span><?php echo esc_html($publico_alvo); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Botões de ação -->
                                <div class="flex gap-3 mt-auto">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="flex-1 inline-flex items-center justify-center gap-2 bg-recife-primary text-white px-4 py-3 rounded-lg hover:bg-recife-primary-dark font-semibold group-hover:gap-3 transition-all">
                                        <span>Explorar Guia</span>
                                        <?php echo recifemais_get_icon_svg('arrow-right', '16'); ?>
                                    </a>
                                    
                                    <!-- Botão para download/favoritar -->
                                    <button class="px-4 py-3 border border-recife-gray-300 rounded-lg hover:bg-recife-gray-50 transition-colors" 
                                            title="Salvar guia">
                                        <?php echo recifemais_get_icon_svg('heart', '16'); ?>
                                    </button>
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
                    'prev_text' => '← Guias anteriores',
                    'next_text' => 'Próximos guias →',
                    'mid_size' => 2,
                    'before_page_number' => '<span class="sr-only">Página </span>',
                    'class' => 'pagination-recifemais'
                ]);
                ?>
            </div>

        <?php else : ?>
            
            <!-- Nenhum resultado -->
            <div class="text-center py-16">
                <div class="text-6xl mb-6">📚</div>
                <h2 class="text-2xl font-bold text-recife-gray-900 mb-4">Nenhum guia temático encontrado</h2>
                <p class="text-recife-gray-600 mb-8 max-w-md mx-auto">
                    Estamos preparando guias especiais para sua exploração. Em breve, novos roteiros temáticos estarão disponíveis.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/roteiros')); ?>" 
                       class="btn btn-primary">
                        🗺️ Ver Roteiros
                    </a>
                    <a href="<?php echo esc_url(home_url('/lugares')); ?>" 
                       class="btn btn-outline">
                        📍 Explorar Lugares
                    </a>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<style>
.card-guia {
    @apply transform transition-all duration-300;
}

.card-guia:hover {
    @apply -translate-y-1 scale-105;
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

/* Grid responsivo especial para guias */
@media (min-width: 1280px) {
    .grid.lg\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 2rem;
    }
}

/* Animação especial no hover dos cards */
.card-guia:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.card-guia:hover .group-hover\:gap-3 {
    gap: 0.75rem;
}
</style>

<?php get_footer(); ?> 