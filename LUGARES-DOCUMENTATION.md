# Sistema de Lugares - RecifeMais

## Visão Geral

O sistema de lugares do RecifeMais é uma implementação completa para exibição e gerenciamento de locais turísticos, gastronômicos, culturais e comerciais da cidade do Recife. O sistema foi desenvolvido seguindo os mesmos princípios do sistema de eventos, garantindo consistência e reutilização máxima de componentes.

## Arquivos Principais

### Templates
- `archive-lugares.php` - Página de listagem de lugares
- `single-lugares.php` - Página individual do lugar
- `template-parts/single/meta-lugar.php` - Meta informações detalhadas

### Assets
- `css/lugares.css` - Estilos específicos para lugares
- `js/lugares.js` - JavaScript específico para lugares

### Integração
- `functions.php` - Carregamento condicional de assets
- Integração completa com plugin RecifeMais Core V2

## Funcionalidades do Archive (archive-lugares.php)

### Hero Section
- **Background dinâmico** com gradiente azul (identidade visual de lugares)
- **Estatísticas em tempo real**:
  - Total de lugares cadastrados
  - Lugares com coordenadas (para mapas)
  - Tipos de lugares disponíveis
  - Bairros cobertos
- **Animações suaves** com parallax e efeitos de hover

### Sistema de Filtros Avançado
- **Filtros disponíveis**:
  - Busca textual (nome, descrição, endereço)
  - Tipo de lugar (tipos_lugares taxonomy)
  - Bairro (bairros_recife taxonomy)
  - Faixa de preço (econômico, moderado, caro, muito caro)
  - Especialidades gastronômicas
- **Funcionalidades**:
  - Sticky filters com backdrop blur
  - Indicadores de filtros ativos
  - Contador de resultados em tempo real
  - Reset de filtros com animação
  - Responsividade completa

### Grid de Lugares
- **Layout responsivo**: 1 coluna (mobile), 2 colunas (tablet), 3 colunas (desktop)
- **Utiliza card-lugar.php existente** com todas as variações
- **Animações escalonadas** para entrada dos cards
- **Hover effects** com elevação e sombras
- **Loading states** com skeleton placeholders

### Seção de Categorias
- **Grid visual** com ícones representativos
- **Links diretos** para filtros específicos
- **Contador por categoria** em tempo real
- **Hover effects** com transformações suaves

### Paginação
- **Estilizada** seguindo design system
- **Funcional** com WordPress pagination
- **Acessível** com ARIA labels
- **Responsiva** com adaptação mobile

## Funcionalidades do Single (single-lugares.php)

### Hero Section
- **Imagem fullscreen** com overlay gradiente
- **Breadcrumbs navegáveis** com hierarquia completa
- **Status badge dinâmico** baseado em horário de funcionamento
- **Informações principais**: título, categoria, endereço
- **Zoom effect** na imagem ao hover

### Meta Informações Rápidas
- **Cards coloridos** para diferentes tipos de informação:
  - **Horário de funcionamento** (amarelo)
  - **Faixa de preço** (verde) com ícones visuais
  - **Telefone** (azul) com link direto para ligação
  - **Localização** (vermelho) com link para mapas
- **Hover effects** com elevação
- **Responsividade** adaptativa

### Botões de Ação
- **Ligar Agora** - Link direto tel: com analytics
- **Visitar Site** - Link externo com ícone
- **Compartilhar** - Web Share API com fallbacks
- **Como Chegar** - Dropdown com Google Maps e Waze
- **Animações** com shimmer effects
- **Gradientes** e sombras dinâmicas

### Conteúdo Principal
- **Descrição completa** com typography otimizada
- **Seção de especialidades** com tags visuais
- **Mapa interativo** com placeholder visual
- **Integração preparada** para Google Maps
- **Botões de navegação** para apps externos

### Sidebar Informativa
- **Meta informações detalhadas** via template-part
- **Categorias e taxonomias** com links funcionais
- **Newsletter signup** reutilizado
- **Design consistente** com outros CPTs

### Lugares Relacionados
- **Query inteligente** baseada em tipo de lugar
- **Exclusão do lugar atual**
- **Grid responsivo** com 6 lugares
- **Utiliza card-lugar.php** para consistência
- **Fallback** para quando não há relacionados

## Integração com Plugin RecifeMais Core V2

### Meta Fields Utilizados (100%)
```php
// Dados básicos
$endereco = get_post_meta(get_the_ID(), 'lugar_endereco', true);
$cep = get_post_meta(get_the_ID(), 'lugar_cep', true);
$telefone = get_post_meta(get_the_ID(), 'lugar_telefone', true);
$email = get_post_meta(get_the_ID(), 'lugar_email', true);
$website = get_post_meta(get_the_ID(), 'lugar_website', true);

// Funcionamento e localização
$horario_funcionamento = get_post_meta(get_the_ID(), 'lugar_horario_funcionamento', true);
$latitude = get_post_meta(get_the_ID(), 'lugar_latitude', true);
$longitude = get_post_meta(get_the_ID(), 'lugar_longitude', true);

// Informações comerciais
$faixa_preco = get_post_meta(get_the_ID(), 'lugar_faixa_preco', true);
$especialidades = get_post_meta(get_the_ID(), 'lugar_especialidades', true);
```

### Taxonomias Integradas
- **tipos_lugares** - Categorização principal (restaurante, hotel, atração, etc.)
- **bairros_recife** - Localização geográfica
- **categorias_lugares** - Categorias secundárias

### Dicionários Utilizados
- **Especialidades gastronômicas** com labels traduzidos
- **Faixas de preço** com ícones visuais
- **Tipos de lugares** com cores específicas

## Components Reutilizados

### Cards
- **card-lugar.php** - Component principal (100% reutilizado)
- **Variações**: standard, mini, horizontal
- **Props**: variant, size, show_meta, show_badge, show_excerpt

### Template Parts
- **template-parts/single/meta-lugar.php** - Meta informações
- **template-parts/homepage/newsletter-signup.php** - Newsletter
- **template-parts/navigation/breadcrumbs.php** - Navegação

### Layouts
- **Grid responsivo** consistente
- **Container system** padronizado
- **Spacing system** do design system

## Funcionalidades JavaScript

### Archive (LugaresArchive)
- **Sticky filters** com Intersection Observer
- **Animações de entrada** para cards
- **Search enhancements** com debounce
- **Category hovers** com transformações
- **Infinite scroll** preparado

### Single (LugaresSingle)
- **Web Share API** com fallbacks completos
- **Directions dropdown** com analytics
- **Map interactions** preparadas para Google Maps
- **Contact actions** com tracking
- **Image lightbox** preparado

### Common (LugaresCommon)
- **Lazy loading** para imagens
- **Tooltips** dinâmicos
- **Accessibility** enhancements
- **Keyboard navigation**

### Utilitários
- **Sistema de notificações** toast
- **Debug logging** condicional
- **Analytics integration** preparada
- **Error handling** robusto

## Responsividade e Acessibilidade

### Mobile First
- **Grid adaptativo**: 1/2/3 colunas conforme viewport
- **Filtros em coluna única** no mobile
- **Botões de ação empilhados** verticalmente
- **Typography escalável** com clamp()

### Acessibilidade
- **ARIA labels** em todos os elementos interativos
- **Focus states** visíveis e consistentes
- **Keyboard navigation** completa
- **Screen reader** friendly
- **High contrast mode** suportado
- **Reduced motion** respeitado

### Performance
- **CSS condicional** carregado apenas quando necessário
- **JavaScript modular** com classes separadas
- **Lazy loading** para imagens
- **Debounced events** para performance
- **Intersection Observer** para animações

## SEO e Metadados

### Schema.org
- **LocalBusiness** markup preparado
- **Place** structured data
- **Review** integration preparada
- **Opening hours** structured

### Open Graph
- **og:type** = place
- **og:title** = nome do lugar
- **og:description** = descrição otimizada
- **og:image** = imagem destacada
- **og:url** = URL canônica

### Meta Tags
- **Title** otimizado com localização
- **Description** com informações relevantes
- **Keywords** baseadas em taxonomias
- **Canonical URL** definida

## Customização e Extensibilidade

### CSS Variables
```css
:root {
    --lugares-primary: #2563eb;
    --lugares-secondary: #3b82f6;
    --lugares-accent: #93c5fd;
    --lugares-text: #1f2937;
    --lugares-bg: #f8fafc;
}
```

### JavaScript Hooks
```javascript
// Eventos customizáveis
document.addEventListener('lugares:filter:applied', function(e) {
    // Custom logic
});

document.addEventListener('lugares:share:success', function(e) {
    // Analytics tracking
});
```

### PHP Filters
```php
// Customizar query de lugares relacionados
add_filter('recifemais_lugares_relacionados_args', function($args) {
    // Modificar argumentos da query
    return $args;
});

// Customizar meta fields exibidos
add_filter('recifemais_lugares_meta_fields', function($fields) {
    // Adicionar/remover campos
    return $fields;
});
```

## Compatibilidade e Manutenção

### WordPress
- **Versão mínima**: 5.0
- **PHP**: 7.4+
- **MySQL**: 5.6+

### Plugins
- **RecifeMais Core V2**: Obrigatório
- **Rank Math**: Integração automática
- **Google Maps**: Preparado para integração

### Browsers
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+

### Testes
- **Syntax check**: `php -l` validado
- **CSS validation**: W3C compliant
- **JavaScript**: ESLint clean
- **Accessibility**: WAVE tested

## Próximos Passos e Melhorias

### Funcionalidades Planejadas
1. **Google Maps integration** completa
2. **Reviews system** com ratings
3. **Favorites** para usuários logados
4. **Advanced search** com filtros geográficos
5. **Social media** integration
6. **Booking system** para reservas

### Otimizações
1. **Service Worker** para cache
2. **WebP images** com fallback
3. **Critical CSS** inline
4. **Preload** de recursos importantes
5. **Database queries** optimization

### Analytics
1. **Heatmaps** para UX insights
2. **Conversion tracking** para ações
3. **Performance monitoring**
4. **Error tracking** automático

## Conclusão

O sistema de lugares do RecifeMais foi desenvolvido seguindo as melhores práticas de desenvolvimento WordPress, garantindo:

- **100% de integração** com o plugin RecifeMais Core V2
- **Reutilização máxima** de componentes existentes
- **Performance otimizada** com carregamento condicional
- **Acessibilidade completa** seguindo WCAG 2.1
- **SEO otimizado** com structured data
- **Responsividade** mobile-first
- **Extensibilidade** para futuras funcionalidades

O sistema está pronto para uso em produção e pode ser facilmente estendido conforme as necessidades do projeto evoluem. 