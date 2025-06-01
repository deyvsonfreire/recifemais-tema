<?php
/**
 * RecifeMais - Componente Widget Agenda Cultural
 * Widget compacto para exibir próximos eventos
 * 
 * @package RecifeMaisTema
 * @version 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Argumentos padrão
$defaults = array(
    'limit' => 5,
    'show_date' => true,
    'show_location' => true,
    'show_price' => false,
    'title' => 'Próximos Eventos',
    'view_all_link' => true
);

$args = wp_parse_args($args ?? array(), $defaults);

// Obter eventos
if (!isset($events) || empty($events)) {
    $agenda = RecifeMais_Agenda_Cultural::get_instance();
    $events = $agenda->get_events(array('limit' => $args['limit']));
}
?>

<div class="agenda-widget bg-white rounded-xl shadow-lg border border-recife-gray-200 overflow-hidden">
    <!-- Cabeçalho do Widget -->
    <div class="bg-gradient-to-r from-cpt-eventos to-recife-secondary text-white p-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('calendar', '20', 'white'); ?>
                <?php echo esc_html($args['title']); ?>
            </h3>
            
            <?php if ($args['view_all_link']) : ?>
                <a href="/agenda-cultural/" 
                   class="text-white/80 hover:text-white text-sm font-medium transition-colors">
                    Ver todos →
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Lista de Eventos -->
    <div class="p-4">
        <?php if (!empty($events)) : ?>
            <div class="space-y-3">
                <?php foreach ($events as $event) : ?>
                    <article class="flex items-start gap-3 p-3 rounded-lg hover:bg-recife-gray-50 transition-colors group">
                        <!-- Data (se habilitada) -->
                        <?php if ($args['show_date'] && !empty($event['date'])) : ?>
                            <div class="flex-shrink-0 text-center">
                                <div class="w-12 h-12 bg-cpt-eventos/10 rounded-lg flex flex-col items-center justify-center">
                                    <div class="text-xs font-medium text-cpt-eventos uppercase">
                                        <?php echo date_i18n('M', strtotime($event['date'])); ?>
                                    </div>
                                    <div class="text-sm font-bold text-cpt-eventos">
                                        <?php echo date_i18n('d', strtotime($event['date'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Informações do Evento -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-recife-gray-900 text-sm line-clamp-2 group-hover:text-cpt-eventos transition-colors">
                                <a href="<?php echo esc_url($event['permalink']); ?>">
                                    <?php echo esc_html($event['title']); ?>
                                </a>
                            </h4>
                            
                            <!-- Horário -->
                            <?php if (!empty($event['time'])) : ?>
                                <div class="text-xs text-recife-gray-500 mt-1">
                                    🕐 <?php echo esc_html($event['time']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Local (se habilitado) -->
                            <?php if ($args['show_location'] && !empty($event['location'])) : ?>
                                <div class="text-xs text-recife-gray-500 mt-1 truncate">
                                    📍 <?php echo esc_html($event['location']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Preço (se habilitado) -->
                            <?php if ($args['show_price'] && !empty($event['price'])) : ?>
                                <div class="text-xs mt-1">
                                    <?php if ($event['is_free']) : ?>
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                            Gratuito
                                        </span>
                                    <?php else : ?>
                                        <span class="text-recife-gray-600">
                                            💰 <?php echo esc_html($event['price']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Tipo do Evento -->
                            <?php if (!empty($event['type'])) : ?>
                                <div class="mt-2">
                                    <span class="inline-block px-2 py-1 bg-cpt-eventos/10 text-cpt-eventos text-xs rounded-full">
                                        <?php echo esc_html($event['type']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Status do Evento -->
                        <div class="flex-shrink-0">
                            <?php if ($event['status'] === 'today') : ?>
                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Acontecendo hoje"></span>
                            <?php elseif ($event['status'] === 'upcoming') : ?>
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full" title="Próximo evento"></span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Rodapé do Widget -->
            <div class="mt-4 pt-4 border-t border-recife-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-recife-gray-500">
                        <?php echo count($events); ?> próximos eventos
                    </span>
                    
                    <div class="flex gap-2">
                        <a href="/agenda-cultural/" 
                           class="text-cpt-eventos hover:text-cpt-eventos/80 font-medium">
                            Calendário
                        </a>
                        <span class="text-recife-gray-300">|</span>
                        <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                           class="text-cpt-eventos hover:text-cpt-eventos/80 font-medium">
                            Todos
                        </a>
                    </div>
                </div>
            </div>
            
        <?php else : ?>
            <!-- Estado Vazio -->
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-recife-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <?php echo recifemais_get_icon_svg('calendar', '32', '#9ca3af'); ?>
                </div>
                <h4 class="font-semibold text-recife-gray-900 mb-2">Nenhum evento próximo</h4>
                <p class="text-sm text-recife-gray-500 mb-4">
                    Não há eventos programados para os próximos dias.
                </p>
                <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                   class="inline-flex items-center gap-2 text-cpt-eventos hover:text-cpt-eventos/80 font-medium text-sm">
                    <?php echo recifemais_get_icon_svg('search', '16'); ?>
                    Explorar todos os eventos
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.agenda-widget .truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 640px) {
    .agenda-widget {
        margin: 0 -1rem;
        border-radius: 0;
    }
}
</style> 