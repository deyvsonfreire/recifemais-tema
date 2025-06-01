<?php
/**
 * Template Name: Agenda Cultural
 * Página dedicada para o calendário cultural avançado
 * 
 * @package RecifeMaisTema
 * @version 2.0.0
 */

get_header();
?>

<main id="main" class="site-main">
    
    <!-- Hero da Agenda Cultural -->
    <section class="py-12 lg:py-16 bg-gradient-to-br from-cpt-eventos via-recife-secondary to-recife-accent text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6">
                    <?php echo recifemais_get_icon_svg('calendar', '40', 'white'); ?>
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                    Agenda Cultural do Recife
                </h1>
                
                <p class="text-xl lg:text-2xl text-white/90 mb-8 max-w-3xl mx-auto">
                    Descubra, explore e participe dos melhores eventos culturais de Pernambuco. 
                    Sua agenda cultural completa em um só lugar.
                </p>
                
                <!-- Estatísticas Rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <?php
                    // Obter estatísticas dos eventos
                    $agenda = RecifeMais_Agenda_Cultural::get_instance();
                    $total_eventos = $agenda->get_events_count();
                    $eventos_hoje = $agenda->get_events_count(['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]);
                    $eventos_semana = $agenda->get_events_count(['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d', strtotime('+7 days'))]);
                    $eventos_gratuitos = count($agenda->filter_events(['preco' => 'free']));
                    ?>
                    
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold"><?php echo $total_eventos; ?></div>
                        <div class="text-sm opacity-90">Total de Eventos</div>
                    </div>
                    
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold"><?php echo $eventos_hoje; ?></div>
                        <div class="text-sm opacity-90">Hoje</div>
                    </div>
                    
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold"><?php echo $eventos_semana; ?></div>
                        <div class="text-sm opacity-90">Esta Semana</div>
                    </div>
                    
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold"><?php echo $eventos_gratuitos; ?></div>
                        <div class="text-sm opacity-90">Gratuitos</div>
                    </div>
                </div>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#calendario" class="btn bg-white text-cpt-eventos hover:bg-recife-gray-100 font-bold">
                        <?php echo recifemais_get_icon_svg('calendar', '20'); ?>
                        <span>Ver Calendário</span>
                    </a>
                    
                    <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                       class="btn border-2 border-white text-white hover:bg-white hover:text-cpt-eventos">
                        <?php echo recifemais_get_icon_svg('list', '20'); ?>
                        <span>Lista de Eventos</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Calendário Cultural -->
    <section id="calendario" class="py-12 bg-recife-gray-50">
        <div class="container mx-auto px-4">
            <?php
            // Renderizar o componente de calendário
            $agenda = RecifeMais_Agenda_Cultural::get_instance();
            $agenda->render_agenda_calendar(array(
                'view' => 'month',
                'show_filters' => true,
                'show_search' => true,
                'height' => '600px'
            ));
            ?>
        </div>
    </section>

    <!-- Seção Informativa -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Como Usar -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <?php echo recifemais_get_icon_svg('help-circle', '32', '#3b82f6'); ?>
                    </div>
                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3">Como Usar</h3>
                    <p class="text-recife-gray-600 mb-4">
                        Navegue pelo calendário, use os filtros para encontrar eventos do seu interesse 
                        e clique nos eventos para ver mais detalhes.
                    </p>
                    <ul class="text-sm text-recife-gray-500 space-y-1">
                        <li>• Clique nos dias para ver eventos</li>
                        <li>• Use filtros por tipo, bairro e preço</li>
                        <li>• Mude entre visualizações</li>
                        <li>• Ative notificações</li>
                    </ul>
                </div>
                
                <!-- Tipos de Eventos -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                        <?php echo recifemais_get_icon_svg('tag', '32', '#7c3aed'); ?>
                    </div>
                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3">Tipos de Eventos</h3>
                    <p class="text-recife-gray-600 mb-4">
                        Encontre shows, festivais, teatro, dança, exposições, workshops e muito mais.
                    </p>
                    <div class="flex flex-wrap gap-2 justify-center">
                        <?php
                        $tipos = $agenda->get_event_types();
                        foreach (array_slice($tipos, 0, 6) as $tipo) {
                            echo '<span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">' . esc_html($tipo) . '</span>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Links Úteis -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <?php echo recifemais_get_icon_svg('external-link', '32', '#10b981'); ?>
                    </div>
                    <h3 class="text-xl font-bold text-recife-gray-900 mb-3">Explore Mais</h3>
                    <p class="text-recife-gray-600 mb-4">
                        Descubra também nossos locais culturais, artistas e roteiros turísticos.
                    </p>
                    <div class="space-y-2">
                        <a href="<?php echo get_post_type_archive_link('lugares'); ?>" 
                           class="block text-green-600 hover:text-green-700 font-medium">
                            📍 Locais Culturais
                        </a>
                        <a href="<?php echo get_post_type_archive_link('artistas'); ?>" 
                           class="block text-green-600 hover:text-green-700 font-medium">
                            🎨 Artistas
                        </a>
                        <a href="<?php echo get_post_type_archive_link('roteiros'); ?>" 
                           class="block text-green-600 hover:text-green-700 font-medium">
                            🗺️ Roteiros Culturais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-12 bg-gradient-to-r from-cpt-eventos to-recife-secondary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Não Perca Nenhum Evento!</h2>
            <p class="text-xl text-white/90 mb-6 max-w-2xl mx-auto">
                Ative as notificações e receba lembretes sobre os eventos que você não pode perder.
            </p>
            <button id="enable-notifications-cta" 
                    class="btn bg-white text-cpt-eventos hover:bg-recife-gray-100 font-bold">
                <?php echo recifemais_get_icon_svg('bell', '20'); ?>
                <span>Ativar Notificações</span>
            </button>
        </div>
    </section>

</main>

<style>
.btn {
    @apply inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ativar notificações
    const notificationBtn = document.getElementById('enable-notifications-cta');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            if ('Notification' in window) {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        localStorage.setItem('recifemais_notifications', 'true');
                        
                        // Feedback visual
                        notificationBtn.innerHTML = '✅ Notificações Ativadas';
                        notificationBtn.disabled = true;
                        
                        // Notificação de teste
                        new Notification('RecifeMais - Agenda Cultural', {
                            body: 'Notificações ativadas com sucesso! Você receberá lembretes sobre eventos próximos.',
                            icon: '/wp-content/themes/recifemais-tema/assets/images/icon-192.png'
                        });
                    }
                });
            } else {
                alert('Seu navegador não suporta notificações.');
            }
        });
        
        // Verificar se já está ativado
        if (localStorage.getItem('recifemais_notifications') === 'true') {
            notificationBtn.innerHTML = '✅ Notificações Ativadas';
            notificationBtn.disabled = true;
        }
    }
    
    // Scroll suave para o calendário
    const calendarLink = document.querySelector('a[href="#calendario"]');
    if (calendarLink) {
        calendarLink.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('calendario').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }
});
</script>

<?php get_footer(); ?> 