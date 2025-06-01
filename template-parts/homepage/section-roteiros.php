<?php
/**
 * Section Roteiros - Homepage RecifeMais
 * Seção de roteiros temáticos e experiências curadas
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações da seção
$roteiros_config = [
    'show_section' => true,
    'roteiros_limit' => 6,
    'show_featured' => true,
    'show_categories' => true,
    'layout' => 'carousel' // carousel, grid
];

// Categorias de roteiros
$roteiro_categories = [
    'fim-de-semana' => [
        'title' => 'Fim de Semana',
        'description' => 'Roteiros perfeitos para 2-3 dias',
        'icon' => '🌅',
        'color' => 'var(--recife-primary)',
        'count' => 0
    ],
    'tematicos' => [
        'title' => 'Temáticos',
        'description' => 'Experiências especializadas',
        'icon' => '🎯',
        'color' => 'var(--recife-creative)',
        'count' => 0
    ],
    'por-bairro' => [
        'title' => 'Por Bairro',
        'description' => 'Explore cada região',
        'icon' => '🗺️',
        'color' => 'var(--recife-secondary)',
        'count' => 0
    ],
    'acessiveis' => [
        'title' => 'Acessíveis',
        'description' => 'Roteiros inclusivos',
        'icon' => '♿',
        'color' => 'var(--recife-success)',
        'count' => 0
    ]
];

// Buscar roteiros em destaque
$featured_roteiros = new WP_Query([
    'post_type' => 'roteiros',
    'posts_per_page' => $roteiros_config['roteiros_limit'],
    'meta_query' => [
        [
            'key' => '_recifemais_featured',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Se não houver roteiros em destaque, buscar os mais recentes
if (!$featured_roteiros->have_posts()) {
    $featured_roteiros = new WP_Query([
        'post_type' => 'roteiros',
        'posts_per_page' => $roteiros_config['roteiros_limit'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Contar roteiros por categoria
foreach ($roteiro_categories as $slug => &$category) {
    $count_query = new WP_Query([
        'post_type' => 'roteiros',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'tipos_roteiros',
                'field' => 'slug',
                'terms' => $slug
            ]
        ],
        'post_status' => 'publish',
        'fields' => 'ids'
    ]);
    $category['count'] = $count_query->found_posts;
    wp_reset_postdata();
}

if (!$featured_roteiros->have_posts()) return;
?>

<section class="section-roteiros py-12 lg:py-16 bg-recife-gray-50" role="region" aria-label="Roteiros Culturais">
    <div class="container mx-auto px-4">
        
        <!-- Cabeçalho da Seção -->
        <div class="section-header mb-12 lg:mb-16">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-end">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-8 bg-recife-secondary mr-3"></div>
                        <span class="text-sm font-semibold text-recife-secondary uppercase tracking-wide">
                            Roteiros
                        </span>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-recife-gray-900 mb-4">
                        Experiências Curadas
                    </h2>
                    <p class="text-lg text-recife-gray-600 max-w-2xl">
                        Roteiros especialmente criados para você descobrir o melhor de Pernambuco, 
                        com dicas exclusivas e experiências autênticas.
                    </p>
                </div>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-recife-primary mb-1">
                            <?php echo $featured_roteiros->found_posts; ?>+
                        </div>
                        <div class="text-sm text-recife-gray-600">Roteiros Disponíveis</div>
                    </div>
                    <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-recife-secondary mb-1">
                            <?php echo array_sum(array_column($roteiro_categories, 'count')); ?>+
                        </div>
                        <div class="text-sm text-recife-gray-600">Experiências</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros de Categoria -->
        <?php if ($roteiros_config['show_categories']) : ?>
        <div class="category-filters mb-12">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach ($roteiro_categories as $slug => $category) : ?>
                <button class="category-filter-btn group relative overflow-hidden bg-white rounded-xl p-6 text-left hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                        data-category="<?php echo $slug; ?>">
                    
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                        <div class="w-full h-full" style="background: <?php echo $category['color']; ?>;"></div>
                    </div>
                    
                    <!-- Conteúdo -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300"
                                 style="background: linear-gradient(135deg, <?php echo $category['color']; ?>22 0%, <?php echo $category['color']; ?>44 100%);">
                                <?php echo $category['icon']; ?>
                            </div>
                            <span class="text-2xl font-bold group-hover:text-recife-primary transition-colors"
                                  style="color: <?php echo $category['color']; ?>;">
                                <?php echo $category['count']; ?>
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-recife-gray-900 mb-2 group-hover:text-recife-primary transition-colors">
                            <?php echo $category['title']; ?>
                        </h3>
                        
                        <p class="text-sm text-recife-gray-600">
                            <?php echo $category['description']; ?>
                        </p>
                    </div>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Grid/Carousel de Roteiros -->
        <div class="roteiros-container">
            
            <?php if ($roteiros_config['layout'] === 'carousel') : ?>
            <!-- Layout Carousel -->
            <div class="roteiros-carousel relative" data-auto-rotate="true" data-interval="6000">
                <div class="carousel-track flex transition-transform duration-500 ease-in-out">
                    
                    <?php 
                    $roteiro_count = 0;
                    while ($featured_roteiros->have_posts()) : $featured_roteiros->the_post();
                        $roteiro_duracao = get_post_meta(get_the_ID(), 'roteiro_duracao', true);
                        $roteiro_dificuldade = get_post_meta(get_the_ID(), 'roteiro_dificuldade', true);
                        $roteiro_preco_estimado = get_post_meta(get_the_ID(), 'roteiro_preco_estimado', true);
                        $roteiro_pontos_interesse = get_post_meta(get_the_ID(), 'roteiro_pontos_interesse', true);
                        $roteiro_categories = get_the_terms(get_the_ID(), 'tipos_roteiros');
                        $primary_category = $roteiro_categories ? $roteiro_categories[0] : null;
                    ?>
                    
                    <article class="roteiro-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-3 group" 
                             data-category="<?php echo $primary_category ? $primary_category->slug : 'all'; ?>">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                            
                            <!-- Imagem -->
                            <div class="relative aspect-[4/3] bg-recife-gray-200 overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110',
                                        'loading' => $roteiro_count < 3 ? 'eager' : 'lazy'
                                    ]); ?>
                                </a>
                                <?php endif; ?>
                                
                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex flex-col gap-2">
                                    <?php if ($primary_category) : ?>
                                    <span class="px-3 py-1 bg-white/95 backdrop-blur-sm text-recife-gray-800 text-xs font-semibold rounded-full">
                                        <?php echo $primary_category->name; ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($roteiro_duracao) : ?>
                                    <span class="px-3 py-1 bg-recife-secondary/95 backdrop-blur-sm text-white text-xs font-semibold rounded-full">
                                        <?php echo $roteiro_duracao; ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Dificuldade -->
                                <?php if ($roteiro_dificuldade) : ?>
                                <div class="absolute top-4 right-4">
                                    <div class="flex items-center gap-1 px-2 py-1 bg-black/50 backdrop-blur-sm rounded-full">
                                        <?php 
                                        $difficulty_level = (int)$roteiro_dificuldade;
                                        for ($i = 1; $i <= 3; $i++) :
                                            $filled = $i <= $difficulty_level;
                                        ?>
                                        <div class="w-2 h-2 rounded-full <?php echo $filled ? 'bg-yellow-400' : 'bg-white/30'; ?>"></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Overlay Gradiente -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <!-- Conteúdo -->
                            <div class="p-6">
                                
                                <!-- Título -->
                                <h3 class="text-xl font-bold text-recife-gray-900 mb-3 group-hover:text-recife-primary transition-colors line-clamp-2">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <!-- Resumo -->
                                <p class="text-recife-gray-600 mb-4 line-clamp-3">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </p>
                                
                                <!-- Pontos de Interesse -->
                                <?php if ($roteiro_pontos_interesse && is_array($roteiro_pontos_interesse)) : ?>
                                <div class="mb-4">
                                    <div class="flex items-center text-sm text-recife-gray-500 mb-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Pontos de Interesse
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach (array_slice($roteiro_pontos_interesse, 0, 3) as $ponto) : ?>
                                        <span class="px-2 py-1 bg-recife-gray-100 text-recife-gray-700 text-xs rounded-full">
                                            <?php echo esc_html($ponto); ?>
                                        </span>
                                        <?php endforeach; ?>
                                        <?php if (count($roteiro_pontos_interesse) > 3) : ?>
                                        <span class="px-2 py-1 bg-recife-gray-100 text-recife-gray-700 text-xs rounded-full">
                                            +<?php echo count($roteiro_pontos_interesse) - 3; ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Meta Informações -->
                                <div class="flex items-center justify-between text-sm text-recife-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?php echo $roteiro_duracao ?: 'Flexível'; ?>
                                    </div>
                                    <?php if ($roteiro_preco_estimado) : ?>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        R$ <?php echo number_format((float)$roteiro_preco_estimado, 0, ',', '.'); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- CTA -->
                                <div class="flex items-center justify-between">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="inline-flex items-center text-recife-primary hover:text-recife-primary-dark font-semibold transition-colors">
                                        Ver Roteiro
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Ações Rápidas -->
                                    <div class="flex items-center gap-2">
                                        <button class="w-8 h-8 bg-recife-gray-100 hover:bg-recife-primary hover:text-white rounded-full flex items-center justify-center transition-colors group/share"
                                                data-share-url="<?php the_permalink(); ?>"
                                                data-share-title="<?php echo esc_attr(get_the_title()); ?>"
                                                aria-label="Compartilhar roteiro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                            </svg>
                                        </button>
                                        
                                        <button class="w-8 h-8 bg-recife-gray-100 hover:bg-recife-success hover:text-white rounded-full flex items-center justify-center transition-colors group/save"
                                                data-roteiro-id="<?php echo get_the_ID(); ?>"
                                                aria-label="Salvar roteiro">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    <?php 
                    $roteiro_count++;
                    endwhile; 
                    wp_reset_postdata(); 
                    ?>
                </div>
                
                <!-- Controles do Carousel -->
                <div class="carousel-controls flex items-center justify-center mt-8 gap-4">
                    <button class="carousel-prev w-12 h-12 bg-white hover:bg-recife-primary hover:text-white rounded-full flex items-center justify-center shadow-sm transition-colors"
                            aria-label="Roteiro anterior">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <div class="carousel-dots flex gap-2">
                        <?php for ($i = 0; $i < ceil($roteiro_count / 3); $i++) : ?>
                        <button class="carousel-dot w-3 h-3 rounded-full bg-recife-gray-300 hover:bg-recife-primary transition-colors <?php echo $i === 0 ? 'active bg-recife-primary' : ''; ?>"
                                data-slide="<?php echo $i; ?>"
                                aria-label="Ir para slide <?php echo $i + 1; ?>"></button>
                        <?php endfor; ?>
                    </div>
                    
                    <button class="carousel-next w-12 h-12 bg-white hover:bg-recife-primary hover:text-white rounded-full flex items-center justify-center shadow-sm transition-colors"
                            aria-label="Próximo roteiro">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <?php else : ?>
            <!-- Layout Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Conteúdo similar ao carousel, mas sem controles -->
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Call to Action -->
        <div class="text-center mt-12">
            <div class="inline-flex flex-col sm:flex-row gap-4">
                <a href="<?php echo get_post_type_archive_link('roteiros'); ?>" 
                   class="btn-primary inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0V7"/>
                    </svg>
                    Todos os Roteiros
                </a>
                <a href="/criar-roteiro/" 
                   class="btn-secondary inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Meu Roteiro
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Estilos específicos da Section Roteiros */
.category-filter-btn {
    border: 1px solid var(--recife-gray-200);
    cursor: pointer;
}

.category-filter-btn:hover,
.category-filter-btn.active {
    border-color: var(--recife-primary);
    box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.1);
}

.roteiro-card {
    transition: all 0.3s ease;
}

.carousel-track {
    width: calc(100% * var(--slides-count, 3));
}

.carousel-dot.active {
    background-color: var(--recife-primary) !important;
}

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

/* Animações de entrada */
.roteiro-card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s ease forwards;
}

.roteiro-card:nth-child(1) { animation-delay: 0.1s; }
.roteiro-card:nth-child(2) { animation-delay: 0.2s; }
.roteiro-card:nth-child(3) { animation-delay: 0.3s; }
.roteiro-card:nth-child(4) { animation-delay: 0.4s; }
.roteiro-card:nth-child(5) { animation-delay: 0.5s; }
.roteiro-card:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estados de interação */
.roteiro-card.saved .group\/save {
    background-color: var(--recife-success);
    color: white;
}

/* Responsividade */
@media (max-width: 768px) {
    .carousel-track {
        width: calc(100% * var(--slides-count, 6));
    }
    
    .roteiro-card {
        width: 100%;
    }
    
    .category-filters {
        margin-bottom: 2rem;
    }
    
    .category-filter-btn {
        padding: 1rem;
    }
}

@media (max-width: 1024px) {
    .carousel-track {
        width: calc(100% * var(--slides-count, 3) / 2);
    }
    
    .roteiro-card {
        width: 50%;
    }
}

/* Loading states */
.roteiro-card.loading {
    opacity: 0.6;
    pointer-events: none;
}

.roteiro-card.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
</style>

<script>
// JavaScript para a Section Roteiros
document.addEventListener('DOMContentLoaded', function() {
    
    // Carousel de roteiros
    const carousel = document.querySelector('.roteiros-carousel');
    if (carousel) {
        const track = carousel.querySelector('.carousel-track');
        const cards = carousel.querySelectorAll('.roteiro-card');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
        const dots = carousel.querySelectorAll('.carousel-dot');
        
        let currentSlide = 0;
        const slidesPerView = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
        const totalSlides = Math.ceil(cards.length / slidesPerView);
        
        function updateCarousel() {
            const translateX = -(currentSlide * (100 / slidesPerView));
            track.style.transform = `translateX(${translateX}%)`;
            
            // Atualizar dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }
        
        // Event listeners
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });
        
        // Auto-rotate
        if (carousel.dataset.autoRotate === 'true') {
            const interval = parseInt(carousel.dataset.interval) || 6000;
            setInterval(nextSlide, interval);
        }
        
        // Responsividade
        window.addEventListener('resize', () => {
            const newSlidesPerView = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
            if (newSlidesPerView !== slidesPerView) {
                location.reload(); // Recarregar para recalcular
            }
        });
    }
    
    // Filtros de categoria
    const categoryBtns = document.querySelectorAll('.category-filter-btn');
    const roteiroCards = document.querySelectorAll('.roteiro-card');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Atualizar botões ativos
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar cards
            roteiroCards.forEach(card => {
                const cardCategory = card.dataset.category;
                const shouldShow = category === 'all' || cardCategory === category;
                
                if (shouldShow) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.6s ease forwards';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'filter_roteiros', {
                    event_category: 'homepage',
                    filter_type: category
                });
            }
        });
    });
    
    // Salvar roteiros
    const saveButtons = document.querySelectorAll('[data-roteiro-id]');
    saveButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const roteiroId = this.dataset.roteiroId;
            const card = this.closest('.roteiro-card');
            
            // Toggle visual
            if (card.classList.contains('saved')) {
                card.classList.remove('saved');
                this.classList.remove('bg-recife-success', 'text-white');
            } else {
                card.classList.add('saved');
                this.classList.add('bg-recife-success', 'text-white');
                
                // Animação de feedback
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            }
            
            // Salvar no localStorage
            let savedRoteiros = JSON.parse(localStorage.getItem('recifemais_saved_roteiros') || '[]');
            const index = savedRoteiros.indexOf(roteiroId);
            
            if (index > -1) {
                savedRoteiros.splice(index, 1);
            } else {
                savedRoteiros.push(roteiroId);
            }
            
            localStorage.setItem('recifemais_saved_roteiros', JSON.stringify(savedRoteiros));
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'save_roteiro', {
                    event_category: 'engagement',
                    roteiro_id: roteiroId,
                    action: index > -1 ? 'remove' : 'add'
                });
            }
        });
    });
    
    // Carregar roteiros salvos
    const savedRoteiros = JSON.parse(localStorage.getItem('recifemais_saved_roteiros') || '[]');
    savedRoteiros.forEach(roteiroId => {
        const btn = document.querySelector(`[data-roteiro-id="${roteiroId}"]`);
        if (btn) {
            const card = btn.closest('.roteiro-card');
            card.classList.add('saved');
            btn.classList.add('bg-recife-success', 'text-white');
        }
    });
    
    // Compartilhamento
    document.querySelectorAll('[data-share-url]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const url = this.dataset.shareUrl;
            const title = this.dataset.shareTitle;
            
            if (navigator.share) {
                navigator.share({ title, url });
            } else {
                // Fallback
                const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'share_roteiro', {
                    event_category: 'engagement',
                    share_method: navigator.share ? 'native' : 'twitter'
                });
            }
        });
    });
    
    // Intersection Observer para animações
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.roteiro-card').forEach(card => {
            card.style.animationPlayState = 'paused';
            observer.observe(card);
        });
    }
});
</script> 