# Header Moderno RecifeMais - Documentação

## Visão Geral

O header moderno do RecifeMais foi desenvolvido seguindo os princípios do nosso Design System, oferecendo uma experiência de navegação profissional e intuitiva inspirada em portais de notícias de referência como Globo.com.

## Estrutura de Arquivos

```
themes/recifemais-tema/
├── components/layout/header.php    # Componente principal
├── css/header.css                  # Estilos específicos
├── js/header.js                    # Funcionalidades JavaScript
├── header.php                      # Template principal
└── HEADER-DOCS.md                  # Esta documentação
```

## Características Principais

### 🎨 Design System
- **Cores**: Paleta RecifeMais (vermelho #E11D48, azul #0EA5E9, cinzas)
- **Tipografia**: Hierárquica com pesos definidos
- **Espaçamento**: Grid system responsivo
- **Componentes**: Modulares e reutilizáveis

### 📱 Responsividade
- **Desktop**: Navegação horizontal com dropdowns
- **Tablet**: Layout adaptado com busca simplificada
- **Mobile**: Menu hambúrguer com navegação vertical

### ⚡ Performance
- **Lazy Loading**: Recursos carregados sob demanda
- **Throttled Events**: Scroll otimizado
- **CSS Containment**: Isolamento de layout
- **JavaScript Modular**: Classes ES6 organizadas

## Componentes

### 1. Top Bar
```php
// Configuração
$header_config['show_top_bar'] = true;
```

**Funcionalidades:**
- Informações de contato (email, localização)
- Relógio em tempo real
- Links para redes sociais
- Responsivo (oculto em mobile)

### 2. Logo e Branding
```php
// Logo profissional com gradiente
<div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-recife-primary to-recife-secondary">
```

**Características:**
- Logo responsivo com efeito hover
- Gradiente animado
- Tipografia hierárquica
- Acessibilidade completa

### 3. Navegação Desktop
```php
// Menu estruturado
$main_menu = [
    'inicio' => [...],
    'descubra' => ['submenu' => [...]],
    // ...
];
```

**Funcionalidades:**
- Dropdowns animados
- Indicadores visuais de estado ativo
- Hover effects suaves
- Navegação por teclado

### 4. Sistema de Busca
```javascript
// Busca em tempo real
async performLiveSearch(query, input) {
    // Implementação AJAX
}
```

**Características:**
- Autocomplete em tempo real
- Busca em múltiplos CPTs
- Navegação por teclado
- Debounce para performance

### 5. Menu Mobile
```javascript
// Controle do menu mobile
toggleMobileMenu() {
    // Animações e estados
}
```

**Funcionalidades:**
- Animações fluidas
- Submenus expansíveis
- Prevenção de scroll do body
- Fechamento automático

## Configurações

### Header Config
```php
$header_config = [
    'show_top_bar' => true,        // Exibir barra superior
    'show_social' => true,         // Links sociais
    'show_search' => true,         // Campo de busca
    'show_newsletter_cta' => true, // CTA newsletter
    'sticky' => true               // Header fixo
];
```

### Dados de Contato
```php
$contact_info = [
    'email' => 'contato@recifemais.com.br',
    'location' => 'Recife, PE',
    'phone' => '(81) 9999-9999'
];
```

### Redes Sociais
```php
$social_links = [
    'instagram' => 'https://instagram.com/recifemais',
    'facebook' => 'https://facebook.com/recifemais',
    'twitter' => 'https://twitter.com/recifemais',
    'youtube' => 'https://youtube.com/recifemais',
    'whatsapp' => 'https://wa.me/5581999999999'
];
```

## Funcionalidades JavaScript

### Classe Principal
```javascript
class RecifeMaisHeader {
    constructor() {
        this.header = document.getElementById('masthead');
        this.init();
    }
}
```

### Métodos Principais

#### 1. Menu Mobile
```javascript
setupMobileMenu()     // Configuração inicial
toggleMobileMenu()    // Toggle do menu
openMobileMenu()      // Abrir menu
closeMobileMenu()     // Fechar menu
toggleSubmenu()       // Submenus
```

#### 2. Sistema de Busca
```javascript
setupSearch()         // Configuração
performLiveSearch()   // Busca AJAX
showSuggestions()     // Exibir resultados
hideSuggestions()     // Ocultar resultados
```

#### 3. Comportamento de Scroll
```javascript
setupScrollBehavior() // Configuração
updateHeaderOnScroll() // Auto-hide/show
```

#### 4. Analytics
```javascript
setupAnalytics()      // Configuração
trackEvent()          // Tracking de eventos
```

## Estilos CSS

### Variáveis CSS
```css
:root {
  --header-height: 80px;
  --header-height-mobile: 64px;
  --top-bar-height: 40px;
  --header-shadow: 0 2px 15px -3px rgba(0, 0, 0, 0.07);
  --header-backdrop: rgba(255, 255, 255, 0.95);
}
```

### Classes Principais
```css
.site-header          // Container principal
.nav-link             // Links de navegação
.dropdown-menu        // Menus dropdown
.mobile-menu          // Menu mobile
.search-form          // Formulário de busca
.social-link          // Links sociais
```

### Animações
```css
@keyframes slideDown     // Entrada do header
@keyframes fadeInUp      // Itens do menu mobile
@keyframes pulse         // Badges e indicadores
@keyframes shimmer       // Loading states
```

## Handlers AJAX

### Busca em Tempo Real
```php
function recifemais_live_search() {
    // Verifica nonce
    // Sanitiza query
    // Busca em CPTs
    // Retorna JSON
}
```

**Endpoint:** `wp-admin/admin-ajax.php?action=recifemais_live_search`

**Parâmetros:**
- `query`: Termo de busca
- `nonce`: Token de segurança

**Resposta:**
```json
{
    "success": true,
    "data": [
        {
            "title": "Título do resultado",
            "url": "/permalink/",
            "type": "Evento",
            "date": "01/01/2024",
            "excerpt": "Resumo do conteúdo..."
        }
    ]
}
```

## Acessibilidade (WCAG 2.1)

### Navegação por Teclado
- **Tab**: Navegação sequencial
- **Enter/Space**: Ativação de elementos
- **Escape**: Fechar menus/modais
- **Arrow Keys**: Navegação em dropdowns

### ARIA Labels
```html
<button aria-label="Abrir menu de navegação" aria-expanded="false">
<nav role="navigation" aria-label="Menu Principal">
<form role="search">
```

### Skip Links
```html
<a class="skip-link" href="#main">Pular para o conteúdo principal</a>
```

### Estados de Foco
```css
.nav-link:focus {
    outline: 2px solid var(--recife-primary);
    outline-offset: 2px;
}
```

## Responsividade

### Breakpoints
```css
/* Mobile First */
@media (max-width: 767px)   // Mobile
@media (max-width: 1023px)  // Tablet
@media (min-width: 1024px)  // Desktop
```

### Adaptações Mobile
- Top bar oculta
- Menu hambúrguer
- Busca simplificada
- Navegação vertical
- Touch-friendly targets

## Performance

### Otimizações CSS
```css
.site-header {
    will-change: transform;
    contain: layout style paint;
}
```

### Otimizações JavaScript
```javascript
// Throttled scroll
const handleScroll = throttle(() => {
    this.updateHeaderOnScroll();
}, 16);

// Debounced search
const searchTimeout = debounce(() => {
    this.performLiveSearch(query);
}, 300);
```

### Lazy Loading
- Ícones SVG inline
- Scripts carregados sob demanda
- Imagens com loading="lazy"

## Analytics e Tracking

### Eventos Trackados
```javascript
// Navegação
trackEvent('nav_click', { link_text, link_url });

// Busca
trackEvent('search_submit', { query });

// Menu mobile
trackEvent('mobile_menu_open');

// Redes sociais
trackEvent('social_click', { platform });
```

### Integração
- **Google Analytics 4**: gtag()
- **Facebook Pixel**: fbq()
- **Console Logging**: Debug mode

## Segurança

### Nonce Verification
```php
wp_verify_nonce($_GET['nonce'], 'recifemais_ajax_nonce')
```

### Sanitização
```php
$query = sanitize_text_field($_GET['query']);
$email = sanitize_email($_POST['email']);
```

### Escape de Output
```php
echo esc_html($title);
echo esc_url($link);
echo esc_attr($class);
```

## Customização

### Modificar Menu
```php
// Em functions.php ou child theme
function custom_header_menu($main_menu) {
    $main_menu['custom'] = [
        'label' => 'Custom',
        'url' => '/custom/',
        'submenu' => [...]
    ];
    return $main_menu;
}
add_filter('recifemais_header_menu', 'custom_header_menu');
```

### Adicionar Estilos
```css
/* Em style.css ou arquivo CSS customizado */
.site-header.custom-variant {
    background: linear-gradient(135deg, #custom1, #custom2);
}
```

### Hooks Disponíveis
```php
// Actions
do_action('recifemais_header_before');
do_action('recifemais_header_after');
do_action('recifemais_mobile_menu_before');

// Filters
apply_filters('recifemais_header_config', $config);
apply_filters('recifemais_header_menu', $menu);
apply_filters('recifemais_social_links', $links);
```

## Troubleshooting

### Problemas Comuns

#### Menu Mobile Não Abre
```javascript
// Verificar se elementos existem
if (!this.mobileMenuToggle || !this.mobileMenu) return;

// Verificar console para erros
console.log('🎭 RecifeMais Header inicializado');
```

#### Busca Não Funciona
```php
// Verificar AJAX handler
add_action('wp_ajax_recifemais_live_search', 'recifemais_live_search');
add_action('wp_ajax_nopriv_recifemais_live_search', 'recifemais_live_search');

// Verificar nonce
wp_create_nonce('recifemais_ajax_nonce');
```

#### Estilos Não Carregam
```php
// Verificar enqueue
wp_enqueue_style('recifemais-header', 
    RECIFEMAIS_THEME_URI . '/css/header.css');

// Verificar caminho do arquivo
file_exists(RECIFEMAIS_THEME_DIR . '/css/header.css');
```

### Debug Mode
```javascript
// Ativar debug
window.recifemais_debug = true;

// Logs detalhados
console.log('📊 Event tracked:', eventName, properties);
```

## Manutenção

### Atualizações Regulares
1. **Testar responsividade** em diferentes dispositivos
2. **Verificar performance** com ferramentas de dev
3. **Validar acessibilidade** com screen readers
4. **Monitorar analytics** para otimizações

### Backup de Configurações
```php
// Exportar configurações
$header_settings = [
    'config' => $header_config,
    'menu' => $main_menu,
    'social' => $social_links,
    'contact' => $contact_info
];

update_option('recifemais_header_backup', $header_settings);
```

## Roadmap

### Próximas Funcionalidades
- [ ] **Mega Menu**: Dropdowns com múltiplas colunas
- [ ] **Dark Mode**: Alternância de tema
- [ ] **Notificações**: Sistema de alertas
- [ ] **Personalização**: Customizer integration
- [ ] **PWA**: Service worker integration

### Melhorias Planejadas
- [ ] **Performance**: Otimização adicional
- [ ] **A11y**: Melhorias de acessibilidade
- [ ] **SEO**: Structured data
- [ ] **Analytics**: Eventos mais detalhados

---

**Versão:** 2.0  
**Última Atualização:** Janeiro 2025  
**Compatibilidade:** WordPress 6.0+, PHP 8.0+ 