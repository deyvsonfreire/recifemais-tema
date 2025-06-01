# Documentação dos Componentes da Homepage - RecifeMais

## Visão Geral

Este documento detalha todos os componentes implementados para a homepage do portal RecifeMais, seguindo o padrão de qualidade estabelecido no projeto de refatoração do tema.

## Componentes Implementados

### 1. Hero Breaking News (`hero-breaking.php`)
**Localização:** `template-parts/homepage/hero-breaking.php`

#### Características:
- Breaking news bar com animação de scroll automático
- Carousel de posts em destaque com auto-rotate (8 segundos)
- Sidebar "Mais Lidas" com ranking numerado
- Banner promocional da newsletter integrado
- Sistema de compartilhamento com Web Share API e fallback
- Controles de navegação (dots, setas) com acessibilidade
- Meta informações (data, autor, tempo de leitura)
- Responsive design completo

#### Funcionalidades JavaScript:
- Auto-rotate pausável no hover
- Navegação por teclado
- Analytics integrado (GA4)
- Lazy loading de imagens

#### Configurações:
```php
$hero_config = [
    'show_breaking_news' => true,
    'show_featured_posts' => true,
    'posts_limit' => 5,
    'auto_rotate' => true,
    'rotation_interval' => 8000
];
```

### 2. Featured Stories (`featured-stories.php`)
**Localização:** `template-parts/homepage/featured-stories.php`

#### Características:
- Layout misto inspirado na Globo.com (história principal + secundárias)
- Sistema de filtros por categoria de histórias
- Grid responsivo com animações escalonadas
- Cards com hover effects e transições suaves
- Call-to-action para arquivo completo e projeto

#### Funcionalidades JavaScript:
- Filtros dinâmicos por categoria
- Intersection Observer para animações
- Analytics de filtros
- Lazy loading otimizado

### 3. Section Descubra (`section-descubra.php`)
**Localização:** `template-parts/homepage/section-descubra.php`

#### Características:
- 4 categorias principais: Gastronomia, Cultura, Entretenimento, Natureza
- Cards interativos com ícones emoji e gradientes
- Grid de lugares em destaque com sistema de favoritos
- Badges informativos (categoria, faixa de preço)
- CTA final com gradiente e múltiplas ações

#### Funcionalidades JavaScript:
- Sistema de favoritos com localStorage
- Hover effects avançados com shimmer
- Tracking de cliques por categoria
- Intersection Observer para performance

### 4. Section Roteiros (`section-roteiros.php`)
**Localização:** `template-parts/homepage/section-roteiros.php`

#### Características:
- Filtros de categoria com estatísticas em tempo real
- Carousel responsivo com controles touch-friendly
- Cards detalhados com badges de duração e dificuldade
- Sistema de pontos de interesse
- Ações rápidas (compartilhar, salvar)

#### Funcionalidades JavaScript:
- Carousel com auto-rotate e responsividade
- Sistema de salvamento de roteiros
- Compartilhamento nativo e fallback
- Filtros dinâmicos com animações

### 5. Section Agenda (`section-agenda.php`)
**Localização:** `template-parts/homepage/section-agenda.php`

#### Características:
- Seção especial "Acontece Hoje" com destaque
- Mini calendário com estatísticas
- Badges dinâmicos (HOJE, AMANHÃ, X DIAS)
- Sistema de preços (GRÁTIS, R$ valor)
- Integração com Google Calendar

#### Funcionalidades JavaScript:
- Adicionar eventos ao Google Calendar
- Atualização em tempo real de badges
- Filtros por categoria de eventos
- Sistema de compartilhamento de eventos
- Contador dinâmico de tempo até eventos

### 6. Newsletter Signup (`newsletter-signup.php`) ⭐ NOVO
**Localização:** `template-parts/homepage/newsletter-signup.php`

#### Características:
- Design moderno com gradientes e padrões de fundo
- Formulário completo com validação
- Social proof com estatísticas
- Benefícios visuais da newsletter
- Sistema de interesses personalizáveis
- Checkbox de privacidade obrigatório
- Estados de loading e sucesso

#### Funcionalidades JavaScript:
- Validação em tempo real
- Estados visuais (loading, sucesso, erro)
- Select múltiplo customizado
- Analytics integrado
- Simulação de API (pronto para integração real)

#### Configurações:
```php
$newsletter_config = [
    'show_benefits' => true,
    'show_privacy_note' => true,
    'show_social_proof' => true,
    'enable_analytics' => true
];
```

#### Benefícios Configuráveis:
- Agenda Semanal
- Conteúdo Exclusivo
- Descontos Especiais
- Dicas de Especialistas

### 7. Weather Widget (`weather-widget.php`) ⭐ NOVO
**Localização:** `template-parts/homepage/weather-widget.php`

#### Características:
- Informações completas do clima de Recife
- Previsão para 3 dias
- Índice UV com cores dinâmicas
- Dicas culturais baseadas no clima
- Geolocalização opcional
- Auto-refresh a cada 30 minutos
- Design responsivo e moderno

#### Funcionalidades JavaScript:
- Refresh manual com animação
- Geolocalização com fallback
- Estados de loading
- Notificações de erro
- Auto-refresh programado
- Simulação de API (pronto para integração real)

#### Configurações:
```php
$weather_config = [
    'city' => 'Recife',
    'state' => 'PE',
    'show_forecast' => true,
    'show_cultural_tips' => true,
    'show_uv_index' => true,
    'enable_geolocation' => true
];
```

#### Dicas Culturais Contextuais:
- **Ensolarado:** Explorar Marco Zero e Recife Antigo
- **Parcialmente Nublado:** Museus e centros culturais
- **Chuvoso:** Teatros, cinemas e eventos cobertos
- **Nublado:** Caminhadas culturais pelo centro histórico

### 8. Sidebar Widgets (`sidebar-widgets.php`)
**Localização:** `template-parts/homepage/sidebar-widgets.php`

#### Características:
- Posts em trending com base em visualizações
- Links rápidos para funcionalidades
- Widgets sociais
- Design integrado com o sistema

## Arquivo Principal da Homepage

### Front-Page Novo (`front-page-novo.php`) ⭐ NOVO
**Localização:** `themes/recifemais-tema/front-page-novo.php`

#### Estrutura Completa:
1. **Hero Breaking News** - Seção principal com destaques
2. **Featured Stories** - Histórias em destaque
3. **Section Descubra** - Explorar lugares por categoria
4. **Section Roteiros** - Roteiros culturais
5. **Section Agenda** - Eventos e agenda cultural
6. **Newsletter Signup** - Inscrição na newsletter
7. **Sidebar Section** - Últimas notícias + Weather Widget + Sidebar Widgets
8. **Call to Action Final** - Comunidade e redes sociais

#### Funcionalidades Globais:
- Analytics integrado para toda a homepage
- Lazy loading de imagens
- Smooth scroll para links internos
- Animações de entrada para seções
- Responsive design completo
- Print styles otimizados

## Padrões Técnicos Implementados

### Design System Consistente
- **Cores:** recife-primary (#E11D48), recife-secondary (#0EA5E9), recife-creative, recife-warning, recife-success
- **Tipografia:** Hierárquica com pesos específicos
- **Espaçamentos:** Padronizados (py-12 lg:py-16)
- **Border radius:** Consistente (rounded-xl, rounded-2xl)

### Performance e Acessibilidade
- **Lazy loading:** Intersection Observer para imagens
- **Loading states:** Shimmer effects e spinners
- **ARIA labels:** Navegação por teclado completa
- **Responsive design:** Mobile-first approach
- **Animações:** Reduced motion support

### Funcionalidades Avançadas
- **localStorage:** Persistência de favoritos, roteiros salvos, eventos
- **Analytics:** Integração com GTM/GA4
- **Web Share API:** Com fallbacks para navegadores não suportados
- **Intersection Observer:** Animações performáticas
- **Debounce/Throttle:** Otimização de eventos

### Segurança e Boas Práticas
- **Escape de dados:** esc_html(), esc_url(), esc_attr()
- **Sanitização:** sanitize_text_field()
- **Queries otimizadas:** WP_Query com parâmetros específicos
- **Fallbacks:** Para funcionalidades não suportadas
- **Nonce verification:** Para formulários (quando aplicável)

## Integrações Necessárias

### APIs Externas (Para Produção)
1. **Newsletter:** MailChimp, ConvertKit, ou similar
2. **Weather:** OpenWeatherMap ou AccuWeather
3. **Analytics:** Google Analytics 4
4. **Maps:** Google Maps (para geolocalização)

### WordPress Integrations
1. **Custom Fields:** Meta boxes para featured posts, breaking news
2. **Taxonomies:** Categorias de histórias, eventos, lugares
3. **User Roles:** Permissões para gerenciar conteúdo
4. **Caching:** Transients API para dados externos

## Estrutura de Arquivos

```
themes/recifemais-tema/
├── template-parts/
│   └── homepage/
│       ├── hero-breaking.php
│       ├── featured-stories.php
│       ├── section-descubra.php
│       ├── section-roteiros.php
│       ├── section-agenda.php
│       ├── newsletter-signup.php ⭐ NOVO
│       ├── weather-widget.php ⭐ NOVO
│       └── sidebar-widgets.php
├── front-page-novo.php ⭐ NOVO
└── HOMEPAGE-COMPONENTS-DOCS.md ⭐ NOVO
```

## Status do Projeto

✅ **Concluído:** 8/8 componentes da homepage implementados
✅ **Qualidade:** Padrão consistente com header/footer
✅ **Responsividade:** Mobile-first design
✅ **Performance:** Lazy loading e otimizações
✅ **Acessibilidade:** ARIA labels e navegação por teclado
✅ **Analytics:** Integração com GA4
✅ **Segurança:** Escape e sanitização de dados

## Próximos Passos

1. **Integração com APIs reais** (Newsletter, Weather)
2. **Testes de performance** e otimizações
3. **Testes de acessibilidade** com ferramentas especializadas
4. **Implementação de cache** para dados externos
5. **Configuração de analytics** em produção
6. **Testes cross-browser** e dispositivos

## Notas de Desenvolvimento

- Todos os componentes seguem o padrão WordPress de template parts
- JavaScript modular e bem documentado
- CSS responsivo com Tailwind CSS
- Pronto para integração com sistemas reais
- Código bem comentado e documentado
- Seguindo WordPress Coding Standards