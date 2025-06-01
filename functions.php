<?php
/**
 * RecifeMais Theme Functions
 * 
 * Tema principal do portal RecifeMais com:
 * - Integração com plugin RecifeMais Core V2
 * - Sistema de Design responsivo
 * - Otimizações para performance e SEO
 * - Componentes modulares reutilizáveis
 * 
 * @package RecifeMais_Tema
 * @version 2.0.0
 * @author RecifeMais Team
 * @link https://recifemais.com.br
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// ====================================================================
// CONSTANTES E CONFIGURAÇÕES
// ====================================================================

define('RECIFEMAIS_VERSION', '2.0.0');
define('RECIFEMAIS_THEME_DIR', get_stylesheet_directory());
// Fix para CLI - Define variáveis SERVER se não existirem
if (!isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = 'localhost';
}
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = '';
}

// Constantes básicas
define('RECIFEMAIS_VERSION', '2.0.0');
define('RECIFEMAIS_THEME_DIR', get_stylesheet_directory());
define('RECIFEMAIS_THEME_URI', get_stylesheet_directory_uri());

// Incluir integração com mapas
require_once RECIFEMAIS_THEME_DIR . '/includes/maps-integration.php';

/**
 * Configuração básica do tema
 */
function recifemais_setup() {
    // Suporte a recursos do WordPress
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Tamanhos de imagem
    add_image_size('recifemais-card-horizontal', 400, 250, true);
    add_image_size('recifemais-card-quadrado', 300, 300, true);
    add_image_size('recifemais-hero', 1200, 600, true);
}
add_action('after_setup_theme', 'recifemais_setup');

/**
 * Carregamento de estilos e scripts - SIMPLIFICADO
 */
function recifemais_enqueue_assets() {
    // CSS principal (Tailwind compilado)
    wp_enqueue_style(
        'recifemais-style',
        get_stylesheet_uri(),
        array(),
        RECIFEMAIS_VERSION
    );
    
    // CSS específico do header
    if (file_exists(RECIFEMAIS_THEME_DIR . '/css/header.css')) {
        wp_enqueue_style(
            'recifemais-header',
            RECIFEMAIS_THEME_URI . '/css/header.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // CSS específico do footer
    if (file_exists(RECIFEMAIS_THEME_DIR . '/css/footer.css')) {
        wp_enqueue_style(
            'recifemais-footer',
            RECIFEMAIS_THEME_URI . '/css/footer.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // CSS específico da homepage
    if (is_front_page() && file_exists(RECIFEMAIS_THEME_DIR . '/css/homepage.css')) {
        wp_enqueue_style(
            'recifemais-homepage',
            RECIFEMAIS_THEME_URI . '/css/homepage.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // CSS específico para eventos (archive e single)
    if ((is_post_type_archive('eventos_festivais') || is_singular('eventos_festivais')) && file_exists(RECIFEMAIS_THEME_DIR . '/css/eventos.css')) {
        wp_enqueue_style(
            'recifemais-eventos',
            RECIFEMAIS_THEME_URI . '/css/eventos.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // CSS específico para lugares (archive e single)
    if ((is_post_type_archive('lugares') || is_singular('lugares')) && file_exists(RECIFEMAIS_THEME_DIR . '/css/lugares.css')) {
        wp_enqueue_style(
            'recifemais-lugares',
            RECIFEMAIS_THEME_URI . '/css/lugares.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // CSS específico para artistas (archive e single)
    if ((is_post_type_archive('artistas') || is_singular('artistas')) && file_exists(RECIFEMAIS_THEME_DIR . '/css/artistas.css')) {
        wp_enqueue_style(
            'recifemais-artistas',
            RECIFEMAIS_THEME_URI . '/css/artistas.css',
            array('recifemais-style'),
            RECIFEMAIS_VERSION
        );
    }
    
    // JavaScript básico
    if (file_exists(RECIFEMAIS_THEME_DIR . '/js/main.js')) {
        wp_enqueue_script(
            'recifemais-main',
            RECIFEMAIS_THEME_URI . '/js/main.js',
            array('jquery'),
            RECIFEMAIS_VERSION,
            true
        );
    }
    
    // JavaScript do header
    if (file_exists(RECIFEMAIS_THEME_DIR . '/js/header.js')) {
        wp_enqueue_script(
            'recifemais-header',
            RECIFEMAIS_THEME_URI . '/js/header.js',
            array('jquery'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script do header
        wp_localize_script('recifemais-header', 'recifemais_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_ajax_nonce'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    // JavaScript da homepage
    if (is_front_page() && file_exists(RECIFEMAIS_THEME_DIR . '/js/homepage.js')) {
        wp_enqueue_script(
            'recifemais-homepage',
            RECIFEMAIS_THEME_URI . '/js/homepage.js',
            array('jquery', 'recifemais-main'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script da homepage
        wp_localize_script('recifemais-homepage', 'recifemais_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_ajax_nonce'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    // JavaScript do footer
    if (file_exists(RECIFEMAIS_THEME_DIR . '/js/footer.js')) {
        wp_enqueue_script(
            'recifemais-footer',
            RECIFEMAIS_THEME_URI . '/js/footer.js',
            array('jquery', 'recifemais-main'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script do footer
        wp_localize_script('recifemais-footer', 'recifemais_ajax', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_ajax_nonce'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    // JavaScript para filtros de meta fields (apenas em archives de CPTs)
    if (is_post_type_archive() && file_exists(RECIFEMAIS_THEME_DIR . '/js/filtros-meta-fields.js')) {
        wp_enqueue_script(
            'recifemais-filtros-meta-fields',
            RECIFEMAIS_THEME_URI . '/js/filtros-meta-fields.js',
            array('jquery'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script
        wp_localize_script('recifemais-filtros-meta-fields', 'recifemais_filters', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_filters_nonce'),
            'api_url' => home_url('/wp-json/recifemais/v1/'),
            'post_type' => get_post_type()
        ));
    }
    
    // JavaScript específico para eventos (archive e single)
    if ((is_post_type_archive('eventos_festivais') || is_singular('eventos_festivais')) && file_exists(RECIFEMAIS_THEME_DIR . '/js/eventos.js')) {
        wp_enqueue_script(
            'recifemais-eventos',
            RECIFEMAIS_THEME_URI . '/js/eventos.js',
            array('jquery', 'recifemais-main'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script de eventos
        wp_localize_script('recifemais-eventos', 'recifemais_eventos', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_eventos_nonce'),
            'api_url' => home_url('/wp-json/recifemais/v1/'),
            'maps_api_key' => get_option('recifemais_google_maps_api_key', ''),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    // JavaScript específico para lugares (archive e single)
    if ((is_post_type_archive('lugares') || is_singular('lugares')) && file_exists(RECIFEMAIS_THEME_DIR . '/js/lugares.js')) {
        wp_enqueue_script(
            'recifemais-lugares',
            RECIFEMAIS_THEME_URI . '/js/lugares.js',
            array('jquery', 'recifemais-main'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script de lugares
        wp_localize_script('recifemais-lugares', 'recifemais_lugares', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_lugares_nonce'),
            'api_url' => home_url('/wp-json/recifemais/v1/'),
            'maps_api_key' => get_option('recifemais_google_maps_api_key', ''),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    // JavaScript específico para artistas (archive e single)
    if ((is_post_type_archive('artistas') || is_singular('artistas')) && file_exists(RECIFEMAIS_THEME_DIR . '/js/artistas.js')) {
        wp_enqueue_script(
            'recifemais-artistas',
            RECIFEMAIS_THEME_URI . '/js/artistas.js',
            array('jquery', 'recifemais-main'),
            RECIFEMAIS_VERSION,
            true
        );
        
        // Localização para o script de artistas
        wp_localize_script('recifemais-artistas', 'recifemaisData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('recifemais_artistas_nonce'),
            'apiUrl' => home_url('/wp-json/recifemais/v1/'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
}
add_action('wp_enqueue_scripts', 'recifemais_enqueue_assets');

/**
 * Sistema de Ícones SVG - RecifeMais
 * Ícones flat com linhas finas nas cores da paleta
 */
function recifemais_get_icon_svg($icon_name, $size = '20', $color = 'currentColor') {
    $icons = array(
        // Ícones de Notícias
        'news' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/></svg>',
        
        // Ícones de Categorias
        'politics' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/><path d="M9 18v.01"/></svg>',
        
        'business' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M20 7h-9"/><path d="M14 17H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2Z"/><path d="M7 15h4l2-6 2 4 2-2"/></svg>',
        
        'city' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/></svg>',
        
        'sports' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
        
        // Ícones de Meta Informações
        'calendar' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        
        'clock' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>',
        
        'user' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        
        'eye' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
        
        // Ícones de Navegação
        'arrow-right' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12,5 19,12 12,19"/></svg>',
        
        'arrow-left' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12,19 5,12 12,5"/></svg>',
        
        'external-link' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15,3 21,3 21,9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
        
        // Ícones de Ações
        'search' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>',
        
        'filter' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46"/></svg>',
        
        'share' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
        
        // Ícones de Status
        'trending' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polyline points="23,6 13.5,15.5 8.5,10.5 1,18"/><polyline points="17,6 23,6 23,12"/></svg>',
        
        'fire' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
        
        'star' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>',
        
        // Ícones de Comunicação
        'mail' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        
        'bell' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
        
        // Ícones de Mídia Social
        'facebook' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        
        'instagram' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        
        'twitter' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>',
        
        // Ícones de Localização
        'map-pin' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
        
        'globe' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        
        // Ícones de Categorias Culturais
        'music' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
        
        'theater' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M2 16.1A5 5 0 0 1 5.9 20M6.3 20.9l13.4-13.4M7.7 19.5l13.4-13.4M2 12.1a9 9 0 0 1 8.9 10M2 8.1a13 13 0 0 1 12.9 14"/></svg>',
        
        'camera' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
        
        'palette' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>',
        
        // Ícones de Urgência
        'alert-circle' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        
        'zap' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polygon points="13,2 3,14 12,14 11,22 21,10 12,10"/></svg>',
        
        // Ícones de Interface
        'menu' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
        
        'x' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        
        'plus' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        
        'minus' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        
        'home' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>',
        
        'folder' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
        
        'tag' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
        
        // Ícones Adicionais
        'heart' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        
        'book' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        
        'leaf' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
        
        'shield' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        
        // Ícones Específicos para CPTs
        'event' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/></svg>',
        
        'food' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
        
        'art' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>',
        
        // Ícones de Mapas e Navegação
        'map' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polygon points="1,6 1,22 8,18 16,22 23,18 23,2 16,6 8,2"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>',
        
        'navigation' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polygon points="3,11 22,2 13,21 11,13 3,11"/></svg>',
        
        'target' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
        
        'compass' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polygon points="16.24,7.76 14.12,14.12 7.76,16.24 9.88,9.88"/></svg>',
        
        'route' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/></svg>',
        
        // Ícones Adicionais para CPTs
        'info' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        
        'lightbulb' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M9 21h6"/><path d="M12 17h.01"/><path d="M12 3a6 6 0 0 1 6 6c0 3-2 5.5-2 8H8c0-2.5-2-5-2-8a6 6 0 0 1 6-6z"/></svg>',
        
        'book-open' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
        
        'users' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        
        'dollar-sign' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        
        'sun' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
        
        'phone' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        
        'package' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27,6.96 12,12.01 20.73,6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
        
        'truck' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16,8 20,8 23,11 23,16 16,16"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        
        'download' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
        
        'printer' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><polyline points="6,9 6,2 18,2 18,9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>',
        
        // Ícones específicos da Agenda Cultural
        'agenda' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>',
        
        'event' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><circle cx="12" cy="15" r="2"/></svg>',
        
        'festival' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/><circle cx="12" cy="12" r="3"/></svg>',
        
        'concert' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/><path d="M9 9l12-2"/></svg>',
        
        'exhibition' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>',
        
        'workshop' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        
        'conference' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        
        'performance' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M2 16.1A5 5 0 0 1 5.9 20M6.3 20.9l13.4-13.4M7.7 19.5l13.4-13.4M2 12.1a9 9 0 0 1 8.9 10M2 8.1a13 13 0 0 1 12.9 14"/><circle cx="19" cy="5" r="2"/></svg>',
        
        'cultural-space' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/></svg>',
        
        'ticket' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>',
        
        'notification' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
        
        'reminder' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>',
        
        'calendar-add' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="12" y1="14" x2="12" y2="18"/><line x1="10" y1="16" x2="14" y2="16"/></svg>',
        
        'export' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17,8 12,3 7,8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
        
        'view-month' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="16" y2="18"/></svg>',
        
        'view-week' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="4" x2="9" y2="22"/><line x1="15" y1="4" x2="15" y2="22"/></svg>',
        
        'view-day' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="12" cy="16" r="3"/></svg>',
        
        'view-list' => '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="1.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
    );
    
    return isset($icons[$icon_name]) ? $icons[$icon_name] : '';
}

/**
 * Debug: Adiciona informações no head para verificar se está carregando
 */
function recifemais_debug_info() {
    echo "\n<!-- RecifeMais Debug: Tema carregado em " . date('Y-m-d H:i:s') . " -->\n";
    echo "<!-- CSS URL: " . get_stylesheet_uri() . " -->\n";
    echo "<!-- Função reading_time() disponível: " . (function_exists('reading_time') ? 'SIM' : 'NÃO') . " -->\n";
}
// TEMPORARIAMENTE DESATIVADO PARA DEBUG
// add_action('wp_head', 'recifemais_debug_info');

/**
 * Remove a barra de administração para usuários não logados
 */
if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
}

/**
 * Funções do tema RecifeMais
 * 
 * @package RecifeMaisTema
 */

/**
 * Configuração do tema
 */
function recifemais_tema_setup() {
    // Suporte a funcionalidades do WordPress
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Tamanhos de imagem personalizados
    add_image_size('recifemais-hero', 1200, 675, true);
    add_image_size('recifemais-card', 400, 225, true);
    add_image_size('recifemais-thumb', 150, 150, true);
    
    // Menus
    register_nav_menus(array(
        'primary' => 'Menu Principal',
        'footer' => 'Menu do Rodapé',
    ));
}
add_action('after_setup_theme', 'recifemais_tema_setup');

/**
 * Calcula tempo de leitura de um post
 */
function recifemais_reading_time($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags($content);
    $word_count = str_word_count($content);
    
    // Média de 200 palavras por minuto
    $reading_time = ceil($word_count / 200);
    
    if ($reading_time === 1) {
        return '1 minuto';
    } else {
        return $reading_time . ' minutos';
    }
}

/**
 * Alias para compatibilidade - Tempo de leitura
 */
function recifemais_get_reading_time($post_id = null) {
    return recifemais_reading_time($post_id);
}

/**
 * Obtém a categoria principal de uma notícia
 */
function recifemais_get_primary_category($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    return !empty($categories) ? $categories[0] : null;
}

/**
 * Obtém cor da categoria baseada no design system
 */
function recifemais_get_category_color($category_slug) {
    $colors = [
        'noticias' => '#dc2626',
        'cultura' => '#7c3aed',
        'gastronomia' => '#059669',
        'turismo' => '#0ea5e9',
        'eventos' => '#ea580c',
        'historia' => '#6366f1',
        'default' => '#6b7280'
    ];
    
    return isset($colors[$category_slug]) ? $colors[$category_slug] : $colors['default'];
}

/**
 * Obtém ícone da categoria (SVG em vez de emoji)
 */
function recifemais_get_category_icon($category_slug) {
    $icons = [
        'noticias' => 'news',
        'cultura' => 'theater',
        'gastronomia' => 'restaurant',
        'turismo' => 'map',
        'eventos' => 'calendar',
        'historia' => 'book',
        'default' => 'news'
    ];
    
    return isset($icons[$category_slug]) ? $icons[$category_slug] : $icons['default'];
}

/**
 * Renderiza badge de urgência com ícone SVG
 */
function recifemais_render_urgency_badge($urgency) {
    if (!$urgency || $urgency === 'normal') {
        return '';
    }
    
    $badges = array(
        'breaking' => array(
            'class' => 'bg-red-600 text-white',
            'icon' => recifemais_get_icon_svg('zap', '12', 'white'),
            'text' => 'URGENTE'
        ),
        'importante' => array(
            'class' => 'bg-orange-500 text-white',
            'icon' => recifemais_get_icon_svg('alert-circle', '12', 'white'),
            'text' => 'IMPORTANTE'
        ),
        'destaque' => array(
            'class' => 'bg-blue-600 text-white',
            'icon' => recifemais_get_icon_svg('star', '12', 'white'),
            'text' => 'DESTAQUE'
        )
    );
    
    if (!isset($badges[$urgency])) {
        return '';
    }
    
    $badge = $badges[$urgency];
    
    return sprintf(
        '<span class="inline-flex items-center gap-1 px-2 py-1 %s text-xs font-medium rounded-full">
            %s
            <span>%s</span>
        </span>',
        $badge['class'],
        $badge['icon'],
        $badge['text']
    );
}

/**
 * Verifica se é uma notícia breaking news
 */
function recifemais_is_breaking_news($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $is_breaking = get_post_meta($post_id, '_breaking_news', true);
    $post_date = get_the_date('U', $post_id);
    $current_time = current_time('timestamp');
    
    // Considera breaking news se marcado e publicado nas últimas 24 horas
    return $is_breaking && ($current_time - $post_date) < (24 * 60 * 60);
}

/**
 * Obtém posts relacionados inteligentemente
 */
function recifemais_get_related_posts($post_id = null, $limit = 4) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $categories = get_the_category($post_id);
    $tags = get_the_tags($post_id);
    
    $args = array(
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array()
    );
    
    // Priorizar posts da mesma categoria
    if (!empty($categories)) {
        $args['category__in'] = array($categories[0]->term_id);
    }
    
    // Se não encontrar suficientes por categoria, buscar por tags
    $related_posts = new WP_Query($args);
    
    if ($related_posts->found_posts < $limit && !empty($tags)) {
        $tag_ids = array_map(function($tag) { return $tag->term_id; }, $tags);
        
        $args_tags = array(
            'posts_per_page' => $limit,
            'post__not_in' => array($post_id),
            'tag__in' => $tag_ids,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $related_posts = new WP_Query($args_tags);
    }
    
    // Se ainda não encontrar suficientes, buscar posts recentes
    if ($related_posts->found_posts < $limit) {
        $args_recent = array(
            'posts_per_page' => $limit,
            'post__not_in' => array($post_id),
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $related_posts = new WP_Query($args_recent);
    }
    
    return $related_posts;
}

/**
 * Enfileira estilos e scripts
 */
function recifemais_tema_scripts() {
    // CSS principal
    wp_enqueue_style('recifemais-style', get_stylesheet_uri(), array(), '2.0.0');
    
    // Tailwind CSS (se não estiver sendo carregado via CDN)
    wp_enqueue_style('tailwindcss', 'https://cdn.tailwindcss.com', array(), '3.4.0');
    
    // CSS específico para single-post
    if (is_single() && get_post_type() === 'post') {
        wp_enqueue_style('recifemais-single-post', get_template_directory_uri() . '/css/single-post.css', array('recifemais-style'), '2.0.0');
        wp_enqueue_script('recifemais-single-post', get_template_directory_uri() . '/js/single-post.js', array('jquery'), '2.0.0', true);
    }
    
    // CSS específico para category
    if (is_category()) {
        wp_enqueue_style('recifemais-category', get_template_directory_uri() . '/css/category.css', array('recifemais-style'), '2.0.0');
        wp_enqueue_script('recifemais-category', get_template_directory_uri() . '/js/category.js', array('jquery'), '2.0.0', true);
    }
    
    // Scripts personalizados
    wp_enqueue_script('recifemais-main', get_template_directory_uri() . '/js/main.js', array('jquery'), '2.0.0', true);
    
    // Header script para funcionalidades específicas
    if (file_exists(get_template_directory() . '/js/header.js')) {
        wp_enqueue_script('recifemais-header', get_template_directory_uri() . '/js/header.js', array('jquery'), '2.0.0', true);
    }
    
    // Localizar scripts para AJAX
    wp_localize_script('recifemais-main', 'recifemais_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('recifemais_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'recifemais_tema_scripts');

/**
 * Adiciona suporte a structured data para notícias
 */
function recifemais_add_news_structured_data() {
    if (!is_single() || get_post_type() !== 'post') {
        return;
    }
    
    global $post;
    
    $primary_category = recifemais_get_primary_category($post->ID);
    $author = get_the_author_meta('display_name', $post->post_author);
    $published_date = get_the_date('c', $post->ID);
    $modified_date = get_the_modified_date('c', $post->ID);
    
    $structured_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => get_the_title($post->ID),
        'description' => get_the_excerpt($post->ID),
        'image' => get_the_post_thumbnail_url($post->ID, 'large'),
        'datePublished' => $published_date,
        'dateModified' => $modified_date,
        'author' => array(
            '@type' => 'Person',
            'name' => $author
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => 'RecifeMais',
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_template_directory_uri() . '/assets/images/logo.png'
            )
        )
    );
    
    if ($primary_category) {
        $structured_data['articleSection'] = $primary_category->name;
    }
    
    echo '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_SLASHES) . '</script>';
}
// TEMPORARIAMENTE DESATIVADO PARA DEBUG
// add_action('wp_head', 'recifemais_add_news_structured_data');

/**
 * Adiciona meta tags OpenGraph para compartilhamento
 */
function recifemais_add_og_tags() {
    if (!is_single()) {
        return;
    }
    
    global $post;
    
    $title = get_the_title($post->ID);
    $description = get_the_excerpt($post->ID);
    $image = get_the_post_thumbnail_url($post->ID, 'large');
    $url = get_permalink($post->ID);
    
    echo '<meta property="og:type" content="article" />';
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />';
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />';
    echo '<meta property="og:url" content="' . esc_url($url) . '" />';
    echo '<meta property="og:site_name" content="RecifeMais" />';
    
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />';
    }
    
    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image" />';
    echo '<meta name="twitter:site" content="@RecifeMais" />';
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />';
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />';
    
    if ($image) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '" />';
    }
}
// TEMPORARIAMENTE DESATIVADO PARA DEBUG
// add_action('wp_head', 'recifemais_add_og_tags');

/**
 * Hook para remover destaque automaticamente (agendado)
 */
function recifemais_remove_featured_post($post_id) {
    update_post_meta($post_id, '_featured_post', '0');
    update_post_meta($post_id, '_manchete_principal', '0');
    
    // Log para debugging
    error_log("RecifeMais: Destaque removido automaticamente do post ID: {$post_id}");
}
add_action('recifemais_remove_featured', 'recifemais_remove_featured_post');

/**
 * Customiza excerpt para notícias
 */
function recifemais_custom_excerpt_more($more) {
    if (is_single()) {
        return '';
    }
    return '...';
}
add_filter('excerpt_more', 'recifemais_custom_excerpt_more');

/**
 * Define comprimento do excerpt
 */
function recifemais_excerpt_length($length) {
    if (is_home() || is_category()) {
        return 25; // Mais curto para listagens
    }
    return 55; // Padrão para outros locais
}
add_filter('excerpt_length', 'recifemais_excerpt_length');

/**
 * Rank Math Integration Fix
 * Garante que as configurações apareçam no painel admin
 */
add_action('admin_init', function() {
    if (class_exists('RankMath')) {
        // Força refresh das configurações do Rank Math
        if (current_user_can('manage_options')) {
            // Verifica se precisa aplicar configurações
            $setup_applied = get_option('recifemais_rank_math_setup_applied', false);
            
            if (!$setup_applied) {
                // Aplica configurações básicas se não foram aplicadas
                update_option('rank_math_general_knowledgegraph_name', 'RecifeMais - Portal Cultural de Pernambuco');
                update_option('rank_math_general_knowledgegraph_type', 'Organization');
                update_option('rank_math_general_local_seo', 'on');
                update_option('rank_math_general_geo_latitude', '-8.0476');
                update_option('rank_math_general_geo_longitude', '-34.8770');
                
                // Marca como aplicado
                update_option('recifemais_rank_math_setup_applied', true);
            }
        }
    }
});

/**
 * Breadcrumbs Display Function
 * Para usar nos templates: recifemais_breadcrumbs()
 */
function recifemais_breadcrumbs() {
    if (function_exists('rank_math_the_breadcrumbs')) {
        rank_math_the_breadcrumbs();
    }
}

/**
 * Carrega compatibilidade com Rank Math SEO
 * Inclui arquivo de compatibilidade específico
 */
if (file_exists(get_template_directory() . '/rank-math.php')) {
    require_once get_template_directory() . '/rank-math.php';
}

/**
 * Carrega sistema de Agenda Cultural Avançada
 * Integração com calendário interativo e funcionalidades AJAX
 */
if (file_exists(get_template_directory() . '/includes/agenda-cultural-integration.php')) {
    require_once get_template_directory() . '/includes/agenda-cultural-integration.php';
}

/**
 * Handler AJAX para Newsletter
 * Processa inscrições na newsletter do footer
 */
function recifemais_newsletter_signup() {
    // Verifica nonce
    if (!wp_verify_nonce($_POST['nonce'], 'recifemais_ajax_nonce')) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Erro de segurança. Recarregue a página e tente novamente.'
        ]));
    }
    
    // Sanitiza email
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Email inválido.'
        ]));
    }
    
    // Verifica se já está inscrito
    $existing_subscriber = get_users([
        'meta_key' => 'newsletter_email',
        'meta_value' => $email,
        'number' => 1
    ]);
    
    if (!empty($existing_subscriber)) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Este email já está inscrito na nossa newsletter.'
        ]));
    }
    
    // Salva na tabela de newsletter (ou cria usuário subscriber)
    $subscriber_data = [
        'user_login' => 'newsletter_' . uniqid(),
        'user_email' => $email,
        'user_pass' => wp_generate_password(),
        'role' => 'subscriber',
        'meta_input' => [
            'newsletter_subscribed' => true,
            'newsletter_date' => current_time('mysql'),
            'newsletter_source' => 'footer',
            'newsletter_ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'newsletter_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]
    ];
    
    $user_id = wp_insert_user($subscriber_data);
    
    if (is_wp_error($user_id)) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Erro ao processar inscrição. Tente novamente.'
        ]));
    }
    
    // Log da inscrição
    error_log("RecifeMais Newsletter: Nova inscrição - Email: {$email}, IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    
    // Resposta de sucesso
    wp_die(json_encode([
        'success' => true,
        'message' => 'Inscrição realizada com sucesso! Você receberá nossas novidades culturais.'
    ]));
}

// Registra handlers AJAX
add_action('wp_ajax_recifemais_newsletter_signup', 'recifemais_newsletter_signup');
add_action('wp_ajax_nopriv_recifemais_newsletter_signup', 'recifemais_newsletter_signup');

/**
 * Adiciona variáveis JavaScript globais
 * Para uso nos scripts do tema
 */
function recifemais_add_js_variables() {
    ?>
    <script>
        window.recifemais_debug = <?php echo defined('WP_DEBUG') && WP_DEBUG ? 'true' : 'false'; ?>;
        window.recifemais_ajax = {
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('recifemais_ajax_nonce'); ?>'
        };
    </script>
    <?php
}
add_action('wp_head', 'recifemais_add_js_variables');

/**
 * Adiciona classes CSS customizadas ao body
 * Para estilização específica por página
 */
function recifemais_body_classes($classes) {
    // Adiciona classe para páginas com footer
    $classes[] = 'has-footer';
    
    // Adiciona classe para tipo de página
    if (is_home()) {
        $classes[] = 'page-home';
    } elseif (is_single()) {
        $classes[] = 'page-single';
        $classes[] = 'post-type-' . get_post_type();
    } elseif (is_archive()) {
        $classes[] = 'page-archive';
        if (is_post_type_archive()) {
            $classes[] = 'archive-' . get_post_type();
        }
    }
    
    return $classes;
}
add_filter('body_class', 'recifemais_body_classes');

/**
 * Handler AJAX para Busca em Tempo Real
 * Processa buscas do header com sugestões
 */
function recifemais_live_search() {
    // Verifica nonce
    if (!wp_verify_nonce($_GET['nonce'], 'recifemais_ajax_nonce')) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Erro de segurança.'
        ]));
    }
    
    // Sanitiza query
    $query = sanitize_text_field($_GET['query']);
    
    if (strlen($query) < 2) {
        wp_die(json_encode([
            'success' => false,
            'message' => 'Query muito curta.'
        ]));
    }
    
    // Busca em posts e CPTs
    $search_args = [
        's' => $query,
        'post_type' => ['post', 'eventos_festivais', 'lugares', 'artistas', 'roteiros', 'historias'],
        'post_status' => 'publish',
        'posts_per_page' => 8,
        'orderby' => 'relevance',
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => '_featured_post',
                'value' => '1',
                'compare' => '='
            ],
            [
                'key' => '_featured_post',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ];
    
    $search_query = new WP_Query($search_args);
    $suggestions = [];
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            // Determina o tipo de conteúdo
            $post_type = get_post_type();
            $type_labels = [
                'post' => 'Notícia',
                'eventos_festivais' => 'Evento',
                'lugares' => 'Lugar',
                'artistas' => 'Artista',
                'roteiros' => 'Roteiro',
                'historias' => 'História'
            ];
            
            $suggestions[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => $type_labels[$post_type] ?? 'Conteúdo',
                'date' => get_the_date('d/m/Y'),
                'excerpt' => wp_trim_words(get_the_excerpt(), 15)
            ];
        }
        wp_reset_postdata();
    }
    
    // Busca também em categorias
    $categories = get_terms([
        'taxonomy' => 'category',
        'name__like' => $query,
        'number' => 3,
        'hide_empty' => true
    ]);
    
    foreach ($categories as $category) {
        $suggestions[] = [
            'title' => $category->name,
            'url' => get_category_link($category->term_id),
            'type' => 'Categoria',
            'date' => '',
            'excerpt' => $category->description ? wp_trim_words($category->description, 10) : 'Ver todos os posts desta categoria'
        ];
    }
    
    // Log da busca
    error_log("RecifeMais Search: Query '{$query}' - " . count($suggestions) . " resultados");
    
    // Resposta
    wp_die(json_encode([
        'success' => true,
        'data' => $suggestions
    ]));
}

// Registra handlers AJAX para busca
add_action('wp_ajax_recifemais_live_search', 'recifemais_live_search');
add_action('wp_ajax_nopriv_recifemais_live_search', 'recifemais_live_search');

// DEBUG TEMPORÁRIO - REMOVER APÓS TESTES
if (WP_DEBUG && current_user_can('manage_options')) {
    include_once RECIFEMAIS_THEME_DIR . '/debug-meta-fields.php';
}