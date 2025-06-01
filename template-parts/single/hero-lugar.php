<?php
/**
 * Template Part: Hero Lugar
 * 
 * Hero section específico para lugares com:
 * - Informações de endereço e contato
 * - Horário de funcionamento
 * - Faixa de preço e especialidades
 * - Botões de ação (direções, contato)
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
$title = get_the_title();
$excerpt = get_the_excerpt();
$featured_image = get_the_post_thumbnail_url($post_id, 'full');

// Meta fields do lugar (usando nomes corretos do plugin)
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

// Status de funcionamento
$status_funcionamento = 'fechado';
$status_label = 'Fechado';
$status_color = 'red';

// Verificar se está aberto (simplificado - pode ser melhorado)
$hora_atual = date('H:i');
if ($horario_funcionamento && strpos($horario_funcionamento, 'Segunda') !== false) {
    // Lógica básica - pode ser expandida
    $status_funcionamento = 'aberto';
    $status_label = 'Aberto';
    $status_color = 'green';
}

// Ícones por faixa de preço
$preco_icons = [
    '$' => '💰',
    '$$' => '💰💰',
    '$$$' => '💰💰💰',
    '$$$$' => '💰💰💰💰'
];
$preco_icon = $faixa_preco ? ($preco_icons[$faixa_preco] ?? '💰') : '';

// Configurações do hero
$args = wp_parse_args($args ?? [], [
    'show_breadcrumbs' => true,
    'show_social_share' => true,
    'show_map_link' => true,
    'show_contact_buttons' => true,
    'layout' => 'place' // place, restaurant, attraction
]);
?>

<section class="single-hero-lugar bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-900 text-white relative overflow-hidden">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><path d="M20 20c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10zm10 0c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10z"/></g></svg>');"></div>
    </div>
    
    <?php if ($args['show_breadcrumbs']): ?>
        <!-- Breadcrumbs -->
        <div class="relative z-10 border-b border-white/20">
            <div class="container mx-auto px-4 py-3">
                <nav class="text-sm text-white/80">
                    <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Início</a>
                    <span class="mx-2">›</span>
                    <a href="<?php echo get_post_type_archive_link('lugares'); ?>" class="hover:text-white transition-colors">Lugares</a>
                    <span class="mx-2">›</span>
                    <span class="text-white"><?php echo esc_html($title); ?></span>
                </nav>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="relative z-10 container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                
                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 bg-<?php echo esc_attr($status_color); ?>-600 text-white px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wide">
                        <?php if ($status_funcionamento === 'aberto'): ?>
                            <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                        <?php endif; ?>
                        <?php echo esc_html($status_label); ?>
                    </span>
                </div>
                
                <!-- Tipo/Categoria -->
                <?php if ($tipos_lugares): ?>
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 text-blue-200 text-sm font-semibold uppercase tracking-wide">
                            <span class="text-lg">📍</span>
                            <?php echo esc_html($tipos_lugares[0]->name); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <!-- Título -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    <?php echo esc_html($title); ?>
                </h1>
                
                <!-- Excerpt/Descrição -->
                <?php if ($excerpt): ?>
                    <div class="text-xl text-blue-100 leading-relaxed mb-8 max-w-3xl">
                        <?php echo esc_html($excerpt); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Informações Principais -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Endereço -->
                    <?php if ($endereco): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Endereço</h3>
                            </div>
                            <p class="text-blue-100 text-lg font-medium"><?php echo esc_html($endereco); ?></p>
                            <?php if ($cep): ?>
                                <p class="text-blue-200 text-sm">CEP: <?php echo esc_html($cep); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contato -->
                    <?php if ($telefone || $email): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Contato</h3>
                            </div>
                            <?php if ($telefone): ?>
                                <p class="text-blue-100 text-lg font-medium">
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>" 
                                       class="hover:text-white transition-colors">
                                        <?php echo esc_html($telefone); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            <?php if ($email): ?>
                                <p class="text-blue-200 text-sm">
                                    <a href="mailto:<?php echo esc_attr($email); ?>" 
                                       class="hover:text-white transition-colors">
                                        <?php echo esc_html($email); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Horário de Funcionamento -->
                    <?php if ($horario_funcionamento): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Horário</h3>
                            </div>
                            <div class="text-blue-100 text-sm whitespace-pre-line">
                                <?php echo esc_html($horario_funcionamento); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Faixa de Preço -->
                    <?php if ($faixa_preco): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="font-semibold text-white">Faixa de Preço</h3>
                            </div>
                            <p class="text-blue-100 text-lg font-medium">
                                <span class="text-2xl mr-2"><?php echo esc_html($preco_icon); ?></span>
                                <?php echo esc_html($faixa_preco); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Especialidades -->
                <?php if ($especialidades): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-white mb-3">Especialidades:</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $especialidades_array = is_array($especialidades) ? $especialidades : explode(',', $especialidades);
                            foreach ($especialidades_array as $especialidade): ?>
                                <span class="inline-block bg-blue-600/50 text-white px-3 py-1 rounded-full text-sm">
                                    <?php echo esc_html(trim($especialidade)); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Botões de Ação -->
                <div class="flex flex-wrap gap-4">
                    
                    <?php if ($args['show_map_link'] && $latitude && $longitude): ?>
                        <a href="https://www.google.com/maps?q=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Como Chegar
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($args['show_contact_buttons'] && $telefone): ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>" 
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            Ligar
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                            </svg>
                            Site Oficial
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($telefone): ?>
                        <a href="https://wa.me/55<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>?text=Olá! Vi o <?php echo urlencode($title); ?> no RecifeMais e gostaria de mais informações." 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar com Imagem -->
            <div class="lg:col-span-1">
                <?php if ($featured_image): ?>
                    <div class="relative">
                        <div class="aspect-square bg-white/10 rounded-xl overflow-hidden">
                            <img src="<?php echo esc_url($featured_image); ?>" 
                                 alt="<?php echo esc_attr($title); ?>"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Caption da imagem -->
                        <?php 
                        $image_caption = get_the_post_thumbnail_caption();
                        if ($image_caption): ?>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-3 rounded-b-xl">
                                <p class="text-sm"><?php echo esc_html($image_caption); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Informações Adicionais -->
                <div class="mt-6 space-y-4">
                    
                    <?php if ($bairros): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Bairro</h4>
                            <p class="text-blue-100"><?php echo esc_html($bairros[0]->name); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($categorias): ?>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Categoria</h4>
                            <p class="text-blue-100"><?php echo esc_html($categorias[0]->name); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Avaliação (placeholder para futuro) -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Avaliação</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-gray-300" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <span class="text-blue-100 text-sm">4.2 (Em breve)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 