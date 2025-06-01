<?php
/**
 * Single Template: Lugares
 * Inspirado no layout de referência do Agenda Viva SP
 * 
 * Utiliza todos os meta fields do plugin RecifeMais Core V2:
 * - lugar_endereco, lugar_cep, lugar_telefone, lugar_email
 * - lugar_website, lugar_horario_funcionamento
 * - lugar_latitude, lugar_longitude
 * - lugar_faixa_preco, lugar_especialidades
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header();

while (have_posts()) : the_post();

// Meta fields do lugar
$endereco = get_post_meta(get_the_ID(), 'lugar_endereco', true);
$cep = get_post_meta(get_the_ID(), 'lugar_cep', true);
$telefone = get_post_meta(get_the_ID(), 'lugar_telefone', true);
$email = get_post_meta(get_the_ID(), 'lugar_email', true);
$website = get_post_meta(get_the_ID(), 'lugar_website', true);
$horario_funcionamento = get_post_meta(get_the_ID(), 'lugar_horario_funcionamento', true);
$latitude = get_post_meta(get_the_ID(), 'lugar_latitude', true);
$longitude = get_post_meta(get_the_ID(), 'lugar_longitude', true);
$faixa_preco = get_post_meta(get_the_ID(), 'lugar_faixa_preco', true);
$especialidades = get_post_meta(get_the_ID(), 'lugar_especialidades', true);

// Taxonomias
$tipos_lugares = get_the_terms(get_the_ID(), 'tipos_lugares');
$bairros = get_the_terms(get_the_ID(), 'bairros_recife');
$categorias = get_the_terms(get_the_ID(), 'categorias_lugares');

// Imagem destacada
$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
$placeholder_image = get_template_directory_uri() . '/assets/images/placeholder-lugar.jpg';
$hero_image = $featured_image ?: $placeholder_image;

// Status do lugar (baseado em horário de funcionamento)
$status_lugar = 'Informações não disponíveis';
$status_class = 'bg-gray-100 text-gray-700';

if ($horario_funcionamento) {
    // Lógica simples para determinar se está aberto
    $hora_atual = current_time('H:i');
    $dia_semana = current_time('w'); // 0 = domingo, 6 = sábado
    
    // Por simplicidade, vamos assumir que está aberto se tem horário cadastrado
    $status_lugar = 'Consulte horários';
    $status_class = 'bg-blue-100 text-blue-700';
}

// Formatação de endereço completo
$endereco_completo = $endereco;
if ($bairros && !empty($bairros)) {
    $endereco_completo .= ', ' . $bairros[0]->name;
}

// Formatação de preço
$preco_display = '';
$preco_icons = '';
switch ($faixa_preco) {
    case 'economico':
        $preco_display = 'Econômico';
        $preco_icons = '💰';
        break;
    case 'moderado':
        $preco_display = 'Moderado';
        $preco_icons = '💰💰';
        break;
    case 'caro':
        $preco_display = 'Caro';
        $preco_icons = '💰💰💰';
        break;
    case 'muito_caro':
        $preco_display = 'Muito Caro';
        $preco_icons = '💰💰💰💰';
        break;
}

?>

<main class="single-lugar bg-gray-50 min-h-screen">
    
    <!-- Hero Section -->
    <section class="hero-lugar relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent z-10"></div>
        
        <!-- Imagem de fundo -->
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($hero_image); ?>" 
                 alt="<?php echo esc_attr(get_the_title()); ?>"
                 class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
        </div>
        
        <!-- Conteúdo sobreposto -->
        <div class="relative z-20 container mx-auto px-4 py-16 lg:py-24">
            <div class="max-w-4xl">
                
                <!-- Breadcrumbs -->
                <nav class="mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 text-sm text-gray-200">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">
                                Início
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <a href="<?php echo get_post_type_archive_link('lugares'); ?>" class="hover:text-white transition-colors">
                                Lugares
                            </a>
                        </li>
                        <?php if ($tipos_lugares && !empty($tipos_lugares)) : ?>
                            <li class="flex items-center gap-2">
                                <span>›</span>
                                <a href="<?php echo add_query_arg('tipo', $tipos_lugares[0]->slug, get_post_type_archive_link('lugares')); ?>" 
                                   class="hover:text-white transition-colors">
                                    <?php echo esc_html($tipos_lugares[0]->name); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="flex items-center gap-2">
                            <span>›</span>
                            <span class="text-white font-medium"><?php the_title(); ?></span>
                        </li>
                    </ol>
                </nav>
                
                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 <?php echo esc_attr($status_class); ?> px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-sm">
                        <span class="w-2 h-2 bg-current rounded-full animate-pulse"></span>
                        <?php echo esc_html($status_lugar); ?>
                    </span>
                </div>
                
                <!-- Título e Categoria -->
                <div class="mb-8">
                    <?php if ($tipos_lugares && !empty($tipos_lugares)) : ?>
                        <div class="mb-3">
                            <span class="inline-block bg-blue-600/90 text-white px-4 py-2 rounded-lg text-sm font-semibold backdrop-blur-sm">
                                <?php echo esc_html($tipos_lugares[0]->name); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="text-4xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                        <?php the_title(); ?>
                    </h1>
                    
                    <?php if ($endereco_completo) : ?>
                        <p class="text-xl text-gray-200 flex items-center gap-2">
                            <?php echo recifemais_get_icon_svg('map-pin', '20', '#e5e7eb'); ?>
                            <?php echo esc_html($endereco_completo); ?>
                        </p>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Meta Informações Rápidas -->
    <section class="meta-rapida bg-white shadow-lg border-b border-gray-200 -mt-8 relative z-30">
        <div class="container mx-auto px-4 py-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Horário -->
                    <?php if ($horario_funcionamento) : ?>
                        <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <?php echo recifemais_get_icon_svg('clock', '24', '#f59e0b'); ?>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-yellow-800">Funcionamento</div>
                                <div class="text-yellow-700 text-sm">
                                    <?php echo esc_html(wp_trim_words($horario_funcionamento, 4)); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Preço -->
                    <?php if ($faixa_preco) : ?>
                        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl border border-green-200">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-lg"><?php echo esc_html($preco_icons); ?></span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-green-800">Faixa de Preço</div>
                                <div class="text-green-700 font-medium">
                                    <?php echo esc_html($preco_display); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contato -->
                    <?php if ($telefone) : ?>
                        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <?php echo recifemais_get_icon_svg('phone', '24', '#2563eb'); ?>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-blue-800">Telefone</div>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>" 
                                   class="text-blue-700 font-medium hover:text-blue-800 transition-colors">
                                    <?php echo esc_html($telefone); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Localização -->
                    <?php if ($latitude && $longitude) : ?>
                        <div class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border border-red-200">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <?php echo recifemais_get_icon_svg('map-pin', '24', '#dc2626'); ?>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-red-800">Localização</div>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                                   target="_blank"
                                   class="text-red-700 font-medium hover:text-red-800 transition-colors">
                                    Ver no mapa
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- Botões de Ação -->
    <section class="acoes-lugar bg-white py-6 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                    
                    <!-- Ligar -->
                    <?php if ($telefone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>" 
                           class="inline-flex items-center gap-3 bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <?php echo recifemais_get_icon_svg('phone', '20', '#ffffff'); ?>
                            Ligar Agora
                        </a>
                    <?php endif; ?>
                    
                    <!-- Website -->
                    <?php if ($website) : ?>
                        <a href="<?php echo esc_url($website); ?>" 
                           target="_blank"
                           class="inline-flex items-center gap-3 bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <?php echo recifemais_get_icon_svg('globe', '20', '#ffffff'); ?>
                            Visitar Site
                            <?php echo recifemais_get_icon_svg('external-link', '16', '#ffffff'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Compartilhar -->
                    <button id="btn-compartilhar" 
                            class="inline-flex items-center gap-3 bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <?php echo recifemais_get_icon_svg('share', '20', '#ffffff'); ?>
                        Compartilhar
                    </button>
                    
                    <!-- Direções -->
                    <?php if ($latitude && $longitude) : ?>
                        <div class="relative">
                            <button id="btn-direcoes" 
                                    class="inline-flex items-center gap-3 bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <?php echo recifemais_get_icon_svg('navigation', '20', '#ffffff'); ?>
                                Como Chegar
                                <?php echo recifemais_get_icon_svg('chevron-down', '16', '#ffffff'); ?>
                            </button>
                            
                            <!-- Dropdown de direções -->
                            <div id="dropdown-direcoes" class="absolute top-full left-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 py-2 min-w-48 z-50 hidden">
                                <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                                   target="_blank"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                    <?php echo recifemais_get_icon_svg('map', '16', '#2563eb'); ?>
                                    <span class="text-gray-700">Google Maps</span>
                                </a>
                                <a href="https://waze.com/ul?ll=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>&navigate=yes" 
                                   target="_blank"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                    <?php echo recifemais_get_icon_svg('navigation', '16', '#7c3aed'); ?>
                                    <span class="text-gray-700">Waze</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Conteúdo Principal -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Descrição -->
                    <?php if (get_the_content()) : ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('file-text', '20', '#2563eb'); ?>
                                </div>
                                Sobre o Local
                            </h2>
                            
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Especialidades -->
                    <?php if ($especialidades) : ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('star', '20', '#ea580c'); ?>
                                </div>
                                Especialidades
                            </h2>
                            
                            <div class="bg-orange-50 rounded-lg p-6">
                                <?php
                                // Formatação de especialidades
                                if (class_exists('RecifeMais_V2_Dicionarios')) {
                                    $dicionarios = new RecifeMais_V2_Dicionarios();
                                    $especialidades_array = is_array($especialidades) ? $especialidades : explode(',', $especialidades);
                                    $especialidades_labels = [];
                                    
                                    foreach ($especialidades_array as $especialidade) {
                                        $label = $dicionarios->get_label('especialidades_gastronomicas', trim($especialidade));
                                        if ($label) {
                                            $especialidades_labels[] = $label;
                                        }
                                    }
                                    
                                    if (!empty($especialidades_labels)) {
                                        echo '<div class="flex flex-wrap gap-2">';
                                        foreach ($especialidades_labels as $especialidade_label) {
                                            echo '<span class="bg-orange-100 text-orange-800 px-3 py-2 rounded-lg text-sm font-medium">' . esc_html($especialidade_label) . '</span>';
                                        }
                                        echo '</div>';
                                    } else {
                                        echo '<p class="text-orange-700">' . esc_html($especialidades) . '</p>';
                                    }
                                } else {
                                    echo '<p class="text-orange-700">' . esc_html($especialidades) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mapa Interativo -->
                    <?php if ($latitude && $longitude) : ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <?php echo recifemais_get_icon_svg('map', '20', '#dc2626'); ?>
                                </div>
                                Localização
                            </h2>
                            
                            <div class="bg-gray-100 rounded-lg overflow-hidden mb-6">
                                <div id="lugar-mapa-principal" class="w-full h-80"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                                   target="_blank"
                                   class="flex items-center justify-center gap-3 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    <?php echo recifemais_get_icon_svg('map', '20', '#ffffff'); ?>
                                    Abrir no Google Maps
                                </a>
                                <a href="https://waze.com/ul?ll=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>&navigate=yes" 
                                   target="_blank"
                                   class="flex items-center justify-center gap-3 bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
                                    <?php echo recifemais_get_icon_svg('navigation', '20', '#ffffff'); ?>
                                    Navegar com Waze
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Meta Informações Detalhadas -->
                    <?php get_template_part('template-parts/single/meta-lugar'); ?>
                    
                    <!-- Categorias e Tags -->
                    <?php if (($tipos_lugares && !empty($tipos_lugares)) || ($categorias && !empty($categorias))) : ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <?php echo recifemais_get_icon_svg('tag', '18', '#6b7280'); ?>
                                Categorias
                            </h3>
                            
                            <div class="space-y-4">
                                <?php if ($tipos_lugares && !empty($tipos_lugares)) : ?>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Tipo de Local</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($tipos_lugares as $tipo) : ?>
                                                <a href="<?php echo add_query_arg('tipo', $tipo->slug, get_post_type_archive_link('lugares')); ?>" 
                                                   class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition-colors">
                                                    <?php echo esc_html($tipo->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($categorias && !empty($categorias)) : ?>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Categorias</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($categorias as $categoria) : ?>
                                                <a href="<?php echo get_term_link($categoria); ?>" 
                                                   class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm hover:bg-purple-200 transition-colors">
                                                    <?php echo esc_html($categoria->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Newsletter Signup -->
                    <?php get_template_part('template-parts/homepage/newsletter-signup'); ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Lugares Relacionados -->
    <section class="lugares-relacionados bg-white py-16 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Lugares Similares
                    </h2>
                    <p class="text-xl text-gray-600">
                        Outros lugares que você pode gostar
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    // Query para lugares relacionados
                    $related_args = [
                        'post_type' => 'lugares',
                        'posts_per_page' => 6,
                        'post__not_in' => [get_the_ID()],
                        'orderby' => 'rand'
                    ];
                    
                    // Se tem tipo de lugar, buscar do mesmo tipo
                    if ($tipos_lugares && !empty($tipos_lugares)) {
                        $related_args['tax_query'] = [
                            [
                                'taxonomy' => 'tipos_lugares',
                                'field' => 'term_id',
                                'terms' => $tipos_lugares[0]->term_id
                            ]
                        ];
                    }
                    
                    $related_query = new WP_Query($related_args);
                    
                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) : $related_query->the_post();
                            
                            // Usar o component card-lugar existente
                            get_template_part('components/cards/card-lugar', null, [
                                'post_id' => get_the_ID(),
                                'variant' => 'standard',
                                'size' => 'md',
                                'show_meta' => true,
                                'show_badge' => true,
                                'show_excerpt' => false,
                                'classes' => ['lugar-relacionado']
                            ]);
                            
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="col-span-full text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <?php echo recifemais_get_icon_svg('map-pin', '32', '#9ca3af'); ?>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                Nenhum lugar relacionado encontrado
                            </h3>
                            <p class="text-gray-600 mb-6">
                                Explore nossa lista completa de lugares incríveis
                            </p>
                            <a href="<?php echo get_post_type_archive_link('lugares'); ?>" 
                               class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                Ver todos os lugares
                                <?php echo recifemais_get_icon_svg('arrow-right', '16', '#ffffff'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-12">
                    <a href="<?php echo get_post_type_archive_link('lugares'); ?>" 
                       class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors text-lg">
                        Ver todos os lugares
                        <?php echo recifemais_get_icon_svg('arrow-right', '20', '#2563eb'); ?>
                    </a>
                </div>
                
            </div>
        </div>
    </section>
    
</main>

<!-- JavaScript específico para lugares -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Compartilhamento
    const btnCompartilhar = document.getElementById('btn-compartilhar');
    if (btnCompartilhar) {
        btnCompartilhar.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo esc_js(get_the_title()); ?>',
                    text: 'Confira este lugar incrível: <?php echo esc_js(get_the_title()); ?>',
                    url: window.location.href
                });
            } else {
                // Fallback para navegadores sem suporte
                const url = window.location.href;
                const text = 'Confira este lugar incrível: <?php echo esc_js(get_the_title()); ?> - ' + url;
                
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(function() {
                        alert('Link copiado para a área de transferência!');
                    });
                } else {
                    // Fallback ainda mais antigo
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Link copiado para a área de transferência!');
                }
            }
        });
    }
    
    // Dropdown de direções
    const btnDirecoes = document.getElementById('btn-direcoes');
    const dropdownDirecoes = document.getElementById('dropdown-direcoes');
    
    if (btnDirecoes && dropdownDirecoes) {
        btnDirecoes.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownDirecoes.classList.toggle('hidden');
        });
        
        // Fechar dropdown ao clicar fora
        document.addEventListener('click', function(e) {
            if (!btnDirecoes.contains(e.target) && !dropdownDirecoes.contains(e.target)) {
                dropdownDirecoes.classList.add('hidden');
            }
        });
    }
    
    // Mapa principal
    const mapaContainer = document.getElementById('lugar-mapa-principal');
    if (mapaContainer) {
        // Por enquanto, vamos usar um placeholder visual
        // Futuramente pode ser integrado com Google Maps ou OpenStreetMap
        mapaContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center relative">
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <?php echo recifemais_get_icon_svg('map-pin', '40', '#ffffff'); ?>
                    </div>
                    <h3 class="text-gray-800 font-bold text-lg mb-2"><?php echo esc_js(get_the_title()); ?></h3>
                    <p class="text-gray-600"><?php echo esc_js($endereco); ?></p>
                    <?php if ($latitude && $longitude) : ?>
                    <p class="text-gray-500 text-sm mt-3">
                        Coordenadas: <?php echo esc_js($latitude); ?>, <?php echo esc_js($longitude); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg px-4 py-2">
                    <span class="text-sm font-medium text-gray-700">Mapa Interativo</span>
                </div>
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm rounded-lg px-4 py-2">
                    <span class="text-xs text-gray-600">Clique nos botões abaixo para navegar</span>
                </div>
            </div>
        `;
    }
    
});
</script>

<?php endwhile; ?>

<?php get_footer(); ?> 