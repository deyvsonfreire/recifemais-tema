<?php
/**
 * Template Part: Hero Evento
 * 
 * Hero section específico para eventos com:
 * - Informações de data, horário e local
 * - Status do evento (próximo, hoje, encerrado)
 * - Organizador e artistas participantes
 * - Botões de ação (ingressos, calendário)
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
$title = get_the_title();
$excerpt = get_the_excerpt();
$featured_image = get_the_post_thumbnail_url($post_id, 'full');

// Meta fields do evento (usando nomes corretos do plugin)
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
$local_title = $local_id ? get_the_title($local_id) : '';
$local_endereco = $local_id ? get_post_meta($local_id, 'lugar_endereco', true) : '';
$local_latitude = $local_id ? get_post_meta($local_id, 'lugar_latitude', true) : '';
$local_longitude = $local_id ? get_post_meta($local_id, 'lugar_longitude', true) : '';

// Dados do organizador
$organizador_title = $organizador_id ? get_the_title($organizador_id) : '';

// Taxonomias
$tipos_eventos = get_the_terms($post_id, 'tipos_eventos');
$bairros = get_the_terms($post_id, 'bairros_recife');
$manifestacoes = get_the_terms($post_id, 'manifestacoes_culturais');

// Status do evento
$status_evento = 'futuro';
$status_label = 'Evento Futuro';
$status_color = 'blue';

if ($data_inicio) {
    $data_evento = DateTime::createFromFormat('Y-m-d', $data_inicio);
    $hoje = new DateTime();
    $amanha = new DateTime('+1 day');
    
    if ($data_evento->format('Y-m-d') === $hoje->format('Y-m-d')) {
        $status_evento = 'hoje';
        $status_label = 'Acontece Hoje';
        $status_color = 'red';
    } elseif ($data_evento->format('Y-m-d') === $amanha->format('Y-m-d')) {
        $status_evento = 'amanha';
        $status_label = 'Amanhã';
        $status_color = 'orange';
    } elseif ($data_evento < $hoje) {
        $status_evento = 'passado';
        $status_label = 'Evento Encerrado';
        $status_color = 'gray';
    }
}

// Formatação de datas
$data_formatada = '';
if ($data_inicio) {
    $data_obj = DateTime::createFromFormat('Y-m-d', $data_inicio);
    if ($data_fim && $data_fim !== $data_inicio) {
        $data_fim_obj = DateTime::createFromFormat('Y-m-d', $data_fim);
        $data_formatada = $data_obj->format('d/m/Y') . ' a ' . $data_fim_obj->format('d/m/Y');
    } else {
        $data_formatada = $data_obj->format('d/m/Y');
    }
}

// Horário formatado
$horario_formatado = '';
if ($horario_inicio) {
    $horario_formatado = $horario_inicio;
    if ($horario_fim) {
        $horario_formatado .= ' às ' . $horario_fim;
    }
}

// Configurações do hero
$args = wp_parse_args($args ?? [], [
    'show_breadcrumbs' => true,
    'show_social_share' => true,
    'show_map_link' => true,
    'show_calendar_button' => true,
    'layout' => 'event' // event, festival, show
]);
?>

<section class="single-hero-evento bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white relative overflow-hidden">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="4"/></g></svg>');"></div>
    </div>
    
    <?php if ($args['show_breadcrumbs']): ?>
        <!-- Breadcrumbs -->
        <div class="relative z-10 border-b border-white/20">
            <div class="container mx-auto px-4 py-3">
                <nav class="text-sm text-white/80">
                    <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Início</a>
                    <span class="mx-2">›</span>
                    <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" class="hover:text-white transition-colors">Eventos</a>
                    <span class="mx-2">›</span>
                    <span class="text-white"><?php echo esc_html($title); ?></span>
                </nav>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="relative z-10 container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                
                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 bg-<?php echo esc_attr($status_color); ?>-600 text-white px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wide">
                        <?php if ($status_evento === 'hoje'): ?>
                            <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                        <?php endif; ?>
                        <?php echo esc_html($status_label); ?>
                    </span>
                </div>
                
                <!-- Tipo/Categoria -->
                <?php if ($tipos_eventos): ?>
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 text-purple-200 text-sm font-semibold uppercase tracking-wide">
                            <span class="text-lg">🎭</span>
                            <?php echo esc_html($tipos_eventos[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <!-- Título -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    <?php echo esc_html($title); ?>
                </h1>
                
                <!-- Excerpt/Descrição -->
                <?php if ($excerpt): ?>
                    <div class="text-xl text-purple-100 leading-relaxed mb-8 max-w-3xl">
                        <?php echo esc_html($excerpt); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Informações Principais -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Data e Horário -->
                    <?php if ($data_formatada): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Data e Horário</h3>
                            </div>
                            <p class="text-purple-100 text-lg font-medium"><?php echo esc_html($data_formatada); ?></p>
                            <?php if ($horario_formatado): ?>
                                <p class="text-purple-200"><?php echo esc_html($horario_formatado); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Local -->
                    <?php if ($local_title): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Local</h3>
                            </div>
                            <p class="text-purple-100 text-lg font-medium">
                                <a href="<?php echo esc_url(get_permalink($local_id)); ?>" 
                                   class="hover:text-white transition-colors">
                                    <?php echo esc_html($local_title); ?>
                                </a>
                            </p>
                            <?php if ($local_endereco): ?>
                                <p class="text-purple-200 text-sm"><?php echo esc_html($local_endereco); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Preço -->
                    <?php if ($preco): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Ingresso</h3>
                            </div>
                            <p class="text-purple-100 text-lg font-medium"><?php echo esc_html($preco); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Organizador -->
                    <?php if ($organizador_title): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Organizador</h3>
                            </div>
                            <p class="text-purple-100 text-lg font-medium">
                                <a href="<?php echo esc_url(get_permalink($organizador_id)); ?>" 
                                   class="hover:text-white transition-colors">
                                    <?php echo esc_html($organizador_title); ?>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Manifestações Culturais -->
                <?php if ($manifestacoes): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-white mb-3">Manifestações Culturais:</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($manifestacoes as $manifestacao): ?>
                                <a href="<?php echo esc_url(get_term_link($manifestacao)); ?>" 
                                   class="inline-block bg-purple-600/50 hover:bg-purple-600/70 text-white px-3 py-1 rounded-full text-sm transition-colors">
                                    <?php echo esc_html($manifestacao->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Botões de Ação -->
                <div class="flex flex-wrap gap-4">
                    
                    <?php if ($link_inscricao): ?>
                        <a href="<?php echo esc_url($link_inscricao); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                            </svg>
                            Comprar Ingressos
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($args['show_calendar_button'] && $data_inicio): ?>
                        <button type="button" 
                                onclick="addToCalendar()"
                                class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Adicionar ao Calendário
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($args['show_map_link'] && $local_latitude && $local_longitude): ?>
                        <a href="https://www.google.com/maps?q=<?php echo esc_attr($local_latitude); ?>,<?php echo esc_attr($local_longitude); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Ver no Mapa
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar com Imagem -->
            <div class="lg:col-span-1">
                <?php if ($featured_image): ?>
                    <div class="relative">
                        <div class="aspect-square bg-white/10 rounded-xl overflow-hidden">
                            <img src="<?php echo esc_url($featured_image); ?>" 
                                 alt="<?php echo esc_attr($title); ?>"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Caption da imagem -->
                        <?php 
                        $image_caption = get_the_post_thumbnail_caption();
                        if ($image_caption): ?>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-3 rounded-b-xl">
                                <p class="text-sm"><?php echo esc_html($image_caption); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Informações Adicionais -->
                <div class="mt-6 space-y-4">
                    
                    <?php if ($publico_alvo): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Público-Alvo</h4>
                            <p class="text-purple-100"><?php echo esc_html($publico_alvo); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contato): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Contato</h4>
                            <p class="text-purple-100 text-sm"><?php echo esc_html($contato); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($bairros): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Bairro</h4>
                            <p class="text-purple-100"><?php echo esc_html($bairros[0]->name); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function addToCalendar() {
    const eventData = {
        title: '<?php echo esc_js($title); ?>',
        start: '<?php echo esc_js($data_inicio . ($horario_inicio ? 'T' . $horario_inicio : 'T09:00')); ?>',
        end: '<?php echo esc_js(($data_fim ?: $data_inicio) . ($horario_fim ? 'T' . $horario_fim : 'T18:00')); ?>',
        location: '<?php echo esc_js($local_title . ($local_endereco ? ', ' . $local_endereco : '')); ?>',
        description: '<?php echo esc_js($excerpt); ?>'
    };
    
    // Criar URL do Google Calendar
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventData.title)}&dates=${eventData.start.replace(/[-:]/g, '')}/${eventData.end.replace(/[-:]/g, '')}&location=${encodeURIComponent(eventData.location)}&details=${encodeURIComponent(eventData.description)}`;
    
    window.open(googleCalendarUrl, '_blank');
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'add_to_calendar', {
            'event_id': '<?php echo get_the_ID(); ?>',
            'event_title': eventData.title
        });
    }
}
</script> 