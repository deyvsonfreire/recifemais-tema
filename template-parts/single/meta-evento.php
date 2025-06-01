<?php
/**
 * Template Part: Meta Fields de Eventos
 * 
 * Exibe informações específicas de eventos usando os meta fields
 * do plugin RecifeMais Core V2
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();
$post_type = get_post_type($post_id);

// Só funciona para eventos
if ($post_type !== 'eventos_festivais') {
    return;
}

// Meta fields do plugin RecifeMais Core V2
$evento_data_inicio = get_post_meta($post_id, 'evento_data_inicio', true);
$evento_data_fim = get_post_meta($post_id, 'evento_data_fim', true);
$evento_horario_inicio = get_post_meta($post_id, 'evento_horario_inicio', true);
$evento_horario_fim = get_post_meta($post_id, 'evento_horario_fim', true);
$evento_preco = get_post_meta($post_id, 'evento_preco', true);
$evento_local = get_post_meta($post_id, 'evento_local', true);
$evento_organizador = get_post_meta($post_id, 'evento_organizador', true);
$evento_atracoes = get_post_meta($post_id, 'evento_atracoes', true);
$evento_link_inscricao = get_post_meta($post_id, 'evento_link_inscricao', true);
$evento_contato = get_post_meta($post_id, 'evento_contato', true);
$evento_tipos = get_post_meta($post_id, 'evento_tipos', true);
$evento_publico_alvo = get_post_meta($post_id, 'evento_publico_alvo', true);

// Dados relacionados
$local_nome = $evento_local ? get_the_title($evento_local) : '';
$local_endereco = $evento_local ? get_post_meta($evento_local, 'lugar_endereco', true) : '';
$organizador_nome = $evento_organizador ? get_the_title($evento_organizador) : '';

// Taxonomias
$tipos_eventos = get_the_terms($post_id, 'tipos_eventos');
$manifestacoes = get_the_terms($post_id, 'manifestacoes_culturais');
$bairros = get_the_terms($post_id, 'bairros_recife');

// Dicionários do plugin (se disponível)
$dicionarios = null;
if (class_exists('RecifeMais_V2_Dicionarios')) {
    $dicionarios = new RecifeMais_V2_Dicionarios();
}

// Formatação de data e hora
$data_formatada = '';
$horario_formatado = '';

if ($evento_data_inicio) {
    $data_obj = DateTime::createFromFormat('Y-m-d', $evento_data_inicio);
    if ($data_obj) {
        $data_formatada = $data_obj->format('d/m/Y');
        if ($evento_data_fim && $evento_data_fim !== $evento_data_inicio) {
            $data_fim_obj = DateTime::createFromFormat('Y-m-d', $evento_data_fim);
            if ($data_fim_obj) {
                $data_formatada .= ' a ' . $data_fim_obj->format('d/m/Y');
            }
        }
    }
}

if ($evento_horario_inicio) {
    $horario_formatado = $evento_horario_inicio;
    if ($evento_horario_fim && $evento_horario_fim !== $evento_horario_inicio) {
        $horario_formatado .= ' às ' . $evento_horario_fim;
    }
}

// Só exibe se há informações para mostrar
$has_meta_info = $data_formatada || $horario_formatado || $local_nome || $evento_preco || $organizador_nome;

if (!$has_meta_info) {
    return;
}
?>

<div class="evento-meta-fields bg-white rounded-xl shadow-sm border border-recife-gray-200 p-6 mb-8">
    
    <!-- Título da Seção -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-recife-gray-100">
        <div class="w-10 h-10 bg-cpt-eventos rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-recife-gray-900">Informações do Evento</h3>
            <p class="text-sm text-recife-gray-600">Detalhes importantes para participar</p>
        </div>
    </div>
    
    <!-- Grid de Informações -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Data e Horário -->
        <?php if ($data_formatada || $horario_formatado): ?>
            <div class="space-y-4">
                <?php if ($data_formatada): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            📅
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Data do Evento</dt>
                            <dd class="text-recife-gray-700"><?php echo esc_html($data_formatada); ?></dd>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($horario_formatado): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            🕐
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Horário</dt>
                            <dd class="text-recife-gray-700"><?php echo esc_html($horario_formatado); ?></dd>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Local e Organizador -->
        <div class="space-y-4">
            <?php if ($local_nome): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        📍
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-recife-gray-900 mb-1">Local</dt>
                        <dd class="text-recife-gray-700">
                            <?php if ($evento_local): ?>
                                <a href="<?php echo esc_url(get_permalink($evento_local)); ?>" class="text-recife-primary hover:text-recife-primary-dark transition-colors">
                                    <?php echo esc_html($local_nome); ?>
                                </a>
                            <?php else: ?>
                                <?php echo esc_html($local_nome); ?>
                            <?php endif; ?>
                            <?php if ($local_endereco): ?>
                                <br><span class="text-sm text-recife-gray-600"><?php echo esc_html($local_endereco); ?></span>
                            <?php endif; ?>
                        </dd>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($organizador_nome): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        🏢
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-recife-gray-900 mb-1">Organização</dt>
                        <dd class="text-recife-gray-700">
                            <?php if ($evento_organizador): ?>
                                <a href="<?php echo esc_url(get_permalink($evento_organizador)); ?>" class="text-recife-primary hover:text-recife-primary-dark transition-colors">
                                    <?php echo esc_html($organizador_nome); ?>
                                </a>
                            <?php else: ?>
                                <?php echo esc_html($organizador_nome); ?>
                            <?php endif; ?>
                        </dd>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Preço e Público -->
        <?php if ($evento_preco || ($evento_publico_alvo && $dicionarios)): ?>
            <div class="space-y-4">
                <?php if ($evento_preco): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            💰
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Ingresso</dt>
                            <dd class="text-recife-gray-700 font-medium">
                                <?php 
                                $preco_lower = strtolower($evento_preco);
                                if (strpos($preco_lower, 'gratu') !== false || strpos($preco_lower, 'livre') !== false) {
                                    echo '<span class="text-green-600">🎉 ' . esc_html($evento_preco) . '</span>';
                                } else {
                                    echo esc_html($evento_preco);
                                }
                                ?>
                            </dd>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($evento_publico_alvo && $dicionarios): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            👥
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Público-Alvo</dt>
                            <dd class="text-recife-gray-700">
                                <?php echo esc_html($dicionarios->get_label_by_value('publico_alvo', $evento_publico_alvo)); ?>
                            </dd>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Tipo de Evento -->
        <?php if (($evento_tipos && $dicionarios) || $tipos_eventos): ?>
            <div class="space-y-4">
                <?php if ($evento_tipos && $dicionarios): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            🎪
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Tipo</dt>
                            <dd class="text-recife-gray-700">
                                <?php echo esc_html($dicionarios->get_label_by_value('tipos_eventos', $evento_tipos)); ?>
                            </dd>
                        </div>
                    </div>
                <?php elseif ($tipos_eventos): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            🎪
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-recife-gray-900 mb-1">Categoria</dt>
                            <dd class="text-recife-gray-700">
                                <?php echo esc_html($tipos_eventos[0]->name); ?>
                            </dd>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Atrações -->
    <?php if ($evento_atracoes && is_array($evento_atracoes) && !empty($evento_atracoes)): ?>
        <div class="mt-6 pt-6 border-t border-recife-gray-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-recife-gray-100 rounded-lg flex items-center justify-center">
                    🎨
                </div>
                <h4 class="text-base font-medium text-recife-gray-900">Atrações</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($evento_atracoes as $atracao): ?>
                    <div class="bg-recife-gray-50 rounded-lg p-3 text-sm text-recife-gray-700">
                        <?php echo esc_html($atracao); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Ações -->
    <div class="mt-6 pt-6 border-t border-recife-gray-100">
        <div class="flex flex-wrap gap-3">
            
            <?php if ($evento_link_inscricao): ?>
                <a href="<?php echo esc_url($evento_link_inscricao); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-recife-primary text-white px-4 py-2 rounded-lg hover:bg-recife-primary-dark transition-colors text-sm font-medium">
                    🎫 Ingressos
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            <?php endif; ?>
            
            <?php if ($evento_contato): ?>
                <span class="inline-flex items-center gap-2 bg-recife-gray-100 text-recife-gray-700 px-4 py-2 rounded-lg text-sm">
                    📞 <?php echo esc_html($evento_contato); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($evento_local): ?>
                <a href="<?php echo esc_url(get_permalink($evento_local)); ?>" 
                   class="inline-flex items-center gap-2 bg-recife-accent text-white px-4 py-2 rounded-lg hover:bg-recife-accent-dark transition-colors text-sm font-medium">
                    📍 Ver Local
                </a>
            <?php endif; ?>
            
        </div>
    </div>
    
</div> 