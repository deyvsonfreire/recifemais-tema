<?php
/**
 * Rank Math Compatibility for RecifeMais Theme
 * 
 * Arquivo dedicado para compatibilidade com Rank Math SEO Plugin
 * Conforme documentação: https://rankmath.com/kb/make-theme-rank-math-compatible/
 * 
 * @package RecifeMaisTema
 * @since 1.0.0
 */

// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Theme Support for Breadcrumbs
 * Registra suporte do tema para breadcrumbs do Rank Math
 */
add_theme_support('rank-math-breadcrumbs');

/**
 * 2. Content Analysis API Integration
 * Inclui meta fields customizados na análise de conteúdo
 */
add_filter('rank_math/content/content_for_analysis', function($content, $post_id) {
    if (!$post_id) {
        return $content;
    }
    
    $post_type = get_post_type($post_id);
    
    // Adiciona campos específicos por CPT na análise
    switch ($post_type) {
        case 'lugares':
            $endereco = get_post_meta($post_id, 'endereco_completo', true);
            $telefone = get_post_meta($post_id, 'telefone', true);
            $especialidades = wp_get_post_terms($post_id, 'tipos_gastronomia', ['fields' => 'names']);
            
            if ($endereco) $content .= ' ' . $endereco;
            if ($telefone) $content .= ' ' . $telefone;
            if (!empty($especialidades)) $content .= ' ' . implode(' ', $especialidades);
            break;
            
        case 'eventos_festivais':
            $local = get_post_meta($post_id, 'local_evento', true);
            $data = get_post_meta($post_id, '_data_inicio_evento_festival', true);
            $organizadores = wp_get_post_terms($post_id, 'organizadores', ['fields' => 'names']);
            
            if ($local) $content .= ' ' . $local;
            if ($data) $content .= ' ' . date('d/m/Y', strtotime($data));
            if (!empty($organizadores)) $content .= ' ' . implode(' ', $organizadores);
            break;
            
        case 'artistas':
            $cidade_origem = get_post_meta($post_id, 'cidade_origem', true);
            $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
            
            if ($cidade_origem) $content .= ' ' . $cidade_origem;
            if (!empty($manifestacoes)) $content .= ' ' . implode(' ', $manifestacoes);
            break;
    }
    
    return $content;
}, 10, 2);

/**
 * 3. Ajustar Content Analysis Tests para CPTs específicos
 * Remove testes irrelevantes para alguns tipos de conteúdo
 */
add_filter('rank_math/researches/tests', function($tests, $type) {
    if ($type === 'post') {
        global $post;
        if ($post && in_array($post->post_type, ['organizadores', 'agremiações'])) {
            // Remove testes desnecessários para organizadores e agremiações
            unset(
                $tests['contentHasTOC'],
                $tests['lengthContent']
            );
        }
    }
    return $tests;
}, 10, 2);

/**
 * 4. Customizar Schema Data para CPTs
 * Adiciona dados específicos aos schemas
 */
add_filter('rank_math/snippet/rich_snippet_event_entity', function($entity) {
    $post_id = get_the_ID();
    if (!$post_id) return $entity;
    
    // Adiciona informações específicas de eventos culturais
    $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
    if (!empty($manifestacoes)) {
        $entity['genre'] = implode(', ', $manifestacoes);
    }
    
    // Adiciona coordenadas se disponíveis
    $latitude = get_post_meta($post_id, 'latitude', true);
    $longitude = get_post_meta($post_id, 'longitude', true);
    
    if ($latitude && $longitude && isset($entity['location'])) {
        $entity['location']['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }
    
    return $entity;
});

/**
 * 5. Customizar Schema Data para Lugares (LocalBusiness)
 */
add_filter('rank_math/snippet/rich_snippet_local-business_entity', function($entity) {
    $post_id = get_the_ID();
    if (!$post_id) return $entity;
    
    // Adiciona faixa de preço
    $faixa_preco = get_post_meta($post_id, 'faixa_preco', true);
    if ($faixa_preco) {
        $entity['priceRange'] = $faixa_preco;
    }
    
    // Adiciona horários de funcionamento
    $horarios = recifemais_get_opening_hours($post_id);
    if (!empty($horarios)) {
        $opening_hours = [];
        foreach ($horarios as $dia => $horario) {
            if (!empty($horario['abertura']) && !empty($horario['fechamento'])) {
                $dia_en = [
                    'segunda' => 'Monday',
                    'terca' => 'Tuesday', 
                    'quarta' => 'Wednesday',
                    'quinta' => 'Thursday',
                    'sexta' => 'Friday',
                    'sabado' => 'Saturday',
                    'domingo' => 'Sunday'
                ];
                
                if (isset($dia_en[$dia])) {
                    $opening_hours[] = $dia_en[$dia] . ' ' . $horario['abertura'] . '-' . $horario['fechamento'];
                }
            }
        }
        
        if (!empty($opening_hours)) {
            $entity['openingHours'] = $opening_hours;
        }
    }
    
    // Adiciona formas de pagamento
    $pagamento = get_post_meta($post_id, 'formas_pagamento', true);
    if (!empty($pagamento)) {
        $entity['paymentAccepted'] = is_array($pagamento) ? implode(', ', $pagamento) : $pagamento;
    }
    
    return $entity;
});

/**
 * 6. Customizar Schema Data para Artistas (Person)
 */
add_filter('rank_math/snippet/rich_snippet_person_entity', function($entity) {
    $post_id = get_the_ID();
    if (!$post_id) return $entity;
    
    // Adiciona nacionalidade
    $entity['nationality'] = 'Brazilian';
    
    // Adiciona cidade de origem
    $cidade_origem = get_post_meta($post_id, 'cidade_origem', true);
    if ($cidade_origem) {
        $entity['birthPlace'] = [
            '@type' => 'Place',
            'name' => $cidade_origem . ', Pernambuco, Brasil'
        ];
    }
    
    // Adiciona gêneros musicais/manifestações culturais
    $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
    if (!empty($manifestacoes)) {
        $entity['genre'] = $manifestacoes;
    }
    
    // Adiciona links sociais
    $social_links = [];
    $platforms = ['spotify', 'youtube', 'instagram', 'facebook', 'soundcloud'];
    
    foreach ($platforms as $platform) {
        $link = get_post_meta($post_id, "link_{$platform}", true);
        if (!empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
            $social_links[] = $link;
        }
    }
    
    if (!empty($social_links)) {
        $entity['sameAs'] = $social_links;
    }
    
    return $entity;
});

/**
 * 7. Customizar HTML do Review Snippet (se aplicável)
 */
add_filter('rank_math/review/text', function($text) {
    return 'Avaliação RecifeMais';
});

/**
 * 8. Customizar Front-End SEO Score
 */
add_filter('rank_math/frontend/seo_score/html', function($html, $args, $score) {
    // Adiciona classe customizada para styling
    $html = str_replace('rank-math-seo-score', 'rank-math-seo-score recifemais-seo-score', $html);
    return $html;
}, 10, 3);

/**
 * 9. Configurar Breadcrumbs Customizados
 */
add_filter('rank_math/frontend/breadcrumb/items', function($crumbs, $class) {
    // Adiciona breadcrumb específico para taxonomias regionais
    if (is_tax('cidades_pernambuco') || is_tax('bairros_recife')) {
        $pernambuco_crumb = [
            'text' => 'Pernambuco',
            'url' => home_url('/pernambuco/')
        ];
        
        // Insere antes do último item
        $last_item = array_pop($crumbs);
        array_splice($crumbs, -1, 0, [$pernambuco_crumb]);
        $crumbs[] = $last_item;
    }
    
    return $crumbs;
}, 10, 2);

/**
 * 10. Otimizar Focus Keywords para CPTs
 * Permite mais keywords para eventos e lugares
 */
add_filter('rank_math/focus_keyword/maxtags', function($max) {
    global $post;
    
    if ($post && in_array($post->post_type, ['eventos_festivais', 'lugares'])) {
        return 8; // Mais keywords para tipos principais
    }
    
    return $max;
});

/**
 * 11. Adicionar suporte para Table of Contents Plugin
 * Se o tema tiver TOC nativo
 */
add_filter('rank_math/researches/toc_plugins', function($toc_plugins) {
    // Se houver plugin de TOC específico do tema
    $toc_plugins['recifemais-toc/recifemais-toc.php'] = 'RecifeMais TOC';
    return $toc_plugins;
});

/**
 * 12. Customizar títulos específicos para Local SEO
 */
add_filter('rank_math/frontend/title', function($title) {
    if (is_singular('lugares')) {
        $cidade = wp_get_post_terms(get_the_ID(), 'cidades_pernambuco', ['fields' => 'names']);
        $bairro = wp_get_post_terms(get_the_ID(), 'bairros_recife', ['fields' => 'names']);
        
        if (!empty($cidade)) {
            $location = !empty($bairro) ? $bairro[0] . ', ' . $cidade[0] : $cidade[0];
            $title = str_replace(' | RecifeMais', ' em ' . $location . ' | RecifeMais', $title);
        }
    }
    
    return $title;
});

/**
 * 13. Adicionar dados estruturados específicos para região
 */
add_filter('rank_math/json_ld', function($data, $post_id) {
    if (is_array($data)) {
        // Adiciona informações de região para todos os schemas
        foreach ($data as &$schema) {
            if (isset($schema['@type']) && in_array($schema['@type'], ['Event', 'LocalBusiness', 'Place'])) {
                if (!isset($schema['areaServed'])) {
                    $schema['areaServed'] = [
                        '@type' => 'State',
                        'name' => 'Pernambuco',
                        'containedInPlace' => [
                            '@type' => 'Country',
                            'name' => 'Brasil'
                        ]
                    ];
                }
                
                // Adiciona informações culturais específicas
                if ($schema['@type'] === 'Event') {
                    $schema['inLanguage'] = 'pt-BR';
                    $schema['eventAttendanceMode'] = 'https://schema.org/OfflineEventAttendanceMode';
                }
            }
        }
    }
    
    return $data;
}, 10, 2);

/**
 * 14. Log de compatibilidade (apenas em development)
 */
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_loaded', function() {
        if (class_exists('RankMath')) {
            error_log('RecifeMais Theme: Rank Math compatibility loaded successfully');
        }
    });
}

/**
 * 15. Force Rank Math Settings Refresh 
 * Força atualização das configurações no painel admin
 */
add_action('admin_init', function() {
    if (class_exists('RankMath\\Helper') && current_user_can('manage_options')) {
        // Força refresh das configurações
        if (!get_transient('recifemais_rank_math_settings_refreshed')) {
            // Limpa cache de configurações
            wp_cache_delete('rank_math_options_general', 'options');
            wp_cache_delete('rank_math_options_titles', 'options');
            wp_cache_delete('rank_math_options_sitemap', 'options');
            
            // Marca como atualizado
            set_transient('recifemais_rank_math_settings_refreshed', true, HOUR_IN_SECONDS);
        }
    }
});

/**
 * 16. Integrate with Rank Math Admin
 * Integração com interface administrativa do Rank Math
 */
add_action('rank_math/admin/editor_scripts', function() {
    // Adiciona scripts customizados para o editor do Rank Math
    wp_add_inline_script('rank-math-editor', '
        // Adiciona dados específicos do RecifeMais ao editor
        if (typeof rankMath !== "undefined") {
            rankMath.recifemais = {
                cpts: ["lugares", "eventos_festivais", "artistas", "roteiros"],
                taxonomies: ["cidades_pernambuco", "manifestacoes_culturais"],
                isRecifeMais: true
            };
        }
    ');
});

/**
 * 17. Enhanced Focus Keywords for CPTs
 * Palavras-chave focais específicas para CPTs culturais
 */
add_filter('rank_math/focus_keywords', function($keywords, $post_id) {
    $post_type = get_post_type($post_id);
    
    switch ($post_type) {
        case 'lugares':
            $cidade = wp_get_post_terms($post_id, 'cidades_pernambuco', ['fields' => 'names']);
            $tipo = wp_get_post_terms($post_id, 'tipos_lugares', ['fields' => 'names']);
            
            $suggested = [];
            if (!empty($cidade)) $suggested[] = $cidade[0] . ' ' . (!empty($tipo) ? $tipo[0] : 'lugar');
            if (!empty($tipo)) $suggested[] = $tipo[0] . ' Recife';
            $suggested[] = 'turismo Pernambuco';
            
            return array_merge($keywords, $suggested);
            
        case 'eventos_festivais':
            $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
            
            $suggested = ['eventos Recife', 'cultura Pernambuco'];
            if (!empty($manifestacoes)) {
                foreach ($manifestacoes as $manifestacao) {
                    $suggested[] = $manifestacao . ' Recife';
                }
            }
            
            return array_merge($keywords, $suggested);
            
        case 'artistas':
            $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
            
            $suggested = ['artistas Pernambuco', 'cultura nordestina'];
            if (!empty($manifestacoes)) {
                foreach ($manifestacoes as $manifestacao) {
                    $suggested[] = 'artista ' . $manifestacao;
                }
            }
            
            return array_merge($keywords, $suggested);
    }
    
    return $keywords;
}, 10, 2);

/**
 * 18. Customize Rank Math Admin Page
 * Customiza páginas administrativas do Rank Math
 */
add_action('admin_head', function() {
    global $pagenow;
    
    if ($pagenow === 'admin.php' && isset($_GET['page']) && strpos($_GET['page'], 'rank-math') !== false) {
        echo '<style>
            .rank-math-app .components-notice {
                background: linear-gradient(135deg, #e11d48, #ff6b35);
                color: white;
            }
            .rank-math-metabox-frame {
                border-left: 4px solid #e11d48;
            }
            .rank-math-score {
                color: #e11d48 !important;
            }
        </style>';
    }
});

/**
 * 19. Add Custom Variables for Templates
 * Variáveis customizadas para templates de SEO
 */
add_filter('rank_math/vars/register_extra_replacements', function() {
    rank_math_register_var_replacement(
        'recifemais_location',
        [
            'name' => 'Localização RecifeMais',
            'description' => 'Mostra cidade e bairro do post',
            'variable' => 'recifemais_location',
            'example' => 'Boa Viagem, Recife',
        ],
        function($location, $args) {
            $post_id = get_the_ID();
            if (!$post_id) return '';
            
            $cidade = wp_get_post_terms($post_id, 'cidades_pernambuco', ['fields' => 'names']);
            $bairro = wp_get_post_terms($post_id, 'bairros_recife', ['fields' => 'names']);
            
            $parts = [];
            if (!empty($bairro)) $parts[] = $bairro[0];
            if (!empty($cidade)) $parts[] = $cidade[0];
            
            return implode(', ', $parts);
        }
    );
    
    rank_math_register_var_replacement(
        'recifemais_cultural_type',
        [
            'name' => 'Tipo Cultural',
            'description' => 'Mostra manifestação cultural ou tipo do lugar',
            'variable' => 'recifemais_cultural_type',
            'example' => 'Frevo, Maracatu',
        ],
        function($type, $args) {
            $post_id = get_the_ID();
            if (!$post_id) return '';
            
            $post_type = get_post_type($post_id);
            
            if ($post_type === 'artistas' || $post_type === 'eventos_festivais') {
                $manifestacoes = wp_get_post_terms($post_id, 'manifestacoes_culturais', ['fields' => 'names']);
                return !empty($manifestacoes) ? implode(', ', $manifestacoes) : '';
            }
            
            if ($post_type === 'lugares') {
                $tipos = wp_get_post_terms($post_id, 'tipos_lugares', ['fields' => 'names']);
                return !empty($tipos) ? $tipos[0] : '';
            }
            
            return '';
        }
    );
});

/**
 * 20. Setup Automatic Local Business Schema
 * Schema automático para negócios locais
 */
add_filter('rank_math/snippet/rich_snippet_local-business_entity', function($entity) {
    $post_id = get_the_ID();
    if (!$post_id || get_post_type($post_id) !== 'lugares') return $entity;
    
    // Adiciona informações específicas de Recife
    $entity['areaServed'] = [
        '@type' => 'State',
        'name' => 'Pernambuco',
        'containedInPlace' => [
            '@type' => 'Country', 
            'name' => 'Brasil'
        ]
    ];
    
    // Adiciona currency brasileiro
    if (isset($entity['priceRange'])) {
        $entity['priceCurrency'] = 'BRL';
    }
    
    // Adiciona informações de acessibilidade se disponível
    $acessibilidade = get_post_meta($post_id, 'acessibilidade', true);
    if ($acessibilidade) {
        $entity['amenityFeature'] = [
            '@type' => 'LocationFeatureSpecification',
            'name' => 'Acessibilidade',
            'value' => $acessibilidade
        ];
    }
    
    return $entity;
});

/**
 * 21. Debug Helper for Development
 * Helper para debug em desenvolvimento
 */
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', function() {
        if (current_user_can('administrator')) {
            echo '<!-- RecifeMais + Rank Math Debug -->';
            echo '<!-- Rank Math Active: ' . (class_exists('RankMath') ? 'YES' : 'NO') . ' -->';
            echo '<!-- Current Post Type: ' . get_post_type() . ' -->';
            echo '<!-- Schema Type: ' . (function_exists('rank_math_get_snippet_type') ? rank_math_get_snippet_type() : 'none') . ' -->';
        }
    });
} 