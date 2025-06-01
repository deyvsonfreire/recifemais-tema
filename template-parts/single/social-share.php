<?php
/**
 * Template Part: Social Share
 * 
 * Compartilhamento social com:
 * - Múltiplas plataformas sociais
 * - Web Share API nativa
 * - Copiar link para clipboard
 * - Analytics de compartilhamento
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do post
$post_id = get_the_ID();
$post_title = get_the_title();
$post_url = get_permalink();
$post_excerpt = get_the_excerpt();
$post_image = get_the_post_thumbnail_url($post_id, 'large');

// Configurações
$args = wp_parse_args($args ?? [], [
    'layout' => 'horizontal', // horizontal, vertical, floating, minimal
    'show_labels' => true,
    'show_counts' => false,
    'show_copy_link' => true,
    'show_web_share' => true,
    'show_print' => true,
    'show_email' => true,
    'platforms' => ['facebook', 'twitter', 'whatsapp', 'linkedin', 'telegram'],
    'title' => 'Compartilhar',
    'size' => 'medium' // small, medium, large
]);

// Preparar dados para compartilhamento
$share_title = esc_attr($post_title);
$share_url = esc_url($post_url);
$share_text = esc_attr($post_excerpt ?: $post_title);
$share_image = esc_url($post_image);

// URLs de compartilhamento
$share_urls = [
    'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($share_url),
    'twitter' => 'https://twitter.com/intent/tweet?text=' . urlencode($share_title) . '&url=' . urlencode($share_url) . '&via=RecifeMais',
    'whatsapp' => 'https://wa.me/?text=' . urlencode($share_title . ' ' . $share_url),
    'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($share_url),
    'telegram' => 'https://t.me/share/url?url=' . urlencode($share_url) . '&text=' . urlencode($share_title),
    'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($share_url) . '&media=' . urlencode($share_image) . '&description=' . urlencode($share_text),
    'reddit' => 'https://reddit.com/submit?url=' . urlencode($share_url) . '&title=' . urlencode($share_title),
    'email' => 'mailto:?subject=' . urlencode($share_title) . '&body=' . urlencode($share_text . ' ' . $share_url)
];

// Configurações de tamanho
$size_classes = [
    'small' => 'w-8 h-8 text-sm',
    'medium' => 'w-10 h-10 text-base',
    'large' => 'w-12 h-12 text-lg'
];

$icon_size = [
    'small' => 'w-4 h-4',
    'medium' => 'w-5 h-5',
    'large' => 'w-6 h-6'
];

$button_size = $size_classes[$args['size']] ?? $size_classes['medium'];
$icon_size_class = $icon_size[$args['size']] ?? $icon_size['medium'];

// Classes de layout
$layout_classes = [
    'horizontal' => 'flex flex-wrap items-center gap-3',
    'vertical' => 'flex flex-col gap-3',
    'floating' => 'fixed right-4 top-1/2 transform -translate-y-1/2 z-50 flex flex-col gap-3',
    'minimal' => 'flex items-center gap-2'
];

$container_class = $layout_classes[$args['layout']] ?? $layout_classes['horizontal'];

// Plataformas disponíveis
$platforms_config = [
    'facebook' => [
        'name' => 'Facebook',
        'color' => 'bg-blue-600 hover:bg-blue-700',
        'icon' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'
    ],
    'twitter' => [
        'name' => 'Twitter',
        'color' => 'bg-sky-500 hover:bg-sky-600',
        'icon' => '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>'
    ],
    'whatsapp' => [
        'name' => 'WhatsApp',
        'color' => 'bg-green-500 hover:bg-green-600',
        'icon' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>'
    ],
    'linkedin' => [
        'name' => 'LinkedIn',
        'color' => 'bg-blue-700 hover:bg-blue-800',
        'icon' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>'
    ],
    'telegram' => [
        'name' => 'Telegram',
        'color' => 'bg-blue-500 hover:bg-blue-600',
        'icon' => '<path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>'
    ],
    'pinterest' => [
        'name' => 'Pinterest',
        'color' => 'bg-red-600 hover:bg-red-700',
        'icon' => '<path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-12C24.007 5.367 18.641.001 12.017.001z"/>'
    ],
    'reddit' => [
        'name' => 'Reddit',
        'color' => 'bg-orange-600 hover:bg-orange-700',
        'icon' => '<path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/>'
    ]
];
?>

<div class="social-share-container <?php echo esc_attr($args['layout']); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    
    <?php if ($args['show_labels'] && $args['layout'] !== 'minimal'): ?>
        <div class="share-title mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                </svg>
                <?php echo esc_html($args['title']); ?>
            </h3>
        </div>
    <?php endif; ?>
    
    <div class="<?php echo esc_attr($container_class); ?>">
        
        <!-- Web Share API (se suportado) -->
        <?php if ($args['show_web_share']): ?>
            <button type="button" 
                    onclick="shareNative()"
                    class="share-btn native-share hidden <?php echo esc_attr($button_size); ?> bg-recife-primary hover:bg-recife-primary/90 text-white rounded-full flex items-center justify-center transition-colors"
                    title="Compartilhar"
                    data-platform="native">
                <svg class="<?php echo esc_attr($icon_size_class); ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                </svg>
                <?php if ($args['show_labels'] && $args['layout'] === 'horizontal'): ?>
                    <span class="ml-2 text-sm font-medium">Compartilhar</span>
                <?php endif; ?>
            </button>
        <?php endif; ?>
        
        <!-- Plataformas Sociais -->
        <?php foreach ($args['platforms'] as $platform): ?>
            <?php if (isset($platforms_config[$platform]) && isset($share_urls[$platform])): 
                $config = $platforms_config[$platform];
                ?>
                <a href="<?php echo esc_url($share_urls[$platform]); ?>" 
                   target="_blank" 
                   rel="noopener"
                   onclick="trackShare('<?php echo esc_js($platform); ?>', '<?php echo esc_js($post_id); ?>')"
                   class="share-btn <?php echo esc_attr($button_size); ?> <?php echo esc_attr($config['color']); ?> text-white rounded-full flex items-center justify-center transition-colors"
                   title="Compartilhar no <?php echo esc_attr($config['name']); ?>"
                   data-platform="<?php echo esc_attr($platform); ?>">
                    <svg class="<?php echo esc_attr($icon_size_class); ?>" fill="currentColor" viewBox="0 0 24 24">
                        <?php echo $config['icon']; ?>
                    </svg>
                    <?php if ($args['show_labels'] && $args['layout'] === 'horizontal'): ?>
                        <span class="ml-2 text-sm font-medium"><?php echo esc_html($config['name']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <!-- Copiar Link -->
        <?php if ($args['show_copy_link']): ?>
            <button type="button" 
                    onclick="copyToClipboard('<?php echo esc_js($share_url); ?>')"
                    class="share-btn copy-link <?php echo esc_attr($button_size); ?> bg-gray-600 hover:bg-gray-700 text-white rounded-full flex items-center justify-center transition-colors"
                    title="Copiar link"
                    data-platform="copy">
                <svg class="<?php echo esc_attr($icon_size_class); ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                </svg>
                <?php if ($args['show_labels'] && $args['layout'] === 'horizontal'): ?>
                    <span class="ml-2 text-sm font-medium">Copiar</span>
                <?php endif; ?>
            </button>
        <?php endif; ?>
        
        <!-- Email -->
        <?php if ($args['show_email']): ?>
            <a href="<?php echo esc_url($share_urls['email']); ?>" 
               onclick="trackShare('email', '<?php echo esc_js($post_id); ?>')"
               class="share-btn <?php echo esc_attr($button_size); ?> bg-gray-700 hover:bg-gray-800 text-white rounded-full flex items-center justify-center transition-colors"
               title="Compartilhar por email"
               data-platform="email">
                <svg class="<?php echo esc_attr($icon_size_class); ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
                <?php if ($args['show_labels'] && $args['layout'] === 'horizontal'): ?>
                    <span class="ml-2 text-sm font-medium">Email</span>
                <?php endif; ?>
            </a>
        <?php endif; ?>
        
        <!-- Imprimir -->
        <?php if ($args['show_print']): ?>
            <button type="button" 
                    onclick="window.print(); trackShare('print', '<?php echo esc_js($post_id); ?>')"
                    class="share-btn <?php echo esc_attr($button_size); ?> bg-gray-500 hover:bg-gray-600 text-white rounded-full flex items-center justify-center transition-colors"
                    title="Imprimir"
                    data-platform="print">
                <svg class="<?php echo esc_attr($icon_size_class); ?>" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zM5 14H4v-2h1v2zm1 0v2h8v-2H6z" clip-rule="evenodd"></path>
                </svg>
                <?php if ($args['show_labels'] && $args['layout'] === 'horizontal'): ?>
                    <span class="ml-2 text-sm font-medium">Imprimir</span>
                <?php endif; ?>
            </button>
        <?php endif; ?>
    </div>
    
    <!-- Feedback de Sucesso -->
    <div id="share-feedback" class="hidden fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="share-feedback-text">Link copiado!</span>
        </div>
    </div>
</div>

<script>
// Web Share API
function shareNative() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo esc_js($share_title); ?>',
            text: '<?php echo esc_js($share_text); ?>',
            url: '<?php echo esc_js($share_url); ?>'
        }).then(() => {
            trackShare('native', '<?php echo esc_js($post_id); ?>');
        }).catch(console.error);
    }
}

// Copiar para clipboard
function copyToClipboard(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showShareFeedback('Link copiado para a área de transferência!');
            trackShare('copy', '<?php echo esc_js($post_id); ?>');
        }).catch(() => {
            fallbackCopyToClipboard(url);
        });
    } else {
        fallbackCopyToClipboard(url);
    }
}

// Fallback para copiar
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showShareFeedback('Link copiado!');
        trackShare('copy', '<?php echo esc_js($post_id); ?>');
    } catch (err) {
        showShareFeedback('Erro ao copiar link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Mostrar feedback
function showShareFeedback(message, type = 'success') {
    const feedback = document.getElementById('share-feedback');
    const text = document.getElementById('share-feedback-text');
    
    text.textContent = message;
    feedback.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    feedback.classList.remove('hidden');
    
    setTimeout(() => {
        feedback.classList.add('hidden');
    }, 3000);
}

// Analytics de compartilhamento
function trackShare(platform, postId) {
    // Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'share', {
            'method': platform,
            'content_type': 'article',
            'content_id': postId,
            'custom_parameter': 'social_share'
        });
    }
    
    // Facebook Pixel
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Share', {
            content_type: 'article',
            content_id: postId
        });
    }
    
    // Console para debug
    console.log('Share tracked:', platform, postId);
}

// Detectar Web Share API
document.addEventListener('DOMContentLoaded', function() {
    if (navigator.share) {
        const nativeShareBtn = document.querySelector('.native-share');
        if (nativeShareBtn) {
            nativeShareBtn.classList.remove('hidden');
        }
    }
    
    // Animação de entrada para layout floating
    <?php if ($args['layout'] === 'floating'): ?>
        const floatingShare = document.querySelector('.social-share-container.floating');
        if (floatingShare) {
            setTimeout(() => {
                floatingShare.style.opacity = '1';
                floatingShare.style.transform = 'translateY(-50%) translateX(0)';
            }, 1000);
        }
    <?php endif; ?>
});
</script>

<?php if ($args['layout'] === 'floating'): ?>
<style>
.social-share-container.floating {
    opacity: 0;
    transform: translateY(-50%) translateX(100px);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .social-share-container.floating {
        display: none;
    }
}
</style>
<?php endif; ?> 