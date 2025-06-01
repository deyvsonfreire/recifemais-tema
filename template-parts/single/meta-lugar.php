<?php
/**
 * Template Part: Meta Informações do Lugar
 * 
 * Exibe todas as informações específicas do CPT lugares
 * Utiliza todos os meta fields do plugin RecifeMais Core V2
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Verificar se é um lugar
if (get_post_type() !== 'lugares') {
    return;
}

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

// Formatação de dados
$endereco_completo = $endereco;
if ($bairros && !empty($bairros)) {
    $endereco_completo .= ', ' . $bairros[0]->name;
}
if ($cep) {
    $endereco_completo .= ' - CEP: ' . $cep;
}

// Ícones de preço
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

// Formatação de especialidades
$especialidades_formatadas = '';
if ($especialidades && class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
    $especialidades_array = is_array($especialidades) ? $especialidades : explode(',', $especialidades);
    $especialidades_labels = [];
    
    foreach ($especialidades_array as $especialidade) {
        $label = $dicionarios->get_label('especialidades_gastronomicas', trim($especialidade));
        if ($label) {
            $especialidades_labels[] = $label;
        }
    }
    
    $especialidades_formatadas = implode(', ', $especialidades_labels);
}

?>

<div class="lugar-meta-info bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <?php echo recifemais_get_icon_svg('info', '20', '#2563eb'); ?>
        </div>
        Informações do Local
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Endereço e Localização -->
        <?php if ($endereco_completo) : ?>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <?php echo recifemais_get_icon_svg('map-pin', '20', '#dc2626'); ?>
                    Localização
                </h3>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('map-pin', '16', '#dc2626'); ?>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900"><?php echo esc_html($endereco_completo); ?></p>
                            <?php if ($bairros && !empty($bairros)) : ?>
                                <p class="text-sm text-gray-600 mt-1">
                                    Bairro: <?php echo esc_html($bairros[0]->name); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Botões de Navegação -->
                    <?php if ($latitude && $longitude) : ?>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                               target="_blank"
                               class="inline-flex items-center gap-2 bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <?php echo recifemais_get_icon_svg('navigation', '16', '#ffffff'); ?>
                                Google Maps
                            </a>
                            <a href="https://waze.com/ul?ll=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>&navigate=yes" 
                               target="_blank"
                               class="inline-flex items-center gap-2 bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                                <?php echo recifemais_get_icon_svg('navigation', '16', '#ffffff'); ?>
                                Waze
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Contato -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('phone', '20', '#059669'); ?>
                Contato
            </h3>
            
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                
                <?php if ($telefone) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('phone', '16', '#059669'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Telefone</div>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefone)); ?>" 
                               class="font-medium text-green-600 hover:text-green-700 transition-colors">
                                <?php echo esc_html($telefone); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($email) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('mail', '16', '#2563eb'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">E-mail</div>
                            <a href="mailto:<?php echo esc_attr($email); ?>" 
                               class="font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($website) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('globe', '16', '#7c3aed'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Website</div>
                            <a href="<?php echo esc_url($website); ?>" 
                               target="_blank"
                               class="font-medium text-purple-600 hover:text-purple-700 transition-colors">
                                Visitar site
                                <?php echo recifemais_get_icon_svg('external-link', '14', '#7c3aed'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!$telefone && !$email && !$website) : ?>
                    <p class="text-gray-500 text-sm italic">
                        Informações de contato não disponíveis
                    </p>
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
    
    <!-- Informações Adicionais -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t border-gray-200">
        
        <!-- Horário de Funcionamento -->
        <?php if ($horario_funcionamento) : ?>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <?php echo recifemais_get_icon_svg('clock', '20', '#f59e0b'); ?>
                    Horário de Funcionamento
                </h3>
                
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('clock', '16', '#f59e0b'); ?>
                        </div>
                        <div class="flex-1">
                            <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                                <?php echo esc_html($horario_funcionamento); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Faixa de Preço e Especialidades -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('tag', '20', '#8b5cf6'); ?>
                Informações Gerais
            </h3>
            
            <div class="bg-purple-50 rounded-lg p-4 space-y-3">
                
                <?php if ($faixa_preco) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm"><?php echo esc_html($preco_icons); ?></span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Faixa de Preço</div>
                            <div class="font-medium text-purple-700">
                                <?php echo esc_html($preco_display); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($especialidades_formatadas) : ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('star', '16', '#ea580c'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Especialidades</div>
                            <div class="font-medium text-orange-700">
                                <?php echo esc_html($especialidades_formatadas); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($tipos_lugares && !empty($tipos_lugares)) : ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('grid', '16', '#2563eb'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Categoria</div>
                            <div class="font-medium text-blue-700">
                                <?php echo esc_html($tipos_lugares[0]->name); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
    
    <!-- Mapa Interativo -->
    <?php if ($latitude && $longitude) : ?>
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('map', '20', '#dc2626'); ?>
                Localização no Mapa
            </h3>
            
            <div class="bg-gray-100 rounded-lg overflow-hidden">
                <div id="lugar-mapa" class="w-full h-64"></div>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600 mb-3">
                    Clique nos botões abaixo para abrir no seu aplicativo de navegação preferido
                </p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" 
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <?php echo recifemais_get_icon_svg('map', '16', '#ffffff'); ?>
                        Ver no Google Maps
                    </a>
                    <a href="https://waze.com/ul?ll=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>&navigate=yes" 
                       target="_blank"
                       class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <?php echo recifemais_get_icon_svg('navigation', '16', '#ffffff'); ?>
                        Abrir no Waze
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
</div>

<!-- JavaScript para o mapa -->
<?php if ($latitude && $longitude) : ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapaContainer = document.getElementById('lugar-mapa');
    if (mapaContainer) {
        // Por enquanto, vamos usar um placeholder visual
        // Futuramente pode ser integrado com Google Maps ou OpenStreetMap
        mapaContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center relative">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <?php echo recifemais_get_icon_svg('map-pin', '32', '#ffffff'); ?>
                    </div>
                    <p class="text-gray-700 font-semibold"><?php echo esc_js(get_the_title()); ?></p>
                    <p class="text-gray-600 text-sm"><?php echo esc_js($endereco); ?></p>
                    <p class="text-gray-500 text-xs mt-2">
                        Lat: <?php echo esc_js($latitude); ?>, Lng: <?php echo esc_js($longitude); ?>
                    </p>
                </div>
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                    <span class="text-xs font-medium text-gray-700">Mapa Interativo</span>
                </div>
            </div>
        `;
    }
});
</script>
<?php endif; ?> 