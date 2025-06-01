<?php
/**
 * Section Agenda - Homepage RecifeMais
 * Seção de agenda cultural com eventos e festivais
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações da seção
$agenda_config = [
    'show_section' => true,
    'events_limit' => 8,
    'show_calendar' => true,
    'show_filters' => true,
    'days_ahead' => 30
];

// Data atual e período
$today = new DateTime();
$end_date = clone $today;
$end_date->add(new DateInterval('P' . $agenda_config['days_ahead'] . 'D'));

// Buscar eventos próximos
$upcoming_events = new WP_Query([
    'post_type' => 'eventos_festivais',
    'posts_per_page' => $agenda_config['events_limit'],
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'evento_data_inicio',
            'value' => $today->format('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE'
        ],
        [
            'key' => 'evento_data_inicio',
            'value' => $end_date->format('Y-m-d'),
            'compare' => '<=',
            'type' => 'DATE'
        ]
    ],
    'meta_key' => 'evento_data_inicio',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'post_status' => 'publish'
]);

// Se não houver eventos próximos, buscar os mais recentes
if (!$upcoming_events->have_posts()) {
    $upcoming_events = new WP_Query([
        'post_type' => 'eventos_festivais',
        'posts_per_page' => $agenda_config['events_limit'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
}

// Categorias de eventos
$event_categories = get_terms([
    'taxonomy' => 'tipos_eventos',
    'hide_empty' => true,
    'number' => 6
]);

// Eventos de hoje
$today_events = new WP_Query([
    'post_type' => 'eventos_festivais',
    'posts_per_page' => 3,
    'meta_query' => [
        [
            'key' => 'evento_data_inicio',
            'value' => $today->format('Y-m-d'),
            'compare' => '=',
            'type' => 'DATE'
        ]
    ],
    'post_status' => 'publish'
]);

if (!$upcoming_events->have_posts()) return;
?>

<section class="section-agenda py-12 lg:py-16 bg-white" role="region" aria-label="Agenda Cultural">
    <div class="container mx-auto px-4">
        
        <!-- Cabeçalho da Seção -->
        <div class="section-header mb-12 lg:mb-16">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between">
                <div class="mb-8 lg:mb-0">
                    <div class="flex items-center mb-4">
                        <div class="w-1 h-8 bg-recife-warning mr-3"></div>
                        <span class="text-sm font-semibold text-recife-warning uppercase tracking-wide">
                            Agenda Cultural
                        </span>
                        <?php if ($today_events->have_posts()) : ?>
                        <span class="ml-3 px-3 py-1 bg-recife-warning text-white text-xs font-bold rounded-full animate-pulse">
                            HOJE
                        </span>
                        <?php endif; ?>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-recife-gray-900 mb-4">
                        Próximos Eventos
                    </h2>
                    <p class="text-lg text-recife-gray-600 max-w-2xl">
                        Não perca os melhores eventos culturais de Pernambuco. 
                        Música, teatro, exposições e muito mais esperando por você.
                    </p>
                </div>
                
                <!-- Mini Calendário -->
                <?php if ($agenda_config['show_calendar']) : ?>
                <div class="mini-calendar bg-recife-gray-50 rounded-xl p-6 shadow-sm">
                    <div class="text-center mb-4">
                        <div class="text-2xl font-bold text-recife-primary">
                            <?php echo $today->format('d'); ?>
                        </div>
                        <div class="text-sm text-recife-gray-600 uppercase tracking-wide">
                            <?php echo strftime('%B %Y', $today->getTimestamp()); ?>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-semibold text-recife-gray-900 mb-1">
                            <?php echo $upcoming_events->found_posts; ?> eventos
                        </div>
                        <div class="text-xs text-recife-gray-500">
                            próximos <?php echo $agenda_config['days_ahead']; ?> dias
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Eventos de Hoje (se houver) -->
        <?php if ($today_events->have_posts()) : ?>
        <div class="today-events mb-12">
            <div class="bg-gradient-to-r from-recife-warning to-recife-warning-dark rounded-2xl p-6 lg:p-8 text-white">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl lg:text-2xl font-bold mb-2">
                            🎉 Acontece Hoje
                        </h3>
                        <p class="text-white/90">
                            Eventos imperdíveis para hoje, <?php echo $today->format('d/m/Y'); ?>
                        </p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="grid md:grid-cols-3 gap-4">
                    <?php while ($today_events->have_posts()) : $today_events->the_post(); 
                        $evento_horario = get_post_meta(get_the_ID(), 'evento_horario', true);
                        $evento_local = get_post_meta(get_the_ID(), 'evento_local', true);
                    ?>
                    
                    <div class="today-event-card bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition-colors">
                        <div class="flex items-start gap-3">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden">
                                <?php the_post_thumbnail('thumbnail', [
                                    'class' => 'w-full h-full object-cover',
                                    'loading' => 'eager'
                                ]); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-white mb-1 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:underline">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($evento_horario) : ?>
                                <div class="flex items-center text-sm text-white/80 mb-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo $evento_horario; ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($evento_local) : ?>
                                <div class="flex items-center text-xs text-white/70">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="line-clamp-1"><?php echo esc_html($evento_local); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Filtros de Categoria -->
        <?php if ($agenda_config['show_filters'] && !empty($event_categories)) : ?>
        <div class="event-filters mb-8">
            <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                <button class="filter-btn active px-4 py-2 rounded-full text-sm font-medium transition-all duration-300" 
                        data-filter="all">
                    Todos os Eventos
                </button>
                <?php foreach ($event_categories as $category) : ?>
                <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium transition-all duration-300" 
                        data-filter="<?php echo $category->slug; ?>">
                    <?php echo $category->name; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Grid de Eventos -->
        <div class="events-grid">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                
                <?php 
                $event_count = 0;
                while ($upcoming_events->have_posts()) : $upcoming_events->the_post();
                    $evento_data_inicio = get_post_meta(get_the_ID(), 'evento_data_inicio', true);
                    $evento_data_fim = get_post_meta(get_the_ID(), 'evento_data_fim', true);
                    $evento_horario = get_post_meta(get_the_ID(), 'evento_horario', true);
                    $evento_local = get_post_meta(get_the_ID(), 'evento_local', true);
                    $evento_preco = get_post_meta(get_the_ID(), 'evento_preco', true);
                    $evento_organizador = get_post_meta(get_the_ID(), 'evento_organizador', true);
                    $event_categories = get_the_terms(get_the_ID(), 'tipos_eventos');
                    $primary_category = $event_categories ? $event_categories[0] : null;
                    
                    // Calcular dias até o evento
                    $event_date = new DateTime($evento_data_inicio);
                    $days_until = $today->diff($event_date)->days;
                    $is_today = $event_date->format('Y-m-d') === $today->format('Y-m-d');
                    $is_tomorrow = $days_until === 1;
                ?>
                
                <article class="event-card group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2" 
                         data-category="<?php echo $primary_category ? $primary_category->slug : 'all'; ?>">
                    
                    <!-- Imagem -->
                    <div class="relative aspect-[4/3] bg-recife-gray-200 overflow-hidden">
                        <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large', [
                                'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110',
                                'loading' => $event_count < 4 ? 'eager' : 'lazy'
                            ]); ?>
                        </a>
                        <?php endif; ?>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            <?php if ($is_today) : ?>
                            <span class="px-2 py-1 bg-recife-warning text-white text-xs font-bold rounded-full animate-pulse">
                                HOJE
                            </span>
                            <?php elseif ($is_tomorrow) : ?>
                            <span class="px-2 py-1 bg-recife-secondary text-white text-xs font-bold rounded-full">
                                AMANHÃ
                            </span>
                            <?php elseif ($days_until <= 7) : ?>
                            <span class="px-2 py-1 bg-recife-primary text-white text-xs font-bold rounded-full">
                                <?php echo $days_until; ?> DIAS
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($primary_category) : ?>
                            <span class="px-2 py-1 bg-white/95 backdrop-blur-sm text-recife-gray-800 text-xs font-semibold rounded-full">
                                <?php echo $primary_category->name; ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Preço -->
                        <?php if ($evento_preco) : ?>
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 bg-recife-success text-white text-xs font-bold rounded-full">
                                <?php echo $evento_preco === '0' || strtolower($evento_preco) === 'gratuito' ? 'GRÁTIS' : 'R$ ' . $evento_preco; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Overlay Gradiente -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <!-- Conteúdo -->
                    <div class="p-6">
                        
                        <!-- Data e Horário -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-sm text-recife-primary font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <?php echo date('d/m', strtotime($evento_data_inicio)); ?>
                            </div>
                            <?php if ($evento_horario) : ?>
                            <div class="flex items-center text-sm text-recife-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?php echo $evento_horario; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Título -->
                        <h3 class="text-lg font-bold text-recife-gray-900 mb-3 group-hover:text-recife-primary transition-colors line-clamp-2">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <!-- Local -->
                        <?php if ($evento_local) : ?>
                        <div class="flex items-center text-sm text-recife-gray-500 mb-3">
                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="line-clamp-1"><?php echo esc_html($evento_local); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Resumo -->
                        <p class="text-sm text-recife-gray-600 mb-4 line-clamp-2">
                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                        </p>
                        
                        <!-- Organizador -->
                        <?php if ($evento_organizador) : ?>
                        <div class="flex items-center text-xs text-recife-gray-500 mb-4">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Por: <?php echo esc_html($evento_organizador); ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- CTA -->
                        <div class="flex items-center justify-between">
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-flex items-center text-recife-primary hover:text-recife-primary-dark font-semibold transition-colors">
                                Ver Detalhes
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            
                            <!-- Ações Rápidas -->
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 bg-recife-gray-100 hover:bg-recife-warning hover:text-white rounded-full flex items-center justify-center transition-colors group/calendar"
                                        data-event-id="<?php echo get_the_ID(); ?>"
                                        data-event-title="<?php echo esc_attr(get_the_title()); ?>"
                                        data-event-date="<?php echo $evento_data_inicio; ?>"
                                        data-event-time="<?php echo $evento_horario; ?>"
                                        data-event-location="<?php echo esc_attr($evento_local); ?>"
                                        aria-label="Adicionar ao calendário">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                
                                <button class="w-8 h-8 bg-recife-gray-100 hover:bg-recife-primary hover:text-white rounded-full flex items-center justify-center transition-colors group/share"
                                        data-share-url="<?php the_permalink(); ?>"
                                        data-share-title="<?php echo esc_attr(get_the_title()); ?>"
                                        aria-label="Compartilhar evento">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
                
                <?php 
                $event_count++;
                endwhile; 
                wp_reset_postdata(); 
                ?>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="text-center mt-12">
            <div class="inline-flex flex-col sm:flex-row gap-4">
                <a href="/agenda/" 
                   class="btn-primary inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Agenda Completa
                </a>
                <a href="/submeter-evento/" 
                   class="btn-secondary inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Divulgar Evento
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Estilos específicos da Section Agenda */
.filter-btn {
    background: white;
    color: var(--recife-gray-600);
    border: 1px solid var(--recife-gray-300);
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--recife-warning);
    color: white;
    border-color: var(--recife-warning);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
}

.event-card {
    border: 1px solid var(--recife-gray-200);
    transition: all 0.3s ease;
}

.event-card:hover {
    border-color: var(--recife-primary);
}

.event-card.hidden {
    display: none;
}

.today-event-card:hover {
    transform: translateY(-2px);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animações de entrada */
.event-card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.8s ease forwards;
}

.event-card:nth-child(1) { animation-delay: 0.1s; }
.event-card:nth-child(2) { animation-delay: 0.2s; }
.event-card:nth-child(3) { animation-delay: 0.3s; }
.event-card:nth-child(4) { animation-delay: 0.4s; }
.event-card:nth-child(5) { animation-delay: 0.5s; }
.event-card:nth-child(6) { animation-delay: 0.6s; }
.event-card:nth-child(7) { animation-delay: 0.7s; }
.event-card:nth-child(8) { animation-delay: 0.8s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estados especiais */
.event-card.added-to-calendar .group\/calendar {
    background-color: var(--recife-warning);
    color: white;
}

/* Responsividade */
@media (max-width: 768px) {
    .today-events {
        margin-bottom: 2rem;
    }
    
    .today-events .grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .event-filters {
        margin-bottom: 1.5rem;
    }
    
    .mini-calendar {
        padding: 1rem;
    }
}

/* Loading states */
.event-card.loading {
    opacity: 0.6;
    pointer-events: none;
}

.event-card.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Pulse animation para badges HOJE */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>

<script>
// JavaScript para a Section Agenda
document.addEventListener('DOMContentLoaded', function() {
    
    // Filtros de categoria
    const filterBtns = document.querySelectorAll('.filter-btn');
    const eventCards = document.querySelectorAll('.event-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Atualizar botões ativos
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar cards
            eventCards.forEach(card => {
                const category = card.dataset.category;
                const shouldShow = filter === 'all' || category === filter;
                
                if (shouldShow) {
                    card.classList.remove('hidden');
                    card.style.animation = 'fadeInUp 0.6s ease forwards';
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'filter_events', {
                    event_category: 'agenda',
                    filter_type: filter
                });
            }
        });
    });
    
    // Adicionar ao calendário
    const calendarButtons = document.querySelectorAll('[data-event-id]');
    calendarButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const eventData = {
                id: this.dataset.eventId,
                title: this.dataset.eventTitle,
                date: this.dataset.eventDate,
                time: this.dataset.eventTime,
                location: this.dataset.eventLocation
            };
            
            // Criar evento para Google Calendar
            const startDate = new Date(eventData.date + (eventData.time ? ' ' + eventData.time : ''));
            const endDate = new Date(startDate.getTime() + (2 * 60 * 60 * 1000)); // +2 horas
            
            const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventData.title)}&dates=${startDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z&location=${encodeURIComponent(eventData.location || '')}&details=${encodeURIComponent('Evento do RecifeMais - ' + window.location.origin)}`;
            
            // Abrir Google Calendar
            window.open(googleCalendarUrl, '_blank');
            
            // Feedback visual
            const card = this.closest('.event-card');
            card.classList.add('added-to-calendar');
            this.classList.add('bg-recife-warning', 'text-white');
            
            // Animação de feedback
            this.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
            
            // Salvar no localStorage
            let calendarEvents = JSON.parse(localStorage.getItem('recifemais_calendar_events') || '[]');
            if (!calendarEvents.includes(eventData.id)) {
                calendarEvents.push(eventData.id);
                localStorage.setItem('recifemais_calendar_events', JSON.stringify(calendarEvents));
            }
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'add_to_calendar', {
                    event_category: 'engagement',
                    event_id: eventData.id
                });
            }
        });
    });
    
    // Carregar eventos salvos no calendário
    const savedCalendarEvents = JSON.parse(localStorage.getItem('recifemais_calendar_events') || '[]');
    savedCalendarEvents.forEach(eventId => {
        const btn = document.querySelector(`[data-event-id="${eventId}"]`);
        if (btn) {
            const card = btn.closest('.event-card');
            card.classList.add('added-to-calendar');
            btn.classList.add('bg-recife-warning', 'text-white');
        }
    });
    
    // Compartilhamento de eventos
    document.querySelectorAll('[data-share-url]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const url = this.dataset.shareUrl;
            const title = this.dataset.shareTitle;
            
            if (navigator.share) {
                navigator.share({ 
                    title: `🎉 ${title}`,
                    text: `Confira este evento no RecifeMais!`,
                    url: url 
                });
            } else {
                // Fallback
                const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(`🎉 ${title} - Confira este evento no RecifeMais!`)}&url=${encodeURIComponent(url)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'share_event', {
                    event_category: 'engagement',
                    share_method: navigator.share ? 'native' : 'twitter'
                });
            }
        });
    });
    
    // Intersection Observer para animações
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.event-card').forEach(card => {
            card.style.animationPlayState = 'paused';
            observer.observe(card);
        });
    }
    
    // Atualizar contador de tempo real para eventos "hoje"
    function updateTimeUntilEvents() {
        const todayBadges = document.querySelectorAll('.event-card [data-event-date]');
        const now = new Date();
        
        todayBadges.forEach(badge => {
            const eventDate = new Date(badge.dataset.eventDate);
            const diffTime = eventDate - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) {
                // Evento é hoje - verificar se já passou
                const eventTime = badge.dataset.eventTime;
                if (eventTime) {
                    const [hours, minutes] = eventTime.split(':');
                    const eventDateTime = new Date(eventDate);
                    eventDateTime.setHours(parseInt(hours), parseInt(minutes));
                    
                    if (eventDateTime < now) {
                        badge.textContent = 'EM ANDAMENTO';
                        badge.classList.add('bg-recife-success');
                    }
                }
            }
        });
    }
    
    // Atualizar a cada minuto
    updateTimeUntilEvents();
    setInterval(updateTimeUntilEvents, 60000);
    
    // Lazy loading para imagens
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px'
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});
</script> 