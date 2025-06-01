<?php
/**
 * Integração com Mapas - RecifeMais V2
 * 
 * Gerencia a integração com Google Maps API:
 * - Configuração da API key
 * - Enqueue de scripts
 * - Helpers para templates
 * - Geração de dados para JavaScript
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

class RecifeMais_Maps_Integration {
    
    private $api_key;
    private $default_center;
    private $default_zoom;
    
    public function __construct() {
        $this->api_key = get_option('recifemais_google_maps_api_key', '');
        $this->default_center = array('lat' => -8.0476, 'lng' => -34.8770); // Recife
        $this->default_zoom = 13;
        
        $this->init();
    }
    
    /**
     * Inicializa a integração
     */
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'output_map_config'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        
        // Adicionar meta box para coordenadas nos CPTs
        add_action('add_meta_boxes', array($this, 'add_coordinates_meta_box'));
        add_action('save_post', array($this, 'save_coordinates_meta_box'));
    }
    
    /**
     * Enqueue scripts e estilos dos mapas
     */
    public function enqueue_scripts() {
        if (!$this->should_load_maps()) {
            return;
        }
        
        // CSS dos mapas
        wp_enqueue_style(
            'recifemais-maps-css',
            get_template_directory_uri() . '/css/maps.css',
            array(),
            '2.0.0'
        );
        
        // JavaScript dos mapas (nossa classe)
        wp_enqueue_script(
            'recifemais-maps-js',
            get_template_directory_uri() . '/js/recifemais-maps.js',
            array('jquery'),
            '2.0.0',
            true
        );
        
        // MarkerClusterer para clustering
        wp_enqueue_script(
            'marker-clusterer',
            'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js',
            array(),
            '2.0.0',
            true
        );
    }
    
    /**
     * Outputa configuração JavaScript dos mapas
     */
    public function output_map_config() {
        if (!$this->should_load_maps() || !$this->api_key) {
            return;
        }
        
        $config = array(
            'api_key' => $this->api_key,
            'default_center' => $this->default_center,
            'default_zoom' => $this->default_zoom,
            'icons' => array(
                'lugar' => get_template_directory_uri() . '/assets/images/icons/lugar-marker.png',
                'evento' => get_template_directory_uri() . '/assets/images/icons/evento-marker.png',
                'artista' => get_template_directory_uri() . '/assets/images/icons/artista-marker.png',
                'roteiro' => get_template_directory_uri() . '/assets/images/icons/roteiro-marker.png',
            ),
            'cluster_images' => get_template_directory_uri() . '/assets/images/clusters/m',
        );
        
        ?>
        <script type="text/javascript">
            window.recifemais_maps_config = <?php echo json_encode($config); ?>;
        </script>
        <?php
    }
    
    /**
     * Verifica se deve carregar os mapas na página atual
     */
    private function should_load_maps() {
        global $post;
        
        // Singles que usam mapas
        if (is_singular(array('lugares', 'eventos_festivais', 'roteiros', 'artistas'))) {
            return true;
        }
        
        // Archives que usam mapas
        if (is_post_type_archive(array('lugares', 'eventos_festivais', 'roteiros'))) {
            return true;
        }
        
        // Página de busca
        if (is_page_template('page-busca.php')) {
            return true;
        }
        
        // Páginas específicas que podem ter mapas
        if (is_page() && $post) {
            $load_maps = get_post_meta($post->ID, '_recifemais_load_maps', true);
            if ($load_maps === 'yes') {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Adiciona menu no admin para configuração dos mapas
     */
    public function add_admin_menu() {
        add_options_page(
            'Configurações dos Mapas',
            'Mapas RecifeMais',
            'manage_options',
            'recifemais-maps',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Inicializa configurações do admin
     */
    public function admin_init() {
        register_setting('recifemais_maps_settings', 'recifemais_google_maps_api_key');
        register_setting('recifemais_maps_settings', 'recifemais_maps_default_zoom');
        register_setting('recifemais_maps_settings', 'recifemais_maps_default_lat');
        register_setting('recifemais_maps_settings', 'recifemais_maps_default_lng');
    }
    
    /**
     * Página de configuração no admin
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Configurações dos Mapas - RecifeMais</h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('recifemais_maps_settings'); ?>
                <?php do_settings_sections('recifemais_maps_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="recifemais_google_maps_api_key">Google Maps API Key</label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="recifemais_google_maps_api_key" 
                                   name="recifemais_google_maps_api_key" 
                                   value="<?php echo esc_attr($this->api_key); ?>" 
                                   class="regular-text" />
                            <p class="description">
                                Insira sua chave da API do Google Maps. 
                                <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">
                                    Como obter uma API key
                                </a>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="recifemais_maps_default_zoom">Zoom Padrão</label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="recifemais_maps_default_zoom" 
                                   name="recifemais_maps_default_zoom" 
                                   value="<?php echo esc_attr(get_option('recifemais_maps_default_zoom', $this->default_zoom)); ?>" 
                                   min="1" max="20" />
                            <p class="description">Nível de zoom padrão para os mapas (1-20)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Localização Padrão</th>
                        <td>
                            <label for="recifemais_maps_default_lat">Latitude:</label>
                            <input type="text" 
                                   id="recifemais_maps_default_lat" 
                                   name="recifemais_maps_default_lat" 
                                   value="<?php echo esc_attr(get_option('recifemais_maps_default_lat', $this->default_center['lat'])); ?>" 
                                   class="small-text" />
                            
                            <label for="recifemais_maps_default_lng">Longitude:</label>
                            <input type="text" 
                                   id="recifemais_maps_default_lng" 
                                   name="recifemais_maps_default_lng" 
                                   value="<?php echo esc_attr(get_option('recifemais_maps_default_lng', $this->default_center['lng'])); ?>" 
                                   class="small-text" />
                            <p class="description">Coordenadas centrais para os mapas (padrão: Recife)</p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <hr>
            
            <h2>Funcionalidades dos Mapas</h2>
            <p>O sistema de mapas do RecifeMais inclui:</p>
            <ul>
                <li><strong>Geolocalização automática</strong> - Detecta localização do usuário</li>
                <li><strong>Mapas interativos nos singles</strong> - Cada lugar, evento e roteiro tem seu mapa</li>
                <li><strong>Direções e rotas</strong> - Botões para obter direções</li>
                <li><strong>Clusters por bairro</strong> - Agrupamento inteligente de pontos próximos</li>
                <li><strong>Compartilhamento de localização</strong> - Fácil compartilhamento via redes sociais</li>
                <li><strong>Ícones personalizados</strong> - Diferentes ícones para cada tipo de conteúdo</li>
            </ul>
            
            <h3>Como usar:</h3>
            <ol>
                <li>Configure sua API key do Google Maps acima</li>
                <li>Adicione coordenadas nos posts de Lugares, Eventos e Roteiros</li>
                <li>Os mapas aparecerão automaticamente nos templates</li>
            </ol>
        </div>
        <?php
    }
    
    /**
     * Adiciona meta box para coordenadas
     */
    public function add_coordinates_meta_box() {
        $post_types = array('lugares', 'eventos_festivais', 'roteiros', 'artistas');
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'recifemais_coordinates',
                'Coordenadas para Mapa',
                array($this, 'coordinates_meta_box_callback'),
                $post_type,
                'side',
                'default'
            );
        }
    }
    
    /**
     * Callback do meta box de coordenadas
     */
    public function coordinates_meta_box_callback($post) {
        wp_nonce_field('recifemais_coordinates_nonce', 'recifemais_coordinates_nonce');
        
        $latitude = get_post_meta($post->ID, 'latitude', true);
        $longitude = get_post_meta($post->ID, 'longitude', true);
        
        ?>
        <p>
            <label for="latitude"><strong>Latitude:</strong></label><br>
            <input type="text" id="latitude" name="latitude" value="<?php echo esc_attr($latitude); ?>" style="width: 100%;" />
        </p>
        
        <p>
            <label for="longitude"><strong>Longitude:</strong></label><br>
            <input type="text" id="longitude" name="longitude" value="<?php echo esc_attr($longitude); ?>" style="width: 100%;" />
        </p>
        
        <p>
            <small>
                <strong>Dica:</strong> Use o <a href="https://www.latlong.net/" target="_blank">LatLong.net</a> 
                para encontrar as coordenadas do local.
            </small>
        </p>
        
        <p>
            <small>
                <strong>Recife Centro:</strong> -8.0634, -34.8731<br>
                <strong>Boa Viagem:</strong> -8.1223, -34.8969<br>
                <strong>Olinda:</strong> -8.0089, -34.8553
            </small>
        </p>
        <?php
    }
    
    /**
     * Salva dados do meta box de coordenadas
     */
    public function save_coordinates_meta_box($post_id) {
        if (!isset($_POST['recifemais_coordinates_nonce']) || 
            !wp_verify_nonce($_POST['recifemais_coordinates_nonce'], 'recifemais_coordinates_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (isset($_POST['latitude'])) {
            update_post_meta($post_id, 'latitude', sanitize_text_field($_POST['latitude']));
        }
        
        if (isset($_POST['longitude'])) {
            update_post_meta($post_id, 'longitude', sanitize_text_field($_POST['longitude']));
        }
    }
    
    /**
     * Obtém dados de um lugar para o mapa
     */
    public static function get_lugar_data($post_id) {
        $latitude = get_post_meta($post_id, 'latitude', true);
        $longitude = get_post_meta($post_id, 'longitude', true);
        
        if (!$latitude || !$longitude) {
            return null;
        }
        
        return array(
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'lat' => floatval($latitude),
            'lng' => floatval($longitude),
            'url' => get_permalink($post_id),
            'excerpt' => wp_trim_words(get_the_excerpt($post_id), 15),
            'image' => get_the_post_thumbnail_url($post_id, 'medium'),
            'address' => get_post_meta($post_id, 'endereco_completo', true),
            'type' => get_post_type($post_id)
        );
    }
    
    /**
     * Obtém dados de múltiplos lugares para o mapa de cluster
     */
    public static function get_lugares_data($args = array()) {
        $default_args = array(
            'post_type' => array('lugares', 'eventos_festivais'),
            'posts_per_page' => 100,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'latitude',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'longitude',
                    'compare' => 'EXISTS'
                )
            )
        );
        
        $args = wp_parse_args($args, $default_args);
        $query = new WP_Query($args);
        $lugares_data = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $lugar_data = self::get_lugar_data(get_the_ID());
                if ($lugar_data) {
                    $lugares_data[] = $lugar_data;
                }
            }
            wp_reset_postdata();
        }
        
        return $lugares_data;
    }
    
    /**
     * Obtém dados de um roteiro para o mapa de rota
     */
    public static function get_roteiro_data($post_id) {
        $ponto_partida = get_post_meta($post_id, 'ponto_partida', true);
        $ponto_chegada = get_post_meta($post_id, 'ponto_chegada', true);
        $pontos_interesse = get_post_meta($post_id, 'pontos_interesse', true);
        
        // Tentar obter coordenadas dos pontos (se disponíveis)
        $pontos = array();
        
        // Ponto de partida
        if ($ponto_partida) {
            $lat_partida = get_post_meta($post_id, 'lat_partida', true);
            $lng_partida = get_post_meta($post_id, 'lng_partida', true);
            
            if ($lat_partida && $lng_partida) {
                $pontos[] = array(
                    'title' => $ponto_partida,
                    'lat' => floatval($lat_partida),
                    'lng' => floatval($lng_partida),
                    'type' => 'partida'
                );
            }
        }
        
        // Pontos de interesse (se tiverem coordenadas)
        if ($pontos_interesse) {
            $pontos_array = explode(',', $pontos_interesse);
            foreach ($pontos_array as $index => $ponto) {
                $lat_key = 'lat_ponto_' . $index;
                $lng_key = 'lng_ponto_' . $index;
                $lat = get_post_meta($post_id, $lat_key, true);
                $lng = get_post_meta($post_id, $lng_key, true);
                
                if ($lat && $lng) {
                    $pontos[] = array(
                        'title' => trim($ponto),
                        'lat' => floatval($lat),
                        'lng' => floatval($lng),
                        'type' => 'interesse'
                    );
                }
            }
        }
        
        // Ponto de chegada
        if ($ponto_chegada) {
            $lat_chegada = get_post_meta($post_id, 'lat_chegada', true);
            $lng_chegada = get_post_meta($post_id, 'lng_chegada', true);
            
            if ($lat_chegada && $lng_chegada) {
                $pontos[] = array(
                    'title' => $ponto_chegada,
                    'lat' => floatval($lat_chegada),
                    'lng' => floatval($lng_chegada),
                    'type' => 'chegada'
                );
            }
        }
        
        return array(
            'pontos' => $pontos,
            'title' => get_the_title($post_id),
            'duracao' => get_post_meta($post_id, 'duracao_estimada', true),
            'distancia' => get_post_meta($post_id, 'distancia_total', true)
        );
    }
    
    /**
     * Obtém dados de um evento para o mapa
     */
    public static function get_evento_data($post_id) {
        $latitude = get_post_meta($post_id, 'latitude', true);
        $longitude = get_post_meta($post_id, 'longitude', true);
        
        if (!$latitude || !$longitude) {
            return null;
        }
        
        return array(
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'lat' => floatval($latitude),
            'lng' => floatval($longitude),
            'url' => get_permalink($post_id),
            'date' => get_post_meta($post_id, 'data_evento', true),
            'time' => get_post_meta($post_id, 'horario_inicio', true),
            'venue' => get_post_meta($post_id, 'local_evento', true),
            'address' => get_post_meta($post_id, 'endereco_evento', true),
            'type' => 'evento'
        );
    }
}

// Inicializar a integração
new RecifeMais_Maps_Integration(); 