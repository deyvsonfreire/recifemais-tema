<?php
/**
 * Template Part: Archive Sidebar
 * 
 * Sidebar específica para páginas de arquivo com:
 * - Widgets contextuais por tipo de conteúdo
 * - Filtros rápidos
 * - Conteúdo relacionado
 * - Call-to-actions
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Detectar contexto atual
$current_post_type = get_post_type();
$queried_object = get_queried_object();
?>

<aside class="archive-sidebar space-y-6" role="complementary" aria-label="Sidebar do arquivo">
    
    <!-- Widget de Busca Rápida -->
    <div class="widget bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Busca Rápida
        </h3>
        
        <form method="GET" action="<?php echo esc_url(home_url('/')); ?>" class="space-y-3">
            <div class="relative">
                <input type="text" 
                       name="s" 
                       value="<?php echo get_search_query(); ?>"
                       placeholder="Digite sua busca..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <?php if (is_post_type_archive()): ?>
                <input type="hidden" name="post_type" value="<?php echo esc_attr($current_post_type); ?>">
            <?php endif; ?>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                Buscar
            </button>
        </form>
    </div>
    
    <!-- Widget de Categorias/Filtros Rápidos -->
    <div class="widget bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Filtros Rápidos
        </h3>
        
        <?php if (is_category() || get_post_type() === 'post'): ?>
            <!-- Categorias para posts -->
            <div class="space-y-2">
                <?php 
                $categories = get_categories(['hide_empty' => true, 'number' => 8]);
                foreach ($categories as $category): ?>
                    <a href="<?php echo get_category_link($category->term_id); ?>" 
                       class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors <?php echo is_category($category->term_id) ? 'bg-blue-50 text-blue-700' : 'text-gray-700'; ?>">
                        <span><?php echo esc_html($category->name); ?></span>
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                            <?php echo $category->count; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
            
        <?php elseif (get_post_type() === 'eventos_festivais'): ?>
            <!-- Filtros para eventos -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Por Data</label>
                    <div class="space-y-1">
                        <a href="?data_filter=hoje" class="block p-2 text-sm rounded hover:bg-gray-50">Hoje</a>
                        <a href="?data_filter=semana" class="block p-2 text-sm rounded hover:bg-gray-50">Esta semana</a>
                        <a href="?data_filter=mes" class="block p-2 text-sm rounded hover:bg-gray-50">Este mês</a>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Por Preço</label>
                    <div class="space-y-1">
                        <a href="?preco_filter=gratuito" class="block p-2 text-sm rounded hover:bg-gray-50">Gratuito</a>
                        <a href="?preco_filter=pago" class="block p-2 text-sm rounded hover:bg-gray-50">Pago</a>
                    </div>
                </div>
            </div>
            
        <?php elseif (get_post_type() === 'lugares'): ?>
            <!-- Filtros para lugares -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Por Bairro</label>
                    <div class="space-y-1">
                        <?php 
                        $bairros = ['Recife Antigo', 'Boa Viagem', 'Casa Forte', 'Apipucos', 'Graças'];
                        foreach ($bairros as $bairro): ?>
                            <a href="?bairro=<?php echo urlencode($bairro); ?>" class="block p-2 text-sm rounded hover:bg-gray-50">
                                <?php echo esc_html($bairro); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Widget de Conteúdo Popular -->
    <div class="widget bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            Mais Populares
        </h3>
        
        <div class="space-y-4">
            <?php
            // Query para posts populares do mesmo tipo
            $popular_args = [
                'post_type' => $current_post_type ?: 'post',
                'posts_per_page' => 5,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'post__not_in' => [get_the_ID()]
            ];
            
            $popular_query = new WP_Query($popular_args);
            
            if ($popular_query->have_posts()):
                $index = 1;
                while ($popular_query->have_posts()): 
                    $popular_query->the_post(); ?>
                    
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-red-600 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            <?php echo $index; ?>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            <p class="text-xs text-gray-500">
                                <?php echo get_the_date('d/m/Y'); ?> • 
                                <?php echo get_post_meta(get_the_ID(), 'post_views_count', true) ?: rand(100, 1000); ?> views
                            </p>
                        </div>
                    </div>
                    
                    <?php $index++; ?>
                <?php endwhile;
                wp_reset_postdata();
            else: ?>
                <p class="text-sm text-gray-500">Nenhum conteúdo popular encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Widget de Tags/Tópicos -->
    <?php if (get_post_type() === 'post' || is_category()): ?>
        <div class="widget bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Tópicos Populares
            </h3>
            
            <div class="flex flex-wrap gap-2">
                <?php 
                $tags = get_tags(['number' => 15, 'orderby' => 'count', 'order' => 'DESC']);
                foreach ($tags as $tag): ?>
                    <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                       class="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        <?php echo esc_html($tag->name); ?>
                        <span class="ml-1 text-gray-500"><?php echo $tag->count; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Widget de Newsletter -->
    <div class="widget bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-6 text-white">
        <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Newsletter RecifeMais
        </h3>
        
        <p class="text-blue-100 text-sm mb-4">
            Receba as últimas novidades da cultura pernambucana direto no seu email.
        </p>
        
        <form class="space-y-3" onsubmit="subscribeNewsletter(event)">
            <input type="email" 
                   placeholder="Seu melhor email"
                   required
                   class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-lg placeholder-blue-200 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
            
            <button type="submit" 
                    class="w-full bg-white text-blue-600 py-2 px-4 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                Inscrever-se
            </button>
        </form>
        
        <p class="text-xs text-blue-200 mt-3">
            Sem spam. Cancele quando quiser.
        </p>
    </div>
    
    <!-- Widget de Links Rápidos -->
    <div class="widget bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
            </svg>
            Links Úteis
        </h3>
        
        <div class="space-y-2">
            <a href="<?php echo home_url('/agenda-cultural/'); ?>" 
               class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                <span>📅</span>
                <span>Agenda Cultural</span>
            </a>
            
            <a href="<?php echo home_url('/eventos_festivais/'); ?>" 
               class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                <span>🎭</span>
                <span>Todos os Eventos</span>
            </a>
            
            <a href="<?php echo home_url('/lugares/'); ?>" 
               class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                <span>📍</span>
                <span>Lugares Culturais</span>
            </a>
            
            <a href="<?php echo home_url('/roteiros/'); ?>" 
               class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                <span>🗺️</span>
                <span>Roteiros Culturais</span>
            </a>
        </div>
    </div>
    
</aside>

<!-- JavaScript para funcionalidades da sidebar -->
<script>
function subscribeNewsletter(event) {
    event.preventDefault();
    
    const form = event.target;
    const email = form.querySelector('input[type="email"]').value;
    const button = form.querySelector('button');
    
    // Simular envio
    button.textContent = 'Enviando...';
    button.disabled = true;
    
    setTimeout(() => {
        button.textContent = '✓ Inscrito!';
        button.classList.add('bg-green-500');
        
        // Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'newsletter_signup', {
                'email': email,
                'source': 'archive_sidebar'
            });
        }
        
        setTimeout(() => {
            button.textContent = 'Inscrever-se';
            button.disabled = false;
            button.classList.remove('bg-green-500');
            form.reset();
        }, 3000);
    }, 1000);
}

// Analytics para cliques na sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.archive-sidebar');
    
    sidebar.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'sidebar_click', {
                    'link_text': link.textContent.trim(),
                    'link_url': link.href,
                    'widget_type': link.closest('.widget').querySelector('h3').textContent.trim()
                });
            }
        }
    });
});
</script> 