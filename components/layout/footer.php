<?php
/**
 * Componente Footer Moderno - RecifeMais
 * Footer responsivo com seções organizadas e interativo
 *
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados dinâmicos do site
$site_name = get_bloginfo('name');
$site_description = get_bloginfo('description');
$current_year = date('Y');

// Estatísticas dinâmicas (podem vir do banco ou cache)
$stats = array(
    'posts' => wp_count_posts('post')->publish,
    'eventos' => wp_count_posts('eventos_festivais')->publish,
    'lugares' => wp_count_posts('lugares')->publish,
    'artistas' => wp_count_posts('artistas')->publish,
);

// Links de redes sociais (podem vir de customizer)
$social_links = array(
    'facebook' => get_theme_mod('facebook_url', '#'),
    'instagram' => get_theme_mod('instagram_url', '#'),
    'twitter' => get_theme_mod('twitter_url', '#'),
    'youtube' => get_theme_mod('youtube_url', '#'),
    'whatsapp' => get_theme_mod('whatsapp_url', '#'),
);
?>

<footer role="contentinfo" class="site-footer bg-recife-gray-900 text-white relative overflow-hidden">
    
    <!-- Background Decorativo -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-32 h-32 bg-recife-primary rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-20 w-24 h-24 bg-recife-secondary rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-recife-accent rounded-full animate-float" style="animation-delay: 4s;"></div>
    </div>

    <!-- Conteúdo Principal do Footer -->
    <div class="relative py-16 lg:py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                
                <!-- Seção Sobre -->
                <div class="footer-section footer-about-section">
                    <div class="footer-logo-container mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-gradient-to-br from-recife-primary to-recife-secondary text-white p-3 rounded-lg">
                                <span class="text-2xl font-bold">R+</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white"><?php echo esc_html($site_name); ?></h3>
                                <p class="text-recife-gray-300 text-sm"><?php echo esc_html($site_description); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-recife-gray-300 mb-6 leading-relaxed">
                        Conectando você com o melhor de Recife e Pernambuco. 
                        Descubra cultura, eventos, gastronomia e muito mais.
                    </p>
                    
                    <!-- Estatísticas do Site -->
                    <div class="footer-stats-grid grid grid-cols-2 gap-4">
                        <div class="footer-stats-item text-center p-3 bg-white bg-opacity-5 rounded-lg">
                            <div class="footer-stats-number text-2xl font-bold text-recife-accent"><?php echo $stats['posts']; ?></div>
                            <div class="text-xs text-recife-gray-400 uppercase tracking-wide">Notícias</div>
                        </div>
                        <div class="footer-stats-item text-center p-3 bg-white bg-opacity-5 rounded-lg">
                            <div class="footer-stats-number text-2xl font-bold text-recife-secondary"><?php echo $stats['eventos']; ?></div>
                            <div class="text-xs text-recife-gray-400 uppercase tracking-wide">Eventos</div>
                        </div>
                        <div class="footer-stats-item text-center p-3 bg-white bg-opacity-5 rounded-lg">
                            <div class="footer-stats-number text-2xl font-bold text-recife-accent"><?php echo $stats['lugares']; ?></div>
                            <div class="text-xs text-recife-gray-400 uppercase tracking-wide">Lugares</div>
                        </div>
                        <div class="footer-stats-item text-center p-3 bg-white bg-opacity-5 rounded-lg">
                            <div class="footer-stats-number text-2xl font-bold text-recife-primary"><?php echo $stats['artistas']; ?></div>
                            <div class="text-xs text-recife-gray-400 uppercase tracking-wide">Artistas</div>
                        </div>
                    </div>
                </div>

                <!-- Seção Navegação -->
                <div class="footer-section footer-nav-section">
                    <h3 class="text-lg font-semibold text-white mb-6 border-b border-recife-gray-700 pb-2">
                        🧭 Navegação
                    </h3>
                    <nav class="space-y-3">
                        <a href="<?php echo home_url(); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('home', '16'); ?>
                            <span>Início</span>
                        </a>
                        <a href="<?php echo home_url('/noticias'); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('news', '16'); ?>
                            <span>Notícias</span>
                        </a>
                        <a href="<?php echo home_url('/eventos'); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('calendar', '16'); ?>
                            <span>Eventos</span>
                        </a>
                        <a href="<?php echo home_url('/lugares'); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('map-pin', '16'); ?>
                            <span>Lugares</span>
                        </a>
                        <a href="<?php echo home_url('/roteiros'); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('route', '16'); ?>
                            <span>Roteiros</span>
                        </a>
                        <a href="<?php echo home_url('/sobre'); ?>" class="footer-nav-link group">
                            <?php echo recifemais_get_icon_svg('info', '16'); ?>
                            <span>Sobre</span>
                        </a>
                    </nav>
                </div>

                <!-- Seção Categorias -->
                <div class="footer-section footer-categories-section">
                    <h3 class="text-lg font-semibold text-white mb-6 border-b border-recife-gray-700 pb-2">
                        🏷️ Categorias
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 6,
                            'hide_empty' => true
                        ));
                        
                        foreach ($categories as $category) :
                        ?>
                        <a href="<?php echo get_category_link($category->term_id); ?>" class="category-link group">
                            <span class="category-dot"></span>
                            <span><?php echo $category->name; ?></span>
                            <span class="text-recife-gray-500 text-sm ml-auto">(<?php echo $category->count; ?>)</span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Seção Contato & Newsletter -->
                <div class="footer-section footer-contact-section">
                    <h3 class="text-lg font-semibold text-white mb-6 border-b border-recife-gray-700 pb-2">
                        📞 Contato
                    </h3>
                    
                    <!-- Informações de Contato -->
                    <div class="space-y-4 mb-8">
                        <div class="contact-item">
                            <div class="contact-icon-container">
                                <?php echo recifemais_get_icon_svg('mail', '18'); ?>
                            </div>
                            <div>
                                <div class="text-sm text-recife-gray-300">Email</div>
                                <a href="mailto:contato@recifemais.com.br" class="text-white hover:text-recife-accent transition-colors">
                                    contato@recifemais.com.br
                                </a>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon-container">
                                <?php echo recifemais_get_icon_svg('map-pin', '18'); ?>
                            </div>
                            <div>
                                <div class="text-sm text-recife-gray-300">Localização</div>
                                <span class="text-white">Recife, Pernambuco</span>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon-container">
                                <?php echo recifemais_get_icon_svg('phone', '18'); ?>
                            </div>
                            <div>
                                <div class="text-sm text-recife-gray-300">WhatsApp</div>
                                <a href="https://wa.me/5581999999999" class="text-white hover:text-recife-accent transition-colors">
                                    (81) 99999-9999
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="newsletter-container">
                        <h4 class="newsletter-title">📧 Newsletter</h4>
                        <p class="newsletter-description">
                            Receba as melhores dicas e novidades de Recife
                        </p>
                        
                        <form class="newsletter-form" id="footer-newsletter-form">
                            <div class="flex">
                                <input type="email" 
                                       class="newsletter-input" 
                                       placeholder="Seu email..."
                                       required>
                                <button type="submit" class="newsletter-button">
                                    <?php echo recifemais_get_icon_svg('send', '18'); ?>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Stats da Newsletter -->
                        <div class="newsletter-stats">
                            <div class="newsletter-stat">
                                <span class="newsletter-stat-number">2.5k+</span>
                                <span class="newsletter-stat-label">Assinantes</span>
                            </div>
                            <div class="newsletter-stat">
                                <span class="newsletter-stat-number">98%</span>
                                <span class="newsletter-stat-label">Satisfação</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Separador -->
    <div class="footer-separator"></div>

    <!-- Footer Bottom -->
    <div class="footer-bottom py-8 bg-recife-gray-950">
        <div class="container mx-auto px-4">
            <div class="footer-bottom-content">
                
                <!-- Copyright -->
                <div class="text-center lg:text-left">
                    <p class="text-recife-gray-300">
                        © <?php echo $current_year; ?> <strong class="text-white"><?php echo esc_html($site_name); ?></strong>. 
                        Todos os direitos reservados.
                    </p>
                    <p class="text-sm text-recife-gray-400 mt-1">
                        Desenvolvido com ❤️ em Recife, Pernambuco
                    </p>
                </div>

                <!-- Redes Sociais -->
                <div class="social-links-container">
                    <span class="text-sm text-recife-gray-400 mb-3 block text-center lg:text-right">Siga-nos</span>
                    <div class="flex justify-center lg:justify-end gap-3">
                        <?php foreach ($social_links as $platform => $url) : ?>
                            <?php if ($url && $url !== '#') : ?>
                            <a href="<?php echo esc_url($url); ?>" 
                               class="social-link <?php echo $platform; ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="<?php echo ucfirst($platform); ?>">
                                <?php echo recifemais_get_social_icon($platform); ?>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Links Legais -->
                <div class="legal-links">
                    <a href="<?php echo home_url('/politica-privacidade'); ?>" class="legal-link">
                        Política de Privacidade
                    </a>
                    <a href="<?php echo home_url('/termos-uso'); ?>" class="legal-link">
                        Termos de Uso
                    </a>
                    <a href="<?php echo home_url('/cookies'); ?>" class="legal-link">
                        Cookies
                    </a>
                </div>
            </div>

            <!-- Badges de Segurança e Tecnologia -->
            <div class="pt-6 mt-6 border-t border-recife-gray-800">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    
                    <!-- Badges de Segurança -->
                    <div class="security-badges">
                        <div class="security-badge">
                            <div class="security-badge-icon">🔒</div>
                            <span>SSL Seguro</span>
                        </div>
                    </div>
                    
                    <!-- Badges de Tecnologia -->
                    <div class="tech-badges">
                        <div class="tech-badge">WordPress</div>
                        <div class="tech-badge">PHP 8+</div>
                        <div class="tech-badge">MySQL</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
/**
 * Função para gerar ícones de redes sociais
 */
function recifemais_get_social_icon($platform) {
    $icons = array(
        'facebook' => '📘',
        'instagram' => '📸',
        'twitter' => '🐦',
        'youtube' => '📺',
        'whatsapp' => '📱',
        'linkedin' => '💼',
        'tiktok' => '🎵'
    );
    
    return isset($icons[$platform]) ? $icons[$platform] : '🔗';
}
?> 