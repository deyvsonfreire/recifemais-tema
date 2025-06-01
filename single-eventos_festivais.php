<?php
/**
 * Single Template: Evento/Festival
 * Inspirado no layout da referência - Design moderno e funcional
 * 
 * Utiliza TODOS os meta fields do plugin RecifeMais Core V2:
 * - evento_data_inicio, evento_data_fim
 * - evento_horario_inicio, evento_horario_fim
 * - evento_preco, evento_local, evento_organizador
 * - evento_atracoes, evento_link_inscricao, evento_contato
 * - evento_tipos, evento_publico_alvo, evento_descricao_curta
 * - evento_coordenadas_lat, evento_coordenadas_lng
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) : the_post();

// Meta fields do evento
$data_inicio = get_post_meta(get_the_ID(), 'evento_data_inicio', true);
$data_fim = get_post_meta(get_the_ID(), 'evento_data_fim', true);
$horario_inicio = get_post_meta(get_the_ID(), 'evento_horario_inicio', true);
$horario_fim = get_post_meta(get_the_ID(), 'evento_horario_fim', true);
$preco = get_post_meta(get_the_ID(), 'evento_preco', true);
$local_id = get_post_meta(get_the_ID(), 'evento_local', true);
$organizador_id = get_post_meta(get_the_ID(), 'evento_organizador', true);
$atracoes = get_post_meta(get_the_ID(), 'evento_atracoes', true);
$link_inscricao = get_post_meta(get_the_ID(), 'evento_link_inscricao', true);
$contato = get_post_meta(get_the_ID(), 'evento_contato', true);
$tipos = get_post_meta(get_the_ID(), 'evento_tipos', true);
$publico_alvo = get_post_meta(get_the_ID(), 'evento_publico_alvo', true);
$descricao_curta = get_post_meta(get_the_ID(), 'evento_descricao_curta', true);
$coordenadas_lat = get_post_meta(get_the_ID(), 'evento_coordenadas_lat', true);
$coordenadas_lng = get_post_meta(get_the_ID(), 'evento_coordenadas_lng', true);

// Dados relacionados
$local_nome = $local_id ? get_the_title($local_id) : '';
$local_endereco = $local_id ? get_post_meta($local_id, 'lugar_endereco', true) : '';
$organizador_nome = $organizador_id ? get_the_title($organizador_id) : '';

// Taxonomias
$tipos_eventos = get_the_terms(get_the_ID(), 'tipos_eventos');
$manifestacoes = get_the_terms(get_the_ID(), 'manifestacoes_culturais');
$bairros = get_the_terms(get_the_ID(), 'bairros_recife');

// Formatação de datas
$data_formatada = '';
$data_completa = '';
if ($data_inicio) {
    $data_obj = DateTime::createFromFormat('Y-m-d', $data_inicio);
    if ($data_obj) {
        $data_formatada = $data_obj->format('d/m/Y');
        $data_completa = $data_obj->format('l, d \d\e F \d\e Y');
        
        if ($data_fim && $data_fim !== $data_inicio) {
            $data_fim_obj = DateTime::createFromFormat('Y-m-d', $data_fim);
            if ($data_fim_obj) {
                $data_formatada .= ' - ' . $data_fim_obj->format('d/m/Y');
                $data_completa .= ' até ' . $data_fim_obj->format('l, d \d\e F \d\e Y');
            }
        }
    }
}

// Status do evento
$evento_status = 'upcoming';
if ($data_inicio) {
    $hoje = date('Y-m-d');
    if ($data_inicio <= $hoje && (!$data_fim || $data_fim >= $hoje)) {
        $evento_status = 'happening';
    } elseif ($data_fim && $data_fim < $hoje) {
        $evento_status = 'finished';
    }
}

?>

<main class="single-evento bg-gray-50 min-h-screen">
    
    <!-- Hero Section -->
    <section class="hero-evento relative">
        
        <!-- Imagem de fundo -->
        <div class="relative h-96 lg:h-[500px] overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" 
                     alt="<?php echo esc_attr(get_the_title()); ?>"
                     class="w-full h-full object-cover">
            <?php else : ?>
                <div class="w-full h-full bg-gradient-to-br from-red-600 via-red-700 to-red-800"></div>
            <?php endif; ?>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            
            <!-- Conteúdo sobreposto -->
            <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8 text-white">
                <div class="container mx-auto max-w-6xl">
                    
                    <!-- Breadcrumbs -->
                    <nav class="mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center gap-2 text-sm text-gray-200">
                            <li>
                                <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                    Início
                                </a>
                            </li>
                            <li class="flex items-center gap-2">
                                <span>›</span>
                                <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                                   class="hover:text-white transition-colors">
                                    Agenda Cultural
                                </a>
                            </li>
                            <li class="flex items-center gap-2">
                                <span>›</span>
                                <span class="text-white font-medium truncate">
                                    <?php echo wp_trim_words(get_the_title(), 5); ?>
                                </span>
                            </li>
                        </ol>
                    </nav>
                    
                    <!-- Status Badge -->
                    <div class="mb-4">
                        <?php if ($evento_status === 'happening') : ?>
                            <span class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                Acontecendo agora
                            </span>
                        <?php elseif ($evento_status === 'finished') : ?>
                            <span class="bg-gray-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                Evento finalizado
                            </span>
                        <?php else : ?>
                            <span class="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                Próximo evento
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Título -->
                    <h1 class="text-3xl lg:text-5xl font-bold mb-4 leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Descrição curta -->
                    <?php if ($descricao_curta) : ?>
                        <p class="text-xl text-gray-200 mb-6 max-w-3xl leading-relaxed">
                            <?php echo esc_html($descricao_curta); ?>
                        </p>
                    <?php endif; ?>
                    
                    <!-- Meta informações principais -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Data e Horário -->
                        <?php if ($data_formatada) : ?>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('calendar', '24', '#ffffff'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-200">Data</div>
                                    <div class="font-semibold"><?php echo esc_html($data_formatada); ?></div>
                                    <?php if ($horario_inicio) : ?>
                                        <div class="text-sm text-gray-200">
                                            <?php echo esc_html($horario_inicio); ?>
                                            <?php if ($horario_fim) : ?>
                                                - <?php echo esc_html($horario_fim); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Local -->
                        <?php if ($local_nome) : ?>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('map-pin', '24', '#ffffff'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-200">Local</div>
                                    <div class="font-semibold"><?php echo esc_html($local_nome); ?></div>
                                    <?php if ($local_endereco) : ?>
                                        <div class="text-sm text-gray-200"><?php echo esc_html($local_endereco); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Preço -->
                        <?php if ($preco) : ?>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('ticket', '24', '#ffffff'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-200">Entrada</div>
                                    <div class="font-semibold"><?php echo esc_html($preco); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
    </section>
    
    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Botões de Ação -->
                <div class="flex flex-wrap gap-4">
                    <?php if ($link_inscricao && $evento_status !== 'finished') : ?>
                        <a href="<?php echo esc_url($link_inscricao); ?>" 
                           target="_blank"
                           class="inline-flex items-center gap-2 bg-red-600 text-white px-8 py-4 rounded-lg hover:bg-red-700 transition-colors font-semibold text-lg">
                            <?php echo recifemais_get_icon_svg('ticket', '20', '#ffffff'); ?>
                            Garantir Ingresso
                        </a>
                    <?php endif; ?>
                    
                    <button onclick="compartilharEvento()" 
                            class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <?php echo recifemais_get_icon_svg('share', '20', '#ffffff'); ?>
                        Compartilhar
                    </button>
                    
                    <button onclick="adicionarCalendario()" 
                            class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <?php echo recifemais_get_icon_svg('calendar-plus', '20', '#ffffff'); ?>
                        Adicionar à Agenda
                    </button>
                </div>
                
                <!-- Conteúdo do Evento -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Sobre o evento</h2>
                    
                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Atrações -->
                    <?php if ($atracoes) : ?>
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Atrações</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <p class="text-gray-700 leading-relaxed"><?php echo nl2br(esc_html($atracoes)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mapa do Local -->
                <?php if ($coordenadas_lat && $coordenadas_lng) : ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Localização</h2>
                        
                        <div class="space-y-4">
                            <!-- Endereço -->
                            <?php if ($local_nome) : ?>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                                        <?php echo recifemais_get_icon_svg('map-pin', '20', '#dc2626'); ?>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo esc_html($local_nome); ?></h3>
                                        <?php if ($local_endereco) : ?>
                                            <p class="text-gray-600"><?php echo esc_html($local_endereco); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Mapa -->
                            <div class="h-64 bg-gray-100 rounded-lg overflow-hidden">
                                <div id="evento-mapa" class="w-full h-full"></div>
                            </div>
                            
                            <!-- Botões de Navegação -->
                            <div class="flex flex-wrap gap-3">
                                <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo esc_attr($coordenadas_lat); ?>,<?php echo esc_attr($coordenadas_lng); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    Google Maps
                                </a>
                                <a href="https://waze.com/ul?ll=<?php echo esc_attr($coordenadas_lat); ?>,<?php echo esc_attr($coordenadas_lng); ?>&navigate=yes" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                                    Waze
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Eventos Relacionados -->
                <?php
                $eventos_relacionados = new WP_Query([
                    'post_type' => 'eventos_festivais',
                    'posts_per_page' => 3,
                    'post__not_in' => [get_the_ID()],
                    'meta_query' => [
                        [
                            'key' => 'evento_data_inicio',
                            'value' => date('Y-m-d'),
                            'compare' => '>='
                        ]
                    ],
                    'orderby' => 'meta_value',
                    'meta_key' => 'evento_data_inicio',
                    'order' => 'ASC'
                ]);
                
                if ($eventos_relacionados->have_posts()) :
                ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Outros eventos que podem interessar</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php while ($eventos_relacionados->have_posts()) : $eventos_relacionados->the_post(); ?>
                                <?php
                                get_template_part('components/cards/card-evento', null, [
                                    'post_id' => get_the_ID(),
                                    'variant' => 'standard',
                                    'size' => 'sm',
                                    'show_meta' => true,
                                    'show_badge' => true,
                                    'classes' => ['evento-relacionado']
                                ]);
                                ?>
                            <?php endwhile; ?>
                        </div>
                        
                        <div class="text-center mt-6">
                            <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>" 
                               class="inline-flex items-center gap-2 text-red-600 font-semibold hover:text-red-700 transition-colors">
                                Ver todos os eventos
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php 
                endif;
                wp_reset_postdata();
                ?>
                
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Informações do Evento -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informações do evento</h3>
                    
                    <div class="space-y-4">
                        
                        <!-- Data completa -->
                        <?php if ($data_completa) : ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('calendar', '16', '#dc2626'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Data</div>
                                    <div class="font-medium text-gray-900"><?php echo esc_html($data_completa); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Horário -->
                        <?php if ($horario_inicio) : ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('clock', '16', '#2563eb'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Horário</div>
                                    <div class="font-medium text-gray-900">
                                        <?php echo esc_html($horario_inicio); ?>
                                        <?php if ($horario_fim) : ?>
                                            às <?php echo esc_html($horario_fim); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Público-alvo -->
                        <?php if ($publico_alvo) : ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('users', '16', '#059669'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Público-alvo</div>
                                    <div class="font-medium text-gray-900"><?php echo esc_html($publico_alvo); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Organizador -->
                        <?php if ($organizador_nome) : ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('user', '16', '#7c3aed'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Organizador</div>
                                    <div class="font-medium text-gray-900">
                                        <a href="<?php echo get_permalink($organizador_id); ?>" 
                                           class="text-purple-600 hover:text-purple-700 transition-colors">
                                            <?php echo esc_html($organizador_nome); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Contato -->
                        <?php if ($contato) : ?>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <?php echo recifemais_get_icon_svg('phone', '16', '#ea580c'); ?>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Contato</div>
                                    <div class="font-medium text-gray-900"><?php echo esc_html($contato); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- Categorias -->
                <?php if ($tipos_eventos || $manifestacoes) : ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Categorias</h3>
                        
                        <div class="space-y-3">
                            <?php if ($tipos_eventos) : ?>
                                <div>
                                    <div class="text-sm text-gray-600 mb-2">Tipo de evento</div>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($tipos_eventos as $tipo) : ?>
                                            <a href="<?php echo get_term_link($tipo); ?>" 
                                               class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-red-200 transition-colors">
                                                <?php echo esc_html($tipo->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($manifestacoes) : ?>
                                <div>
                                    <div class="text-sm text-gray-600 mb-2">Manifestação cultural</div>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($manifestacoes as $manifestacao) : ?>
                                            <a href="<?php echo get_term_link($manifestacao); ?>" 
                                               class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                                <?php echo esc_html($manifestacao->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Newsletter -->
                <?php get_template_part('template-parts/homepage/newsletter-signup'); ?>
                
            </div>
            
        </div>
    </div>
    
</main>

<!-- JavaScript para funcionalidades -->
<script>
// Compartilhar evento
function compartilharEvento() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo esc_js(get_the_title()); ?>',
            text: '<?php echo esc_js($descricao_curta ?: wp_trim_words(get_the_excerpt(), 20)); ?>',
            url: window.location.href
        });
    } else {
        // Fallback para clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copiado para a área de transferência!');
        });
    }
}

// Adicionar ao calendário
function adicionarCalendario() {
    const evento = {
        title: '<?php echo esc_js(get_the_title()); ?>',
        start: '<?php echo esc_js($data_inicio . ($horario_inicio ? 'T' . $horario_inicio : '')); ?>',
        end: '<?php echo esc_js(($data_fim ?: $data_inicio) . ($horario_fim ? 'T' . $horario_fim : '')); ?>',
        location: '<?php echo esc_js($local_nome . ($local_endereco ? ', ' . $local_endereco : '')); ?>',
        description: '<?php echo esc_js($descricao_curta ?: wp_trim_words(get_the_content(), 30)); ?>'
    };
    
    // Criar URL do Google Calendar
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(evento.title)}&dates=${evento.start.replace(/[-:]/g, '')}/${evento.end.replace(/[-:]/g, '')}&location=${encodeURIComponent(evento.location)}&details=${encodeURIComponent(evento.description)}`;
    
    window.open(googleCalendarUrl, '_blank');
}

// Inicializar mapa
<?php if ($coordenadas_lat && $coordenadas_lng) : ?>
document.addEventListener('DOMContentLoaded', function() {
    // Aqui você pode integrar com Google Maps ou OpenStreetMap
    // Por enquanto, vamos usar um placeholder
    const mapaContainer = document.getElementById('evento-mapa');
    if (mapaContainer) {
        mapaContainer.innerHTML = `
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <?php echo recifemais_get_icon_svg('map-pin', '32', '#ffffff'); ?>
                    </div>
                    <p class="text-gray-600 font-medium"><?php echo esc_js($local_nome); ?></p>
                    <p class="text-gray-500 text-sm"><?php echo esc_js($local_endereco); ?></p>
                </div>
            </div>
        `;
    }
});
<?php endif; ?>
</script>

<?php
endwhile;
get_footer();
?> 