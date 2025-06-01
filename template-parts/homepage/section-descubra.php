<?php
/**
 * Template Part: Section Descubra
 * Experiências autênticas do Recife - Estilo Globo.com
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações da seção
$section_config = [
    'title' => 'Descubra',
    'subtitle' => 'Experiências autênticas do Recife',
    'description' => 'Conteúdo especial sobre cultura, gastronomia, entretenimento e natureza pernambucana, com dicas exclusivas e experiências autênticas.',
    'color' => 'recife-primary', // Vermelho
    'categories' => [
        'gastronomia' => [
            'name' => 'Gastronomia',
            'color' => 'red-600',
            'category_id' => get_cat_ID('Gastronomia') ?: get_cat_ID('gastronomia')
        ],
        'cultura' => [
            'name' => 'Cultura',
            'color' => 'purple-600',
            'category_id' => get_cat_ID('Cultura') ?: get_cat_ID('cultura')
        ],
        'entretenimento' => [
            'name' => 'Entretenimento',
            'color' => 'orange-600',
            'category_id' => get_cat_ID('Entretenimento') ?: get_cat_ID('entretenimento')
        ],
        'natureza' => [
            'name' => 'Natureza',
            'color' => 'green-600',
            'category_id' => get_cat_ID('Natureza') ?: get_cat_ID('natureza')
        ]
    ]
];

// Buscar conteúdo para cada categoria
$categories_content = [];
foreach ($section_config['categories'] as $slug => $category) {
    if ($category['category_id']) {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => 6,
            'post_status' => 'publish',
            'cat' => $category['category_id'],
            'meta_key' => '_thumbnail_id'
        ];
        
        $categories_content[$slug] = new WP_Query($args);
    }
}

// Contar total de posts
$total_posts = 0;
foreach ($categories_content as $query) {
    $total_posts += $query->found_posts;
}
?>

<section class="descubra-section py-12 lg:py-16 bg-white" id="descubra" role="region" aria-label="Descubra Recife">
    <div class="container mx-auto px-4">
        
        <!-- Cabeçalho da Seção - Mesmo padrão de Roteiros -->
        <div class="section-header mb-12 lg:mb-16">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-end">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-8 bg-<?php echo $section_config['color']; ?> mr-3"></div>
                        <span class="text-sm font-semibold text-<?php echo $section_config['color']; ?> uppercase tracking-wide">
                            <?php echo esc_html($section_config['title']); ?>
                        </span>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        <?php echo esc_html($section_config['subtitle']); ?>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl">
                        <?php echo esc_html($section_config['description']); ?>
                    </p>
                </div>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-<?php echo $section_config['color']; ?> mb-1">
                            <?php echo $total_posts; ?>+
                        </div>
                        <div class="text-sm text-gray-600">Conteúdos</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center shadow-sm">
                        <div class="text-2xl font-bold text-orange-600 mb-1">
                            <?php echo count($section_config['categories']); ?>
                        </div>
                        <div class="text-sm text-gray-600">Categorias</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação por Categorias -->
        <div class="flex flex-wrap justify-center gap-4 mb-8">
            <?php foreach ($section_config['categories'] as $slug => $category) : ?>
                <?php if (isset($categories_content[$slug]) && $categories_content[$slug]->have_posts()) : ?>
                    <button class="category-tab px-6 py-3 rounded-full font-medium transition-all duration-300 
                                  border-2 border-<?php echo $category['color']; ?> text-<?php echo $category['color']; ?>
                                  hover:bg-<?php echo $category['color']; ?> hover:text-white
                                  <?php echo $slug === 'gastronomia' ? 'active bg-' . $category['color'] . ' text-white' : ''; ?>"
                            data-category="<?php echo esc_attr($slug); ?>">
                        <?php echo esc_html($category['name']); ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Conteúdo das Categorias -->
        <?php foreach ($section_config['categories'] as $slug => $category) : ?>
            <?php if (isset($categories_content[$slug]) && $categories_content[$slug]->have_posts()) : ?>
                <div class="category-content <?php echo $slug !== 'gastronomia' ? 'hidden' : ''; ?>" 
                     data-category="<?php echo esc_attr($slug); ?>">
                    
                    <div class="grid lg:grid-cols-3 gap-8">
                        
                        <!-- Coluna Principal - Destaque -->
                        <div class="lg:col-span-2">
                            <?php 
                            $featured_post = $categories_content[$slug]->posts[0];
                            $categories_content[$slug]->the_post();
                            ?>
                            
                            <!-- Card Principal -->
                            <article class="featured-card group cursor-pointer mb-8" 
                                     onclick="window.location.href='<?php echo get_permalink(); ?>'">
                                <div class="relative overflow-hidden rounded-2xl aspect-video mb-4">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', [
                                            'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                            'loading' => 'lazy'
                                        ]); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full bg-gradient-to-br from-<?php echo $category['color']; ?>/20 to-<?php echo $category['color']; ?>/40 flex items-center justify-center">
                                            <span class="text-<?php echo $category['color']; ?> text-6xl font-bold opacity-50">
                                                <?php echo strtoupper(substr($category['name'], 0, 1)); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Overlay com categoria -->
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-<?php echo $category['color']; ?> text-white px-3 py-1 rounded-full text-sm font-medium">
                                            <?php echo esc_html($category['name']); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Overlay hover -->
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 group-hover:text-<?php echo $category['color']; ?> transition-colors line-clamp-2">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <p class="text-gray-600 line-clamp-3 leading-relaxed">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                    </p>
                                    
                                    <!-- Meta informações -->
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span><?php echo get_the_date('d/m/Y'); ?></span>
                                        <span><?php echo recifemais_reading_time(get_the_ID()); ?> min de leitura</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <?php the_author(); ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </div>
                        
                        <!-- Coluna Lateral - Lista -->
                        <div class="space-y-6">
                            <h4 class="text-xl font-bold text-gray-900 border-b-2 border-<?php echo $category['color']; ?> pb-2">
                                Mais em <?php echo esc_html($category['name']); ?>
                            </h4>
                            
                            <?php 
                            // Pular o primeiro post (já mostrado como destaque)
                            for ($i = 1; $i < min(6, $categories_content[$slug]->post_count); $i++) :
                                $categories_content[$slug]->the_post();
                            ?>
                                <article class="list-item group cursor-pointer" 
                                         onclick="window.location.href='<?php echo get_permalink(); ?>'">
                                    <div class="flex gap-4">
                                        <!-- Thumbnail -->
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 rounded-lg overflow-hidden">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('thumbnail', [
                                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                                        'loading' => 'lazy'
                                                    ]); ?>
                                                <?php else : ?>
                                                    <div class="w-full h-full bg-gradient-to-br from-<?php echo $category['color']; ?>/20 to-<?php echo $category['color']; ?>/40 flex items-center justify-center">
                                                        <span class="text-<?php echo $category['color']; ?> text-lg font-bold">
                                                            <?php echo strtoupper(substr($category['name'], 0, 1)); ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Conteúdo -->
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-semibold text-gray-900 group-hover:text-<?php echo $category['color']; ?> transition-colors line-clamp-2 mb-1">
                                                <?php the_title(); ?>
                                            </h5>
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-2">
                                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                <?php echo get_the_date('d/m/Y'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            <?php endfor; ?>
                            
                            <!-- Link Ver Mais -->
                            <div class="pt-4 border-t border-gray-100">
                                <a href="<?php echo get_category_link($category['category_id']); ?>" 
                                   class="inline-flex items-center gap-2 text-<?php echo $category['color']; ?> hover:text-<?php echo $category['color']; ?>/80 font-medium text-sm transition-colors">
                                    Ver todos em <?php echo esc_html($category['name']); ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        <?php endforeach; ?>
    </div>
</section>

<style>
/* Estilos específicos da seção Descubra */
.descubra-section .category-tab.active {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.descubra-section .featured-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.descubra-section .list-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin: -0.75rem;
}

/* Animações de entrada */
.descubra-section .category-content {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .descubra-section .grid.lg\\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .descubra-section .lg\\:col-span-2 {
        order: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navegação entre categorias
    const categoryTabs = document.querySelectorAll('.category-tab');
    const categoryContents = document.querySelectorAll('.category-content');
    
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetCategory = this.dataset.category;
            
            // Remover active de todas as tabs
            categoryTabs.forEach(t => {
                t.classList.remove('active');
                // Remover classes de cor ativa
                const colorClass = t.className.match(/bg-\w+-\d+/);
                if (colorClass) {
                    t.classList.remove(colorClass[0]);
                    t.classList.remove('text-white');
                }
            });
            
            // Adicionar active na tab clicada
            this.classList.add('active');
            const activeColorClass = this.className.match(/border-(\w+-\d+)/);
            if (activeColorClass) {
                this.classList.add('bg-' + activeColorClass[1]);
                this.classList.add('text-white');
            }
            
            // Esconder todos os conteúdos
            categoryContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Mostrar conteúdo da categoria selecionada
            const targetContent = document.querySelector(`[data-category="${targetCategory}"].category-content`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
    
    // Analytics tracking
    if (typeof gtag !== 'undefined') {
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                gtag('event', 'category_view', {
                    'category': this.dataset.category,
                    'section': 'descubra'
                });
            });
        });
    }
});
</script> 