<?php
/**
 * RecifeMais - Componente Agenda Cultural - Calendário
 * Template para visualização completa do calendário cultural
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
    'view' => 'month',
    'show_filters' => true,
    'show_search' => true,
    'height' => 'auto'
);

$args = wp_parse_args($args ?? array(), $defaults);
?>

<div class="agenda-cultural-container fade-in">
    <!-- Cabeçalho da Agenda -->
    <div class="agenda-header">
        <h1>Agenda Cultural do Recife</h1>
        <p>Descubra os melhores eventos culturais da cidade</p>
    </div>

    <!-- Controles de Navegação -->
    <div class="agenda-controls">
        <!-- Navegação do Calendário -->
        <div class="calendar-navigation">
            <button class="nav-button" data-calendar-nav="prev" title="Anterior">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
            
            <button class="nav-button" data-calendar-nav="today" title="Hoje">
                Hoje
            </button>
            
            <button class="nav-button" data-calendar-nav="next" title="Próximo">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <!-- Informações de Navegação -->
        <div class="nav-info" id="calendar-nav-info">
            <?php echo date_i18n('F Y'); ?>
        </div>

        <!-- Seletor de Visualização -->
        <div class="view-switcher">
            <button class="view-button active" data-view-change="month">Mês</button>
            <button class="view-button" data-view-change="week">Semana</button>
            <button class="view-button" data-view-change="day">Dia</button>
            <button class="view-button" data-view-change="list">Lista</button>
        </div>
    </div>

    <!-- Layout Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Área Principal do Calendário -->
        <div class="lg:col-span-3">
            <?php if ($args['show_filters']): ?>
            <!-- Filtros -->
            <div class="agenda-filters">
                <div class="filters-header">
                    <h3 class="filters-title">Filtros</h3>
                    <button class="filters-toggle" id="toggle-advanced-filters">Filtros Avançados</button>
                </div>

                <!-- Filtros Rápidos -->
                <div class="quick-filters">
                    <button class="filter-button active" data-period-filter="all">Todos</button>
                    <button class="filter-button" data-period-filter="today">Hoje</button>
                    <button class="filter-button" data-period-filter="week">Esta Semana</button>
                    <button class="filter-button" data-period-filter="weekend">Fim de Semana</button>
                    <button class="filter-button" data-period-filter="month">Este Mês</button>
                </div>

                <!-- Filtros Avançados -->
                <div class="advanced-filters hidden" id="advanced-filters-panel">
                    <div class="filter-group">
                        <label for="filter-tipo">Tipo de Evento:</label>
                        <select id="filter-tipo" data-filter="tipo">
                            <option value="all">Todos os tipos</option>
                            <?php
                            $agenda = RecifeMais_Agenda_Cultural::get_instance();
                            $tipos = $agenda->get_event_types();
                            foreach ($tipos as $tipo) {
                                echo '<option value="' . esc_attr($tipo) . '">' . esc_html($tipo) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filter-bairro">Bairro:</label>
                        <select id="filter-bairro" data-filter="bairro">
                            <option value="all">Todos os bairros</option>
                            <?php
                            $bairros = $agenda->get_event_neighborhoods();
                            foreach ($bairros as $slug => $nome) {
                                echo '<option value="' . esc_attr($slug) . '">' . esc_html($nome) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filter-preco">Preço:</label>
                        <select id="filter-preco" data-filter="preco">
                            <option value="all">Todos os preços</option>
                            <option value="free">Gratuito</option>
                            <option value="paid">Pago</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filter-data-inicio">Data Início:</label>
                        <input type="date" id="filter-data-inicio" data-filter="start_date">
                    </div>

                    <div class="filter-group">
                        <label for="filter-data-fim">Data Fim:</label>
                        <input type="date" id="filter-data-fim" data-filter="end_date">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($args['show_search']): ?>
            <!-- Busca -->
            <div class="agenda-search">
                <div class="search-icon">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="agenda-search" 
                    class="search-input" 
                    placeholder="Buscar eventos por nome, local ou descrição..."
                    autocomplete="off"
                >
            </div>
            <?php endif; ?>

            <!-- Container do Calendário -->
            <div class="calendar-container" style="<?php echo $args['height'] !== 'auto' ? 'height: ' . esc_attr($args['height']) : ''; ?>">
                <div id="agenda-calendar">
                    <!-- Loading inicial -->
                    <div class="loading">
                        <div class="spinner"></div>
                        <span>Carregando eventos...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="agenda-sidebar">
                <!-- Próximos Eventos -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Próximos Eventos</h3>
                    <div id="events-list" class="events-list-sidebar">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Estatísticas</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" id="total-events">-</div>
                            <div class="stat-label">Total de Eventos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="events-today">-</div>
                            <div class="stat-label">Eventos Hoje</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="events-week">-</div>
                            <div class="stat-label">Esta Semana</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="free-events">-</div>
                            <div class="stat-label">Eventos Gratuitos</div>
                        </div>
                    </div>
                </div>

                <!-- Links Úteis -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Links Úteis</h3>
                    <div class="useful-links">
                        <a href="<?php echo esc_url(get_post_type_archive_link('eventos_festivais')); ?>" class="link-item">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Todos os Eventos
                        </a>
                        
                        <a href="<?php echo esc_url(get_post_type_archive_link('lugares')); ?>" class="link-item">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Locais Culturais
                        </a>
                        
                        <a href="<?php echo esc_url(get_post_type_archive_link('artistas')); ?>" class="link-item">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                            Artistas
                        </a>
                        
                        <a href="<?php echo esc_url(get_post_type_archive_link('roteiros')); ?>" class="link-item">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Roteiros Culturais
                        </a>
                    </div>
                </div>

                <!-- Notificações -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Notificações</h3>
                    <div class="notification-settings">
                        <label class="notification-toggle">
                            <input type="checkbox" id="enable-notifications">
                            <span class="toggle-slider"></span>
                            Receber notificações de eventos
                        </label>
                        <p class="notification-help">
                            Ative para receber lembretes sobre eventos próximos
                        </p>
                    </div>
                </div>

                <!-- Exportar Calendário -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Exportar</h3>
                    <div class="export-options">
                        <button class="btn btn-outline btn-sm" id="export-ical">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Baixar iCal
                        </button>
                        
                        <button class="btn btn-outline btn-sm" id="export-google">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                            </svg>
                            Google Calendar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionais para componentes específicos -->
<style>
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: var(--agenda-gray-50);
    border-radius: 0.5rem;
    border: 1px solid var(--agenda-gray-200);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--agenda-primary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--agenda-gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.useful-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.link-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    color: var(--agenda-gray-700);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: var(--agenda-transition);
    border: 1px solid var(--agenda-gray-200);
}

.link-item:hover {
    background: var(--agenda-gray-50);
    color: var(--agenda-primary);
    border-color: var(--agenda-primary);
}

.notification-toggle {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    margin-bottom: 0.5rem;
}

.notification-toggle input[type="checkbox"] {
    display: none;
}

.toggle-slider {
    position: relative;
    width: 3rem;
    height: 1.5rem;
    background: var(--agenda-gray-300);
    border-radius: 1rem;
    transition: var(--agenda-transition);
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 1.25rem;
    height: 1.25rem;
    background: white;
    border-radius: 50%;
    transition: var(--agenda-transition);
}

.notification-toggle input:checked + .toggle-slider {
    background: var(--agenda-primary);
}

.notification-toggle input:checked + .toggle-slider::before {
    transform: translateX(1.5rem);
}

.notification-help {
    font-size: 0.75rem;
    color: var(--agenda-gray-500);
    margin: 0;
}

.export-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .export-options {
        flex-direction: row;
    }
}

@media (max-width: 768px) {
    .agenda-cultural-container .grid {
        grid-template-columns: 1fr;
    }
    
    .agenda-sidebar {
        margin-top: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
// Configurações específicas do componente
document.addEventListener('DOMContentLoaded', function() {
    // Configurar notificações
    const notificationToggle = document.getElementById('enable-notifications');
    if (notificationToggle) {
        // Verificar se já está habilitado
        const isEnabled = localStorage.getItem('recifemais_notifications') === 'true';
        notificationToggle.checked = isEnabled;
        
        notificationToggle.addEventListener('change', function() {
            localStorage.setItem('recifemais_notifications', this.checked);
            
            if (this.checked && 'Notification' in window) {
                Notification.requestPermission();
            }
        });
    }
    
    // Exportar iCal
    const exportIcal = document.getElementById('export-ical');
    if (exportIcal) {
        exportIcal.addEventListener('click', function() {
            window.open('/agenda/export/ical/', '_blank');
        });
    }
    
    // Exportar Google Calendar
    const exportGoogle = document.getElementById('export-google');
    if (exportGoogle) {
        exportGoogle.addEventListener('click', function() {
            window.open('/agenda/export/google/', '_blank');
        });
    }
});
</script> 