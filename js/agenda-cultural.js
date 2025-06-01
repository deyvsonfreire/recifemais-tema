/**
 * RecifeMais - Agenda Cultural Avançada
 * Sistema completo de calendário cultural com filtros inteligentes
 * 
 * @package RecifeMaisTema
 * @version 2.0.0
 */

class RecifeMaisAgendaCultural {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month'; // month, week, day, list
        this.events = [];
        this.filteredEvents = [];
        this.filters = {
            tipo: 'all',
            bairro: 'all',
            preco: 'all',
            periodo: 'all'
        };
        
        this.init();
    }

    /**
     * Inicialização do sistema
     */
    init() {
        this.bindEvents();
        this.loadEvents();
        this.renderCalendar();
        this.setupFilters();
        this.setupNotifications();
        
        // Auto-refresh a cada 5 minutos
        setInterval(() => this.loadEvents(), 300000);
    }

    /**
     * Vincula eventos do DOM
     */
    bindEvents() {
        // Navegação do calendário
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-calendar-nav]')) {
                this.handleNavigation(e.target.dataset.calendarNav);
            }
            
            if (e.target.matches('[data-view-change]')) {
                this.changeView(e.target.dataset.viewChange);
            }
            
            if (e.target.matches('[data-event-id]')) {
                this.showEventDetails(e.target.dataset.eventId);
            }
            
            if (e.target.matches('[data-add-calendar]')) {
                this.addToGoogleCalendar(e.target.dataset.addCalendar);
            }
        });

        // Filtros
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-filter]')) {
                this.updateFilter(e.target.dataset.filter, e.target.value);
            }
        });

        // Busca
        const searchInput = document.querySelector('#agenda-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchEvents(e.target.value);
            });
        }

        // Responsividade
        window.addEventListener('resize', () => {
            this.handleResize();
        });
    }

    /**
     * Carrega eventos via AJAX
     */
    async loadEvents() {
        try {
            const response = await fetch(`${recifemais_agenda.ajax_url}?action=get_agenda_events&nonce=${recifemais_agenda.nonce}`);
            const data = await response.json();
            
            if (data.success) {
                this.events = data.data.events;
                this.applyFilters();
                this.renderCalendar();
                this.updateEventsList();
            }
        } catch (error) {
            console.error('Erro ao carregar eventos:', error);
            this.showNotification('Erro ao carregar eventos', 'error');
        }
    }

    /**
     * Renderiza o calendário
     */
    renderCalendar() {
        const container = document.querySelector('#agenda-calendar');
        if (!container) return;

        switch (this.currentView) {
            case 'month':
                this.renderMonthView(container);
                break;
            case 'week':
                this.renderWeekView(container);
                break;
            case 'day':
                this.renderDayView(container);
                break;
            case 'list':
                this.renderListView(container);
                break;
        }

        this.updateNavigationInfo();
    }

    /**
     * Renderiza visualização mensal
     */
    renderMonthView(container) {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        let html = `
            <div class="calendar-month">
                <!-- Cabeçalho dos dias da semana -->
                <div class="calendar-header grid grid-cols-7 gap-1 mb-2">
                    ${['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'].map(day => 
                        `<div class="calendar-day-header text-center py-2 text-sm font-semibold text-recife-gray-600">${day}</div>`
                    ).join('')}
                </div>
                
                <!-- Grid do calendário -->
                <div class="calendar-grid grid grid-cols-7 gap-1">
        `;

        // Renderizar 6 semanas (42 dias)
        for (let i = 0; i < 42; i++) {
            const currentDay = new Date(startDate);
            currentDay.setDate(startDate.getDate() + i);
            
            const isCurrentMonth = currentDay.getMonth() === month;
            const isToday = this.isToday(currentDay);
            const dayEvents = this.getEventsForDate(currentDay);
            
            html += `
                <div class="calendar-day ${isCurrentMonth ? 'current-month' : 'other-month'} ${isToday ? 'today' : ''} 
                            min-h-[100px] p-2 border border-recife-gray-200 bg-white hover:bg-recife-gray-50 transition-colors cursor-pointer"
                     data-date="${this.formatDate(currentDay)}">
                    
                    <div class="day-number text-sm font-semibold mb-1 ${isToday ? 'text-recife-primary' : 'text-recife-gray-900'}">
                        ${currentDay.getDate()}
                    </div>
                    
                    <div class="day-events space-y-1">
                        ${dayEvents.slice(0, 3).map(event => `
                            <div class="event-mini bg-cpt-eventos text-white text-xs px-2 py-1 rounded truncate cursor-pointer hover:bg-cpt-eventos/80"
                                 data-event-id="${event.id}"
                                 title="${event.title}">
                                ${event.title}
                            </div>
                        `).join('')}
                        
                        ${dayEvents.length > 3 ? `
                            <div class="text-xs text-recife-gray-500">
                                +${dayEvents.length - 3} mais
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        html += `
                </div>
            </div>
        `;

        container.innerHTML = html;
    }

    /**
     * Renderiza visualização semanal
     */
    renderWeekView(container) {
        const startOfWeek = this.getStartOfWeek(this.currentDate);
        const days = [];
        
        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(startOfWeek.getDate() + i);
            days.push(day);
        }

        let html = `
            <div class="calendar-week">
                <div class="week-header grid grid-cols-7 gap-2 mb-4">
                    ${days.map(day => `
                        <div class="week-day-header text-center">
                            <div class="text-sm font-semibold text-recife-gray-600">
                                ${day.toLocaleDateString('pt-BR', { weekday: 'short' })}
                            </div>
                            <div class="text-lg font-bold ${this.isToday(day) ? 'text-recife-primary' : 'text-recife-gray-900'}">
                                ${day.getDate()}
                            </div>
                        </div>
                    `).join('')}
                </div>
                
                <div class="week-grid grid grid-cols-7 gap-2">
                    ${days.map(day => {
                        const dayEvents = this.getEventsForDate(day);
                        return `
                            <div class="week-day min-h-[300px] border border-recife-gray-200 rounded-lg p-3 bg-white">
                                <div class="day-events space-y-2">
                                    ${dayEvents.map(event => `
                                        <div class="event-card bg-cpt-eventos text-white p-2 rounded cursor-pointer hover:bg-cpt-eventos/80"
                                             data-event-id="${event.id}">
                                            <div class="font-semibold text-sm">${event.title}</div>
                                            <div class="text-xs opacity-90">${event.time || ''}</div>
                                            <div class="text-xs opacity-75">${event.location || ''}</div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;

        container.innerHTML = html;
    }

    /**
     * Renderiza visualização diária
     */
    renderDayView(container) {
        const dayEvents = this.getEventsForDate(this.currentDate);
        const hours = Array.from({ length: 24 }, (_, i) => i);

        let html = `
            <div class="calendar-day-view">
                <div class="day-header text-center mb-6">
                    <h3 class="text-2xl font-bold text-recife-gray-900">
                        ${this.currentDate.toLocaleDateString('pt-BR', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}
                    </h3>
                </div>
                
                <div class="day-timeline">
                    ${hours.map(hour => {
                        const hourEvents = dayEvents.filter(event => {
                            const eventHour = event.time ? parseInt(event.time.split(':')[0]) : null;
                            return eventHour === hour;
                        });
                        
                        return `
                            <div class="hour-slot flex border-b border-recife-gray-100 min-h-[60px]">
                                <div class="hour-label w-16 text-sm text-recife-gray-500 py-2 text-right pr-4">
                                    ${hour.toString().padStart(2, '0')}:00
                                </div>
                                <div class="hour-content flex-1 py-2 pl-4">
                                    ${hourEvents.map(event => `
                                        <div class="event-block bg-cpt-eventos text-white p-3 rounded-lg mb-2 cursor-pointer hover:bg-cpt-eventos/80"
                                             data-event-id="${event.id}">
                                            <div class="font-semibold">${event.title}</div>
                                            <div class="text-sm opacity-90">${event.time || ''}</div>
                                            <div class="text-sm opacity-75">${event.location || ''}</div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;

        container.innerHTML = html;
    }

    /**
     * Renderiza visualização em lista
     */
    renderListView(container) {
        const groupedEvents = this.groupEventsByDate(this.filteredEvents);

        let html = `
            <div class="calendar-list">
                ${Object.keys(groupedEvents).map(date => `
                    <div class="date-group mb-8">
                        <h3 class="text-lg font-bold text-recife-gray-900 mb-4 border-b-2 border-recife-primary pb-2">
                            ${new Date(date).toLocaleDateString('pt-BR', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}
                        </h3>
                        
                        <div class="events-list space-y-4">
                            ${groupedEvents[date].map(event => `
                                <div class="event-list-item bg-white border border-recife-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                                     data-event-id="${event.id}">
                                    <div class="flex items-start gap-4">
                                        <div class="event-time text-center">
                                            <div class="text-sm font-semibold text-recife-primary">
                                                ${event.time || 'Todo dia'}
                                            </div>
                                        </div>
                                        
                                        <div class="event-info flex-1">
                                            <h4 class="font-bold text-recife-gray-900 mb-1">${event.title}</h4>
                                            <p class="text-sm text-recife-gray-600 mb-2">${event.excerpt || ''}</p>
                                            
                                            <div class="event-meta flex flex-wrap gap-4 text-xs text-recife-gray-500">
                                                ${event.location ? `
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        ${event.location}
                                                    </span>
                                                ` : ''}
                                                
                                                ${event.price ? `
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        ${event.price}
                                                    </span>
                                                ` : ''}
                                                
                                                ${event.type ? `
                                                    <span class="bg-cpt-eventos text-white px-2 py-1 rounded-full">
                                                        ${event.type}
                                                    </span>
                                                ` : ''}
                                            </div>
                                        </div>
                                        
                                        <div class="event-actions">
                                            <button class="text-recife-primary hover:text-recife-primary-dark"
                                                    data-add-calendar="${event.id}"
                                                    title="Adicionar ao Google Calendar">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        container.innerHTML = html;
    }

    /**
     * Configura filtros
     */
    setupFilters() {
        // Filtro rápido por período
        const periodButtons = document.querySelectorAll('[data-period-filter]');
        periodButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active de todos
                periodButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adiciona active no clicado
                button.classList.add('active');
                
                const period = button.dataset.periodFilter;
                this.filterByPeriod(period);
            });
        });

        // Filtros avançados
        this.setupAdvancedFilters();
    }

    /**
     * Configura filtros avançados
     */
    setupAdvancedFilters() {
        const advancedToggle = document.querySelector('#toggle-advanced-filters');
        const advancedPanel = document.querySelector('#advanced-filters-panel');
        
        if (advancedToggle && advancedPanel) {
            advancedToggle.addEventListener('click', () => {
                advancedPanel.classList.toggle('hidden');
                const isOpen = !advancedPanel.classList.contains('hidden');
                advancedToggle.textContent = isOpen ? 'Ocultar Filtros' : 'Filtros Avançados';
            });
        }
    }

    /**
     * Filtra eventos por período
     */
    filterByPeriod(period) {
        const now = new Date();
        let startDate, endDate;

        switch (period) {
            case 'today':
                startDate = new Date(now);
                endDate = new Date(now);
                break;
            case 'week':
                startDate = new Date(now);
                endDate = new Date(now);
                endDate.setDate(endDate.getDate() + 7);
                break;
            case 'month':
                startDate = new Date(now);
                endDate = new Date(now);
                endDate.setMonth(endDate.getMonth() + 1);
                break;
            case 'weekend':
                startDate = this.getNextWeekend();
                endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 1);
                break;
            default:
                startDate = null;
                endDate = null;
        }

        this.filters.periodo = period;
        this.applyFilters();
        this.renderCalendar();
        this.updateEventsList();
    }

    /**
     * Aplica todos os filtros
     */
    applyFilters() {
        this.filteredEvents = this.events.filter(event => {
            // Filtro por tipo
            if (this.filters.tipo !== 'all' && event.type !== this.filters.tipo) {
                return false;
            }

            // Filtro por bairro
            if (this.filters.bairro !== 'all' && event.bairro !== this.filters.bairro) {
                return false;
            }

            // Filtro por preço
            if (this.filters.preco !== 'all') {
                if (this.filters.preco === 'free' && event.price !== 'Gratuito') {
                    return false;
                }
                if (this.filters.preco === 'paid' && event.price === 'Gratuito') {
                    return false;
                }
            }

            // Filtro por período
            if (this.filters.periodo !== 'all') {
                const eventDate = new Date(event.date);
                const now = new Date();

                switch (this.filters.periodo) {
                    case 'today':
                        if (!this.isSameDay(eventDate, now)) return false;
                        break;
                    case 'week':
                        const weekEnd = new Date(now);
                        weekEnd.setDate(weekEnd.getDate() + 7);
                        if (eventDate < now || eventDate > weekEnd) return false;
                        break;
                    case 'month':
                        const monthEnd = new Date(now);
                        monthEnd.setMonth(monthEnd.getMonth() + 1);
                        if (eventDate < now || eventDate > monthEnd) return false;
                        break;
                    case 'weekend':
                        const dayOfWeek = eventDate.getDay();
                        if (dayOfWeek !== 0 && dayOfWeek !== 6) return false;
                        break;
                }
            }

            return true;
        });
    }

    /**
     * Busca eventos
     */
    searchEvents(query) {
        if (!query.trim()) {
            this.applyFilters();
        } else {
            const searchTerm = query.toLowerCase();
            this.filteredEvents = this.events.filter(event => 
                event.title.toLowerCase().includes(searchTerm) ||
                (event.location && event.location.toLowerCase().includes(searchTerm)) ||
                (event.excerpt && event.excerpt.toLowerCase().includes(searchTerm))
            );
        }
        
        this.renderCalendar();
        this.updateEventsList();
    }

    /**
     * Atualiza filtro específico
     */
    updateFilter(filterType, value) {
        this.filters[filterType] = value;
        this.applyFilters();
        this.renderCalendar();
        this.updateEventsList();
    }

    /**
     * Navegação do calendário
     */
    handleNavigation(direction) {
        switch (direction) {
            case 'prev':
                if (this.currentView === 'month') {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                } else if (this.currentView === 'week') {
                    this.currentDate.setDate(this.currentDate.getDate() - 7);
                } else if (this.currentView === 'day') {
                    this.currentDate.setDate(this.currentDate.getDate() - 1);
                }
                break;
            case 'next':
                if (this.currentView === 'month') {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                } else if (this.currentView === 'week') {
                    this.currentDate.setDate(this.currentDate.getDate() + 7);
                } else if (this.currentView === 'day') {
                    this.currentDate.setDate(this.currentDate.getDate() + 1);
                }
                break;
            case 'today':
                this.currentDate = new Date();
                break;
        }
        
        this.renderCalendar();
    }

    /**
     * Muda visualização
     */
    changeView(view) {
        this.currentView = view;
        
        // Atualiza botões de visualização
        document.querySelectorAll('[data-view-change]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-view-change="${view}"]`).classList.add('active');
        
        this.renderCalendar();
    }

    /**
     * Mostra detalhes do evento
     */
    showEventDetails(eventId) {
        const event = this.events.find(e => e.id == eventId);
        if (!event) return;

        // Criar modal ou redirecionar para página do evento
        if (event.permalink) {
            window.open(event.permalink, '_blank');
        } else {
            this.showEventModal(event);
        }
    }

    /**
     * Mostra modal do evento
     */
    showEventModal(event) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-recife-gray-900">${event.title}</h2>
                        <button class="text-recife-gray-500 hover:text-recife-gray-700" onclick="this.closest('.fixed').remove()">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        ${event.image ? `<img src="${event.image}" alt="${event.title}" class="w-full h-48 object-cover rounded-lg">` : ''}
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong>Data:</strong> ${event.date_formatted}
                            </div>
                            <div>
                                <strong>Horário:</strong> ${event.time || 'Não informado'}
                            </div>
                            <div>
                                <strong>Local:</strong> ${event.location || 'Não informado'}
                            </div>
                            <div>
                                <strong>Preço:</strong> ${event.price || 'Não informado'}
                            </div>
                        </div>
                        
                        ${event.excerpt ? `<p class="text-recife-gray-600">${event.excerpt}</p>` : ''}
                        
                        <div class="flex gap-3 pt-4">
                            <button class="bg-recife-primary text-white px-4 py-2 rounded-lg hover:bg-recife-primary-dark"
                                    onclick="window.open('${event.permalink}', '_blank')">
                                Ver Detalhes
                            </button>
                            <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                                    onclick="recifemaisAgenda.addToGoogleCalendar('${event.id}')">
                                Adicionar ao Google Calendar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    /**
     * Adiciona evento ao Google Calendar
     */
    addToGoogleCalendar(eventId) {
        const event = this.events.find(e => e.id == eventId);
        if (!event) return;

        const startDate = new Date(event.date);
        const endDate = new Date(startDate);
        endDate.setHours(endDate.getHours() + 2); // Duração padrão de 2 horas

        const googleUrl = new URL('https://calendar.google.com/calendar/render');
        googleUrl.searchParams.set('action', 'TEMPLATE');
        googleUrl.searchParams.set('text', event.title);
        googleUrl.searchParams.set('dates', 
            `${this.formatGoogleDate(startDate)}/${this.formatGoogleDate(endDate)}`
        );
        googleUrl.searchParams.set('details', event.excerpt || '');
        googleUrl.searchParams.set('location', event.location || '');

        window.open(googleUrl.toString(), '_blank');
    }

    /**
     * Configura notificações
     */
    setupNotifications() {
        // Solicitar permissão para notificações
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Verificar eventos próximos
        this.checkUpcomingEvents();
        
        // Verificar a cada hora
        setInterval(() => this.checkUpcomingEvents(), 3600000);
    }

    /**
     * Verifica eventos próximos
     */
    checkUpcomingEvents() {
        if (Notification.permission !== 'granted') return;

        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);

        const upcomingEvents = this.events.filter(event => {
            const eventDate = new Date(event.date);
            return eventDate >= now && eventDate <= tomorrow;
        });

        upcomingEvents.forEach(event => {
            const eventDate = new Date(event.date);
            const hoursUntil = Math.round((eventDate - now) / (1000 * 60 * 60));
            
            if (hoursUntil <= 24 && hoursUntil > 0) {
                this.showNotification(
                    `Evento em ${hoursUntil}h: ${event.title}`,
                    'info',
                    event.permalink
                );
            }
        });
    }

    /**
     * Mostra notificação
     */
    showNotification(message, type = 'info', link = null) {
        // Notificação do navegador
        if (Notification.permission === 'granted') {
            const notification = new Notification('RecifeMais - Agenda Cultural', {
                body: message,
                icon: '/wp-content/themes/recifemais-tema/assets/images/icon-192.png'
            });

            if (link) {
                notification.onclick = () => window.open(link, '_blank');
            }
        }

        // Notificação na página
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'success' ? 'bg-green-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove após 5 segundos
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    /**
     * Atualiza lista de eventos
     */
    updateEventsList() {
        const eventsList = document.querySelector('#events-list');
        if (!eventsList) return;

        const upcomingEvents = this.filteredEvents
            .filter(event => new Date(event.date) >= new Date())
            .slice(0, 5);

        eventsList.innerHTML = upcomingEvents.map(event => `
            <div class="event-list-item border-b border-recife-gray-200 py-3 cursor-pointer hover:bg-recife-gray-50"
                 data-event-id="${event.id}">
                <div class="flex items-center gap-3">
                    <div class="event-date text-center">
                        <div class="text-xs text-recife-gray-500">
                            ${new Date(event.date).toLocaleDateString('pt-BR', { month: 'short' }).toUpperCase()}
                        </div>
                        <div class="text-lg font-bold text-recife-primary">
                            ${new Date(event.date).getDate()}
                        </div>
                    </div>
                    <div class="event-info flex-1">
                        <h4 class="font-semibold text-sm text-recife-gray-900">${event.title}</h4>
                        <p class="text-xs text-recife-gray-600">${event.location || ''}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Atualiza informações de navegação
     */
    updateNavigationInfo() {
        const navInfo = document.querySelector('#calendar-nav-info');
        if (!navInfo) return;

        let text = '';
        switch (this.currentView) {
            case 'month':
                text = this.currentDate.toLocaleDateString('pt-BR', { 
                    year: 'numeric', 
                    month: 'long' 
                });
                break;
            case 'week':
                const startOfWeek = this.getStartOfWeek(this.currentDate);
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(endOfWeek.getDate() + 6);
                text = `${startOfWeek.getDate()} - ${endOfWeek.getDate()} de ${
                    startOfWeek.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' })
                }`;
                break;
            case 'day':
                text = this.currentDate.toLocaleDateString('pt-BR', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                break;
            case 'list':
                text = 'Lista de Eventos';
                break;
        }

        navInfo.textContent = text;
    }

    /**
     * Responsividade
     */
    handleResize() {
        const width = window.innerWidth;
        
        // Em mobile, força visualização em lista
        if (width < 768 && this.currentView !== 'list') {
            this.changeView('list');
        }
    }

    // === MÉTODOS UTILITÁRIOS ===

    /**
     * Obtém eventos para uma data específica
     */
    getEventsForDate(date) {
        return this.filteredEvents.filter(event => 
            this.isSameDay(new Date(event.date), date)
        );
    }

    /**
     * Agrupa eventos por data
     */
    groupEventsByDate(events) {
        return events.reduce((groups, event) => {
            const date = event.date;
            if (!groups[date]) {
                groups[date] = [];
            }
            groups[date].push(event);
            return groups;
        }, {});
    }

    /**
     * Verifica se é hoje
     */
    isToday(date) {
        return this.isSameDay(date, new Date());
    }

    /**
     * Verifica se duas datas são do mesmo dia
     */
    isSameDay(date1, date2) {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
    }

    /**
     * Obtém início da semana
     */
    getStartOfWeek(date) {
        const start = new Date(date);
        const day = start.getDay();
        const diff = start.getDate() - day;
        return new Date(start.setDate(diff));
    }

    /**
     * Obtém próximo fim de semana
     */
    getNextWeekend() {
        const now = new Date();
        const daysUntilSaturday = (6 - now.getDay()) % 7;
        const saturday = new Date(now);
        saturday.setDate(now.getDate() + daysUntilSaturday);
        return saturday;
    }

    /**
     * Formata data para exibição
     */
    formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    /**
     * Formata data para Google Calendar
     */
    formatGoogleDate(date) {
        return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    }
}

// Inicialização automática
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('#agenda-calendar') || document.querySelector('.agenda-cultural-container')) {
        window.recifemaisAgenda = new RecifeMaisAgendaCultural();
    }
});

// Filtros rápidos para front-page
document.addEventListener('DOMContentLoaded', function() {
    const eventFilters = document.querySelectorAll('.filter-event-btn');
    const eventsGrid = document.querySelector('#events-grid');
    
    if (eventFilters.length && eventsGrid) {
        eventFilters.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active de todos
                eventFilters.forEach(btn => btn.classList.remove('active'));
                
                // Adiciona active no clicado
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const eventCards = eventsGrid.querySelectorAll('.event-card');
                
                eventCards.forEach(card => {
                    const eventDate = new Date(card.dataset.eventDate);
                    const now = new Date();
                    let show = true;
                    
                    switch (filter) {
                        case 'today':
                            show = eventDate.toDateString() === now.toDateString();
                            break;
                        case 'week':
                            const weekEnd = new Date(now);
                            weekEnd.setDate(weekEnd.getDate() + 7);
                            show = eventDate >= now && eventDate <= weekEnd;
                            break;
                        case 'weekend':
                            const dayOfWeek = eventDate.getDay();
                            show = (dayOfWeek === 0 || dayOfWeek === 6) && eventDate >= now;
                            break;
                        case 'all':
                        default:
                            show = true;
                    }
                    
                    card.style.display = show ? 'block' : 'none';
                });
            });
        });
    }
}); 