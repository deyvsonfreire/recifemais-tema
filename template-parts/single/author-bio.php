<?php
/**
 * Template Part: Author Bio
 * 
 * Bio do autor com:
 * - Informações completas do autor
 * - Redes sociais e contato
 * - Posts recentes do autor
 * - Estatísticas de publicação
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do autor
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name');
$author_bio = get_the_author_meta('description');
$author_email = get_the_author_meta('user_email');
$author_website = get_the_author_meta('user_url');
$author_avatar = get_avatar_url($author_id, ['size' => 120]);

// Meta fields customizados do autor (se existirem)
$author_job_title = get_user_meta($author_id, 'job_title', true);
$author_twitter = get_user_meta($author_id, 'twitter', true);
$author_instagram = get_user_meta($author_id, 'instagram', true);
$author_linkedin = get_user_meta($author_id, 'linkedin', true);
$author_facebook = get_user_meta($author_id, 'facebook', true);

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_recent_posts' => true,
    'recent_posts_count' => 3,
    'show_social_links' => true,
    'show_contact_button' => false,
    'show_stats' => true,
    'layout' => 'card' // card, inline, minimal
]);

// Estatísticas do autor
$author_posts_count = count_user_posts($author_id, 'post');
$author_first_post = get_posts([
    'author' => $author_id,
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'ASC'
]);
$author_since = !empty($author_first_post) ? get_the_date('Y', $author_first_post[0]->ID) : date('Y');

// Posts recentes do autor
$recent_author_posts = [];
if ($args['show_recent_posts']) {
    $recent_author_posts = get_posts([
        'author' => $author_id,
        'posts_per_page' => $args['recent_posts_count'],
        'post__not_in' => [get_the_ID()],
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Se não tiver bio, não exibir
if (!$author_bio && !$args['show_recent_posts']) {
    return;
}
?>

<section class="author-bio bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-lg p-8 my-12">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Informações do Autor -->
        <div class="flex-1">
            <div class="flex flex-col sm:flex-row gap-6">
                
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <img src="<?php echo esc_url($author_avatar); ?>" 
                             alt="<?php echo esc_attr($author_name); ?>"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        
                        <!-- Badge de verificação (se aplicável) -->
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-recife-primary rounded-full flex items-center justify-center border-2 border-white">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Informações Principais -->
                <div class="flex-1">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">
                            <a href="<?php echo get_author_posts_url($author_id); ?>" 
                               class="hover:text-recife-primary transition-colors">
                                <?php echo esc_html($author_name); ?>
                            </a>
                        </h3>
                        
                        <?php if ($author_job_title): ?>
                            <p class="text-recife-primary font-semibold text-lg">
                                <?php echo esc_html($author_job_title); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($args['show_stats']): ?>
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo number_format($author_posts_count); ?> artigos
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Desde <?php echo esc_html($author_since); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Bio -->
                    <?php if ($author_bio): ?>
                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed mb-6">
                            <?php echo wpautop(esc_html($author_bio)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Redes Sociais e Contato -->
                    <div class="flex flex-wrap items-center gap-4">
                        
                        <?php if ($args['show_social_links']): ?>
                            <!-- Redes Sociais -->
                            <div class="flex items-center gap-3">
                                
                                <?php if ($author_twitter): ?>
                                    <a href="https://twitter.com/<?php echo esc_attr($author_twitter); ?>" 
                                       target="_blank" 
                                       rel="noopener"
                                       class="flex items-center justify-center w-8 h-8 bg-sky-500 hover:bg-sky-600 text-white rounded-full transition-colors"
                                       title="Twitter">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($author_instagram): ?>
                                    <a href="https://instagram.com/<?php echo esc_attr($author_instagram); ?>" 
                                       target="_blank" 
                                       rel="noopener"
                                       class="flex items-center justify-center w-8 h-8 bg-pink-500 hover:bg-pink-600 text-white rounded-full transition-colors"
                                       title="Instagram">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.928-.875 2.026-1.365 3.323-1.365s2.448.49 3.323 1.365c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323c-.875.807-2.026 1.218-3.323 1.218zm7.718-1.297c-.875.807-2.026 1.297-3.323 1.297s-2.448-.49-3.323-1.297c-.928-.875-1.418-2.026-1.418-3.323s.49-2.448 1.418-3.323c.875-.875 2.026-1.365 3.323-1.365s2.448.49 3.323 1.365c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.323z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($author_linkedin): ?>
                                    <a href="https://linkedin.com/in/<?php echo esc_attr($author_linkedin); ?>" 
                                       target="_blank" 
                                       rel="noopener"
                                       class="flex items-center justify-center w-8 h-8 bg-blue-700 hover:bg-blue-800 text-white rounded-full transition-colors"
                                       title="LinkedIn">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($author_website): ?>
                                    <a href="<?php echo esc_url($author_website); ?>" 
                                       target="_blank" 
                                       rel="noopener"
                                       class="flex items-center justify-center w-8 h-8 bg-gray-600 hover:bg-gray-700 text-white rounded-full transition-colors"
                                       title="Website">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Botões de Ação -->
                        <div class="flex items-center gap-3">
                            <a href="<?php echo get_author_posts_url($author_id); ?>" 
                               class="inline-flex items-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Ver Todos os Artigos
                            </a>
                            
                            <?php if ($args['show_contact_button'] && $author_email): ?>
                                <a href="mailto:<?php echo esc_attr($author_email); ?>" 
                                   class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    Contato
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Posts Recentes do Autor -->
        <?php if ($args['show_recent_posts'] && !empty($recent_author_posts)): ?>
            <div class="lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Artigos Recentes
                    </h4>
                    
                    <div class="space-y-4">
                        <?php foreach ($recent_author_posts as $recent_post): 
                            $recent_image = get_the_post_thumbnail_url($recent_post->ID, 'thumbnail');
                            $recent_category = get_the_category($recent_post->ID);
                            $recent_date = get_the_date('d/m/Y', $recent_post->ID);
                            ?>
                            <article class="flex gap-3 group">
                                <?php if ($recent_image): ?>
                                    <div class="flex-shrink-0">
                                        <a href="<?php echo get_permalink($recent_post->ID); ?>" 
                                           class="block w-16 h-16 rounded-lg overflow-hidden">
                                            <img src="<?php echo esc_url($recent_image); ?>" 
                                                 alt="<?php echo esc_attr($recent_post->post_title); ?>"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <?php if (!empty($recent_category)): ?>
                                        <div class="mb-1">
                                            <span class="text-xs font-semibold text-recife-primary uppercase tracking-wide">
                                                <?php echo esc_html($recent_category[0]->name); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h5 class="text-sm font-semibold text-gray-900 leading-tight mb-1">
                                        <a href="<?php echo get_permalink($recent_post->ID); ?>" 
                                           class="hover:text-recife-primary transition-colors">
                                            <?php echo esc_html($recent_post->post_title); ?>
                                        </a>
                                    </h5>
                                    
                                    <time class="text-xs text-gray-500" datetime="<?php echo get_the_date('c', $recent_post->ID); ?>">
                                        <?php echo esc_html($recent_date); ?>
                                    </time>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="<?php echo get_author_posts_url($author_id); ?>" 
                           class="inline-flex items-center gap-2 text-sm font-medium text-recife-primary hover:text-recife-primary/80 transition-colors">
                            Ver todos os artigos de <?php echo esc_html($author_name); ?>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section> 