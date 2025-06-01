# Template-Parts de Mapas - RecifeMais

Este diretório contém 6 template-parts especializados para diferentes tipos de mapas interativos no tema RecifeMais.

## 📁 Arquivos Disponíveis

### 1. `single-location.php`
**Mapa para localização única**
- Exibe um mapa focado em uma única localização
- Popup com informações detalhadas
- Botões para direções e Street View
- Suporte a diferentes tipos de marcadores

### 2. `cluster-events.php`
**Mapa com clustering para múltiplos eventos**
- Agrupamento inteligente de marcadores próximos
- Filtros dinâmicos por manifestação cultural, bairro, data
- Busca por texto em tempo real
- Estatísticas em tempo real

### 3. `route-display.php`
**Mapa para exibição de roteiros culturais**
- Pontos de interesse sequenciais numerados
- Cálculo automático de direções entre pontos
- Estimativa de tempo e distância total
- Sidebar com lista detalhada de paradas

### 4. `search-map.php`
**Mapa para busca avançada**
- Busca por texto em tempo real
- Filtros por tipo de conteúdo
- Busca geográfica por área
- Integração com sistema de busca do plugin

### 5. `interactive-filters.php`
**Filtros interativos para mapas**
- Filtros dinâmicos em tempo real
- Interface moderna e responsiva
- Integração AJAX para atualização sem reload
- Estatísticas em tempo real

### 6. `map-controls.php`
**Controles avançados para mapas**
- Controles de zoom e navegação
- Alternância de camadas
- Geolocalização do usuário
- Modo tela cheia

## 🚀 Como Usar

### Exemplo Básico - Localização Única

```php
<?php
// Em single-lugares.php ou similar
get_template_part('template-parts/maps/single-location', null, [
    'post_id' => get_the_ID(),
    'height' => '400px',
    'zoom' => 15,
    'show_directions' => true,
    'show_street_view' => true
]);
?>
```

### Exemplo - Mapa de Eventos com Clustering

```php
<?php
// Em archive-eventos_festivais.php
get_template_part('template-parts/maps/cluster-events', null, [
    'height' => '600px',
    'zoom' => 12,
    'enable_clustering' => true,
    'show_filters' => true,
    'post_types' => ['eventos_festivais'],
    'taxonomies' => ['manifestacoes_culturais', 'bairros_recife']
]);
?>
```

### Exemplo - Roteiro Cultural

```php
<?php
// Em single-roteiros.php
get_template_part('template-parts/maps/route-display', null, [
    'route_id' => get_the_ID(),
    'height' => '500px',
    'show_sidebar' => true,
    'show_directions' => true,
    'transport_mode' => 'walking'
]);
?>
```

### Exemplo - Mapa de Busca

```php
<?php
// Em search.php ou página de busca
get_template_part('template-parts/maps/search-map', null, [
    'height' => '600px',
    'show_search' => true,
    'show_filters' => true,
    'enable_clustering' => true,
    'post_types' => ['eventos_festivais', 'lugares', 'artistas']
]);
?>
```

### Exemplo - Filtros Interativos

```php
<?php
// Junto com qualquer mapa
get_template_part('template-parts/maps/interactive-filters', null, [
    'map_id' => 'main-map',
    'show_search' => true,
    'show_date_filter' => true,
    'auto_apply' => true,
    'post_types' => ['eventos_festivais', 'lugares'],
    'taxonomies' => ['manifestacoes_culturais', 'bairros_recife']
]);
?>
```

### Exemplo - Controles de Mapa

```php
<?php
// Junto com qualquer mapa
get_template_part('template-parts/maps/map-controls', null, [
    'map_id' => 'main-map',
    'show_zoom' => true,
    'show_layers' => true,
    'show_geolocation' => true,
    'show_fullscreen' => true,
    'position' => 'top-right'
]);
?>
```

## ⚙️ Configurações Disponíveis

### Configurações Comuns

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `height` | string | '400px' | Altura do mapa |
| `zoom` | int | 12 | Nível de zoom inicial |
| `center_lat` | float | -8.0476 | Latitude central (Recife) |
| `center_lng` | float | -34.8770 | Longitude central (Recife) |
| `map_id` | string | 'map' | ID único do mapa |

### Single Location

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `post_id` | int | null | ID do post para obter localização |
| `show_popup` | bool | true | Exibir popup com informações |
| `show_directions` | bool | true | Botão para direções |
| `show_street_view` | bool | true | Botão para Street View |
| `marker_type` | string | 'default' | Tipo do marcador |

### Cluster Events

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `enable_clustering` | bool | true | Ativar agrupamento de marcadores |
| `show_filters` | bool | true | Exibir filtros |
| `show_search` | bool | true | Exibir busca por texto |
| `post_types` | array | ['eventos_festivais'] | Tipos de post a exibir |
| `taxonomies` | array | [] | Taxonomias para filtros |

### Route Display

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `route_id` | int | null | ID do roteiro |
| `show_sidebar` | bool | true | Exibir sidebar com pontos |
| `show_directions` | bool | true | Calcular direções |
| `transport_mode` | string | 'walking' | Modo de transporte |
| `show_stats` | bool | true | Exibir estatísticas da rota |

### Search Map

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `show_search` | bool | true | Exibir busca por texto |
| `show_filters` | bool | true | Exibir filtros |
| `enable_clustering` | bool | true | Ativar clustering |
| `enable_draw_search` | bool | true | Busca por área desenhada |
| `post_types` | array | [] | Tipos de post para busca |

### Interactive Filters

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `show_search` | bool | true | Filtro de busca por texto |
| `show_date_filter` | bool | true | Filtros de data |
| `show_type_filter` | bool | true | Filtros por tipo de post |
| `show_taxonomy_filters` | bool | true | Filtros por taxonomia |
| `show_radius_filter` | bool | true | Filtro de raio |
| `auto_apply` | bool | true | Aplicar filtros automaticamente |
| `enable_presets` | bool | true | Filtros predefinidos |

### Map Controls

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `show_zoom` | bool | true | Controles de zoom |
| `show_layers` | bool | true | Alternância de camadas |
| `show_geolocation` | bool | true | Botão de geolocalização |
| `show_fullscreen` | bool | true | Botão de tela cheia |
| `show_drawing` | bool | false | Ferramentas de desenho |
| `show_measure` | bool | false | Ferramentas de medição |
| `position` | string | 'top-right' | Posição dos controles |

## 🎨 Personalização CSS

Todos os template-parts usam classes CSS consistentes que podem ser personalizadas:

```css
/* Container principal do mapa */
.map-container {
    border-radius: 0.5rem;
    overflow: hidden;
}

/* Controles do mapa */
.map-controls-container {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Filtros interativos */
.interactive-filters-container {
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

/* Sidebar de roteiros */
.route-sidebar {
    background: white;
    border-radius: 0.5rem;
    max-height: 500px;
    overflow-y: auto;
}
```

## 📱 Responsividade

Todos os template-parts são totalmente responsivos:

- **Desktop**: Layout completo com todas as funcionalidades
- **Tablet**: Layout adaptado com controles otimizados
- **Mobile**: Interface simplificada com controles essenciais

## 🔧 Integração com Plugin

Os template-parts integram automaticamente com o plugin RecifeMais Core V2:

- **Meta Fields**: Coordenadas, endereços, informações de localização
- **Taxonomias**: Manifestações culturais, bairros, tipos de lugares
- **Custom Post Types**: Eventos, lugares, artistas, roteiros
- **Sistema de Busca**: Integração com busca avançada do plugin

## 📊 Analytics

Todos os template-parts incluem tracking de eventos:

```javascript
// Exemplos de eventos trackados
gtag('event', 'map_marker_click', {
    'post_id': postId,
    'post_type': postType
});

gtag('event', 'filters_applied', {
    'filter_count': filterCount,
    'results_count': resultsCount
});

gtag('event', 'route_directions_requested', {
    'route_id': routeId,
    'transport_mode': transportMode
});
```

## 🚨 Requisitos

- **WordPress**: 5.0+
- **PHP**: 7.4+
- **Plugin**: RecifeMais Core V2
- **JavaScript**: ES6+ (para funcionalidades avançadas)
- **Google Maps API**: Chave válida configurada

## 🔍 Debugging

Para debug, ative o modo de desenvolvimento:

```php
// No wp-config.php
define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);

// Logs específicos dos mapas
error_log('RecifeMais Maps Debug: ' . print_r($debug_data, true));
```

## 📝 Notas Importantes

1. **Performance**: Os mapas usam lazy loading por padrão
2. **SEO**: Coordenadas são incluídas em structured data
3. **Acessibilidade**: Todos os controles são acessíveis via teclado
4. **Segurança**: Todos os inputs são sanitizados e validados
5. **Cache**: Resultados de busca são cacheados quando possível

## 🤝 Contribuição

Para contribuir com melhorias nos template-parts:

1. Mantenha a consistência de código
2. Teste em diferentes dispositivos
3. Documente novas funcionalidades
4. Siga os padrões WordPress
5. Inclua analytics para novas interações 