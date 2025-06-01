<?php
/**
 * Template Part: Content Area
 * 
 * Área de conteúdo principal para posts/CPTs com:
 * - Formatação de texto otimizada para leitura
 * - Galeria de imagens integrada
 * - Índice de conteúdo automático
 * - Progress bar de leitura
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
$content = get_the_content();
$post_type = get_post_type();

// Configurações do content area
$args = wp_parse_args($args ?? [], [
    'show_toc' => true, // Table of Contents
    'show_progress' => true, // Progress bar
    'show_gallery' => true, // Galeria de imagens
    'enable_sharing' => true, // Botões de compartilhamento inline
    'typography' => 'article' // article, guide, story
]);

// Processar conteúdo para índice
$headings = [];
if ($args['show_toc'] && $content) {
    preg_match_all('/<h([2-6])[^>]*>(.*?)<\/h[2-6]>/i', $content, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $level = $match[1];
        $text = strip_tags($match[2]);
        $id = sanitize_title($text);
        $headings[] = [
            'level' => $level,
            'text' => $text,
            'id' => $id
        ];
        // Adicionar ID aos headings no conteúdo
        $content = str_replace($match[0], '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>', $content);
    }
}

// Galeria de imagens do post
$gallery_images = [];
if ($args['show_gallery']) {
    // Buscar imagens anexadas ao post
    $attachments = get_attached_media('image', $post_id);
    foreach ($attachments as $attachment) {
        $gallery_images[] = [
            'id' => $attachment->ID,
            'url' => wp_get_attachment_image_url($attachment->ID, 'large'),
            'thumb' => wp_get_attachment_image_url($attachment->ID, 'thumbnail'),
            'caption' => wp_get_attachment_caption($attachment->ID),
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)
        ];
    }
}
?>

<article class="single-content-area bg-white">
    
    <?php if ($args['show_progress']): ?>
        <!-- Progress Bar de Leitura -->
        <div class="reading-progress-container sticky top-0 z-40 bg-white border-b border-gray-200">
            <div class="reading-progress-bar h-1 bg-recife-primary transition-all duration-300" style="width: 0%"></div>
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar com Índice -->
            <?php if ($args['show_toc'] && !empty($headings)): ?>
                <aside class="lg:col-span-1 order-2 lg:order-1">
                    <div class="sticky top-24">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Índice
                            </h3>
                            
                            <nav class="toc-navigation">
                                <ul class="space-y-2 text-sm">
                                    <?php foreach ($headings as $heading): ?>
                                        <li class="toc-item" data-level="<?php echo esc_attr($heading['level']); ?>">
                                            <a href="#<?php echo esc_attr($heading['id']); ?>" 
                                               class="block text-gray-600 hover:text-recife-primary transition-colors py-1 pl-<?php echo ($heading['level'] - 2) * 4; ?>">
                                                <?php echo esc_html($heading['text']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                        
                        <!-- Botão Voltar ao Topo -->
                        <button type="button" 
                                onclick="scrollToTop()"
                                class="mt-4 w-full bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            ↑ Voltar ao Topo
                        </button>
                    </div>
                </aside>
            <?php endif; ?>
            
            <!-- Conteúdo Principal -->
            <main class="<?php echo $args['show_toc'] && !empty($headings) ? 'lg:col-span-3' : 'lg:col-span-4'; ?> order-1 lg:order-2">
                
                <!-- Conteúdo do Post -->
                <div class="prose prose-lg max-w-none 
                           prose-headings:text-gray-900 prose-headings:font-bold
                           prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-4 prose-h2:text-recife-primary
                           prose-h3:text-xl prose-h3:mt-6 prose-h3:mb-3
                           prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                           prose-a:text-recife-primary prose-a:no-underline hover:prose-a:underline
                           prose-strong:text-gray-900 prose-strong:font-semibold
                           prose-blockquote:border-l-4 prose-blockquote:border-recife-primary prose-blockquote:bg-gray-50 prose-blockquote:p-4 prose-blockquote:italic
                           prose-ul:list-disc prose-ol:list-decimal
                           prose-li:text-gray-700 prose-li:mb-1
                           prose-img:rounded-lg prose-img:shadow-md">
                    
                    <?php echo wp_kses_post($content); ?>
                    
                </div>
                
                <?php if ($args['enable_sharing']): ?>
                    <!-- Compartilhamento Inline -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-gray-900">Gostou? Compartilhe!</h4>
                            
                            <div class="flex items-center gap-3">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-colors"
                                   title="Compartilhar no Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                
                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-10 h-10 bg-sky-500 hover:bg-sky-600 text-white rounded-full transition-colors"
                                   title="Compartilhar no Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-10 h-10 bg-green-500 hover:bg-green-600 text-white rounded-full transition-colors"
                                   title="Compartilhar no WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                </a>
                                
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-10 h-10 bg-blue-700 hover:bg-blue-800 text-white rounded-full transition-colors"
                                   title="Compartilhar no LinkedIn">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_gallery'] && !empty($gallery_images)): ?>
                    <!-- Galeria de Imagens -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Galeria de Imagens</h4>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php foreach ($gallery_images as $index => $image): ?>
                                <div class="gallery-item cursor-pointer group" 
                                     onclick="openLightbox(<?php echo $index; ?>)">
                                    <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="<?php echo esc_url($image['thumb']); ?>" 
                                             alt="<?php echo esc_attr($image['alt']); ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <?php if ($image['caption']): ?>
                                        <p class="text-sm text-gray-600 mt-2"><?php echo esc_html($image['caption']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Lightbox Modal -->
                    <div id="lightbox-modal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
                        <div class="relative max-w-4xl max-h-full">
                            <button type="button" 
                                    onclick="closeLightbox()"
                                    class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
                            
                            <div class="absolute bottom-4 left-4 right-4 text-white text-center">
                                <p id="lightbox-caption" class="text-sm"></p>
                                <div class="flex justify-center gap-4 mt-2">
                                    <button type="button" onclick="previousImage()" class="text-white hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <span id="lightbox-counter" class="text-sm"></span>
                                    <button type="button" onclick="nextImage()" class="text-white hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</article>

<script>
// Progress Bar de Leitura
<?php if ($args['show_progress']): ?>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.reading-progress-bar');
    const article = document.querySelector('.single-content-area main');
    
    if (progressBar && article) {
        window.addEventListener('scroll', function() {
            const articleTop = article.offsetTop;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollTop = window.pageYOffset;
            
            const progress = Math.min(100, Math.max(0, 
                ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
            ));
            
            progressBar.style.width = progress + '%';
        });
    }
});
<?php endif; ?>

// Table of Contents
<?php if ($args['show_toc'] && !empty($headings)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const tocLinks = document.querySelectorAll('.toc-navigation a');
    const headings = document.querySelectorAll('h2, h3, h4, h5, h6');
    
    // Smooth scroll para links do TOC
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Highlight ativo no TOC
    window.addEventListener('scroll', function() {
        let current = '';
        headings.forEach(heading => {
            const rect = heading.getBoundingClientRect();
            if (rect.top <= 100) {
                current = heading.id;
            }
        });
        
        tocLinks.forEach(link => {
            link.classList.remove('text-recife-primary', 'font-semibold');
            link.classList.add('text-gray-600');
            
            if (link.getAttribute('href') === '#' + current) {
                link.classList.remove('text-gray-600');
                link.classList.add('text-recife-primary', 'font-semibold');
            }
        });
    });
});
<?php endif; ?>

// Galeria Lightbox
<?php if ($args['show_gallery'] && !empty($gallery_images)): ?>
const galleryImages = <?php echo json_encode($gallery_images); ?>;
let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    const modal = document.getElementById('lightbox-modal');
    const image = document.getElementById('lightbox-image');
    const caption = document.getElementById('lightbox-caption');
    const counter = document.getElementById('lightbox-counter');
    
    image.src = galleryImages[index].url;
    image.alt = galleryImages[index].alt;
    caption.textContent = galleryImages[index].caption || '';
    counter.textContent = `${index + 1} de ${galleryImages.length}`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const modal = document.getElementById('lightbox-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    openLightbox(currentImageIndex);
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    openLightbox(currentImageIndex);
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightbox-modal');
    if (!modal.classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        } else if (e.key === 'ArrowLeft') {
            previousImage();
        }
    }
});
<?php endif; ?>

// Voltar ao topo
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script> 