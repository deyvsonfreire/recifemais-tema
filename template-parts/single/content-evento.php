<?php
/**
 * Template Part: Content Evento
 * 
 * Conteúdo específico para eventos com:
 * - Informações detalhadas do evento
 * - Programação e cronograma
 * - Artistas e atrações
 * - Informações práticas
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do evento
$post_id = get_the_ID();
$content = get_the_content();

// Meta fields do evento
$data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
$data_fim = get_post_meta($post_id, 'evento_data_fim', true);
$horario_inicio = get_post_meta($post_id, 'evento_horario_inicio', true);
$horario_fim = get_post_meta($post_id, 'evento_horario_fim', true);
$preco = get_post_meta($post_id, 'evento_preco', true);
$local_id = get_post_meta($post_id, 'evento_local', true);
$organizador_id = get_post_meta($post_id, 'evento_organizador', true);
$atracoes = get_post_meta($post_id, 'evento_atracoes', true);
$link_inscricao = get_post_meta($post_id, 'evento_link_inscricao', true);
$contato = get_post_meta($post_id, 'evento_contato', true);
$tipos = get_post_meta($post_id, 'evento_tipos', true);
$publico_alvo = get_post_meta($post_id, 'evento_publico_alvo', true);

// Dados do local
$local_nome = $local_id ? get_the_title($local_id) : '';
$local_endereco = $local_id ? get_post_meta($local_id, 'lugar_endereco', true) : '';
$local_telefone = $local_id ? get_post_meta($local_id, 'lugar_telefone', true) : '';

// Dados do organizador
$organizador_nome = $organizador_id ? get_the_title($organizador_id) : '';
$organizador_contato = $organizador_id ? get_post_meta($organizador_id, 'organizador_telefone', true) : '';

// Taxonomias
$manifestacoes = get_the_terms($post_id, 'manifestacoes_culturais');
$bairros = get_the_terms($post_id, 'bairros_recife');

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_practical_info' => true,
    'show_attractions' => true,
    'show_schedule' => true,
    'show_location_details' => true,
    'show_organizer_info' => true,
    'enable_calendar_export' => true
]);

// Status do evento
$status_evento = 'futuro';
$status_class = 'bg-blue-100 text-blue-800';
$status_text = 'Evento Futuro';

if ($data_inicio) {
    $hoje = date('Y-m-d');
    $data_evento = date('Y-m-d', strtotime($data_inicio));
    
    if ($data_evento == $hoje) {
        $status_evento = 'hoje';
        $status_class = 'bg-green-100 text-green-800';
        $status_text = 'Acontece Hoje';
    } elseif ($data_evento < $hoje) {
        $status_evento = 'passado';
        $status_class = 'bg-gray-100 text-gray-800';
        $status_text = 'Evento Encerrado';
    } elseif ($data_evento == date('Y-m-d', strtotime('+1 day'))) {
        $status_evento = 'amanha';
        $status_class = 'bg-orange-100 text-orange-800';
        $status_text = 'Amanhã';
    }
}
?>

<article class="single-content-evento bg-white">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                
                <!-- Status do Evento -->
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr($status_class); ?>">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo esc_html($status_text); ?>
                    </span>
                </div>
                
                <!-- Descrição do Evento -->
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-recife-primary prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-blockquote:border-l-4 prose-blockquote:border-recife-primary prose-blockquote:bg-gray-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:text-gray-700 mb-8">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>
                
                <?php if ($args['show_attractions'] && !empty($atracoes)): ?>
                    <!-- Atrações e Artistas -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                            </svg>
                            Atrações e Artistas
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($atracoes as $atracao): ?>
                                <div class="bg-white rounded-lg p-4 border border-purple-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">
                                        <?php echo esc_html($atracao['nome'] ?? ''); ?>
                                    </h4>
                                    
                                    <?php if (!empty($atracao['horario'])): ?>
                                        <p class="text-sm text-purple-600 mb-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <?php echo esc_html($atracao['horario']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($atracao['descricao'])): ?>
                                        <p class="text-sm text-gray-600">
                                            <?php echo esc_html($atracao['descricao']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_schedule'] && ($data_inicio || $horario_inicio)): ?>
                    <!-- Programação e Cronograma -->
                    <div class="bg-blue-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Programação
                        </h3>
                        
                        <div class="space-y-4">
                            <?php if ($data_inicio): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Data do Evento</h4>
                                        <p class="text-gray-600">
                                            <?php 
                                            echo date('d/m/Y', strtotime($data_inicio));
                                            if ($data_fim && $data_fim !== $data_inicio) {
                                                echo ' até ' . date('d/m/Y', strtotime($data_fim));
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($horario_inicio): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Horário</h4>
                                        <p class="text-gray-600">
                                            <?php 
                                            echo esc_html($horario_inicio);
                                            if ($horario_fim && $horario_fim !== $horario_inicio) {
                                                echo ' às ' . esc_html($horario_fim);
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($preco): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Preço</h4>
                                        <p class="text-gray-600"><?php echo esc_html($preco); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Manifestações Culturais -->
                <?php if (!empty($manifestacoes)): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Manifestações Culturais</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($manifestacoes as $manifestacao): ?>
                                <a href="<?php echo get_term_link($manifestacao); ?>" 
                                   class="inline-flex items-center gap-1 bg-recife-primary/10 text-recife-primary px-3 py-1 rounded-full text-sm font-medium hover:bg-recife-primary/20 transition-colors">
                                    🎭 <?php echo esc_html($manifestacao->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Galeria de Imagens -->
                <?php
                $gallery_images = get_post_meta($post_id, 'evento_galeria', true);
                if (!empty($gallery_images)):
                ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Galeria de Fotos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <?php foreach ($gallery_images as $image_id): 
                                $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                ?>
                                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer" 
                                     onclick="openLightbox('<?php echo esc_url($image_url); ?>', '<?php echo esc_attr($image_alt); ?>')">
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                         alt="<?php echo esc_attr($image_alt); ?>"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar com Informações Práticas -->
            <div class="lg:col-span-1">
                
                <?php if ($args['show_practical_info']): ?>
                    <!-- Informações Práticas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Informações Práticas
                        </h3>
                        
                        <div class="space-y-4">
                            
                            <!-- Botões de Ação -->
                            <div class="space-y-3">
                                <?php if ($link_inscricao): ?>
                                    <a href="<?php echo esc_url($link_inscricao); ?>" 
                                       target="_blank"
                                       class="w-full flex items-center justify-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                        Comprar Ingressos
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($args['enable_calendar_export'] && $data_inicio): ?>
                                    <button type="button" 
                                            onclick="addToCalendar()"
                                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Adicionar ao Calendário
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($local_endereco): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($local_endereco); ?>" 
                                       target="_blank"
                                       class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ver no Mapa
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Detalhes do Local -->
                            <?php if ($args['show_location_details'] && $local_nome): ?>
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Local</h4>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p class="font-medium"><?php echo esc_html($local_nome); ?></p>
                                        <?php if ($local_endereco): ?>
                                            <p class="flex items-start gap-2">
                                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <?php echo esc_html($local_endereco); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($local_telefone): ?>
                                            <p class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                                </svg>
                                                <a href="tel:<?php echo esc_attr($local_telefone); ?>" 
                                                   class="hover:text-recife-primary transition-colors">
                                                    <?php echo esc_html($local_telefone); ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Organizador -->
                            <?php if ($args['show_organizer_info'] && $organizador_nome): ?>
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Organizador</h4>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p class="font-medium"><?php echo esc_html($organizador_nome); ?></p>
                                        <?php if ($organizador_contato): ?>
                                            <p class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                                </svg>
                                                <a href="tel:<?php echo esc_attr($organizador_contato); ?>" 
                                                   class="hover:text-recife-primary transition-colors">
                                                    <?php echo esc_html($organizador_contato); ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($contato): ?>
                                            <p class="text-xs"><?php echo esc_html($contato); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Público-Alvo -->
                            <?php if ($publico_alvo): ?>
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Público-Alvo</h4>
                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">
                                        <?php echo esc_html($publico_alvo); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Mapa do Local -->
                <?php if ($local_id): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Localização</h3>
                        <?php echo do_shortcode('[recifemais_map post_id="' . $local_id . '" height="250px" zoom="16"]'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>

<script>
function addToCalendar() {
    const eventData = {
        title: '<?php echo esc_js(get_the_title()); ?>',
        start: '<?php echo $data_inicio ? date('Y-m-d', strtotime($data_inicio)) : ''; ?>',
        startTime: '<?php echo esc_js($horario_inicio); ?>',
        end: '<?php echo $data_fim ? date('Y-m-d', strtotime($data_fim)) : ''; ?>',
        endTime: '<?php echo esc_js($horario_fim); ?>',
        location: '<?php echo esc_js($local_endereco); ?>',
        description: '<?php echo esc_js(wp_strip_all_tags(get_the_excerpt())); ?>'
    };
    
    // Criar URL do Google Calendar
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventData.title)}&dates=${eventData.start.replace(/-/g, '')}/${eventData.end.replace(/-/g, '')}&location=${encodeURIComponent(eventData.location)}&details=${encodeURIComponent(eventData.description)}`;
    
    window.open(googleCalendarUrl, '_blank');
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'add_to_calendar', {
            'event_id': '<?php echo get_the_ID(); ?>',
            'event_title': eventData.title
        });
    }
}

function openLightbox(imageUrl, imageAlt) {
    // Implementar lightbox para galeria
    console.log('Abrir lightbox:', imageUrl, imageAlt);
}
</script> 