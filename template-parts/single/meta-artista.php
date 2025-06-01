<?php
/**
 * Template Part: Meta Informações do Artista
 * 
 * Exibe todas as informações específicas do CPT artistas
 * Utiliza todos os meta fields do plugin RecifeMais Core V2
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Verificar se é um artista
if (get_post_type() !== 'artistas') {
    return;
}

// Meta fields do artista
$tipo_grupo = get_post_meta(get_the_ID(), 'artista_tipo_grupo', true);
$origem = get_post_meta(get_the_ID(), 'artista_origem', true);
$ano_formacao = get_post_meta(get_the_ID(), 'artista_ano_formacao', true);
$integrantes = get_post_meta(get_the_ID(), 'artista_integrantes', true);
$biografia = get_post_meta(get_the_ID(), 'artista_biografia', true);
$redes_sociais = get_post_meta(get_the_ID(), 'artista_redes_sociais', true);
$ritmos = get_post_meta(get_the_ID(), 'artista_ritmos', true);
$generos = get_post_meta(get_the_ID(), 'artista_generos', true);
$publico_alvo = get_post_meta(get_the_ID(), 'artista_publico_alvo', true);
$site_oficial = get_post_meta(get_the_ID(), 'artista_site_oficial', true);

// Taxonomias
$generos_musicais = get_the_terms(get_the_ID(), 'generos_musicais');
$tipos_artistas = get_the_terms(get_the_ID(), 'tipos_artistas');
$bairros = get_the_terms(get_the_ID(), 'bairros_recife');

// Ícones por tipo de grupo
$tipo_icons = [
    'solo' => '🎤',
    'banda' => '🎸',
    'grupo' => '👥',
    'coletivo' => '🎭',
    'orquestra' => '🎼',
    'coral' => '🎵'
];
$tipo_icon = $tipo_grupo ? ($tipo_icons[strtolower($tipo_grupo)] ?? '🎨') : '🎨';

// Formatação de ritmos e gêneros
$ritmos_formatados = '';
$generos_formatados = '';

if ($ritmos && class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
    $ritmos_array = is_array($ritmos) ? $ritmos : explode(',', $ritmos);
    $ritmos_labels = [];
    
    foreach ($ritmos_array as $ritmo) {
        $label = $dicionarios->get_label('ritmos_musicais', trim($ritmo));
        if ($label) {
            $ritmos_labels[] = $label;
        }
    }
    
    $ritmos_formatados = implode(', ', $ritmos_labels);
}

if ($generos && class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
    $generos_array = is_array($generos) ? $generos : explode(',', $generos);
    $generos_labels = [];
    
    foreach ($generos_array as $genero) {
        $label = $dicionarios->get_label('generos_musicais', trim($genero));
        if ($label) {
            $generos_labels[] = $label;
        }
    }
    
    $generos_formatados = implode(', ', $generos_labels);
}

// Formatação de público-alvo
$publico_formatado = '';
if ($publico_alvo && class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
    $publico_formatado = $dicionarios->get_label('publico_alvo', $publico_alvo);
}

// Formatação de redes sociais
$redes_formatadas = [];
if ($redes_sociais) {
    $redes_lines = explode("\n", $redes_sociais);
    foreach ($redes_lines as $linha) {
        $linha = trim($linha);
        if (!empty($linha)) {
            // Detectar tipo de rede social
            if (strpos($linha, 'instagram') !== false || strpos($linha, '@') !== false) {
                $redes_formatadas['instagram'] = $linha;
            } elseif (strpos($linha, 'facebook') !== false) {
                $redes_formatadas['facebook'] = $linha;
            } elseif (strpos($linha, 'youtube') !== false) {
                $redes_formatadas['youtube'] = $linha;
            } elseif (strpos($linha, 'spotify') !== false) {
                $redes_formatadas['spotify'] = $linha;
            } else {
                $redes_formatadas['outros'][] = $linha;
            }
        }
    }
}

?>

<div class="artista-meta-info bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <?php echo recifemais_get_icon_svg('info', '20', '#7c3aed'); ?>
        </div>
        Informações do Artista
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Informações Básicas -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('user', '20', '#7c3aed'); ?>
                Dados Básicos
            </h3>
            
            <div class="bg-purple-50 rounded-lg p-4 space-y-3">
                
                <?php if ($tipo_grupo) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm"><?php echo esc_html($tipo_icon); ?></span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Tipo</div>
                            <div class="font-medium text-purple-700">
                                <?php echo esc_html(ucfirst($tipo_grupo)); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($origem) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('map-pin', '16', '#059669'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Origem</div>
                            <div class="font-medium text-green-700">
                                <?php echo esc_html($origem); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($ano_formacao) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('calendar', '16', '#2563eb'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">
                                <?php echo $tipo_grupo === 'solo' ? 'Início da Carreira' : 'Ano de Formação'; ?>
                            </div>
                            <div class="font-medium text-blue-700">
                                <?php echo esc_html($ano_formacao); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($publico_formatado) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('users', '16', '#ea580c'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Público-Alvo</div>
                            <div class="font-medium text-orange-700">
                                <?php echo esc_html($publico_formatado); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Contato e Links -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('link', '20', '#059669'); ?>
                Contato & Links
            </h3>
            
            <div class="bg-green-50 rounded-lg p-4 space-y-3">
                
                <?php if ($site_oficial) : ?>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <?php echo recifemais_get_icon_svg('globe', '16', '#059669'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Site Oficial</div>
                            <a href="<?php echo esc_url($site_oficial); ?>" 
                               target="_blank"
                               class="font-medium text-green-600 hover:text-green-700 transition-colors">
                                Visitar site
                                <?php echo recifemais_get_icon_svg('external-link', '14', '#059669'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($redes_formatadas)) : ?>
                    <div class="space-y-2">
                        <div class="text-sm text-gray-600 font-medium">Redes Sociais</div>
                        <div class="flex flex-wrap gap-2">
                            
                            <?php if (isset($redes_formatadas['instagram'])) : ?>
                                <a href="<?php echo esc_url($redes_formatadas['instagram']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm hover:bg-pink-200 transition-colors">
                                    📷 Instagram
                                </a>
                            <?php endif; ?>
                            
                            <?php if (isset($redes_formatadas['facebook'])) : ?>
                                <a href="<?php echo esc_url($redes_formatadas['facebook']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition-colors">
                                    📘 Facebook
                                </a>
                            <?php endif; ?>
                            
                            <?php if (isset($redes_formatadas['youtube'])) : ?>
                                <a href="<?php echo esc_url($redes_formatadas['youtube']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm hover:bg-red-200 transition-colors">
                                    📺 YouTube
                                </a>
                            <?php endif; ?>
                            
                            <?php if (isset($redes_formatadas['spotify'])) : ?>
                                <a href="<?php echo esc_url($redes_formatadas['spotify']); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm hover:bg-green-200 transition-colors">
                                    🎵 Spotify
                                </a>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!$site_oficial && empty($redes_formatadas)) : ?>
                    <p class="text-gray-500 text-sm italic">
                        Links não disponíveis
                    </p>
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
    
    <!-- Informações Musicais -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t border-gray-200">
        
        <!-- Estilo Musical -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('music', '20', '#f59e0b'); ?>
                Estilo Musical
            </h3>
            
            <div class="bg-yellow-50 rounded-lg p-4 space-y-3">
                
                <?php if ($ritmos_formatados) : ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('music', '16', '#f59e0b'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Ritmos</div>
                            <div class="font-medium text-yellow-700">
                                <?php echo esc_html($ritmos_formatados); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($generos_formatados) : ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('headphones', '16', '#ea580c'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Gêneros</div>
                            <div class="font-medium text-orange-700">
                                <?php echo esc_html($generos_formatados); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($generos_musicais && !empty($generos_musicais)) : ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('tag', '16', '#7c3aed'); ?>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Categoria</div>
                            <div class="font-medium text-purple-700">
                                <?php echo esc_html($generos_musicais[0]->name); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!$ritmos_formatados && !$generos_formatados && (!$generos_musicais || empty($generos_musicais))) : ?>
                    <p class="text-gray-500 text-sm italic">
                        Informações musicais não disponíveis
                    </p>
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Integrantes -->
        <?php if ($integrantes) : ?>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <?php echo recifemais_get_icon_svg('users', '20', '#8b5cf6'); ?>
                    <?php echo $tipo_grupo === 'solo' ? 'Informações Adicionais' : 'Integrantes'; ?>
                </h3>
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <?php echo recifemais_get_icon_svg('users', '16', '#8b5cf6'); ?>
                        </div>
                        <div class="flex-1">
                            <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                                <?php echo esc_html($integrantes); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
    
    <!-- Biografia Adicional -->
    <?php if ($biografia && $biografia !== get_the_content()) : ?>
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('file-text', '20', '#6366f1'); ?>
                Biografia Detalhada
            </h3>
            
            <div class="bg-indigo-50 rounded-lg p-4">
                <div class="prose prose-sm max-w-none text-gray-700">
                    <?php echo wp_kses_post(wpautop($biografia)); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Taxonomias -->
    <?php if (($tipos_artistas && !empty($tipos_artistas)) || ($bairros && !empty($bairros))) : ?>
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <?php echo recifemais_get_icon_svg('tag', '20', '#dc2626'); ?>
                Categorias
            </h3>
            
            <div class="flex flex-wrap gap-2">
                
                <?php if ($tipos_artistas && !empty($tipos_artistas)) : ?>
                    <?php foreach ($tipos_artistas as $tipo) : ?>
                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                            <?php echo recifemais_get_icon_svg('user', '14', '#dc2626'); ?>
                            <?php echo esc_html($tipo->name); ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if ($bairros && !empty($bairros)) : ?>
                    <?php foreach ($bairros as $bairro) : ?>
                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <?php echo recifemais_get_icon_svg('map-pin', '14', '#2563eb'); ?>
                            <?php echo esc_html($bairro->name); ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
        </div>
    <?php endif; ?>
    
</div> 