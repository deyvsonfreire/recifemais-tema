<?php
/**
 * Template Part: Content Post
 * 
 * Área de conteúdo principal para posts/notícias com:
 * - Formatação de texto otimizada
 * - Elementos específicos para jornalismo
 * - Galeria de imagens integrada
 * - Citações e destaques
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

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_progress_bar' => true,
    'show_toc' => true,
    'show_inline_share' => true,
    'enable_lightbox' => true,
    'layout' => 'standard' // standard, wide, minimal
]);

// Processar conteúdo para TOC
$content_with_toc = recifemais_process_content_for_toc($content);
?>

<article class="single-content-post bg-white">
    
    <?php if ($args['show_progress_bar']): ?>
        <!-- Progress Bar de Leitura -->
        <div id="reading-progress" class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
            <div class="h-full bg-recife-primary transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar TOC (Desktop) -->
            <?php if ($args['show_toc']): ?>
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <div class="sticky top-24">
                        <div id="table-of-contents" class="bg-gray-50 rounded-lg p-6 hidden lg:block">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Índice
                            </h3>
                            <nav id="toc-nav" class="space-y-2">
                                <!-- TOC será gerado via JavaScript -->
                            </nav>
                            
                            <!-- Botão Voltar ao Topo -->
                            <button type="button" 
                                    onclick="scrollToTop()"
                                    class="mt-6 w-full flex items-center justify-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Voltar ao Topo
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Conteúdo Principal -->
            <div class="<?php echo $args['show_toc'] ? 'lg:col-span-3' : 'lg:col-span-4'; ?> order-1 lg:order-2">
                
                <!-- TOC Mobile -->
                <?php if ($args['show_toc']): ?>
                    <div class="lg:hidden mb-8">
                        <details class="bg-gray-50 rounded-lg p-4">
                            <summary class="font-bold text-gray-900 cursor-pointer flex items-center gap-2">
                                <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Índice do Artigo
                            </summary>
                            <nav id="toc-nav-mobile" class="mt-4 space-y-2">
                                <!-- TOC será gerado via JavaScript -->
                            </nav>
                        </details>
                    </div>
                <?php endif; ?>
                
                <!-- Conteúdo do Post -->
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-recife-primary prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-blockquote:border-l-4 prose-blockquote:border-recife-primary prose-blockquote:bg-gray-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:text-gray-700">
                    
                    <?php 
                    // Aplicar filtros do WordPress
                    $processed_content = apply_filters('the_content', $content_with_toc);
                    
                    // Processar para compartilhamento inline
                    if ($args['show_inline_share']) {
                        $processed_content = recifemais_add_inline_share_buttons($processed_content);
                    }
                    
                    // Processar galerias para lightbox
                    if ($args['enable_lightbox']) {
                        $processed_content = recifemais_process_galleries_for_lightbox($processed_content);
                    }
                    
                    echo $processed_content;
                    ?>
                </div>
                
                <!-- Compartilhamento Inline (será inserido via JS) -->
                <?php if ($args['show_inline_share']): ?>
                    <div id="inline-share-template" class="hidden">
                        <div class="inline-share-buttons my-8 flex items-center justify-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Compartilhar:</span>
                            <div class="flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-8 h-8 bg-sky-500 hover:bg-sky-600 text-white rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex items-center justify-center w-8 h-8 bg-blue-700 hover:bg-blue-800 text-white rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Galeria Lightbox Modal -->
                <?php if ($args['enable_lightbox']): ?>
                    <div id="lightbox-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
                        <div class="relative max-w-4xl max-h-full p-4">
                            <button type="button" 
                                    onclick="closeLightbox()"
                                    class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
                            <div id="lightbox-caption" class="absolute bottom-4 left-4 right-4 text-white text-center bg-black bg-opacity-50 p-2 rounded"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    <?php if ($args['show_progress_bar']): ?>
    // Progress Bar de Leitura
    function updateReadingProgress() {
        const article = document.querySelector('.single-content-post');
        const progressBar = document.querySelector('#reading-progress div');
        
        if (!article || !progressBar) return;
        
        const articleTop = article.offsetTop;
        const articleHeight = article.offsetHeight;
        const windowHeight = window.innerHeight;
        const scrollTop = window.pageYOffset;
        
        const progress = Math.min(100, Math.max(0, 
            ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
        ));
        
        progressBar.style.width = progress + '%';
    }
    
    window.addEventListener('scroll', updateReadingProgress);
    updateReadingProgress();
    <?php endif; ?>
    
    <?php if ($args['show_toc']): ?>
    // Gerar Table of Contents
    function generateTOC() {
        const headings = document.querySelectorAll('.prose h2, .prose h3, .prose h4, .prose h5, .prose h6');
        const tocNav = document.getElementById('toc-nav');
        const tocNavMobile = document.getElementById('toc-nav-mobile');
        
        if (headings.length === 0) {
            document.getElementById('table-of-contents')?.remove();
            return;
        }
        
        headings.forEach((heading, index) => {
            const id = 'heading-' + index;
            heading.id = id;
            
            const level = parseInt(heading.tagName.charAt(1));
            const indent = (level - 2) * 16; // 16px por nível
            
            const link = document.createElement('a');
            link.href = '#' + id;
            link.textContent = heading.textContent;
            link.className = 'block text-sm text-gray-600 hover:text-recife-primary transition-colors py-1';
            link.style.paddingLeft = indent + 'px';
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            
            tocNav?.appendChild(link.cloneNode(true));
            tocNavMobile?.appendChild(link);
        });
    }
    
    generateTOC();
    <?php endif; ?>
    
    <?php if ($args['show_inline_share']): ?>
    // Compartilhamento Inline
    function addInlineShareButtons() {
        const paragraphs = document.querySelectorAll('.prose p');
        const template = document.getElementById('inline-share-template');
        
        if (paragraphs.length >= 3 && template) {
            const middleIndex = Math.floor(paragraphs.length / 2);
            const shareButtons = template.innerHTML;
            paragraphs[middleIndex].insertAdjacentHTML('afterend', shareButtons);
        }
    }
    
    addInlineShareButtons();
    <?php endif; ?>
    
    <?php if ($args['enable_lightbox']): ?>
    // Lightbox para Imagens
    function initLightbox() {
        const images = document.querySelectorAll('.prose img');
        const modal = document.getElementById('lightbox-modal');
        const modalImage = document.getElementById('lightbox-image');
        const modalCaption = document.getElementById('lightbox-caption');
        
        images.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                modalImage.src = this.src;
                modalImage.alt = this.alt;
                modalCaption.textContent = this.alt || this.title || '';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
        
        // Fechar clicando fora
        modal?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
    }
    
    initLightbox();
    <?php endif; ?>
});

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

<?php if ($args['enable_lightbox']): ?>
function closeLightbox() {
    const modal = document.getElementById('lightbox-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}
<?php endif; ?>
</script> 