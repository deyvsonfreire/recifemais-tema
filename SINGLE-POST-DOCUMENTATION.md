# Single Post - RecifeMais (Estilo Globo.com)

## Visão Geral

O `single-post.php` é um template específico para exibição de posts nativos do WordPress, inspirado no design limpo e profissional da Globo.com. Este template utiliza 100% dos template-parts e components existentes no tema.

## Estrutura do Layout

### 1. Header da Notícia
- **Breadcrumbs**: Navegação contextual usando Rank Math
- **Meta informações**: Categoria, data, autor, tempo de leitura
- **Título principal**: Typography responsiva com hierarquia clara
- **Lead/Subtítulo**: Resumo destacado com barra lateral colorida
- **Badges de urgência**: Breaking news, importante, destaque

### 2. Imagem Destacada
- **Container responsivo**: Aspect ratio otimizado
- **Caption flutuante**: Legenda com backdrop blur
- **Lazy loading**: Performance otimizada
- **Hover effects**: Interação sutil

### 3. Área de Conteúdo Principal
- **Layout 2/3 + 1/3**: Conteúdo principal + sidebar
- **Meta do autor**: Avatar, nome, redes sociais, compartilhamento
- **Conteúdo formatado**: Typography otimizada para leitura
- **Sidebar contextual**: Widgets relacionados

### 4. Seções Complementares
- **Tags e categorias**: Organizadas visualmente
- **Bio do autor**: Template part reutilizável
- **Posts relacionados**: Algoritmo inteligente
- **Navegação entre posts**: Links contextuais

## Template Parts Utilizados

### Core Templates
```php
// Breadcrumbs
get_template_part('template-parts/archive/breadcrumbs');

// Hero do post
get_template_part('template-parts/single/hero-post');

// Conteúdo principal
get_template_part('template-parts/single/content-post');

// Meta informações
get_template_part('template-parts/single/meta-post');

// Bio do autor
get_template_part('template-parts/single/author-bio');

// Posts relacionados
get_template_part('template-parts/single/related-posts');

// Navegação entre posts
get_template_part('template-parts/single/navigation-post');

// Sidebar
get_template_part('template-parts/single/sidebar-post');
```

### Components Integrados
```php
// Cards de posts relacionados
get_template_part('components/cards/card-post');

// Compartilhamento social
get_template_part('components/social-share');

// Comentários
get_template_part('template-parts/single/comments-section');
```

## Funcionalidades JavaScript

### Core Features
- **Barra de progresso de leitura**: Indicador visual do progresso
- **Lazy loading de imagens**: Performance otimizada
- **Smooth scrolling**: Navegação suave entre seções
- **Compartilhamento social**: Web Share API + fallbacks
- **Filtros de posts relacionados**: Interação dinâmica

### Acessibilidade
- **Skip links**: Navegação rápida por teclado
- **Focus management**: Indicadores visuais claros
- **ARIA attributes**: Semântica adequada
- **Keyboard navigation**: Suporte completo

### Performance
- **Debounced scroll**: Otimização de eventos
- **Link prefetching**: Carregamento antecipado
- **Image optimization**: Lazy loading inteligente
- **Analytics tracking**: Eventos importantes

## Estilos CSS

### Design System
```css
/* Cores principais */
--recife-primary: #e11d48;
--recife-secondary: #ff6b35;
--recife-accent: #0ea5e9;

/* Typography */
font-family: 'Inter', system-ui, -apple-system, sans-serif;
line-height: 1.6;

/* Responsividade */
@media (max-width: 1023px) { /* Tablet */ }
@media (max-width: 767px) { /* Mobile */ }
```

### Componentes Específicos
- **Post header**: Gradiente sutil, meta info organizada
- **Featured image**: Sombras, hover effects, caption flutuante
- **Content area**: Typography otimizada, links destacados
- **Author meta**: Card com gradiente, botões interativos
- **Related posts**: Grid responsivo, filtros animados

## Integração com Plugins

### Rank Math SEO
- **Breadcrumbs**: Navegação automática
- **Meta tags**: OpenGraph e Twitter Cards
- **Structured data**: Schema.org para notícias
- **Variáveis customizadas**: Integração completa

### RecifeMais Core V2
- **Meta fields**: Campos específicos de notícias
- **Categorias**: Sistema de cores e ícones
- **Urgência**: Badges dinâmicos
- **Analytics**: Tracking de eventos

## Responsividade

### Desktop (1024px+)
- Layout 2/3 + 1/3
- Typography em tamanho completo
- Hover effects ativos
- Sidebar fixa

### Tablet (768px - 1023px)
- Layout adaptativo
- Typography reduzida
- Touch-friendly buttons
- Sidebar responsiva

### Mobile (< 768px)
- Layout single column
- Typography otimizada
- Menu mobile
- Gestos touch

## Performance

### Otimizações Implementadas
- **CSS específico**: Carregado apenas em single posts
- **JavaScript modular**: Funcionalidades específicas
- **Lazy loading**: Imagens e conteúdo
- **Prefetching**: Links importantes
- **Debouncing**: Eventos de scroll

### Métricas Alvo
- **LCP**: < 2.5s (Largest Contentful Paint)
- **FID**: < 100ms (First Input Delay)
- **CLS**: < 0.1 (Cumulative Layout Shift)
- **TTI**: < 3.5s (Time to Interactive)

## Acessibilidade (WCAG 2.1)

### Nível AA Compliance
- **Contraste**: Mínimo 4.5:1 para texto normal
- **Navegação**: Keyboard-only navigation
- **Screen readers**: ARIA labels e landmarks
- **Focus management**: Indicadores visuais claros

### Recursos Específicos
- **Skip links**: Navegação rápida
- **Alt texts**: Imagens descritivas
- **Semantic HTML**: Estrutura adequada
- **Color independence**: Não depende apenas de cores

## SEO e Structured Data

### Schema.org
```json
{
  "@type": "NewsArticle",
  "headline": "Título do post",
  "description": "Resumo do post",
  "image": "URL da imagem destacada",
  "datePublished": "Data de publicação",
  "dateModified": "Data de modificação",
  "author": {
    "@type": "Person",
    "name": "Nome do autor"
  },
  "publisher": {
    "@type": "Organization",
    "name": "RecifeMais"
  }
}
```

### Meta Tags
- **OpenGraph**: Facebook, LinkedIn
- **Twitter Cards**: Twitter
- **Canonical**: URL canônica
- **Robots**: Indexação controlada

## Testes e Validação

### Checklist de Qualidade
- [ ] Sintaxe PHP válida
- [ ] CSS sem erros
- [ ] JavaScript funcional
- [ ] Responsividade testada
- [ ] Acessibilidade validada
- [ ] Performance otimizada
- [ ] SEO implementado

### Ferramentas de Teste
- **PHP**: `php -l arquivo.php`
- **CSS**: W3C CSS Validator
- **Acessibilidade**: WAVE, axe-core
- **Performance**: Lighthouse, PageSpeed Insights
- **SEO**: Google Search Console

## Manutenção

### Atualizações Regulares
- **WordPress Core**: Compatibilidade
- **Plugins**: Integração mantida
- **Dependencies**: Versões atualizadas
- **Security**: Patches aplicados

### Monitoramento
- **Analytics**: Google Analytics 4
- **Performance**: Core Web Vitals
- **Errors**: Error logging
- **User feedback**: Comentários e interações

## Conclusão

O `single-post.php` representa uma implementação completa e profissional para exibição de notícias, seguindo as melhores práticas de desenvolvimento WordPress e inspirado no design da Globo.com. Utiliza 100% dos recursos existentes no tema, garantindo consistência e manutenibilidade. 