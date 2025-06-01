<?php
/**
 * Componente: Dropdown Geográfico (Wrapper para Plugin)
 * 
 * Interface do tema para usar as funcionalidades do plugin RecifeMais Core
 * 
 * @package RecifeMais
 * @since 2.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Renderiza dropdown geográfico usando o plugin
 * 
 * @param array $args Argumentos de configuração
 */
function recifemais_render_dropdown_geografico($args = []) {
    // Verifica se o plugin está ativo
    if (function_exists('recifemais_core_is_active') && recifemais_core_is_active()) {
        // Usa a funcionalidade do plugin
        if (class_exists('RecifeMais_Dropdown_Geografico')) {
            $dropdown = RecifeMais_Dropdown_Geografico::get_instance();
            echo $dropdown->render_widget_dropdown($args);
            return;
        }
    }
    
    // Fallback se o plugin não estiver ativo
    echo '<div class="notice notice-warning"><p>Plugin RecifeMais Core necessário para funcionalidade de dropdown geográfico.</p></div>';
}

/**
 * Shortcode para dropdown geográfico
 * 
 * Uso: [recifemais_dropdown_geografico cidade_label="Cidade" bairro_label="Bairro"]
 */
function recifemais_dropdown_geografico_shortcode($atts) {
    // Verifica se o plugin está ativo
    if (function_exists('recifemais_core_is_active') && recifemais_core_is_active()) {
        // Usa o shortcode do plugin
        return do_shortcode('[recifemais_filtro_geografico ' . http_build_query($atts, '', ' ') . ']');
    }
    
    return '<div class="notice notice-warning"><p>Plugin RecifeMais Core necessário para esta funcionalidade.</p></div>';
}
add_shortcode('recifemais_dropdown_geografico', 'recifemais_dropdown_geografico_shortcode');

/**
 * Formulário de busca de eventos simplificado
 */
function recifemais_formulario_busca_eventos() {
    ob_start();
    ?>
    <form class="recifemais-busca-eventos" method="GET" action="<?php echo esc_url(home_url('/eventos/')); ?>">
        
        <div class="busca-grid">
            
            <!-- Campo de busca por texto -->
            <div class="form-group">
                <label for="busca-texto" class="sr-only">Buscar eventos</label>
                <input type="text" id="busca-texto" name="s" 
                       placeholder="Buscar eventos..." 
                       value="<?php echo esc_attr(get_search_query()); ?>"
                       class="recifemais-input">
            </div>
            
            <!-- Dropdown geográfico -->
            <div class="form-group">
                <?php recifemais_render_dropdown_geografico([
                    'show_labels' => false,
                    'container_class' => 'busca-localizacao'
                ]); ?>
            </div>
            
            <!-- Botão de busca -->
            <div class="form-group">
                <button type="submit" class="recifemais-btn recifemais-btn--primary">
                    <span class="sr-only">Buscar</span>
                    🔍
                </button>
            </div>
            
        </div>
        
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode para busca de eventos
 */
function recifemais_busca_eventos_shortcode() {
    return recifemais_formulario_busca_eventos();
}
add_shortcode('recifemais_busca_eventos', 'recifemais_busca_eventos_shortcode'); 