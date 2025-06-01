<?php
/**
 * Template para Single de Histórias - RecifeMais V2
 * 
 * Template narrativo para histórias de Pernambuco:
 * - Design editorial imersivo
 * - Meta fields específicos de histórias
 * - Navegação temporal
 * - Personagens históricos
 * - Schema markup para artigos
 * - Integração com dicionários
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) :
    the_post();
    
    $historia_id = get_the_ID();
    
    // Meta dados modernos do plugin v2
    $categoria_historia = get_post_meta($historia_id, 'categoria_historia', true);
    $periodo_historico = get_post_meta($historia_id, 'periodo_historico', true);
    $personagens_historicos = get_post_meta($historia_id, 'personagens_historicos', true);
    $local_historia = get_post_meta($historia_id, 'local_historia', true);
    $endereco = get_post_meta($historia_id, 'endereco', true);
    $latitude = get_post_meta($historia_id, 'latitude', true);
    $longitude = get_post_meta($historia_id, 'longitude', true);
    $fontes_historicas = get_post_meta($historia_id, 'fontes_historicas', true);
    $importancia_cultural = get_post_meta($historia_id, 'importancia_cultural', true);
    $fatos_curiosos = get_post_meta($historia_id, 'fatos_curiosos', true);
    $legado_atual = get_post_meta($historia_id, 'legado_atual', true);
    
    // Taxonomias
    $bairros = get_the_terms($historia_id, 'bairros_recife');
    $cidades = get_the_terms($historia_id, 'cidades_pernambuco');
    
    // Inicializar dicionários
    $dicionarios = null;
    if (class_exists('RecifeMais_V2_Dicionarios')) {
        $dicionarios = new RecifeMais_V2_Dicionarios();
    }
    
    // Calcular tempo de leitura
    $content = get_the_content();
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 palavras por minuto
    
    // Schema markup para SEO (Article)
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => wp_strip_all_tags(get_the_excerpt() ?: wp_trim_words(get_the_content(), 25)),
        'url' => get_permalink(),
        'image' => get_the_post_thumbnail_url($historia_id, 'large'),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'wordCount' => $word_count,
        'author' => [
            '@type' => 'Organization',
            'name' => 'RecifeMais'
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'RecifeMais',
            'url' => home_url()
        ]
    ];
    
    if ($periodo_historico) {
        $schema['about'] = $periodo_historico;
    }
    
    if ($local_historia) {
        $schema['locationCreated'] = [
            '@type' => 'Place',
            'name' => $local_historia
        ];
    }
?>

<!-- Schema Markup -->
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="main" class="site-main bg-recife-gray-50">
    
    <!-- ===== HERO NARRATIVO ===== -->
    <article class="relative overflow-hidden">
        <?php if (has_post_thumbnail()) : ?>
            <div class="relative h-screen">
                <img src="<?php echo get_the_post_thumbnail_url($historia_id, 'full'); ?>" 
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                
                <!-- Conteúdo sobre a imagem -->
                <div class="absolute inset-0 flex items-end">
                    <div class="container mx-auto px-4 pb-20">
                        <div class="max-w-4xl">
                            
                            <!-- Breadcrumb -->
                            <nav class="mb-8" aria-label="Breadcrumb">
                                <div class="flex items-center gap-2 text-white/75 text-sm">
                                    <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                        <?php echo recifemais_get_icon_svg('home', '16'); ?>
                                        <span class="ml-2">Início</span>
                                    </a>
                                    <span>→</span>
                                    <a href="<?php echo get_post_type_archive_link('historias'); ?>" class="hover:text-white transition-colors">Histórias</a>
                                    <span>→</span>
                                    <span class="text-white"><?php the_title(); ?></span>
                                </div>
                            </nav>
                            
                            <!-- Metadados da história -->
                            <div class="flex flex-wrap items-center gap-4 mb-6">
                                <span class="inline-flex items-center gap-2 bg-cpt-lugares text-white px-4 py-2 rounded-full font-semibold">
                                    📖 História
                                </span>
                                
                                <?php if ($categoria_historia) : ?>
                                    <span class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full">
                                        <?php echo recifemais_get_icon_svg('bookmark', '16'); ?>
                                        <?php echo esc_html($categoria_historia); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($periodo_historico) : ?>
                                    <span class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-full">
                                        <?php echo recifemais_get_icon_svg('clock', '16'); ?>
                                        <?php echo esc_html($periodo_historico); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Título -->
                            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                                <?php the_title(); ?>
                            </h1>
                            
                            <!-- Resumo -->
                            <?php if (has_excerpt()) : ?>
                                <p class="text-xl lg:text-2xl text-white/90 leading-relaxed mb-8 max-w-3xl">
                                    <?php the_excerpt(); ?>
                                </p>
                            <?php endif; ?>
                            
                            <!-- Metadados de leitura -->
                            <div class="flex flex-wrap items-center gap-6 text-white/80">
                                <div class="flex items-center gap-2">
                                    <?php echo recifemais_get_icon_svg('book-open', '20'); ?>
                                    <span><?php echo $reading_time; ?> min de leitura</span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <?php echo recifemais_get_icon_svg('calendar', '20'); ?>
                                    <span>Publicado em <?php echo get_the_date('d/m/Y'); ?></span>
                                </div>
                                
                                <?php if ($local_historia) : ?>
                                    <div class="flex items-center gap-2">
                                        <?php echo recifemais_get_icon_svg('map-pin', '20'); ?>
                                        <span><?php echo esc_html($local_historia); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Header sem imagem -->
            <div class="bg-gradient-to-br from-cpt-lugares via-amber-600 to-orange-500 text-white py-20">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php the_title(); ?>
                        </h1>
                        <?php if (has_excerpt()) : ?>
                            <p class="text-xl text-white/90 max-w-3xl">
                                <?php the_excerpt(); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <div class="flex flex-col items-center gap-2">
                <span class="text-sm">Continue lendo</span>
                <?php echo recifemais_get_icon_svg('chevron-down', '24'); ?>
            </div>
        </div>
    </article>

    <!-- ===== CONTEÚDO DA HISTÓRIA ===== -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            
            <!-- Barra de progresso de leitura -->
            <div class="fixed top-0 left-0 w-full h-1 bg-recife-gray-200 z-50">
                <div id="reading-progress" class="h-full bg-gradient-to-r from-cpt-lugares to-amber-500 transition-all duration-300" style="width: 0%"></div>
            </div>
            
            <!-- Conteúdo principal -->
            <div class="bg-white rounded-2xl shadow-xl border border-recife-gray-100 overflow-hidden">
                
                <!-- Introdução destacada -->
                <?php if ($importancia_cultural) : ?>
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-8">
                        <h2 class="text-2xl font-bold text-amber-900 mb-4 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('star', '28', '#d97706'); ?>
                            Importância Cultural
                        </h2>
                        <div class="text-amber-800 text-lg leading-relaxed prose">
                            <?php echo wp_kses_post(wpautop($importancia_cultural)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Texto principal da história -->
                <div class="p-8 lg:p-12">
                    <div class="prose prose-xl max-w-none prose-headings:text-recife-gray-900 prose-p:text-recife-gray-700 prose-p:leading-relaxed prose-strong:text-recife-gray-900">
                        <?php the_content(); ?>
                    </div>
                </div>
                
                <!-- Personagens históricos -->
                <?php if ($personagens_historicos) : ?>
                    <div class="bg-blue-50 border-t border-blue-200 p-8">
                        <h2 class="text-2xl font-bold text-blue-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('users', '28', '#1e40af'); ?>
                            Personagens Históricos
                        </h2>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php 
                            $personagens_array = explode(',', $personagens_historicos);
                            foreach ($personagens_array as $personagem) : 
                                $personagem = trim($personagem);
                                if ($personagem) :
                            ?>
                                <div class="bg-white rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <?php echo recifemais_get_icon_svg('user', '20', '#3b82f6'); ?>
                                        </div>
                                        <span class="font-semibold text-blue-900"><?php echo esc_html($personagem); ?></span>
                                    </div>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Fatos curiosos -->
                <?php if ($fatos_curiosos) : ?>
                    <div class="bg-purple-50 border-t border-purple-200 p-8">
                        <h2 class="text-2xl font-bold text-purple-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('zap', '28', '#7c3aed'); ?>
                            Fatos Curiosos
                        </h2>
                        
                        <div class="prose prose-lg max-w-none text-purple-800">
                            <?php echo wp_kses_post(wpautop($fatos_curiosos)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Legado atual -->
                <?php if ($legado_atual) : ?>
                    <div class="bg-green-50 border-t border-green-200 p-8">
                        <h2 class="text-2xl font-bold text-green-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('trending-up', '28', '#059669'); ?>
                            Legado Atual
                        </h2>
                        
                        <div class="prose prose-lg max-w-none text-green-800">
                            <?php echo wp_kses_post(wpautop($legado_atual)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Localização histórica -->
                <?php if ($local_historia && $endereco) : ?>
                    <div class="bg-gray-50 border-t border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('map-pin', '28', '#374151'); ?>
                            Local da História
                        </h2>
                        
                        <div class="bg-white rounded-lg p-6 border border-gray-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('building', '24', '#6b7280'); ?>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <?php echo esc_html($local_historia); ?>
                                    </h3>
                                    <p class="text-gray-600 mb-4">
                                        <?php echo esc_html($endereco); ?>
                                    </p>
                                    
                                    <?php if ($latitude && $longitude) : ?>
                                        <button onclick="openMaps(<?php echo esc_attr($latitude); ?>, <?php echo esc_attr($longitude); ?>)" 
                                                class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                            <?php echo recifemais_get_icon_svg('external-link', '16'); ?>
                                            <span>Ver no Google Maps</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Fontes históricas -->
                <?php if ($fontes_historicas) : ?>
                    <div class="bg-recife-gray-50 border-t border-recife-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 flex items-center gap-3">
                            <?php echo recifemais_get_icon_svg('book', '28', '#374151'); ?>
                            Fontes Históricas
                        </h2>
                        
                        <div class="bg-white rounded-lg p-6 border border-recife-gray-200">
                            <div class="prose max-w-none text-recife-gray-700">
                                <?php echo wp_kses_post(wpautop($fontes_historicas)); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Ações no final -->
            <div class="mt-12 bg-white rounded-2xl shadow-lg border border-recife-gray-100 p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-recife-gray-900 mb-4">Gostou desta história?</h3>
                    <p class="text-recife-gray-600 mb-8">
                        Compartilhe com seus amigos e ajude a preservar a memória de Pernambuco
                    </p>
                    
                    <div class="flex flex-wrap justify-center gap-4">
                        <button onclick="shareWhatsApp()" class="btn bg-green-500 hover:bg-green-600 text-white">
                            WhatsApp
                        </button>
                        <button onclick="shareFacebook()" class="btn bg-blue-600 hover:bg-blue-700 text-white">
                            Facebook
                        </button>
                        <button onclick="shareTwitter()" class="btn bg-sky-500 hover:bg-sky-600 text-white">
                            Twitter
                        </button>
                        <button onclick="copyLink()" class="btn bg-gray-600 hover:bg-gray-700 text-white">
                            Copiar Link
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Histórias relacionadas -->
            <div class="mt-12">
                <h2 class="text-3xl font-bold text-recife-gray-900 mb-8 text-center">Outras Histórias</h2>
                
                <?php
                // Busca inteligente por histórias relacionadas
                $historias_relacionadas_args = [
                    'post_type' => 'historias',
                    'posts_per_page' => 3,
                    'post__not_in' => [$historia_id],
                    'meta_query' => ['relation' => 'OR']
                ];
                
                // Filtros baseados em meta fields
                if ($categoria_historia) {
                    $historias_relacionadas_args['meta_query'][] = [
                        'key' => 'categoria_historia',
                        'value' => $categoria_historia,
                        'compare' => '='
                    ];
                }
                
                if ($periodo_historico) {
                    $historias_relacionadas_args['meta_query'][] = [
                        'key' => 'periodo_historico',
                        'value' => $periodo_historico,
                        'compare' => '='
                    ];
                }
                
                // Filtro por localização
                if ($bairros && !is_wp_error($bairros)) {
                    $historias_relacionadas_args['tax_query'] = [
                        [
                            'taxonomy' => 'bairros_recife',
                            'field' => 'term_id',
                            'terms' => wp_list_pluck($bairros, 'term_id')
                        ]
                    ];
                }
                
                $historias_relacionadas = new WP_Query($historias_relacionadas_args);
                
                if ($historias_relacionadas->have_posts()) :
                    ?>
                    <div class="grid md:grid-cols-3 gap-8">
                        <?php while ($historias_relacionadas->have_posts()) : $historias_relacionadas->the_post(); 
                            $categoria_rel = get_post_meta(get_the_ID(), 'categoria_historia', true);
                            $periodo_rel = get_post_meta(get_the_ID(), 'periodo_historico', true);
                            $content_rel = get_the_content();
                            $word_count_rel = str_word_count(strip_tags($content_rel));
                            $reading_time_rel = ceil($word_count_rel / 200);
                            ?>
                            <article class="bg-white rounded-xl shadow-lg border border-recife-gray-100 overflow-hidden hover:shadow-xl transition-shadow">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" 
                                             alt="<?php the_title_attribute(); ?>"
                                             class="w-full h-full object-cover">
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-cpt-lugares text-white px-3 py-1 text-xs font-semibold rounded-full">
                                                📖 História
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3 line-clamp-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-cpt-lugares transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php if (has_excerpt()) : ?>
                                        <p class="text-recife-gray-600 mb-4 line-clamp-3">
                                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center justify-between text-sm text-recife-gray-500">
                                        <div class="flex items-center gap-4">
                                            <?php if ($periodo_rel) : ?>
                                                <span class="flex items-center gap-1">
                                                    <?php echo recifemais_get_icon_svg('clock', '14'); ?>
                                                    <?php echo esc_html($periodo_rel); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <span class="flex items-center gap-1">
                                                <?php echo recifemais_get_icon_svg('book-open', '14'); ?>
                                                <?php echo $reading_time_rel; ?> min
                                            </span>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" class="text-cpt-lugares hover:text-cpt-lugares-dark font-medium">
                                            Ler →
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="text-center text-recife-gray-500 py-12">
                        Nenhuma história relacionada encontrada no momento.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Barra de progresso de leitura
window.addEventListener('scroll', function() {
    const article = document.querySelector('article');
    const progressBar = document.getElementById('reading-progress');
    
    if (article && progressBar) {
        const articleHeight = article.offsetHeight;
        const articleTop = article.offsetTop;
        const windowHeight = window.innerHeight;
        const scrolled = window.scrollY - articleTop + windowHeight;
        const progress = Math.min(Math.max(scrolled / articleHeight * 100, 0), 100);
        
        progressBar.style.width = progress + '%';
    }
});

// Funções de compartilhamento
function shareWhatsApp() {
    const text = encodeURIComponent(`${<?php echo json_encode(get_the_title()); ?>} - História de Pernambuco - ${window.location.href}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareTwitter() {
    const text = encodeURIComponent(<?php echo json_encode(get_the_title()); ?>);
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}&hashtags=RecifeMais,HistoriaPE`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copiado para a área de transferência!');
    });
}

function openMaps(lat, lng) {
    const url = `https://www.google.com/maps?q=${lat},${lng}`;
    window.open(url, '_blank');
}

// Smooth scroll para o chevron
document.querySelector('.animate-bounce')?.addEventListener('click', function() {
    const target = document.querySelector('.container');
    if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose-xl {
    font-size: 1.25rem;
    line-height: 1.8;
}

.prose-xl p {
    margin-bottom: 1.5rem;
}

.prose-xl h2 {
    margin-top: 3rem;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    font-weight: 700;
}

.prose-xl h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 600;
}

.btn {
    @apply px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center gap-2;
}

/* Animação do scroll indicator */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0) translateX(-50%);
    }
    50% {
        transform: translateY(-10px) translateX(-50%);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
    cursor: pointer;
}

/* Estilo especial para citações */
.prose blockquote {
    @apply border-l-4 border-amber-500 bg-amber-50 p-6 italic text-amber-800 text-lg;
}

/* Responsividade */
@media (max-width: 768px) {
    .text-4xl.md\\:text-5xl.lg\\:text-7xl {
        font-size: 2.5rem;
        line-height: 1.2;
    }
    
    .prose-xl {
        font-size: 1.125rem;
    }
    
    .grid.md\\:grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>

<?php
endwhile;
get_footer();
?> 