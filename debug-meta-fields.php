<?php
/**
 * Arquivo temporário para debug dos meta fields
 * REMOVER APÓS TESTES
 */

// Verificar se estamos no WordPress
if (!defined('ABSPATH')) {
    exit;
}

// Função para debug de meta fields
function debug_cpt_meta_fields() {
    echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
    echo '<h2>Debug Meta Fields - RecifeMais CPTs</h2>';
    
    // Testar artistas
    $artistas = get_posts([
        'post_type' => 'artistas',
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ]);
    
    echo '<h3>🎨 Artistas (' . count($artistas) . ' encontrados)</h3>';
    foreach ($artistas as $artista) {
        echo '<div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0;">';
        echo '<strong>' . $artista->post_title . '</strong><br>';
        
        $meta_fields = [
            'artista_tipo_grupo',
            'artista_origem', 
            'artista_ano_formacao',
            'artista_biografia',
            'artista_ritmos',
            'artista_generos',
            'artista_publico_alvo',
            'artista_site_oficial'
        ];
        
        foreach ($meta_fields as $field) {
            $value = get_post_meta($artista->ID, $field, true);
            echo "<em>{$field}:</em> " . (is_array($value) ? json_encode($value) : $value) . '<br>';
        }
        echo '</div>';
    }
    
    // Testar eventos
    $eventos = get_posts([
        'post_type' => 'eventos_festivais',
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ]);
    
    echo '<h3>🎭 Eventos (' . count($eventos) . ' encontrados)</h3>';
    foreach ($eventos as $evento) {
        echo '<div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0;">';
        echo '<strong>' . $evento->post_title . '</strong><br>';
        
        $meta_fields = [
            'evento_data_inicio',
            'evento_data_fim',
            'evento_horario_inicio',
            'evento_preco',
            'evento_local',
            'evento_organizador',
            'evento_tipos',
            'evento_publico_alvo'
        ];
        
        foreach ($meta_fields as $field) {
            $value = get_post_meta($evento->ID, $field, true);
            if ($field === 'evento_local' && $value) {
                $local_title = get_the_title($value);
                echo "<em>{$field}:</em> {$value} ({$local_title})<br>";
            } else {
                echo "<em>{$field}:</em> " . (is_array($value) ? json_encode($value) : $value) . '<br>';
            }
        }
        echo '</div>';
    }
    
    // Testar lugares
    $lugares = get_posts([
        'post_type' => 'lugares',
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ]);
    
    echo '<h3>📍 Lugares (' . count($lugares) . ' encontrados)</h3>';
    foreach ($lugares as $lugar) {
        echo '<div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0;">';
        echo '<strong>' . $lugar->post_title . '</strong><br>';
        
        $meta_fields = [
            'lugar_endereco',
            'lugar_telefone',
            'lugar_email',
            'lugar_website',
            'lugar_horario_funcionamento',
            'lugar_faixa_preco',
            'lugar_especialidades'
        ];
        
        foreach ($meta_fields as $field) {
            $value = get_post_meta($lugar->ID, $field, true);
            echo "<em>{$field}:</em> " . (is_array($value) ? json_encode($value) : $value) . '<br>';
        }
        echo '</div>';
    }
    
    echo '</div>';
}

// Adicionar ao hook wp_footer apenas para admins
if (current_user_can('manage_options')) {
    add_action('wp_footer', 'debug_cpt_meta_fields');
}
?> 