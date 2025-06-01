<?php
/**
 * Template para Página de Busca Avançada
 * Template Name: Página de Busca
 * RecifeMais - Tema Oficial
 */

get_header(); 
?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Header da página -->
        <div class="hero-search bg-gradient-to-br from-recife-primary via-purple-600 to-recife-secondary text-white py-20 rounded-2xl mb-8 relative overflow-hidden">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="grid grid-cols-8 gap-4 h-full">
                    <?php for ($i = 0; $i < 32; $i++): ?>
                        <div class="bg-white rounded-full w-2 h-2 animate-pulse" style="animation-delay: <?php echo $i * 0.1; ?>s;"></div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="text-center relative z-10">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl">🔍</span>
                </div>
                <h1 class="text-5xl font-bold mb-6">Encontre Sua Experiência Cultural</h1>
                <p class="text-xl text-white/90 max-w-3xl mx-auto mb-8">
                    Use nossa busca inteligente para descobrir eventos, lugares, artistas e muito mais em Pernambuco. 
                    Filtre por localização, categoria, data e encontre exatamente o que procura.
                </p>
                
                <!-- Stats rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <?php echo wp_count_posts('eventos_festivais')->publish; ?>
                        </div>
                        <div class="text-sm opacity-75">Eventos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <?php echo wp_count_posts('lugares')->publish; ?>
                        </div>
                        <div class="text-sm opacity-75">Lugares</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <?php echo wp_count_posts('artistas')->publish; ?>
                        </div>
                        <div class="text-sm opacity-75">Artistas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            <?php echo wp_count_posts('roteiros')->publish; ?>
                        </div>
                        <div class="text-sm opacity-75">Roteiros</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sistema de busca avançada -->
        <?php include get_template_directory() . '/components/busca-avancada.php'; ?>

        <!-- Seção de buscas populares -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 text-center">Buscas Populares</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Busca por eventos próximos -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white p-6 rounded-xl hover:scale-105 transition-transform cursor-pointer" 
                     onclick="buscarPopular('eventos', 'proximos')">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">🎭</span>
                        <h3 class="text-lg font-semibold">Eventos Próximos</h3>
                    </div>
                    <p class="text-sm opacity-90">Descubra eventos acontecendo nos próximos 7 dias</p>
                </div>
                
                <!-- Busca por lugares históricos -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6 rounded-xl hover:scale-105 transition-transform cursor-pointer" 
                     onclick="buscarPopular('lugares', 'historicos')">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">📍</span>
                        <h3 class="text-lg font-semibold">Lugares Históricos</h3>
                    </div>
                    <p class="text-sm opacity-90">Explore o patrimônio histórico de Pernambuco</p>
                </div>
                
                <!-- Busca por frevo -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl hover:scale-105 transition-transform cursor-pointer" 
                     onclick="buscarPopular('artistas', 'frevo')">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-2xl">🎨</span>
                        <h3 class="text-lg font-semibold">Arte do Frevo</h3>
                    </div>
                    <p class="text-sm opacity-90">Artistas e eventos relacionados ao frevo</p>
                </div>
            </div>
        </div>

        <!-- Dicas de busca -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-8 rounded-xl mb-8">
            <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 text-center">💡 Dicas para uma Busca Eficiente</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-recife-gray-900 mb-3">🎯 Use Filtros Específicos</h3>
                    <ul class="space-y-2 text-recife-gray-700">
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 mt-1">✓</span>
                            <span>Combine tipo de conteúdo + localização</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 mt-1">✓</span>
                            <span>Use filtros de data para eventos</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 mt-1">✓</span>
                            <span>Experimente a busca por proximidade</span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-recife-gray-900 mb-3">🔍 Exemplos de Busca</h3>
                    <div class="space-y-2">
                        <button class="block w-full text-left p-2 bg-white rounded hover:bg-recife-gray-50 transition-colors text-sm" 
                                onclick="exemploSearch('teatro no recife antigo')">
                            "teatro no recife antigo"
                        </button>
                        <button class="block w-full text-left p-2 bg-white rounded hover:bg-recife-gray-50 transition-colors text-sm" 
                                onclick="exemploSearch('comida típica pernambucana')">
                            "comida típica pernambucana"
                        </button>
                        <button class="block w-full text-left p-2 bg-white rounded hover:bg-recife-gray-50 transition-colors text-sm" 
                                onclick="exemploSearch('música instrumental')">
                            "música instrumental"
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorias rápidas -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-recife-gray-900 mb-6 text-center">Explore por Categoria</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                
                <!-- Eventos -->
                <a href="<?php echo home_url('/eventos_festivais'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎭</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Eventos</div>
                </a>
                
                <!-- Lugares -->
                <a href="<?php echo home_url('/lugares'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📍</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Lugares</div>
                </a>
                
                <!-- Artistas -->
                <a href="<?php echo home_url('/artistas'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎨</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Artistas</div>
                </a>
                
                <!-- Roteiros -->
                <a href="<?php echo home_url('/roteiros'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🗺️</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Roteiros</div>
                </a>
                
                <!-- Organizadores -->
                <a href="<?php echo home_url('/organizadores'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏢</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Organizadores</div>
                </a>
                
                <!-- Agremiações -->
                <a href="<?php echo home_url('/agremiacoes'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎪</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Agremiações</div>
                </a>
                
                <!-- Histórias -->
                <a href="<?php echo home_url('/historias'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📖</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Histórias</div>
                </a>
                
                <!-- Guias -->
                <a href="<?php echo home_url('/guias_tematicos'); ?>" 
                   class="group bg-white p-4 rounded-xl border border-recife-gray-200 hover:border-recife-primary hover:shadow-lg transition-all text-center">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📚</div>
                    <div class="text-sm font-medium text-recife-gray-700 group-hover:text-recife-primary">Guias</div>
                </a>
            </div>
        </div>

        <!-- Mapa interativo (placeholder) -->
        <div class="bg-white rounded-xl shadow-lg border border-recife-gray-100 p-6 mb-8">
            <h2 class="text-2xl font-bold text-recife-gray-900 mb-4">🗺️ Explore no Mapa</h2>
            <p class="text-recife-gray-600 mb-6">
                Visualize localizações de eventos, lugares e pontos culturais no mapa interativo.
            </p>
            
            <div class="bg-gradient-to-br from-blue-100 to-cyan-100 rounded-lg p-8 text-center border-2 border-dashed border-blue-300">
                <div class="text-4xl mb-4">🗺️</div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Mapa Interativo</h3>
                <p class="text-blue-700 mb-4">Funcionalidade em desenvolvimento</p>
                <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors" disabled>
                    Em breve
                </button>
            </div>
        </div>
    </div>
</main>

<script>
function buscarPopular(tipo, categoria) {
    // Definir busca baseada no tipo e categoria
    const searchInput = document.getElementById('busca-termo');
    const filtros = document.getElementById('filtros-container');
    
    if (searchInput) {
        switch(categoria) {
            case 'proximos':
                searchInput.value = 'eventos próximos';
                // Definir data para próximos 7 dias
                const hoje = new Date();
                const proxSemana = new Date(hoje.getTime() + 7 * 24 * 60 * 60 * 1000);
                const dataInicio = document.querySelector('[name="data_inicio"]');
                const dataFim = document.querySelector('[name="data_fim"]');
                if (dataInicio) dataInicio.value = hoje.toISOString().split('T')[0];
                if (dataFim) dataFim.value = proxSemana.toISOString().split('T')[0];
                break;
            case 'historicos':
                searchInput.value = 'histórico patrimônio';
                break;
            case 'frevo':
                searchInput.value = 'frevo';
                break;
        }
        
        // Ativar apenas o tipo correto
        document.querySelectorAll('input[name="post_types[]"]').forEach(cb => {
            cb.checked = cb.value.includes(tipo);
        });
        
        // Abrir filtros se não estiverem visíveis
        if (filtros && filtros.classList.contains('hidden')) {
            document.getElementById('toggle-filtros')?.click();
        }
        
        // Executar busca se a classe existir
        if (window.recifemaisBusca) {
            window.recifemaisBusca.performSearch();
        }
    }
}

function exemploSearch(query) {
    const searchInput = document.getElementById('busca-termo');
    if (searchInput) {
        searchInput.value = query;
        searchInput.focus();
        
        // Executar busca se a classe existir
        if (window.recifemaisBusca) {
            window.recifemaisBusca.performSearch();
        }
    }
}
</script>

<style>
.hero-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.1;
    }
    50% {
        opacity: 0.3;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsividade para categorias */
@media (max-width: 768px) {
    .grid.lg\:grid-cols-8 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Efeitos hover suaves */
.transition-all {
    transition: all 0.3s ease;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Estilo para botões de exemplo */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<?php get_footer(); ?> 