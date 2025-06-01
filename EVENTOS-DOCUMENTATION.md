# Sistema de Eventos - RecifeMais

## Visão Geral

Sistema completo de eventos culturais inspirado no **Agenda Viva SP**, utilizando 100% dos recursos do plugin **RecifeMais Core V2** e todos os components/template-parts existentes no tema.

## Arquivos Implementados

### Templates PHP
- `archive-eventos_festivais.php` - Página de arquivo de eventos
- `single-eventos_festivais.php` - Página individual de evento

### Estilos CSS
- `css/eventos.css` - Estilos específicos para eventos

### JavaScript
- `js/eventos.js` - Funcionalidades interativas

## Funcionalidades do Archive

### Hero Section
- **Design inspirado no Agenda Viva SP**
- Gradiente vermelho com padrão de fundo
- Estatísticas dinâmicas:
  - Total de eventos cadastrados
  - Eventos acontecendo hoje
  - Número de categorias
  - Número de bairros

### Sistema de Filtros Avançado
- **Filtros disponíveis:**
  - Data (hoje, amanhã, fim de semana, próxima semana, próximo mês)
  - Categoria (tipos_eventos)
  - Bairro (bairros_recife)
  - Preço (gratuito/pago)
  - Busca textual

- **Funcionalidades:**
  - Filtros sticky com backdrop blur
  - Indicadores de filtros ativos
  - Contador de resultados
  - Reset de filtros com animação

### Grid de Eventos
- Layout responsivo (1/2/3 colunas)
- Utiliza o component `card-evento.php` existente
- Animações de entrada escalonadas
- Hover effects aprimorados

### Seção de Categorias
- Grid de categorias com ícones
- Links diretos para filtros
- Contador de eventos por categoria
- Design inspirado no Agenda Viva SP

## Funcionalidades do Single

### Hero Section
- **Imagem de destaque em fullscreen**
- Overlay com gradiente
- Breadcrumbs navegacionais
- Status badge dinâmico:
  - "Acontecendo agora" (verde com animação)
  - "Evento finalizado" (cinza)
  - "Próximo evento" (azul)

### Meta Informações Principais
- Data e horário formatados
- Local com endereço
- Preço/entrada
- Layout em grid responsivo

### Botões de Ação
- **Garantir Ingresso** (se link_inscricao disponível)
- **Compartilhar** (API nativa + fallback)
- **Adicionar à Agenda** (Google Calendar)

### Conteúdo Principal
- Descrição completa do evento
- Seção de atrações (se disponível)
- Typography otimizada com prose

### Mapa Interativo
- Integração com coordenadas
- OpenStreetMap embed
- Botões para Google Maps e Waze
- Fallback para busca por nome

### Sidebar Informativa
- **Informações detalhadas:**
  - Data completa
  - Horário
  - Público-alvo
  - Organizador (com link)
  - Contato

- **Categorias:**
  - Tipos de eventos
  - Manifestações culturais
  - Links para taxonomias

- **Newsletter signup** (reutiliza template-part)

### Eventos Relacionados
- Query inteligente de próximos eventos
- Exclusão do evento atual
- Grid responsivo com cards

## Integração com Plugin RecifeMais Core V2

### Meta Fields Utilizados
```php
// Dados básicos
'evento_data_inicio'
'evento_data_fim'
'evento_horario_inicio'
'evento_horario_fim'
'evento_preco'

// Relacionamentos
'evento_local' (ID do lugar)
'evento_organizador' (ID do organizador)

// Informações adicionais
'evento_atracoes'
'evento_link_inscricao'
'evento_contato'
'evento_tipos'
'evento_publico_alvo'
'evento_descricao_curta'

// Localização
'evento_coordenadas_lat'
'evento_coordenadas_lng'
```

### Taxonomias Utilizadas
- `tipos_eventos` - Categorias de eventos
- `manifestacoes_culturais` - Manifestações culturais
- `bairros_recife` - Localização por bairro

## Components Reutilizados

### Cards
- `components/cards/card-evento.php` - Card principal de eventos
- Variações: standard, hero, horizontal, mini
- Configurações: size, show_meta, show_badge, show_excerpt

### Template Parts
- `template-parts/homepage/newsletter-signup.php` - Newsletter
- Breadcrumbs automáticos
- Meta informações estruturadas

## Funcionalidades JavaScript

### Archive
- **Filtros sticky** com efeito blur
- **Animações de cards** com Intersection Observer
- **Loading states** para filtros
- **Scroll infinito** (preparado para AJAX)

### Single
- **Compartilhamento nativo** com fallback
- **Integração com calendário** (Google Calendar)
- **Mapa interativo** ou estático
- **Lightbox** para imagens
- **Analytics tracking** para engajamento

### Utilitários
- Sistema de notificações
- Modal de compartilhamento
- Lazy loading de imagens
- Tooltips dinâmicos

## Responsividade

### Mobile First
- Grid adaptativo
- Filtros em coluna única
- Botões full-width
- Typography escalável

### Tablet
- Grid 2 colunas para categorias
- Layout híbrido para single

### Desktop
- Background attachment fixed
- Backdrop blur aprimorado
- Hover effects completos

## Acessibilidade

### Navegação
- Focus states visíveis
- Navegação por teclado
- ARIA attributes
- Screen reader friendly

### Preferências do usuário
- High contrast mode
- Reduced motion
- Print styles otimizados

## Performance

### Otimizações
- Lazy loading de imagens
- CSS e JS condicionais
- Intersection Observer
- Debounced scroll events

### Caching
- Queries otimizadas
- Meta queries eficientes
- Transients para contadores

## SEO e Schema.org

### Meta Tags
- Open Graph para eventos
- Twitter Cards
- Event structured data
- Breadcrumbs schema

### URLs Amigáveis
- Filtros via query parameters
- Canonical URLs
- Pagination SEO-friendly

## Customização

### Cores e Branding
```css
/* Cores principais */
--evento-primary: #dc2626;
--evento-secondary: #b91c1c;
--evento-accent: #2563eb;
```

### Configurações
```php
// Número de eventos por página
$posts_per_page = 12;

// Categorias em destaque
$featured_categories = 8;

// Eventos relacionados
$related_limit = 3;
```

## Extensibilidade

### Hooks Disponíveis
- `recifemais_before_evento_content`
- `recifemais_after_evento_meta`
- `recifemais_evento_sidebar_widgets`

### Filtros
- `recifemais_evento_query_args`
- `recifemais_evento_meta_fields`
- `recifemais_evento_related_args`

## Compatibilidade

### WordPress
- Versão mínima: 5.0
- Gutenberg ready
- Multisite compatible

### Browsers
- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

### Plugins
- RecifeMais Core V2 (obrigatório)
- Rank Math SEO (integração)
- Google Analytics (tracking)

## Manutenção

### Logs e Debug
- JavaScript console logs (WP_DEBUG)
- PHP error logging
- Performance monitoring

### Atualizações
- Versionamento de assets
- Cache busting automático
- Backward compatibility

## Próximos Passos

### Melhorias Planejadas
1. **AJAX para filtros** - Filtros sem reload
2. **Google Maps API** - Mapas mais ricos
3. **Calendário visual** - Vista de calendário
4. **Favoritos** - Sistema de eventos salvos
5. **Notificações** - Alertas de novos eventos

### Integrações Futuras
- **Eventbrite** - Sincronização de ingressos
- **Facebook Events** - Importação automática
- **WhatsApp Business** - Notificações
- **PWA** - App nativo

## Conclusão

O sistema de eventos implementado oferece uma experiência completa e moderna, inspirada nas melhores práticas do Agenda Viva SP, utilizando 100% dos recursos disponíveis no plugin RecifeMais Core V2 e mantendo a consistência com o Design System do tema.

A arquitetura modular permite fácil manutenção e extensibilidade, enquanto as otimizações de performance e acessibilidade garantem uma experiência de qualidade para todos os usuários. 