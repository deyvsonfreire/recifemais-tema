# Sistema de Artistas - RecifeMais

## Visão Geral

O sistema de artistas do RecifeMais foi desenvolvido seguindo os mesmos princípios do sistema de lugares, utilizando todos os recursos disponíveis no tema e no plugin RecifeMais Core V2. O sistema oferece uma experiência completa para descobrir e conhecer os talentos musicais e artísticos de Pernambuco.

## Arquivos Criados/Modificados

### Templates Principais
- `archive-artistas.php` - Página de listagem de artistas (recriado)
- `single-artistas.php` - Página individual do artista (novo)

### Template Parts
- `template-parts/single/meta-artista.php` - Meta informações específicas do artista (novo)

### Assets
- `css/artistas.css` - Estilos específicos para artistas (novo)
- `js/artistas.js` - JavaScript específico para artistas (novo)

### Configuração
- `functions.php` - Adicionado carregamento condicional dos assets de artistas

## Funcionalidades do Archive (archive-artistas.php)

### Hero Section Inspirado no Agenda Viva SP
- **Gradiente roxo** (identidade visual de artistas)
- **Estatísticas dinâmicas**:
  - Total de artistas cadastrados
  - Artistas com origem definida
  - Número de gêneros musicais
  - Tipos de artistas disponíveis
- **Breadcrumbs** integrados
- **Padrão visual** consistente com outros archives

### Sistema de Filtros Avançado
- **Busca textual** com placeholder específico
- **Filtro por tipo** (solo, banda, grupo, coletivo, orquestra, coral)
- **Filtro por gênero musical** (taxonomia generos_musicais)
- **Filtro por ritmo** (integração com dicionários do plugin)
- **Filtro por origem** (cidades principais de Pernambuco)
- **Filtros sticky** com backdrop blur
- **Indicadores de filtros ativos** com contador de resultados
- **Botão de reset** com animação

### Grid de Artistas
- **Layout responsivo** (1/2/3 colunas)
- **Utiliza card-artista.php** existente com configurações específicas
- **Animações escalonadas** com Intersection Observer
- **Hover effects** aprimorados

### Seção de Gêneros Musicais
- **Grid com ícones** específicos por gênero
- **Links diretos** para filtros por gênero
- **Contador** de artistas por categoria
- **Hover effects** com transformações

### Paginação
- **Estilizada** com cores da identidade de artistas
- **Acessível** com ARIA labels

## Funcionalidades do Single (single-artistas.php)

### Hero Section Fullscreen
- **Imagem de fundo** com efeito parallax sutil
- **Overlay gradiente** para legibilidade
- **Breadcrumbs** contextuais
- **Status badge** dinâmico baseado no tipo de artista
- **Meta informações rápidas** em cards com backdrop blur

### Meta Fields Utilizados (Plugin RecifeMais Core V2)
- `artista_tipo_grupo` - Tipo (solo, banda, grupo, etc.)
- `artista_origem` - Cidade de origem
- `artista_ano_formacao` - Ano de formação/início da carreira
- `artista_integrantes` - Lista de integrantes
- `artista_biografia` - Biografia detalhada
- `artista_site_oficial` - Site oficial
- `artista_redes_sociais` - Links das redes sociais
- `artista_ritmos` - Ritmos musicais (com dicionários)
- `artista_generos` - Gêneros musicais (com dicionários)
- `artista_publico_alvo` - Público-alvo (com dicionários)

### Taxonomias Integradas
- `generos_musicais` - Gêneros musicais
- `tipos_artistas` - Tipos de artistas
- `bairros_recife` - Localização geográfica

### Botões de Ação
- **Visitar Site** (se disponível)
- **Compartilhar** com Web Share API + fallbacks
- **Redes Sociais** com dropdown inteligente

### Sidebar Informativa
- **Widget de informações rápidas**
- **Newsletter signup** reutilizado
- **Design consistente** com outros singles

### Artistas Relacionados
- **Query inteligente** baseada em gênero musical
- **Exclusão do artista atual**
- **Grid responsivo** com 6 artistas
- **Animações escalonadas**

## Template Part: meta-artista.php

### Seções Organizadas
1. **Dados Básicos**
   - Tipo de artista com ícone específico
   - Origem geográfica
   - Ano de formação/início da carreira
   - Público-alvo

2. **Contato & Links**
   - Site oficial com link externo
   - Redes sociais com detecção automática de tipo
   - Fallback para "não disponível"

3. **Informações Musicais**
   - Ritmos (integração com dicionários)
   - Gêneros (integração com dicionários)
   - Categoria principal (taxonomia)

4. **Integrantes/Informações Adicionais**
   - Lista de integrantes (para grupos)
   - Informações adicionais (para solos)

5. **Biografia Detalhada**
   - Exibida apenas se diferente do conteúdo principal
   - Formatação com wpautop

6. **Taxonomias**
   - Tags de tipos de artistas
   - Tags de bairros/localização

### Integração com Dicionários
- **Formatação automática** de ritmos e gêneros
- **Labels legíveis** em vez de valores técnicos
- **Fallbacks** para valores não encontrados

## Estilos CSS (artistas.css)

### Archive Artistas
- **Hero com gradiente roxo** e padrão de fundo animado
- **Filtros sticky** com backdrop blur responsivo
- **Cards com animações** escalonadas e hover effects
- **Seção de gêneros** com transformações suaves
- **Paginação estilizada** com cores específicas

### Single Artista
- **Hero fullscreen** com efeito zoom no hover
- **Status badge animado** com pulse customizado
- **Meta rápida** com backdrop blur e hover effects
- **Botões de ação** com efeitos shimmer
- **Dropdown de redes sociais** com animações suaves
- **Meta informações** com gradientes específicos

### Responsividade
- **Mobile first** approach
- **Breakpoints específicos** para tablet e desktop
- **Grid adaptativo** em todas as seções
- **Filtros responsivos** (coluna única no mobile)

### Acessibilidade
- **High contrast mode** support
- **Reduced motion** preferences
- **Focus states** bem definidos
- **Print styles** otimizados

## JavaScript (artistas.js)

### Classe ArtistasArchive
- **Filtros sticky** com detecção de scroll
- **Search enhancements** com debounce
- **Animações** com Intersection Observer
- **Category hovers** com efeitos visuais
- **Filter tracking** para analytics

### Classe ArtistasSingle
- **Share button** com Web Share API + fallbacks
- **Social dropdown** com detecção de cliques externos
- **Contact actions** com tracking
- **Image interactions** com parallax sutil
- **Scroll animations** para elementos

### Classe ArtistasCommon
- **Lazy loading** para imagens
- **Tooltips dinâmicos**
- **Accessibility enhancements**
- **Keyboard navigation**
- **Notification system** (toast)
- **Analytics tracking** (GA4 + Facebook Pixel)

### Funcionalidades Avançadas
- **Web Share API** com fallback para clipboard
- **Modal de compartilhamento** social como último recurso
- **Detecção automática** de tipos de redes sociais
- **Sistema de notificações** toast
- **Debug logging** condicional

## Integração com Plugin RecifeMais Core V2

### Meta Fields
- **100% dos meta fields** utilizados
- **Validação** de existência antes da exibição
- **Formatação** adequada para cada tipo de dado

### Dicionários
- **Integração completa** com sistema de dicionários
- **Formatação automática** de valores
- **Labels legíveis** para usuários finais

### Taxonomias
- **Utilização de todas** as taxonomias relacionadas
- **Queries otimizadas** para performance
- **Fallbacks** para taxonomias vazias

## Components Reutilizados

### Cards
- **card-artista.php** - Utilizado no archive com configurações específicas
- **Variantes suportadas**: standard, hero, horizontal, mini
- **Configurações customizadas** para cada contexto

### Template Parts
- **newsletter-signup** - Reutilizado na sidebar
- **breadcrumbs** - Sistema universal de navegação

### Funcionalidades
- **Sistema de ícones SVG** do tema
- **Paleta de cores** consistente
- **Typography** do design system

## Performance e SEO

### Carregamento Condicional
- **CSS carregado** apenas em páginas de artistas
- **JavaScript carregado** apenas quando necessário
- **Dependências otimizadas** (jQuery + recifemais-main)

### SEO
- **Meta tags** adequadas
- **Schema.org** preparado para implementação
- **Open Graph** tags
- **Breadcrumbs** estruturados

### Performance
- **Lazy loading** de imagens
- **Intersection Observer** para animações
- **Debounce** em buscas
- **Queries otimizadas**

## Customização e Extensibilidade

### CSS
- **Variáveis CSS** para cores principais
- **Classes modulares** para fácil customização
- **Breakpoints** bem definidos
- **Dark mode** preparado

### JavaScript
- **Classes modulares** e extensíveis
- **Configurações centralizadas**
- **Eventos customizados** para integração
- **API pública** para extensões

### PHP
- **Hooks** do WordPress respeitados
- **Filtros** para customização
- **Template hierarchy** seguida
- **Compatibilidade** com child themes

## Compatibilidade

### WordPress
- **Versão mínima**: 5.0
- **PHP**: 7.4+
- **Gutenberg**: Compatível
- **Classic Editor**: Compatível

### Navegadores
- **Chrome**: 80+
- **Firefox**: 75+
- **Safari**: 13+
- **Edge**: 80+
- **Mobile**: iOS 13+, Android 8+

### Plugins
- **RecifeMais Core V2**: Obrigatório
- **Rank Math**: Integração automática
- **Yoast SEO**: Compatível
- **WP Rocket**: Compatível

## Manutenção

### Atualizações
- **Versionamento** através de RECIFEMAIS_VERSION
- **Cache busting** automático
- **Backward compatibility** mantida

### Debug
- **Logging condicional** baseado em WP_DEBUG
- **Console logging** para desenvolvimento
- **Error handling** robusto

### Monitoramento
- **Analytics tracking** integrado
- **Performance monitoring** preparado
- **Error tracking** implementado

## Próximos Passos

### Melhorias Planejadas
1. **Galeria de imagens** para artistas
2. **Sistema de favoritos** para usuários
3. **Integração com Spotify** API
4. **Mapa de artistas** por localização
5. **Sistema de avaliações** e comentários

### Integrações Futuras
1. **API do Spotify** para músicas
2. **YouTube API** para vídeos
3. **Instagram API** para feed
4. **Google Maps** para localização
5. **Sistema de eventos** relacionados

### Otimizações
1. **Service Worker** para cache
2. **Critical CSS** inline
3. **Image optimization** automática
4. **Database queries** otimização
5. **CDN integration** para assets

---

## Conclusão

O sistema de artistas está completo e funcional, seguindo exatamente os mesmos princípios utilizados no sistema de lugares. Oferece uma experiência rica e moderna para descobrir os talentos de Pernambuco, com integração total ao plugin RecifeMais Core V2 e máxima reutilização dos components existentes no tema.

A implementação mantém consistência visual e funcional com o resto do site, garantindo uma experiência de usuário coesa e profissional. 