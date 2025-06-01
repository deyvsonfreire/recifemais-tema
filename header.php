<?php
/**
 * Header Template - RecifeMais
 * Header moderno inspirado no design da Globo.com
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preload de fontes importantes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="<?php echo is_home() ? get_bloginfo('description') : wp_trim_words(get_the_excerpt(), 20); ?>">
    <meta name="keywords" content="Recife, Pernambuco, cultura, eventos, gastronomia, turismo, nordeste">
    <meta name="author" content="RecifeMais">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo is_home() ? get_bloginfo('name') : get_the_title(); ?>">
    <meta property="og:description" content="<?php echo is_home() ? get_bloginfo('description') : wp_trim_words(get_the_excerpt(), 20); ?>">
    <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?php echo get_permalink(); ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <?php if (has_post_thumbnail()) : ?>
    <meta property="og:image" content="<?php echo get_the_post_thumbnail_url(null, 'large'); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo is_home() ? get_bloginfo('name') : get_the_title(); ?>">
    <meta name="twitter:description" content="<?php echo is_home() ? get_bloginfo('description') : wp_trim_words(get_the_excerpt(), 20); ?>">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Pular para o conteúdo', 'recifemais-tema'); ?></a>

    <!-- Header Principal -->
    <header id="masthead" class="site-header sticky top-0 z-50 bg-white shadow-sm transition-all duration-300" role="banner">
        
        <!-- Barra Superior Discreta -->
        <div class="top-bar bg-gray-50 border-b border-gray-200 py-2 hidden lg:block">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between text-sm">
                    <!-- Data e Hora -->
                    <div class="flex items-center gap-4 text-gray-600">
                        <time datetime="<?php echo date('c'); ?>" class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo date_i18n('l, j \d\e F \d\e Y'); ?>
                        </time>
                        <span class="text-gray-400">|</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo date_i18n('H:i'); ?>
                        </span>
                    </div>
                    
                    <!-- Links Rápidos -->
                    <div class="flex items-center gap-4">
                        <a href="<?php echo home_url('/sobre'); ?>" class="text-gray-600 hover:text-red-600 transition-colors">
                            Sobre
                        </a>
                        <a href="<?php echo home_url('/contato'); ?>" class="text-gray-600 hover:text-red-600 transition-colors">
                            Contato
                        </a>
                        <span class="text-gray-400">|</span>
                        <!-- Redes Sociais -->
                        <div class="flex items-center gap-2">
                            <a href="https://instagram.com/recifemais" target="_blank" rel="noopener" 
                               class="text-gray-600 hover:text-pink-600 transition-colors" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.875 2.026-1.297 3.323-1.297s2.448.422 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.141c-.49 0-.928-.422-.928-.928 0-.49.438-.928.928-.928.49 0 .928.438.928.928 0 .506-.438.928-.928.928zm-3.323 9.141c-2.026 0-3.323-1.297-3.323-3.323s1.297-3.323 3.323-3.323 3.323 1.297 3.323 3.323-1.297 3.323-3.323 3.323z"/>
                                </svg>
                            </a>
                            <a href="https://facebook.com/recifemais" target="_blank" rel="noopener" 
                               class="text-gray-600 hover:text-blue-600 transition-colors" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://twitter.com/recifemais" target="_blank" rel="noopener" 
                               class="text-gray-600 hover:text-blue-400 transition-colors" aria-label="Twitter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Principal -->
        <div class="main-header py-4 lg:py-6">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    
                    <!-- Logo e Branding -->
                    <div class="site-branding flex items-center">
                        <?php
                        $custom_logo_id = get_theme_mod('custom_logo');
                        if ($custom_logo_id) :
                            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                                <img src="<?php echo esc_url($logo_url); ?>" 
                                     alt="<?php bloginfo('name'); ?>" 
                                     class="h-10 lg:h-12 w-auto">
                            </a>
                        <?php else : ?>
                            <div class="text-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center" rel="home">
                                    <div class="logo-icon w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-red-600 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">
                                            <?php bloginfo('name'); ?>
                                        </h1>
                                        <?php
                                        $description = get_bloginfo('description', 'display');
                                        if ($description || is_customize_preview()) :
                                        ?>
                                            <p class="text-sm text-gray-600 hidden lg:block">
                                                <?php echo $description; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Navegação Principal -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="Menu principal">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'flex items-center space-x-8',
                            'fallback_cb'    => false,
                            'walker'         => new class extends Walker_Nav_Menu {
                                function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                    $classes = empty($item->classes) ? array() : (array) $item->classes;
                                    $classes[] = 'menu-item-' . $item->ID;
                                    
                                    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                                    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                                    
                                    $output .= '<li' . $class_names .'>';
                                    
                                    $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                                    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
                                    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
                                    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
                                    
                                    $item_output = isset($args->before) ? $args->before : '';
                                    $item_output .= '<a' . $attributes . ' class="text-gray-700 hover:text-red-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-red-600">';
                                    $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
                                    $item_output .= '</a>';
                                    $item_output .= isset($args->after) ? $args->after : '';
                                    
                                    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                                }
                            }
                        ));
                        ?>
                    </nav>

                    <!-- Ações do Header -->
                    <div class="header-actions flex items-center gap-4">
                        
                        <!-- Busca -->
                        <div class="search-toggle relative">
                            <button id="search-toggle" 
                                    class="search-btn w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors"
                                    aria-label="Buscar">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown de Busca -->
                            <div id="search-dropdown" class="search-dropdown absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 p-4 hidden z-50">
                                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                                    <div class="relative">
                                        <input type="search" 
                                               name="s" 
                                               placeholder="Buscar eventos, lugares, artistas..." 
                                               value="<?php echo get_search_query(); ?>"
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">Buscar em:</span>
                                        <a href="<?php echo home_url('/eventos_festivais'); ?>" class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full hover:bg-red-200 transition-colors">Eventos</a>
                                        <a href="<?php echo home_url('/lugares'); ?>" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full hover:bg-green-200 transition-colors">Lugares</a>
                                        <a href="<?php echo home_url('/artistas'); ?>" class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full hover:bg-purple-200 transition-colors">Artistas</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Menu Mobile -->
                        <button id="mobile-menu-toggle" 
                                class="mobile-menu-btn lg:hidden w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors"
                                aria-label="Menu">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="mobile-menu lg:hidden hidden bg-white border-t border-gray-200">
            <div class="container mx-auto px-4 py-4">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'mobile-primary-menu',
                    'container'      => false,
                    'menu_class'     => 'space-y-2',
                    'fallback_cb'    => false,
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                            $classes = empty($item->classes) ? array() : (array) $item->classes;
                            $classes[] = 'menu-item-' . $item->ID;
                            
                            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                            
                            $output .= '<li' . $class_names .'>';
                            
                            $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                            $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
                            $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
                            $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
                            
                            $item_output = isset($args->before) ? $args->before : '';
                            $item_output .= '<a' . $attributes . ' class="block text-gray-700 hover:text-red-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-lg transition-colors">';
                            $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
                            $item_output .= '</a>';
                            $item_output .= isset($args->after) ? $args->after : '';
                            
                            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                        }
                    }
                ));
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
        <main id="main" class="site-main" role="main">
</body>
</html>