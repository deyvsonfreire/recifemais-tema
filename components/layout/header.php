<?php
/**
 * Componente Header Moderno - RecifeMais (Estilo Globo.com)
 * Header limpo e harmônico inspirado na Globo.com
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
$logo_url = get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '';
$current_user = wp_get_current_user();
?>

<header class="site-header sticky top-0 z-50 bg-white shadow-sm transition-all duration-300" role="banner">
    
    <!-- Barra Superior Discreta -->
    <div class="top-bar bg-gray-100 border-b border-gray-200 py-2 hidden lg:block">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm text-gray-600">
                <!-- Data e Informações -->
                <div class="flex items-center gap-6">
                    <span id="current-time" class="font-medium">
                        <?php echo date('l, j \d\e F \d\e Y'); ?>
                    </span>
                    <span class="text-gray-500">Recife, PE</span>
                </div>
                
                <!-- Links Utilitários -->
                <div class="flex items-center gap-6">
                    <a href="<?php echo home_url('/newsletter'); ?>" 
                       class="hover:text-red-600 transition-colors font-medium">
                        Newsletter
                    </a>
                    <a href="<?php echo home_url('/anuncie'); ?>" 
                       class="hover:text-red-600 transition-colors font-medium">
                        Anuncie
                    </a>
                    <?php if (is_user_logged_in()) : ?>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-700">Olá, <?php echo $current_user->display_name; ?></span>
                            <a href="<?php echo wp_logout_url(home_url()); ?>" 
                               class="hover:text-red-600 transition-colors">
                                Sair
                            </a>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo wp_login_url(); ?>" 
                           class="hover:text-red-600 transition-colors font-medium">
                            Entrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Principal -->
    <div class="header-main bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                
                <!-- Logo e Branding -->
                <div class="logo-container">
                    <a href="<?php echo home_url(); ?>" 
                       class="flex items-center gap-4 hover:opacity-90 transition-opacity">
                        
                        <?php if ($logo_url) : ?>
                            <img src="<?php echo esc_url($logo_url); ?>" 
                                 alt="<?php echo esc_attr($site_name); ?>" 
                                 class="h-10 w-auto">
                        <?php else : ?>
                            <!-- Logo Placeholder -->
                            <div class="bg-red-600 text-white px-4 py-2 rounded font-bold text-xl">
                                R+
                            </div>
                        <?php endif; ?>
                        
                        <div class="hidden md:block">
                            <h1 class="text-2xl font-bold text-gray-900 leading-tight">
                                <?php echo esc_html($site_name); ?>
                            </h1>
                            <?php if ($site_description) : ?>
                                <p class="text-sm text-gray-600 leading-tight">
                                    <?php echo esc_html($site_description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>

                <!-- Busca Central -->
                <div class="search-container hidden lg:block flex-1 max-w-md mx-8">
                    <form role="search" method="get" class="search-form relative" action="<?php echo home_url('/'); ?>">
                        <div class="relative">
                            <input type="search" 
                                   class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all" 
                                   placeholder="Buscar notícias, eventos, lugares..."
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   autocomplete="off">
                            
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Ações do Usuário -->
                <div class="user-actions flex items-center gap-4">
                    <!-- Busca Mobile Toggle -->
                    <button class="search-toggle lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" 
                            aria-label="Abrir busca">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Menu Mobile Toggle -->
                    <button class="mobile-nav-toggle lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" 
                            aria-expanded="false" 
                            aria-controls="mobile-menu"
                            aria-label="Abrir menu de navegação">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegação Principal -->
    <div class="nav-main bg-white border-t border-gray-200 hidden lg:block">
        <div class="container mx-auto px-4">
            <nav class="nav-desktop" role="navigation" aria-label="Navegação principal">
                <div class="flex items-center justify-center py-3">
                    <?php
                    // Menu principal customizado no estilo Globo
                    $menu_items = [
                        ['label' => 'Notícias', 'url' => home_url('/noticias'), 'color' => 'text-blue-600'],
                        ['label' => 'Agenda', 'url' => home_url('/eventos_festivais'), 'color' => 'text-red-600'],
                        ['label' => 'Descubra', 'url' => home_url('/lugares'), 'color' => 'text-green-600'],
                        ['label' => 'Roteiros', 'url' => home_url('/roteiros'), 'color' => 'text-orange-600'],
                        ['label' => 'Cultura', 'url' => home_url('/categoria/cultura'), 'color' => 'text-purple-600'],
                        ['label' => 'Gastronomia', 'url' => home_url('/categoria/gastronomia'), 'color' => 'text-yellow-600'],
                        ['label' => 'Histórias', 'url' => home_url('/historias'), 'color' => 'text-indigo-600']
                    ];
                    ?>
                    
                    <ul class="flex items-center gap-8">
                        <?php foreach ($menu_items as $item) : ?>
                            <li>
                                <a href="<?php echo esc_url($item['url']); ?>" 
                                   class="<?php echo $item['color']; ?> hover:opacity-80 font-semibold text-sm uppercase tracking-wide transition-all duration-200 hover:scale-105">
                                    <?php echo esc_html($item['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Busca Mobile (Oculta por padrão) -->
    <div class="search-mobile lg:hidden hidden bg-white border-t border-gray-200" id="mobile-search">
        <div class="container mx-auto px-4 py-4">
            <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                <div class="relative">
                    <input type="search" 
                           class="search-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base" 
                           placeholder="Buscar no RecifeMais..."
                           value="<?php echo get_search_query(); ?>" 
                           name="s">
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div class="mobile-menu lg:hidden hidden bg-white border-t border-gray-200 shadow-lg" id="mobile-menu">
        <div class="container mx-auto px-4 py-6">
            
            <!-- Navegação Mobile -->
            <nav class="mobile-nav" role="navigation" aria-label="Navegação mobile">
                <ul class="space-y-4">
                    <?php foreach ($menu_items as $item) : ?>
                        <li>
                            <a href="<?php echo esc_url($item['url']); ?>" 
                               class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-lg transition-colors font-medium">
                                <?php echo esc_html($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <!-- Seções Adicionais Mobile -->
                <div class="mobile-nav-section mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorias</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?php echo home_url('/categoria/politica'); ?>" 
                           class="block py-2 px-3 text-sm text-gray-600 hover:text-red-600 hover:bg-gray-50 rounded transition-colors">
                            Política
                        </a>
                        <a href="<?php echo home_url('/categoria/economia'); ?>" 
                           class="block py-2 px-3 text-sm text-gray-600 hover:text-red-600 hover:bg-gray-50 rounded transition-colors">
                            Economia
                        </a>
                        <a href="<?php echo home_url('/categoria/esportes'); ?>" 
                           class="block py-2 px-3 text-sm text-gray-600 hover:text-red-600 hover:bg-gray-50 rounded transition-colors">
                            Esportes
                        </a>
                        <a href="<?php echo home_url('/categoria/tecnologia'); ?>" 
                           class="block py-2 px-3 text-sm text-gray-600 hover:text-red-600 hover:bg-gray-50 rounded transition-colors">
                            Tecnologia
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- JavaScript para funcionalidades do header -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle do menu mobile
    const mobileToggle = document.querySelector('.mobile-nav-toggle');
    const mobileMenu = document.querySelector('#mobile-menu');
    
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Toggle da busca mobile
    const searchToggle = document.querySelector('.search-toggle');
    const mobileSearch = document.querySelector('#mobile-search');
    
    if (searchToggle && mobileSearch) {
        searchToggle.addEventListener('click', function() {
            mobileSearch.classList.toggle('hidden');
            if (!mobileSearch.classList.contains('hidden')) {
                const searchInput = mobileSearch.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    }
    
    // Atualizar horário
    function updateTime() {
        const timeElement = document.querySelector('#current-time');
        if (timeElement) {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            timeElement.textContent = now.toLocaleDateString('pt-BR', options);
        }
    }
    
    // Atualizar a cada minuto
    updateTime();
    setInterval(updateTime, 60000);
    
    // Fechar menus ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.mobile-nav-toggle') && !e.target.closest('#mobile-menu')) {
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                if (mobileToggle) {
                    mobileToggle.setAttribute('aria-expanded', 'false');
                }
            }
        }
        
        if (!e.target.closest('.search-toggle') && !e.target.closest('#mobile-search')) {
            if (mobileSearch && !mobileSearch.classList.contains('hidden')) {
                mobileSearch.classList.add('hidden');
            }
        }
    });
});
</script>

<?php
/**
 * Fallback para menu quando não há menu configurado
 */
function recifemais_mobile_menu_fallback() {
    echo '<ul class="space-y-4">';
    echo '<li><a href="' . home_url() . '" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-lg transition-colors font-medium">Home</a></li>';
    echo '<li><a href="' . home_url('/noticias') . '" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-lg transition-colors font-medium">Notícias</a></li>';
    echo '<li><a href="' . home_url('/eventos_festivais') . '" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-lg transition-colors font-medium">Agenda</a></li>';
    echo '<li><a href="' . home_url('/lugares') . '" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-red-600 rounded-lg transition-colors font-medium">Descubra</a></li>';
    echo '</ul>';
}
?> 