# Category Template - RecifeMais (Estilo Globo.com)

## Visão Geral

O `category-globo-style.php` é um template específico para páginas de categoria de posts nativos do WordPress, inspirado no design limpo e profissional da Globo.com. Este template utiliza 100% dos template-parts e components existentes no tema.

## Estrutura do Layout

### 1. Header da Categoria
- **Breadcrumbs**: Navegação contextual (Início > Notícias > Categoria)
- **Ícone da categoria**: Visual distintivo para cada editoria
- **Título e meta**: Nome da categoria, contagem de posts, data de atualização
- **Descrição**: Texto explicativo da categoria (se disponível)
- **Ações rápidas**: Botões de compartilhar e seguir categoria

### 2. Seção de Destaques
- **Query específica**: Posts marcados como destaque (`_featured_post = 1`)
- **Grid responsivo**: 3 colunas em desktop, 2 em tablet, 1 em mobile
- **Cards visuais**: Imagem, badge "DESTAQUE", título, resumo, meta
- **Hover effects**: Animações suaves e feedback visual

### 3. Lista de Todas as Notícias
- **Exclusão inteligente**: Remove posts já exibidos nos destaques
- **Layout horizontal**: Imagem + conteúdo lado a lado
- **Meta informações**: Categoria, data/hora, badges de urgência
- **Filtros rápidos**: Dropdown para ordenação (mais recentes, mais lidas, etc.)

### 4. Sidebar Contextual
- **Outras categorias**: Lista das editorias relacionadas com contadores
- **Mais lidas**: Top 5 posts mais populares da categoria
- **Newsletter**: Componente de inscrição reutilizado

## Funcionalidades JavaScript

### 1. Filtros e Ordenação
```javascript
// Ordenação dinâmica via URL parameters
initFilterSorting()
```

### 2. Scroll Infinito
```javascript
// Carregamento progressivo de posts
initInfiniteScroll()
```

### 3. Compartilhamento
```javascript
// API nativa ou fallback para clipboard
initShareButtons()
```

### 4. Seguir Categoria
```javascript
// Sistema de notificações por categoria
initFollowCategory()
```

### 5. Busca Interna
```javascript
// Busca em tempo real dentro da categoria
initSearchInCategory()
```

### 6. Modos de Visualização
```javascript
// Toggle entre lista e grid
initViewModeToggle()
```

## Integração com Template-Parts

### Template-Parts Utilizados
- `template-parts/homepage/newsletter-signup.php`
- Breadcrumbs via Rank Math
- Sistema de ícones SVG existente

### Components Reutilizados
- Sistema de cores por categoria
- Funções de tempo de leitura
- Estrutura de cards responsivos

## Estilos CSS

### Arquivo: `css/category.css`
- **Layout responsivo**: Mobile-first approach
- **Animações suaves**: Hover effects e transições
- **Acessibilidade**: Foco visível, alto contraste, movimento reduzido
- **Print styles**: Otimização para impressão
- **Dark mode**: Suporte opcional para tema escuro

### Classes Principais
```css
.category-page          /* Container principal */
.category-header        /* Cabeçalho da categoria */
.featured-posts         /* Seção de destaques */
.all-posts             /* Lista de todas as notícias */
.pagination-category    /* Paginação customizada */
```

## Funcionalidades Avançadas

### 1. Estados de Loading
- Indicadores visuais durante carregamento
- Skeleton screens para melhor UX
- Estados de erro com fallbacks

### 2. Performance
- Lazy loading de imagens
- Queries otimizadas
- Cache de preferências do usuário

### 3. Analytics
- Tracking de interações
- Métricas de engajamento
- Eventos personalizados

### 4. SEO
- Schema.org para categorias
- Meta tags otimizadas
- URLs amigáveis

## Responsividade

### Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 1023px
- **Desktop**: > 1024px

### Adaptações por Dispositivo
- **Mobile**: Layout em coluna única, navegação simplificada
- **Tablet**: Grid 2 colunas, sidebar colapsável
- **Desktop**: Layout completo com sidebar fixa

## Acessibilidade

### Recursos Implementados
- **Navegação por teclado**: Tab order lógico
- **Screen readers**: ARIA labels e landmarks
- **Alto contraste**: Suporte para preferências do sistema
- **Movimento reduzido**: Respeita prefers-reduced-motion

### Testes Recomendados
- Navegação apenas por teclado
- Teste com screen readers
- Validação de contraste de cores
- Teste em diferentes tamanhos de fonte

## Customização

### Cores por Categoria
```php
// functions.php
function recifemais_get_category_color($category_slug) {
    $colors = [
        'noticias' => '#dc2626',
        'cultura' => '#7c3aed',
        // ...
    ];
}
```

### Ícones por Categoria
```php
// functions.php
function recifemais_get_category_icon($category_slug) {
    $icons = [
        'noticias' => 'news',
        'cultura' => 'theater',
        // ...
    ];
}
```

## Manutenção

### Arquivos Relacionados
- `category-globo-style.php` - Template principal
- `css/category.css` - Estilos específicos
- `js/category.js` - Funcionalidades JavaScript
- `functions.php` - Funções auxiliares

### Atualizações Recomendadas
- Monitorar performance das queries
- Atualizar cores e ícones conforme necessário
- Revisar acessibilidade periodicamente
- Testar em novos dispositivos/navegadores

## Troubleshooting

### Problemas Comuns
1. **Posts não carregando**: Verificar permissões e queries
2. **Estilos não aplicados**: Confirmar enfileiramento do CSS
3. **JavaScript não funcionando**: Verificar dependências jQuery
4. **Imagens não carregando**: Verificar lazy loading e paths

### Debug
```php
// Ativar debug no WordPress
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Compatibilidade

### WordPress
- **Versão mínima**: 5.0
- **Versão testada**: 6.4+
- **PHP**: 7.4+

### Plugins
- **Rank Math**: Para breadcrumbs e SEO
- **Tailwind CSS**: Para estilos utilitários
- **jQuery**: Para funcionalidades JavaScript

### Navegadores
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+

## Performance

### Métricas Alvo
- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

### Otimizações Implementadas
- Lazy loading de imagens
- CSS crítico inline
- JavaScript assíncrono
- Queries otimizadas

## Conclusão

O template de categoria RecifeMais oferece uma experiência completa e profissional para navegação de conteúdo, inspirada nas melhores práticas da Globo.com. Com foco em performance, acessibilidade e usabilidade, proporciona uma base sólida para um portal de notícias moderno. 