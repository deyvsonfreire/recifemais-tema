<?php
/**
 * RecifeMais - Agenda Cultural Avançada - Integração PHP
 * Sistema backend para calendário cultural com AJAX e endpoints
 * 
 * @package RecifeMaisTema
 * @version 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe principal da Agenda Cultural
 */
class RecifeMais_Agenda_Cultural {
    
    /**
     * Instância única da classe
     */
    private static $instance = null;
    
    /**
     * Obtém instância única
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Construtor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Inicializa hooks do WordPress
     */
    private function init_hooks() {
        // Enqueue de scripts e estilos
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX endpoints
        add_action('wp_ajax_get_agenda_events', array($this, 'ajax_get_events'));
        add_action('wp_ajax_nopriv_get_agenda_events', array($this, 'ajax_get_events'));
        
        add_action('wp_ajax_filter_agenda_events', array($this, 'ajax_filter_events'));
        add_action('wp_ajax_nopriv_filter_agenda_events', array($this, 'ajax_filter_events'));
        
        add_action('wp_ajax_search_agenda_events', array($this, 'ajax_search_events'));
        add_action('wp_ajax_nopriv_search_agenda_events', array($this, 'ajax_search_events'));
        
        // REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Shortcodes
        add_shortcode('agenda_cultural', array($this, 'agenda_shortcode'));
        add_shortcode('agenda_widget', array($this, 'agenda_widget_shortcode'));
        
        // Meta boxes para eventos
        add_action('add_meta_boxes', array($this, 'add_event_meta_boxes'));
        add_action('save_post', array($this, 'save_event_meta'));
        
        // Cron para limpeza de eventos antigos
        add_action('wp', array($this, 'schedule_cleanup'));
        add_action('recifemais_cleanup_old_events', array($this, 'cleanup_old_events'));
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // Apenas em páginas que usam a agenda
        if (!$this->should_load_agenda()) {
            return;
        }
        
        // CSS da agenda
        wp_enqueue_style(
            'recifemais-agenda-cultural',
            get_template_directory_uri() . '/css/agenda-cultural.css',
            array(),
            '2.0.0'
        );
        
        // JavaScript da agenda
        wp_enqueue_script(
            'recifemais-agenda-cultural',
            get_template_directory_uri() . '/js/agenda-cultural.js',
            array('jquery'),
            '2.0.0',
            true
        );
        
        // Localização para AJAX
        wp_localize_script('recifemais-agenda-cultural', 'recifemais_agenda', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('recifemais/v1/'),
            'nonce' => wp_create_nonce('recifemais_agenda_nonce'),
            'strings' => array(
                'loading' => 'Carregando eventos...',
                'no_events' => 'Nenhum evento encontrado',
                'error' => 'Erro ao carregar eventos',
                'today' => 'Hoje',
                'tomorrow' => 'Amanhã',
                'this_week' => 'Esta semana',
                'next_week' => 'Próxima semana',
                'this_month' => 'Este mês',
                'add_to_calendar' => 'Adicionar ao calendário',
                'share_event' => 'Compartilhar evento',
                'view_details' => 'Ver detalhes'
            ),
            'config' => array(
                'default_view' => 'month',
                'enable_notifications' => true,
                'auto_refresh' => true,
                'refresh_interval' => 300000, // 5 minutos
                'date_format' => get_option('date_format'),
                'time_format' => get_option('time_format'),
                'start_of_week' => get_option('start_of_week')
            )
        ));
    }
    
    /**
     * Verifica se deve carregar a agenda
     */
    private function should_load_agenda() {
        // Página dedicada da agenda cultural (Template Name: Agenda Cultural)
        if (is_page_template('page-agenda-cultural.php')) {
            return true;
        }
        
        // Front-page sempre carrega (para o widget)
        if (is_front_page()) {
            return true;
        }
        
        // Archive de eventos (adiciona funcionalidades ao template existente)
        if (is_post_type_archive('eventos_festivais')) {
            return true;
        }
        
        // Single de evento (adiciona funcionalidades ao template existente)
        if (is_singular('eventos_festivais')) {
            return true;
        }
        
        // Páginas com shortcode
        global $post;
        if ($post && (has_shortcode($post->post_content, 'agenda_cultural') || has_shortcode($post->post_content, 'agenda_widget'))) {
            return true;
        }
        
        // Páginas específicas
        $agenda_pages = array('agenda', 'eventos', 'calendario', 'agenda-cultural');
        if (is_page($agenda_pages)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * AJAX: Obtém eventos
     */
    public function ajax_get_events() {
        // Verificar nonce
        if (!wp_verify_nonce($_REQUEST['nonce'], 'recifemais_agenda_nonce')) {
            wp_die('Acesso negado');
        }
        
        $args = array(
            'start_date' => sanitize_text_field($_REQUEST['start_date'] ?? ''),
            'end_date' => sanitize_text_field($_REQUEST['end_date'] ?? ''),
            'limit' => intval($_REQUEST['limit'] ?? 50),
            'offset' => intval($_REQUEST['offset'] ?? 0)
        );
        
        $events = $this->get_events($args);
        
        wp_send_json_success(array(
            'events' => $events,
            'total' => $this->get_events_count($args)
        ));
    }
    
    /**
     * AJAX: Filtra eventos
     */
    public function ajax_filter_events() {
        // Verificar nonce
        if (!wp_verify_nonce($_REQUEST['nonce'], 'recifemais_agenda_nonce')) {
            wp_die('Acesso negado');
        }
        
        $filters = array(
            'tipo' => sanitize_text_field($_REQUEST['tipo'] ?? ''),
            'bairro' => sanitize_text_field($_REQUEST['bairro'] ?? ''),
            'preco' => sanitize_text_field($_REQUEST['preco'] ?? ''),
            'periodo' => sanitize_text_field($_REQUEST['periodo'] ?? ''),
            'start_date' => sanitize_text_field($_REQUEST['start_date'] ?? ''),
            'end_date' => sanitize_text_field($_REQUEST['end_date'] ?? '')
        );
        
        $events = $this->filter_events($filters);
        
        wp_send_json_success(array(
            'events' => $events,
            'total' => count($events)
        ));
    }
    
    /**
     * AJAX: Busca eventos
     */
    public function ajax_search_events() {
        // Verificar nonce
        if (!wp_verify_nonce($_REQUEST['nonce'], 'recifemais_agenda_nonce')) {
            wp_die('Acesso negado');
        }
        
        $query = sanitize_text_field($_REQUEST['query'] ?? '');
        $events = $this->search_events($query);
        
        wp_send_json_success(array(
            'events' => $events,
            'total' => count($events)
        ));
    }
    
    /**
     * Registra rotas da REST API
     */
    public function register_rest_routes() {
        register_rest_route('recifemais/v1', '/agenda/events', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_events'),
            'permission_callback' => '__return_true',
            'args' => array(
                'start_date' => array(
                    'type' => 'string',
                    'format' => 'date'
                ),
                'end_date' => array(
                    'type' => 'string',
                    'format' => 'date'
                ),
                'limit' => array(
                    'type' => 'integer',
                    'default' => 50
                ),
                'offset' => array(
                    'type' => 'integer',
                    'default' => 0
                )
            )
        ));
        
        register_rest_route('recifemais/v1', '/agenda/events/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_event'),
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('recifemais/v1', '/agenda/filters', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_filters'),
            'permission_callback' => '__return_true'
        ));
    }
    
    /**
     * REST: Obtém eventos
     */
    public function rest_get_events($request) {
        $params = $request->get_params();
        $events = $this->get_events($params);
        
        return rest_ensure_response($events);
    }
    
    /**
     * REST: Obtém evento específico
     */
    public function rest_get_event($request) {
        $id = $request['id'];
        $event = $this->get_event_data($id);
        
        if (!$event) {
            return new WP_Error('not_found', 'Evento não encontrado', array('status' => 404));
        }
        
        return rest_ensure_response($event);
    }
    
    /**
     * REST: Obtém filtros disponíveis
     */
    public function rest_get_filters($request) {
        $filters = array(
            'tipos' => $this->get_event_types(),
            'bairros' => $this->get_event_neighborhoods(),
            'precos' => $this->get_price_ranges()
        );
        
        return rest_ensure_response($filters);
    }
    
    /**
     * Obtém eventos com filtros
     */
    public function get_events($args = array()) {
        $defaults = array(
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+1 month')),
            'limit' => 50,
            'offset' => 0,
            'status' => 'publish'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query_args = array(
            'post_type' => 'eventos_festivais',
            'post_status' => $args['status'],
            'posts_per_page' => $args['limit'],
            'offset' => $args['offset'],
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => $args['start_date'],
                    'compare' => '>=',
                    'type' => 'DATE'
                ),
                array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => $args['end_date'],
                    'compare' => '<=',
                    'type' => 'DATE'
                )
            ),
            'meta_key' => '_data_inicio_evento_festival',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );
        
        $query = new WP_Query($query_args);
        $events = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = $this->format_event_data(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }
    
    /**
     * Filtra eventos
     */
    public function filter_events($filters) {
        $query_args = array(
            'post_type' => 'eventos_festivais',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array('relation' => 'AND')
        );
        
        // Filtro por tipo
        if (!empty($filters['tipo']) && $filters['tipo'] !== 'all') {
            $query_args['meta_query'][] = array(
                'key' => '_tipo_evento',
                'value' => $filters['tipo'],
                'compare' => '='
            );
        }
        
        // Filtro por bairro
        if (!empty($filters['bairro']) && $filters['bairro'] !== 'all') {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'bairros_recife',
                'field' => 'slug',
                'terms' => $filters['bairro']
            );
        }
        
        // Filtro por preço
        if (!empty($filters['preco']) && $filters['preco'] !== 'all') {
            if ($filters['preco'] === 'free') {
                $query_args['meta_query'][] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => 'Gratuito',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => 'Grátis',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => '',
                        'compare' => '='
                    )
                );
            } elseif ($filters['preco'] === 'paid') {
                $query_args['meta_query'][] = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => 'Gratuito',
                        'compare' => 'NOT LIKE'
                    ),
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => 'Grátis',
                        'compare' => 'NOT LIKE'
                    ),
                    array(
                        'key' => '_informacoes_preco_ingresso_geral',
                        'value' => '',
                        'compare' => '!='
                    )
                );
            }
        }
        
        // Filtro por período
        if (!empty($filters['periodo']) && $filters['periodo'] !== 'all') {
            $date_filter = $this->get_period_date_filter($filters['periodo']);
            if ($date_filter) {
                $query_args['meta_query'][] = $date_filter;
            }
        }
        
        // Filtro por data específica
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query_args['meta_query'][] = array(
                'key' => '_data_inicio_evento_festival',
                'value' => array($filters['start_date'], $filters['end_date']),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            );
        }
        
        $query = new WP_Query($query_args);
        $events = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = $this->format_event_data(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }
    
    /**
     * Busca eventos
     */
    public function search_events($query) {
        if (empty($query)) {
            return array();
        }
        
        $search_args = array(
            'post_type' => 'eventos_festivais',
            'post_status' => 'publish',
            'posts_per_page' => 50,
            's' => $query,
            'meta_query' => array(
                array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            ),
            'meta_key' => '_data_inicio_evento_festival',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );
        
        $search_query = new WP_Query($search_args);
        $events = array();
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                $events[] = $this->format_event_data(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }
    
    /**
     * Formata dados do evento
     */
    public function format_event_data($post_id) {
        $post = get_post($post_id);
        
        // Meta fields com fallbacks
        $data_inicio = get_post_meta($post_id, '_data_inicio_evento_festival', true);
        if (!$data_inicio) $data_inicio = get_post_meta($post_id, '_data_inicio', true);
        
        $data_fim = get_post_meta($post_id, '_data_fim_evento_festival', true);
        if (!$data_fim) $data_fim = get_post_meta($post_id, '_data_fim', true);
        
        $horario_inicio = get_post_meta($post_id, '_horario_inicio_evento_festival', true);
        if (!$horario_inicio) $horario_inicio = get_post_meta($post_id, '_horario_inicio', true);
        
        $horario_fim = get_post_meta($post_id, '_horario_fim_evento_festival', true);
        if (!$horario_fim) $horario_fim = get_post_meta($post_id, '_horario_fim', true);
        
        $local = get_post_meta($post_id, '_nome_local_avulso', true);
        if (!$local) $local = get_post_meta($post_id, '_local', true);
        
        $endereco = get_post_meta($post_id, '_endereco_local_avulso', true);
        if (!$endereco) $endereco = get_post_meta($post_id, '_endereco', true);
        
        $preco = get_post_meta($post_id, '_informacoes_preco_ingresso_geral', true);
        if (!$preco) $preco = get_post_meta($post_id, '_preco', true);
        
        $tipo_evento = get_post_meta($post_id, '_tipo_evento', true);
        $publico_alvo = get_post_meta($post_id, '_publico_alvo', true);
        $faixa_preco = get_post_meta($post_id, '_faixa_preco', true);
        
        // Coordenadas para mapas
        $latitude = get_post_meta($post_id, 'latitude', true);
        $longitude = get_post_meta($post_id, 'longitude', true);
        
        // Taxonomias
        $bairros = wp_get_post_terms($post_id, 'bairros_recife', array('fields' => 'names'));
        $cidades = wp_get_post_terms($post_id, 'cidades_pernambuco', array('fields' => 'names'));
        
        // Imagem
        $image_id = get_post_thumbnail_id($post_id);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
        $image_large = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
        
        return array(
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'excerpt' => get_the_excerpt($post_id),
            'content' => get_the_content(null, false, $post_id),
            'permalink' => get_permalink($post_id),
            'date' => $data_inicio,
            'date_end' => $data_fim,
            'date_formatted' => $data_inicio ? date_i18n(get_option('date_format'), strtotime($data_inicio)) : '',
            'time' => $horario_inicio,
            'time_end' => $horario_fim,
            'location' => $local,
            'address' => $endereco,
            'price' => $preco ?: 'Não informado',
            'type' => $tipo_evento,
            'audience' => $publico_alvo,
            'price_range' => $faixa_preco,
            'bairro' => !empty($bairros) ? $bairros[0] : '',
            'cidade' => !empty($cidades) ? $cidades[0] : '',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image' => $image_url,
            'image_large' => $image_large,
            'status' => $this->get_event_status($data_inicio, $data_fim),
            'is_featured' => get_post_meta($post_id, '_featured_event', true) === '1',
            'is_free' => $this->is_free_event($preco),
            'google_calendar_url' => $this->generate_google_calendar_url($post_id),
            'ical_url' => $this->generate_ical_url($post_id)
        );
    }
    
    /**
     * Obtém dados de um evento específico
     */
    public function get_event_data($post_id) {
        $post = get_post($post_id);
        
        if (!$post || $post->post_type !== 'eventos_festivais') {
            return false;
        }
        
        return $this->format_event_data($post_id);
    }
    
    /**
     * Obtém status do evento
     */
    private function get_event_status($data_inicio, $data_fim = null) {
        if (!$data_inicio) {
            return 'unknown';
        }
        
        $now = current_time('Y-m-d');
        $start_date = date('Y-m-d', strtotime($data_inicio));
        $end_date = $data_fim ? date('Y-m-d', strtotime($data_fim)) : $start_date;
        
        if ($start_date > $now) {
            return 'upcoming';
        } elseif ($start_date === $now || ($end_date && $now >= $start_date && $now <= $end_date)) {
            return 'today';
        } else {
            return 'past';
        }
    }
    
    /**
     * Verifica se evento é gratuito
     */
    private function is_free_event($preco) {
        if (empty($preco)) {
            return true;
        }
        
        $free_keywords = array('gratuito', 'grátis', 'free', 'entrada franca', 'sem custo');
        $preco_lower = strtolower($preco);
        
        foreach ($free_keywords as $keyword) {
            if (strpos($preco_lower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Gera URL do Google Calendar
     */
    private function generate_google_calendar_url($post_id) {
        $event = $this->get_event_data($post_id);
        
        if (!$event || !$event['date']) {
            return '';
        }
        
        $start_date = new DateTime($event['date']);
        $end_date = clone $start_date;
        $end_date->add(new DateInterval('PT2H')); // Duração padrão de 2 horas
        
        $params = array(
            'action' => 'TEMPLATE',
            'text' => $event['title'],
            'dates' => $start_date->format('Ymd\THis\Z') . '/' . $end_date->format('Ymd\THis\Z'),
            'details' => $event['excerpt'],
            'location' => $event['location']
        );
        
        return 'https://calendar.google.com/calendar/render?' . http_build_query($params);
    }
    
    /**
     * Gera URL do iCal
     */
    private function generate_ical_url($post_id) {
        return home_url("/agenda/ical/{$post_id}/");
    }
    
    /**
     * Obtém filtro de data por período
     */
    private function get_period_date_filter($period) {
        $now = current_time('Y-m-d');
        
        switch ($period) {
            case 'today':
                return array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => $now,
                    'compare' => '=',
                    'type' => 'DATE'
                );
                
            case 'week':
                $week_end = date('Y-m-d', strtotime('+7 days'));
                return array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => array($now, $week_end),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                );
                
            case 'month':
                $month_end = date('Y-m-d', strtotime('+1 month'));
                return array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => array($now, $month_end),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                );
                
            case 'weekend':
                $saturday = date('Y-m-d', strtotime('next saturday'));
                $sunday = date('Y-m-d', strtotime('next sunday'));
                return array(
                    'key' => '_data_inicio_evento_festival',
                    'value' => array($saturday, $sunday),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                );
        }
        
        return null;
    }
    
    /**
     * Obtém tipos de eventos
     */
    public function get_event_types() {
        global $wpdb;
        
        $types = $wpdb->get_col("
            SELECT DISTINCT meta_value 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_tipo_evento' 
            AND meta_value != '' 
            ORDER BY meta_value
        ");
        
        return array_map('ucfirst', $types);
    }
    
    /**
     * Obtém bairros dos eventos
     */
    public function get_event_neighborhoods() {
        $terms = get_terms(array(
            'taxonomy' => 'bairros_recife',
            'hide_empty' => true,
            'orderby' => 'name'
        ));
        
        if (is_wp_error($terms)) {
            return array();
        }
        
        return wp_list_pluck($terms, 'name', 'slug');
    }
    
    /**
     * Obtém faixas de preço
     */
    public function get_price_ranges() {
        return array(
            'free' => 'Gratuito',
            'low' => 'Até R$ 20',
            'medium' => 'R$ 20 - R$ 50',
            'high' => 'Acima de R$ 50'
        );
    }
    
    /**
     * Conta eventos
     */
    public function get_events_count($args = array()) {
        $args['limit'] = -1;
        $events = $this->get_events($args);
        return count($events);
    }
    
    /**
     * Shortcode da agenda cultural
     */
    public function agenda_shortcode($atts) {
        $atts = shortcode_atts(array(
            'view' => 'month',
            'limit' => 50,
            'show_filters' => 'true',
            'show_search' => 'true',
            'height' => 'auto'
        ), $atts);
        
        ob_start();
        $this->render_agenda_calendar($atts);
        return ob_get_clean();
    }
    
    /**
     * Shortcode do widget da agenda
     */
    public function agenda_widget_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5,
            'show_date' => 'true',
            'show_location' => 'true'
        ), $atts);
        
        ob_start();
        $this->render_agenda_widget($atts);
        return ob_get_clean();
    }
    
    /**
     * Renderiza calendário da agenda
     */
    public function render_agenda_calendar($args = array()) {
        $defaults = array(
            'view' => 'month',
            'show_filters' => true,
            'show_search' => true,
            'height' => 'auto'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        include locate_template('components/agenda-cultural-calendar.php');
    }
    
    /**
     * Renderiza widget da agenda
     */
    public function render_agenda_widget($args = array()) {
        $defaults = array(
            'limit' => 5,
            'show_date' => true,
            'show_location' => true
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $events = $this->get_events(array('limit' => $args['limit']));
        
        include locate_template('components/agenda-cultural-widget.php');
    }
    
    /**
     * Adiciona meta boxes para eventos
     */
    public function add_event_meta_boxes() {
        add_meta_box(
            'recifemais_event_agenda',
            'Configurações da Agenda',
            array($this, 'render_event_meta_box'),
            'eventos_festivais',
            'side',
            'default'
        );
    }
    
    /**
     * Renderiza meta box do evento
     */
    public function render_event_meta_box($post) {
        wp_nonce_field('recifemais_event_meta', 'recifemais_event_meta_nonce');
        
        $featured = get_post_meta($post->ID, '_featured_event', true);
        $priority = get_post_meta($post->ID, '_event_priority', true);
        $color = get_post_meta($post->ID, '_event_color', true);
        
        ?>
        <table class="form-table">
            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="featured_event" value="1" <?php checked($featured, '1'); ?>>
                        Evento em destaque
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="event_priority">Prioridade:</label>
                    <select name="event_priority" id="event_priority">
                        <option value="normal" <?php selected($priority, 'normal'); ?>>Normal</option>
                        <option value="high" <?php selected($priority, 'high'); ?>>Alta</option>
                        <option value="urgent" <?php selected($priority, 'urgent'); ?>>Urgente</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="event_color">Cor no calendário:</label>
                    <input type="color" name="event_color" id="event_color" value="<?php echo esc_attr($color ?: '#ec4899'); ?>">
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Salva meta dados do evento
     */
    public function save_event_meta($post_id) {
        // Verificações de segurança
        if (!isset($_POST['recifemais_event_meta_nonce']) || 
            !wp_verify_nonce($_POST['recifemais_event_meta_nonce'], 'recifemais_event_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Salvar campos
        $featured = isset($_POST['featured_event']) ? '1' : '0';
        update_post_meta($post_id, '_featured_event', $featured);
        
        if (isset($_POST['event_priority'])) {
            update_post_meta($post_id, '_event_priority', sanitize_text_field($_POST['event_priority']));
        }
        
        if (isset($_POST['event_color'])) {
            update_post_meta($post_id, '_event_color', sanitize_hex_color($_POST['event_color']));
        }
    }
    
    /**
     * Agenda limpeza de eventos antigos
     */
    public function schedule_cleanup() {
        if (!wp_next_scheduled('recifemais_cleanup_old_events')) {
            wp_schedule_event(time(), 'daily', 'recifemais_cleanup_old_events');
        }
    }
    
    /**
     * Limpa eventos antigos
     */
    public function cleanup_old_events() {
        $old_date = date('Y-m-d', strtotime('-6 months'));
        
        $old_events = get_posts(array(
            'post_type' => 'eventos_festivais',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_data_fim_evento_festival',
                    'value' => $old_date,
                    'compare' => '<',
                    'type' => 'DATE'
                )
            )
        ));
        
        foreach ($old_events as $event) {
            // Move para rascunho em vez de deletar
            wp_update_post(array(
                'ID' => $event->ID,
                'post_status' => 'draft'
            ));
        }
        
        // Log da limpeza
        error_log("RecifeMais: Limpeza automática moveu " . count($old_events) . " eventos antigos para rascunho.");
    }
}

// Inicializar a classe
RecifeMais_Agenda_Cultural::get_instance(); 