<?php
/**
 * Hero Breaking News - Homepage RecifeMais
 * Componente principal da homepage com breaking news e destaques
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações do hero
$hero_config = [
    'show_breaking_news' => true,
    'show_featured_posts' => true,
    'posts_limit' => 5,
    'auto_rotate' => true,
    'rotation_interval' => 8000
];

// Buscar breaking news
$breaking_news = new WP_Query([
    'posts_per_page' => 1,
    'meta_query' => [
        [
            'key' => '_recifemais_breaking_news',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'post_status' => 'publish'
]);

// Buscar posts em destaque
$featured_posts = new WP_Query([
    'posts_per_page' => $hero_config['posts_limit'],
    'meta_query' => [
        [
            'key' => '_recifemais_featured',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'post_status' => 'publish'
]);

// Se não houver posts em destaque, buscar os mais recentes
if (!$featured_posts->have_posts()) {
    $featured_posts = new WP_Query([
        'posts_per_page' => $hero_config['posts_limit'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}
?>

<section class="hero-breaking bg-white border-b border-recife-gray-200" role="banner" aria-label="Destaques principais">
    
    <?php if ($hero_config['show_breaking_news'] && $breaking_news->have_posts()) : ?>
    <!-- Breaking News Bar -->
    <div class="breaking-news-bar bg-recife-primary text-white py-2 overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="flex items-center">
                <div class="flex items-center mr-4 flex-shrink-0">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse mr-2"></div>
                    <span class="font-bold text-sm uppercase tracking-wide">Urgente</span>
                </div>
                <div class="breaking-news-content flex-1 overflow-hidden">
                    <?php while ($breaking_news->have_posts()) : $breaking_news->the_post(); ?>
                    <div class="breaking-news-item">
                        <a href="<?php the_permalink(); ?>" class="hover:underline">
                            <span class="breaking-news-text"><?php the_title(); ?></span>
                        </a>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($hero_config['show_featured_posts'] && $featured_posts->have_posts()) : ?>
    <!-- Hero Principal -->
    <div class="hero-main py-8 lg:py-12">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Destaque Principal -->
                <div class="lg:col-span-2">
                    <div class="hero-featured-carousel relative" data-auto-rotate="<?php echo $hero_config['auto_rotate'] ? 'true' : 'false'; ?>" data-interval="<?php echo $hero_config['rotation_interval']; ?>">
                        
                        <?php 
                        $post_count = 0;
                        while ($featured_posts->have_posts()) : $featured_posts->the_post(); 
                            $is_first = $post_count === 0;
                        ?>
                        
                        <article class="hero-slide <?php echo $is_first ? 'active' : 'hidden'; ?> relative group" data-slide="<?php echo $post_count; ?>">
                            <div class="relative aspect-video lg:aspect-[16/10] rounded-xl overflow-hidden bg-recife-gray-100">
                                
                                <!-- Imagem de Fundo -->
                                <?php if (has_post_thumbnail()) : ?>
                                <div class="absolute inset-0">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105',
                                        'loading' => $is_first ? 'eager' : 'lazy'
                                    ]); ?>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Overlay Gradiente -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                
                                <!-- Conteúdo -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8 text-white">
                                    
                                    <!-- Categoria -->
                                    <?php 
                                    $primary_category = recifemais_get_primary_category();
                                    if ($primary_category) :
                                        $category_color = recifemais_get_category_color($primary_category->slug);
                                    ?>
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide" 
                                              style="background-color: <?php echo $category_color; ?>; color: white;">
                                            <?php echo recifemais_get_category_icon($primary_category->slug); ?>
                                            <span class="ml-1"><?php echo $primary_category->name; ?></span>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Título -->
                                    <h2 class="text-2xl lg:text-4xl font-bold leading-tight mb-3 group-hover:text-recife-secondary transition-colors duration-300">
                                        <a href="<?php the_permalink(); ?>" class="hover:underline">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <!-- Resumo -->
                                    <p class="text-lg text-gray-200 mb-4 line-clamp-2 hidden lg:block">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                    </p>
                                    
                                    <!-- Meta Informações -->
                                    <div class="flex items-center justify-between text-sm text-gray-300">
                                        <div class="flex items-center space-x-4">
                                            <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php echo get_the_date('j \d\e F, Y'); ?>
                                            </time>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <?php echo get_the_author(); ?>
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php echo recifemais_reading_time(); ?> min
                                            </span>
                                        </div>
                                        
                                        <!-- Compartilhamento Rápido -->
                                        <div class="flex items-center space-x-2">
                                            <button class="share-btn w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors" 
                                                    data-url="<?php the_permalink(); ?>" 
                                                    data-title="<?php echo esc_attr(get_the_title()); ?>"
                                                    aria-label="Compartilhar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        
                        <?php 
                        $post_count++;
                        endwhile; 
                        wp_reset_postdata(); 
                        ?>
                        
                        <!-- Controles do Carousel -->
                        <?php if ($post_count > 1) : ?>
                        <div class="carousel-controls absolute bottom-4 right-4 flex space-x-2">
                            <?php for ($i = 0; $i < $post_count; $i++) : ?>
                            <button class="carousel-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors <?php echo $i === 0 ? 'active bg-white' : ''; ?>" 
                                    data-slide="<?php echo $i; ?>"
                                    aria-label="Ir para slide <?php echo $i + 1; ?>"></button>
                            <?php endfor; ?>
                        </div>
                        
                        <!-- Setas de Navegação -->
                        <button class="carousel-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-colors"
                                aria-label="Slide anterior">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <button class="carousel-next absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 rounded-full flex items-center justify-center text-white transition-colors"
                                aria-label="Próximo slide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sidebar de Destaques -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        
                        <!-- Título da Seção -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-recife-gray-900">Mais Lidas</h3>
                            <a href="/noticias/" class="text-sm text-recife-primary hover:text-recife-primary-dark font-medium">
                                Ver todas →
                            </a>
                        </div>
                        
                        <!-- Lista de Posts Populares -->
                        <?php
                        $popular_posts = new WP_Query([
                            'posts_per_page' => 5,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        ]);
                        
                        if ($popular_posts->have_posts()) :
                            $rank = 1;
                            while ($popular_posts->have_posts()) : $popular_posts->the_post();
                        ?>
                        
                        <article class="flex items-start space-x-4 group">
                            <!-- Ranking -->
                            <div class="flex-shrink-0 w-8 h-8 bg-recife-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-recife-gray-600"><?php echo $rank; ?></span>
                            </div>
                            
                            <!-- Conteúdo -->
                            <div class="flex-1 min-w-0">
                                <?php 
                                $primary_category = recifemais_get_primary_category();
                                if ($primary_category) :
                                ?>
                                <div class="mb-1">
                                    <span class="text-xs font-medium text-recife-primary uppercase tracking-wide">
                                        <?php echo $primary_category->name; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                
                                <h4 class="text-sm font-semibold text-recife-gray-900 leading-tight mb-1 group-hover:text-recife-primary transition-colors line-clamp-2">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                
                                <div class="flex items-center text-xs text-recife-gray-500">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('j/m'); ?>
                                    </time>
                                    <span class="mx-1">•</span>
                                    <span><?php echo recifemais_reading_time(); ?> min</span>
                                </div>
                            </div>
                        </article>
                        
                        <?php 
                        $rank++;
                        endwhile; 
                        wp_reset_postdata(); 
                        endif; 
                        ?>
                        
                        <!-- Banner Promocional -->
                        <div class="bg-gradient-to-br from-recife-primary to-recife-secondary rounded-xl p-6 text-white text-center">
                            <div class="mb-3">
                                <svg class="w-8 h-8 mx-auto text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold mb-2">Newsletter RecifeMais</h4>
                            <p class="text-sm text-white/90 mb-4">Receba as principais notícias culturais de Pernambuco</p>
                            <a href="#newsletter" class="inline-block bg-white text-recife-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors">
                                Assinar Grátis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

<style>
/* Estilos específicos do Hero Breaking */
.breaking-news-text {
    animation: scroll-left 30s linear infinite;
    white-space: nowrap;
}

@keyframes scroll-left {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

.hero-slide {
    transition: opacity 0.5s ease-in-out;
}

.hero-slide.active {
    opacity: 1;
}

.carousel-dot.active {
    background-color: white !important;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Hover effects */
.share-btn:hover {
    transform: scale(1.1);
}

.carousel-controls button:hover {
    transform: scale(1.1);
}

/* Responsividade */
@media (max-width: 768px) {
    .breaking-news-bar {
        display: none;
    }
    
    .hero-main {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .carousel-prev,
    .carousel-next {
        display: none;
    }
}
</style>

<script>
// JavaScript para o carousel
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.hero-featured-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.hero-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    
    let currentSlide = 0;
    let autoRotate = carousel.dataset.autoRotate === 'true';
    let interval = parseInt(carousel.dataset.interval) || 8000;
    let autoRotateTimer;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('hidden', i !== index);
            slide.classList.toggle('active', i === index);
        });
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function startAutoRotate() {
        if (autoRotate && slides.length > 1) {
            autoRotateTimer = setInterval(nextSlide, interval);
        }
    }
    
    function stopAutoRotate() {
        if (autoRotateTimer) {
            clearInterval(autoRotateTimer);
        }
    }
    
    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', () => {
        stopAutoRotate();
        nextSlide();
        startAutoRotate();
    });
    
    if (prevBtn) prevBtn.addEventListener('click', () => {
        stopAutoRotate();
        prevSlide();
        startAutoRotate();
    });
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoRotate();
            showSlide(index);
            startAutoRotate();
        });
    });
    
    // Pausar auto-rotate no hover
    carousel.addEventListener('mouseenter', stopAutoRotate);
    carousel.addEventListener('mouseleave', startAutoRotate);
    
    // Iniciar auto-rotate
    startAutoRotate();
    
    // Compartilhamento
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            if (navigator.share) {
                navigator.share({ title, url });
            } else {
                // Fallback para navegadores sem Web Share API
                const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
});
</script> 