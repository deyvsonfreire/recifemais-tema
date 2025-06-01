<?php
/**
 * Template Part: Archive No Results
 * 
 * Estado vazio para quando não há resultados em archives com:
 * - Mensagens contextuais por tipo de conteúdo
 * - Sugestões de ação
 * - Links úteis
 * - Design amigável
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Detectar contexto atual
$current_post_type = get_post_type();
$queried_object = get_queried_object();
$search_query = get_search_query();

// Configurações por contexto
$no_results_configs = [
    'eventos_festivais' => [
        'icon' => '🎭',
        'title' => 'Nenhum evento encontrado',
        'message' => 'Não encontramos eventos que correspondam aos seus critérios de busca.',
        'suggestions' => [
            'Verifique se os filtros estão corretos',
            'Tente expandir o período de busca',
            'Explore eventos em outras categorias',
            'Cadastre-se para receber notificações de novos eventos'
        ],
        'cta_text' => 'Ver todos os eventos',
        'cta_url' => '/eventos_festivais/',
        'color' => 'red'
    ],
    'lugares' => [
        'icon' => '📍',
        'title' => 'Nenhum lugar encontrado',
        'message' => 'Não encontramos lugares culturais que correspondam à sua busca.',
        'suggestions' => [
            'Tente buscar por um bairro diferente',
            'Remova alguns filtros para expandir os resultados',
            'Explore diferentes tipos de lugares',
            'Sugira um novo lugar para nossa base de dados'
        ],
        'cta_text' => 'Explorar lugares',
        'cta_url' => '/lugares/',
        'color' => 'blue'
    ],
    'artistas' => [
        'icon' => '🎨',
        'title' => 'Nenhum artista encontrado',
        'message' => 'Não encontramos artistas que correspondam aos seus critérios.',
        'suggestions' => [
            'Tente buscar por uma área de atuação diferente',
            'Explore artistas de outras regiões',
            'Verifique a ortografia dos termos de busca',
            'Cadastre seu perfil de artista'
        ],
        'cta_text' => 'Ver todos os artistas',
        'cta_url' => '/artistas/',
        'color' => 'purple'
    ],
    'roteiros' => [
        'icon' => '🗺️',
        'title' => 'Nenhum roteiro encontrado',
        'message' => 'Não encontramos roteiros culturais que correspondam à sua busca.',
        'suggestions' => [
            'Tente ajustar a duração ou dificuldade',
            'Explore roteiros de outras categorias',
            'Verifique os filtros aplicados',
            'Crie seu próprio roteiro personalizado'
        ],
        'cta_text' => 'Explorar roteiros',
        'cta_url' => '/roteiros/',
        'color' => 'orange'
    ],
    'post' => [
        'icon' => '📰',
        'title' => 'Nenhum artigo encontrado',
        'message' => 'Não encontramos artigos que correspondam à sua busca.',
        'suggestions' => [
            'Verifique a ortografia dos termos',
            'Tente palavras-chave mais gerais',
            'Explore diferentes categorias',
            'Confira nossos artigos mais recentes'
        ],
        'cta_text' => 'Ver últimas notícias',
        'cta_url' => '/category/noticias/',
        'color' => 'gray'
    ]
];

// Configuração específica para busca
if (is_search()) {
    $config = [
        'icon' => '🔍',
        'title' => 'Nenhum resultado para "' . esc_html($search_query) . '"',
        'message' => 'Sua busca não retornou resultados. Tente outras palavras-chave.',
        'suggestions' => [
            'Verifique a ortografia das palavras',
            'Use termos mais gerais',
            'Tente sinônimos ou palavras relacionadas',
            'Explore nossas categorias principais'
        ],
        'cta_text' => 'Explorar conteúdo',
        'cta_url' => '/',
        'color' => 'blue'
    ];
} else {
    // Configuração para o tipo atual
    $config = $no_results_configs[$current_post_type] ?? $no_results_configs['post'];
}

// Cores por tipo
$color_classes = [
    'red' => 'text-red-600 bg-red-50 border-red-200',
    'blue' => 'text-blue-600 bg-blue-50 border-blue-200',
    'purple' => 'text-purple-600 bg-purple-50 border-purple-200',
    'orange' => 'text-orange-600 bg-orange-50 border-orange-200',
    'gray' => 'text-gray-600 bg-gray-50 border-gray-200'
];

$color_class = $color_classes[$config['color']] ?? $color_classes['gray'];
?>

<div class="no-results-container py-16 px-4 text-center">
    <div class="max-w-2xl mx-auto">
        
        <!-- Ícone e Título -->
        <div class="mb-8">
            <div class="text-6xl mb-4 opacity-50">
                <?php echo $config['icon']; ?>
            </div>
            
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                <?php echo esc_html($config['title']); ?>
            </h2>
            
            <p class="text-lg text-gray-600 leading-relaxed">
                <?php echo esc_html($config['message']); ?>
            </p>
        </div>
        
        <!-- Filtros Ativos (se houver) -->
        <?php 
        $active_filters = [];
        $filter_params = ['s', 'category', 'tag', 'author', 'bairro', 'tipo_evento', 'data_filter', 'preco_filter'];
        
        foreach ($filter_params as $param) {
            if (isset($_GET[$param]) && !empty($_GET[$param])) {
                $active_filters[$param] = sanitize_text_field($_GET[$param]);
            }
        }
        
        if (!empty($active_filters)): ?>
            <div class="mb-8 p-4 <?php echo $color_class; ?> rounded-lg border">
                <h3 class="font-semibold mb-3">Filtros ativos:</h3>
                <div class="flex flex-wrap justify-center gap-2 mb-4">
                    <?php foreach ($active_filters as $key => $value): ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-white rounded-full text-sm font-medium">
                            <?php 
                            $filter_labels = [
                                's' => 'Busca',
                                'category' => 'Categoria',
                                'tag' => 'Tag',
                                'author' => 'Autor',
                                'bairro' => 'Bairro',
                                'tipo_evento' => 'Tipo',
                                'data_filter' => 'Data',
                                'preco_filter' => 'Preço'
                            ];
                            echo esc_html($filter_labels[$key] ?? $key);
                            ?>: 
                            <strong><?php echo esc_html($value); ?></strong>
                        </span>
                    <?php endforeach; ?>
                </div>
                
                <a href="<?php echo esc_url(strtok($_SERVER['REQUEST_URI'], '?')); ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Limpar todos os filtros
                </a>
            </div>
        <?php endif; ?>
        
        <!-- Sugestões -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sugestões:</h3>
            <ul class="text-left max-w-md mx-auto space-y-2">
                <?php foreach ($config['suggestions'] as $suggestion): ?>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700"><?php echo esc_html($suggestion); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Call to Action -->
        <div class="mb-8">
            <a href="<?php echo esc_url(home_url($config['cta_url'])); ?>" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <?php echo esc_html($config['cta_text']); ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <!-- Busca Alternativa -->
        <div class="bg-gray-50 rounded-lg p-6 border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tente uma nova busca:</h3>
            
            <form method="GET" action="<?php echo esc_url(home_url('/')); ?>" class="max-w-md mx-auto">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" 
                               name="s" 
                               placeholder="Digite sua busca..."
                               value="<?php echo esc_attr($search_query); ?>"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Buscar
                    </button>
                </div>
                
                <?php if (is_post_type_archive()): ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr($current_post_type); ?>">
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Links Úteis -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Explore outras seções:</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?php echo home_url('/eventos_festivais/'); ?>" 
                   class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-gray-50 transition-colors group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🎭</span>
                    <span class="text-sm font-medium text-gray-700">Eventos</span>
                </a>
                
                <a href="<?php echo home_url('/lugares/'); ?>" 
                   class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-gray-50 transition-colors group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">📍</span>
                    <span class="text-sm font-medium text-gray-700">Lugares</span>
                </a>
                
                <a href="<?php echo home_url('/artistas/'); ?>" 
                   class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-gray-50 transition-colors group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🎨</span>
                    <span class="text-sm font-medium text-gray-700">Artistas</span>
                </a>
                
                <a href="<?php echo home_url('/roteiros/'); ?>" 
                   class="flex flex-col items-center gap-2 p-4 rounded-lg hover:bg-gray-50 transition-colors group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🗺️</span>
                    <span class="text-sm font-medium text-gray-700">Roteiros</span>
                </a>
            </div>
        </div>
        
        <!-- Contato/Sugestão -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-gray-600 mb-4">
                Não encontrou o que procurava? 
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?php echo home_url('/contato/'); ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Entre em contato
                </a>
                
                <a href="<?php echo home_url('/sugerir-conteudo/'); ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Sugerir conteúdo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- CSS específico para no-results -->
<style>
.no-results-container {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-results-container h2 {
    animation: fadeInUp 0.6s ease;
}

.no-results-container p {
    animation: fadeInUp 0.8s ease;
}

.no-results-container .mb-8:nth-child(3) {
    animation: fadeInUp 1s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .no-results-container {
        padding: 2rem 1rem;
        min-height: 50vh;
    }
    
    .no-results-container h2 {
        font-size: 1.5rem;
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Print styles */
@media print {
    .no-results-container {
        min-height: auto;
        padding: 1rem;
    }
    
    .no-results-container form,
    .no-results-container .grid {
        display: none;
    }
}
</style>

<!-- JavaScript para funcionalidades -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics para estado vazio
    if (typeof gtag !== 'undefined') {
        gtag('event', 'no_results_shown', {
            'search_term': '<?php echo esc_js($search_query); ?>',
            'post_type': '<?php echo esc_js($current_post_type); ?>',
            'active_filters': <?php echo json_encode(array_keys($active_filters ?? [])); ?>,
            'page_location': window.location.href
        });
    }
    
    // Analytics para cliques em sugestões
    document.querySelectorAll('.no-results-container a').forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'no_results_click', {
                    'link_text': this.textContent.trim(),
                    'link_url': this.href,
                    'action_type': this.classList.contains('bg-blue-600') ? 'primary_cta' : 'secondary_link'
                });
            }
        });
    });
    
    // Auto-focus no campo de busca
    const searchInput = document.querySelector('input[name="s"]');
    if (searchInput && !searchInput.value) {
        setTimeout(() => {
            searchInput.focus();
        }, 500);
    }
});
</script> 