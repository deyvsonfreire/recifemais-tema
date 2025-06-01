<?php
/**
 * Script temporário para verificar e corrigir configurações de front-page
 * Execute este arquivo uma vez e depois delete
 */

// Incluir WordPress
require_once('../../../wp-load.php');

echo "<h2>🔧 Diagnóstico RecifeMais Front-Page</h2>\n";

// 1. Verificar configurações de leitura
echo "<h3>📋 Configurações de Leitura:</h3>\n";
$show_on_front = get_option('show_on_front');
$page_on_front = get_option('page_on_front');
$page_for_posts = get_option('page_for_posts');

echo "show_on_front: " . $show_on_front . "<br>\n";
echo "page_on_front: " . $page_on_front . "<br>\n";
echo "page_for_posts: " . $page_for_posts . "<br>\n";

// 2. Verificar template hierarchy
echo "<h3>📁 Template Hierarchy:</h3>\n";
$template_files = [
    'front-page.php' => file_exists(get_template_directory() . '/front-page.php'),
    'home.php' => file_exists(get_template_directory() . '/home.php'),
    'index.php' => file_exists(get_template_directory() . '/index.php')
];

foreach ($template_files as $file => $exists) {
    $status = $exists ? '✅' : '❌';
    echo "$status $file<br>\n";
}

// 3. Verificar template parts
echo "<h3>🧩 Template Parts:</h3>\n";
$template_parts = [
    'template-parts/homepage/hero-breaking.php',
    'template-parts/homepage/featured-stories.php',
    'template-parts/homepage/section-descubra.php',
    'template-parts/homepage/section-roteiros.php',
    'template-parts/homepage/section-agenda.php',
    'template-parts/homepage/newsletter-signup.php',
    'template-parts/homepage/weather-widget.php',
    'template-parts/homepage/sidebar-widgets.php'
];

foreach ($template_parts as $part) {
    $exists = file_exists(get_template_directory() . '/' . $part);
    $status = $exists ? '✅' : '❌';
    echo "$status $part<br>\n";
}

// 4. Forçar configuração correta
echo "<h3>🔧 Corrigindo Configurações:</h3>\n";

// Definir para usar front-page.php
update_option('show_on_front', 'posts');
echo "✅ Configurado show_on_front para 'posts'<br>\n";

// Flush permalinks
flush_rewrite_rules();
echo "✅ Permalinks atualizados<br>\n";

// Limpar cache se existir
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "✅ Cache limpo<br>\n";
}

// 5. Verificar tema ativo
echo "<h3>🎨 Tema Ativo:</h3>\n";
$current_theme = wp_get_theme();
echo "Tema: " . $current_theme->get('Name') . "<br>\n";
echo "Versão: " . $current_theme->get('Version') . "<br>\n";
echo "Diretório: " . get_template_directory() . "<br>\n";

// 6. Verificar URL da homepage
echo "<h3>🌐 URLs:</h3>\n";
echo "Home URL: " . home_url() . "<br>\n";
echo "Site URL: " . site_url() . "<br>\n";

echo "<h3>✅ Diagnóstico Completo!</h3>\n";
echo "<p><strong>Agora acesse a homepage:</strong> <a href='" . home_url() . "' target='_blank'>" . home_url() . "</a></p>\n";
echo "<p><em>Você pode deletar este arquivo após o teste.</em></p>\n";
?> 