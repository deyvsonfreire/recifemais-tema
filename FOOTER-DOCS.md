# Footer Moderno - RecifeMais Tema

## 📋 Visão Geral

O footer do RecifeMais foi completamente refatorado seguindo padrões modernos de design e desenvolvimento, oferecendo uma experiência rica e funcional para os usuários.

## 🎨 Design System

### Cores Utilizadas
- **Background Principal**: `recife-gray-900` (fundo escuro)
- **Texto Principal**: `white` (branco)
- **Texto Secundário**: `recife-gray-300` (cinza claro)
- **Acentos**: `recife-accent` (azul)
- **Links Hover**: `recife-primary` (vermelho)
- **Elementos Decorativos**: Gradientes das cores primárias

### Layout Responsivo
- **Desktop**: Grid de 12 colunas (4+2+2+4)
- **Tablet**: Grid de 2 colunas com seção sobre ocupando 2 colunas
- **Mobile**: Layout em coluna única

## 🏗️ Estrutura de Arquivos

```
themes/recifemais-tema/
├── components/layout/footer.php    # Componente principal
├── css/footer.css                  # Estilos específicos
├── js/footer.js                    # Funcionalidades JavaScript
└── footer.php                      # Template principal
```

## 📱 Seções do Footer

### 1. Seção Sobre (4 colunas)
- **Logo e Descrição**: Branding do site
- **Estatísticas Dinâmicas**: Contadores de eventos, lugares e artistas
- **Redes Sociais**: Links para plataformas sociais

### 2. Navegação Principal (2 colunas)
- Links para páginas principais
- Ícones SVG para melhor UX
- Hover effects animados

### 3. Categorias (2 colunas)
- Links para categorias de conteúdo
- Indicadores visuais (dots)
- Cores diferenciadas por categoria

### 4. Contato e Newsletter (4 colunas)
- **Informações de Contato**: Localização, email, WhatsApp
- **Newsletter Funcional**: Formulário com validação e AJAX

## ⚡ Funcionalidades JavaScript

### Newsletter
```javascript
// Validação em tempo real
input.addEventListener('input', (e) => {
    this.validateEmail(e.target.value) 
        ? this.setInputState(input, 'valid')
        : this.setInputState(input, 'invalid');
});

// Submissão AJAX
const response = await this.submitNewsletter(email);
```

### Animações
- **Intersection Observer**: Animações de entrada
- **Parallax Sutil**: Background decorativo
- **Hover Effects**: Transições suaves
- **Counter Animation**: Números das estatísticas

### Analytics
- **Google Analytics 4**: Tracking de eventos
- **Facebook Pixel**: Conversões
- **Custom Events**: Newsletter, cliques sociais, navegação

## 🎯 Acessibilidade

### WCAG 2.1 Compliance
- **Navegação por Teclado**: Tab navigation otimizada
- **ARIA Labels**: Descrições para screen readers
- **Contraste**: Cores com contraste adequado
- **Focus States**: Indicadores visuais claros

### Recursos Implementados
```css
/* Focus states melhorados */
.footer-focusable:focus {
    outline: 2px solid var(--recife-accent);
    outline-offset: 2px;
    border-radius: 0.25rem;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    * { animation-duration: 0.01ms !important; }
}
```

## 🔧 Configuração

### Variáveis de Configuração
```php
$footer_config = [
    'show_newsletter' => true,
    'show_social' => true,
    'show_contact' => true,
    'show_badges' => true,
    'show_weather' => false, // Para implementação futura
];
```

### Redes Sociais
```php
$social_links = [
    'instagram' => [
        'url' => 'https://instagram.com/recifemais',
        'label' => 'Instagram',
        'icon' => 'instagram'
    ],
    // ... outras redes
];
```

## 📊 Performance

### Otimizações Implementadas
- **Lazy Loading**: Recursos pesados carregados sob demanda
- **Throttled Scroll**: Eventos de scroll otimizados
- **CSS Minificado**: Estilos compactados
- **JavaScript Modular**: Carregamento condicional

### Métricas Esperadas
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Cumulative Layout Shift**: < 0.1

## 🛡️ Segurança

### Newsletter AJAX
```php
// Verificação de nonce
if (!wp_verify_nonce($_POST['nonce'], 'recifemais_ajax_nonce')) {
    wp_die(json_encode(['success' => false, 'message' => 'Erro de segurança']));
}

// Sanitização de dados
$email = sanitize_email($_POST['email']);
```

### Logs de Auditoria
- **Inscrições Newsletter**: IP, User Agent, timestamp
- **Tentativas de Spam**: Bloqueio automático
- **Erros de Validação**: Monitoramento

## 🎨 Customização

### Cores do Design System
```css
:root {
    --recife-primary: #e11d48;
    --recife-secondary: #0ea5e9;
    --recife-accent: #0ea5e9;
    --recife-gray-900: #111827;
    --recife-gray-800: #1f2937;
    --recife-gray-700: #374151;
}
```

### Breakpoints Responsivos
```css
/* Mobile */
@media (max-width: 767px) { /* Estilos mobile */ }

/* Tablet */
@media (min-width: 768px) and (max-width: 1023px) { /* Estilos tablet */ }

/* Desktop */
@media (min-width: 1024px) { /* Estilos desktop */ }
```

## 🔌 Integração com WordPress

### Hooks Utilizados
- `wp_enqueue_scripts`: Carregamento de assets
- `wp_ajax_*`: Handlers AJAX
- `wp_head`: Variáveis JavaScript globais
- `body_class`: Classes CSS dinâmicas

### Funções Auxiliares
```php
// Ícones sociais
recifemais_get_social_icon($platform)

// Contadores dinâmicos
wp_count_posts('eventos_festivais')->publish

// URLs dinâmicas
home_url('/categoria/')
```

## 📈 Analytics e Tracking

### Eventos Rastreados
1. **Newsletter Signup**: Email domain, source
2. **Social Clicks**: Platform, location
3. **Footer Navigation**: Link text, URL
4. **Category Clicks**: Category name, URL

### Implementação
```javascript
this.trackEvent('newsletter_signup', {
    email_domain: email.split('@')[1],
    source: 'footer'
});
```

## 🚀 Implementações Futuras

### Roadmap
- [ ] **Widget de Clima**: Informações meteorológicas do Recife
- [ ] **Feed Social**: Posts recentes das redes sociais
- [ ] **Newsletter Segmentada**: Por categorias de interesse
- [ ] **Dark/Light Mode**: Toggle de tema
- [ ] **PWA Integration**: Service worker para cache

### Melhorias Planejadas
- [ ] **A/B Testing**: Diferentes layouts de newsletter
- [ ] **Geolocalização**: Conteúdo baseado na localização
- [ ] **Personalização**: Footer adaptado ao usuário
- [ ] **Multilíngua**: Suporte a português/inglês

## 🐛 Debugging

### Modo Debug
```javascript
// Ativar debug
window.recifemais_debug = true;

// Logs no console
console.log('RecifeMais Footer: Inicializado com sucesso');
```

### Logs Importantes
```php
// Newsletter
error_log("RecifeMais Newsletter: Nova inscrição - Email: {$email}");

// Erros AJAX
error_log("RecifeMais Footer: Erro AJAX - " . $error_message);
```

## 📞 Suporte

### Contato Técnico
- **Email**: dev@recifemais.com.br
- **Documentação**: `/wp-content/themes/recifemais-tema/FOOTER-DOCS.md`
- **Issues**: GitHub repository

### Manutenção
- **Backup**: Antes de qualquer alteração
- **Testing**: Sempre testar em staging
- **Performance**: Monitorar métricas regularmente

---

**Versão**: 2.0.0  
**Última Atualização**: Janeiro 2025  
**Desenvolvido por**: Equipe RecifeMais 