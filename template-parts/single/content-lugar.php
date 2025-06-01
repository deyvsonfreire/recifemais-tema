<?php
/**
 * Template Part: Content Lugar
 * 
 * Conteúdo específico para lugares com:
 * - Informações detalhadas do estabelecimento
 * - Horários de funcionamento
 * - Especialidades e serviços
 * - Avaliações e reviews
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Dados do lugar
$post_id = get_the_ID();
$content = get_the_content();

// Meta fields do lugar
$endereco = get_post_meta($post_id, 'lugar_endereco', true);
$cep = get_post_meta($post_id, 'lugar_cep', true);
$telefone = get_post_meta($post_id, 'lugar_telefone', true);
$email = get_post_meta($post_id, 'lugar_email', true);
$website = get_post_meta($post_id, 'lugar_website', true);
$horario_funcionamento = get_post_meta($post_id, 'lugar_horario_funcionamento', true);
$latitude = get_post_meta($post_id, 'lugar_latitude', true);
$longitude = get_post_meta($post_id, 'lugar_longitude', true);
$faixa_preco = get_post_meta($post_id, 'lugar_faixa_preco', true);
$especialidades = get_post_meta($post_id, 'lugar_especialidades', true);

// Taxonomias
$tipos_lugares = get_the_terms($post_id, 'tipos_lugares');
$bairros = get_the_terms($post_id, 'bairros_recife');
$categorias = get_the_terms($post_id, 'categorias_lugares');

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_contact_info' => true,
    'show_opening_hours' => true,
    'show_specialties' => true,
    'show_price_range' => true,
    'show_reviews' => true,
    'show_gallery' => true,
    'enable_directions' => true,
    'enable_call_button' => true
]);

// Status de funcionamento (simulado - pode ser integrado com API real)
$status_funcionamento = 'aberto';
$status_class = 'bg-green-100 text-green-800';
$status_text = 'Aberto Agora';

// Lógica básica de horário (pode ser expandida)
$hora_atual = date('H:i');
if ($hora_atual < '08:00' || $hora_atual > '22:00') {
    $status_funcionamento = 'fechado';
    $status_class = 'bg-red-100 text-red-800';
    $status_text = 'Fechado';
}

// Avaliação simulada (pode ser integrada com sistema real)
$avaliacao_media = 4.2;
$total_avaliacoes = 127;

// Ícones por faixa de preço
$preco_icons = [
    '$' => '💰',
    '$$' => '💰💰',
    '$$$' => '💰💰💰',
    '$$$$' => '💰💰💰💰'
];
?>

<article class="single-content-lugar bg-white">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                
                <!-- Status e Informações Básicas -->
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <!-- Status de Funcionamento -->
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr($status_class); ?>">
                        <div class="w-2 h-2 rounded-full bg-current animate-pulse"></div>
                        <?php echo esc_html($status_text); ?>
                    </span>
                    
                    <!-- Faixa de Preço -->
                    <?php if ($args['show_price_range'] && $faixa_preco): ?>
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <?php echo esc_html($preco_icons[$faixa_preco] ?? '💰'); ?>
                            Faixa de Preço: <?php echo esc_html($faixa_preco); ?>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Avaliação -->
                    <?php if ($args['show_reviews']): ?>
                        <div class="flex items-center gap-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-4 h-4 <?php echo $i <= $avaliacao_media ? 'text-yellow-500' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <span><?php echo number_format($avaliacao_media, 1); ?> (<?php echo number_format($total_avaliacoes); ?>)</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Descrição do Lugar -->
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-recife-primary prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900 prose-blockquote:border-l-4 prose-blockquote:border-recife-primary prose-blockquote:bg-gray-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:text-gray-700 mb-8">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>
                
                <?php if ($args['show_specialties'] && !empty($especialidades)): ?>
                    <!-- Especialidades -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Especialidades
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <?php foreach ($especialidades as $especialidade): ?>
                                <div class="bg-white rounded-lg p-3 border border-orange-200 text-center">
                                    <span class="text-2xl mb-2 block">🍽️</span>
                                    <h4 class="font-semibold text-gray-900 text-sm">
                                        <?php echo esc_html($especialidade); ?>
                                    </h4>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($args['show_opening_hours'] && $horario_funcionamento): ?>
                    <!-- Horários de Funcionamento -->
                    <div class="bg-blue-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Horários de Funcionamento
                        </h3>
                        
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <div class="whitespace-pre-line text-gray-700">
                                <?php echo esc_html($horario_funcionamento); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Tipos e Categorias -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <?php if (!empty($tipos_lugares)): ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Tipo de Estabelecimento</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($tipos_lugares as $tipo): ?>
                                    <a href="<?php echo get_term_link($tipo); ?>" 
                                       class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                        🏢 <?php echo esc_html($tipo->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($categorias)): ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Categorias</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($categorias as $categoria): ?>
                                    <a href="<?php echo get_term_link($categoria); ?>" 
                                       class="inline-flex items-center gap-1 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-green-200 transition-colors">
                                        🏷️ <?php echo esc_html($categoria->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Galeria de Fotos -->
                <?php if ($args['show_gallery']): 
                    $gallery_images = get_post_meta($post_id, 'lugar_galeria', true);
                    if (!empty($gallery_images)):
                ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Galeria de Fotos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php foreach ($gallery_images as $image_id): 
                                $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                ?>
                                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer group" 
                                     onclick="openLightbox('<?php echo esc_url($image_url); ?>', '<?php echo esc_attr($image_alt); ?>')">
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                         alt="<?php echo esc_attr($image_alt); ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; endif; ?>
                
                <!-- Reviews e Avaliações -->
                <?php if ($args['show_reviews']): ?>
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Avaliações dos Visitantes
                        </h3>
                        
                        <!-- Resumo das Avaliações -->
                        <div class="bg-white rounded-lg p-4 border border-gray-200 mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="text-3xl font-bold text-gray-900">
                                        <?php echo number_format($avaliacao_media, 1); ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-5 h-5 <?php echo $i <= $avaliacao_media ? 'text-yellow-500' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Baseado em <?php echo number_format($total_avaliacoes); ?> avaliações
                                        </p>
                                    </div>
                                </div>
                                
                                <button type="button" 
                                        onclick="toggleReviewForm()"
                                        class="bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Avaliar Local
                                </button>
                            </div>
                            
                            <!-- Distribuição das Estrelas -->
                            <div class="space-y-2">
                                <?php for ($star = 5; $star >= 1; $star--): 
                                    $percentage = rand(10, 90); // Simulado
                                ?>
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="w-8"><?php echo $star; ?>★</span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <span class="w-12 text-gray-600"><?php echo $percentage; ?>%</span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <!-- Formulário de Avaliação (Oculto) -->
                        <div id="review-form" class="hidden bg-white rounded-lg p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4">Deixe sua avaliação</h4>
                            <form onsubmit="submitReview(event)">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Sua avaliação</label>
                                        <div class="flex gap-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <button type="button" 
                                                        onclick="setRating(<?php echo $i; ?>)"
                                                        class="rating-star w-8 h-8 text-gray-300 hover:text-yellow-500 transition-colors">
                                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </button>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-value" value="0">
                                    </div>
                                    
                                    <div>
                                        <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-2">Comentário</label>
                                        <textarea name="comment" 
                                                  id="review-comment" 
                                                  rows="3" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-recife-primary focus:border-transparent"
                                                  placeholder="Conte sobre sua experiência..."></textarea>
                                    </div>
                                    
                                    <div class="flex gap-3">
                                        <button type="submit" 
                                                class="bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Enviar Avaliação
                                        </button>
                                        <button type="button" 
                                                onclick="toggleReviewForm()"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar com Informações de Contato -->
            <div class="lg:col-span-1">
                
                <?php if ($args['show_contact_info']): ?>
                    <!-- Informações de Contato -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Informações de Contato
                        </h3>
                        
                        <div class="space-y-4">
                            
                            <!-- Endereço -->
                            <?php if ($endereco): ?>
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Endereço</p>
                                        <p class="text-sm text-gray-600">
                                            <?php echo esc_html($endereco); ?>
                                            <?php if ($cep): ?>
                                                <br>CEP: <?php echo esc_html($cep); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Telefone -->
                            <?php if ($telefone): ?>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Telefone</p>
                                        <a href="tel:<?php echo esc_attr($telefone); ?>" 
                                           class="text-sm text-recife-primary hover:text-recife-primary/80 transition-colors">
                                            <?php echo esc_html($telefone); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Email -->
                            <?php if ($email): ?>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Email</p>
                                        <a href="mailto:<?php echo esc_attr($email); ?>" 
                                           class="text-sm text-recife-primary hover:text-recife-primary/80 transition-colors">
                                            <?php echo esc_html($email); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Website -->
                            <?php if ($website): ?>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Website</p>
                                        <a href="<?php echo esc_url($website); ?>" 
                                           target="_blank"
                                           rel="noopener"
                                           class="text-sm text-recife-primary hover:text-recife-primary/80 transition-colors">
                                            Visitar Site
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Botões de Ação -->
                            <div class="pt-4 border-t border-gray-200 space-y-3">
                                
                                <?php if ($args['enable_directions'] && $endereco): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($endereco); ?>" 
                                       target="_blank"
                                       class="w-full flex items-center justify-center gap-2 bg-recife-primary hover:bg-recife-primary/90 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Como Chegar
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($args['enable_call_button'] && $telefone): ?>
                                    <a href="tel:<?php echo esc_attr($telefone); ?>" 
                                       class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                        Ligar Agora
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($website): ?>
                                    <a href="<?php echo esc_url($website); ?>" 
                                       target="_blank"
                                       rel="noopener"
                                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                        </svg>
                                        Site Oficial
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Mapa -->
                <?php if ($latitude && $longitude): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Localização</h3>
                        <?php echo do_shortcode('[recifemais_map post_id="' . $post_id . '" height="250px" zoom="16"]'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>

<script>
let currentRating = 0;

function toggleReviewForm() {
    const form = document.getElementById('review-form');
    form.classList.toggle('hidden');
}

function setRating(rating) {
    currentRating = rating;
    document.getElementById('rating-value').value = rating;
    
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-500');
        } else {
            star.classList.remove('text-yellow-500');
            star.classList.add('text-gray-300');
        }
    });
}

function submitReview(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const rating = formData.get('rating');
    const comment = formData.get('comment');
    
    if (rating == 0) {
        alert('Por favor, selecione uma avaliação.');
        return;
    }
    
    // Simular envio (substituir por integração real)
    alert('Obrigado pela sua avaliação! Ela será analisada e publicada em breve.');
    
    // Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'submit_review', {
            'place_id': '<?php echo get_the_ID(); ?>',
            'rating': rating
        });
    }
    
    toggleReviewForm();
    event.target.reset();
    currentRating = 0;
    setRating(0);
}

function openLightbox(imageUrl, imageAlt) {
    // Implementar lightbox para galeria
    console.log('Abrir lightbox:', imageUrl, imageAlt);
}
</script> 