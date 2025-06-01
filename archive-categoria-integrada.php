<?php
/**
 * Template Universal para Archives Integrados - RecifeMais
 * Inspirado no layout da EBC (ebc.com.br)
 * 
 * Este template combina múltiplos tipos de conteúdo por categoria:
 * - Lugares relacionados
 * - Eventos relacionados  
 * - Notícias relacionadas
 * - Roteiros relacionados
 * - Artistas relacionados
 *
 * @package RecifeMais Tema
 */

get_header();

// Detectar categoria atual
$categoria_atual = '';
$subcategoria_atual = '';
$cor_categoria = '#FF8A00'; // Cor padrão RecifeMais

// Detectar se estamos em uma página de categoria específica
if (is_category()) {
    $category = get_queried_object();
    $categoria_atual = $category->slug;
} else {
    // Usar query vars das rewrite rules
    $categoria_atual = get_query_var('categoria');
    $subcategoria_atual = get_query_var('subcategoria');
    
    // Fallback para $_GET (para debug)
    if (!$categoria_atual && isset($_GET['categoria'])) {
        $categoria_atual = sanitize_text_field($_GET['categoria']);
        $subcategoria_atual = isset($_GET['subcategoria']) ? sanitize_text_field($_GET['subcategoria']) : '';
    }
}

// Obter dados da estrutura de navegação
$estrutura_nav = recifemais_get_estrutura_navegacao();
$dados_categoria = null;
$dados_subcategoria = null;

// Encontrar dados da categoria
foreach ($estrutura_nav as $cat_key => $cat_data) {
    if ($cat_key === $categoria_atual) {
        $dados_categoria = $cat_data;
        $cor_categoria = $cat_data['color'];
        
        // Se há subcategoria, encontrar seus dados
        if ($subcategoria_atual && isset($cat_data['subcategorias'][$subcategoria_atual])) {
            $dados_subcategoria = $cat_data['subcategorias'][$subcategoria_atual];
        }
        break;
    }
}

// Se não encontrou categoria, usar padrão
if (!$dados_categoria) {
    $dados_categoria = array(
        'title' => 'Conteúdo',
        'description' => 'Explore o melhor do Recife e Pernambuco',
        'icon' => 'compass',
        'color' => '#FF8A00'
    );
}

$titulo_pagina = $dados_subcategoria ? $dados_subcategoria['title'] : $dados_categoria['title'];
$descricao_pagina = $dados_subcategoria['description'] ?? $dados_categoria['description'];
?>

<main id="main" class="site-main archive-integrada">
    <div class="container">
        
        <!-- Hero Section com Cor Dinâmica -->
        <section class="hero-categoria" style="--cor-categoria: <?php echo $cor_categoria; ?>">
            <div class="hero-content">
                <div class="hero-icon">
                    <?php echo recifemais_get_icon_svg($dados_categoria['icon']); ?>
                </div>
                <h1 class="hero-title"><?php echo esc_html($titulo_pagina); ?></h1>
                <p class="hero-description"><?php echo esc_html($descricao_pagina); ?></p>
                
                <!-- Navegação de Subcategorias -->
                <?php if ($dados_categoria['subcategorias'] && !$subcategoria_atual): ?>
                <nav class="subcategorias-nav">
                    <?php foreach ($dados_categoria['subcategorias'] as $sub_key => $sub_data): ?>
                        <a href="<?php echo home_url("/{$categoria_atual}/{$sub_key}/"); ?>" 
                           class="subcategoria-link">
                            <?php echo esc_html($sub_data['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <?php endif; ?>
            </div>
        </section>

        <!-- Filtros Rápidos -->
        <section class="filtros-rapidos">
            <div class="filtros-container">
                <button class="filtro-btn active" data-tipo="todos">Todos</button>
                <button class="filtro-btn" data-tipo="lugares">Lugares</button>
                <button class="filtro-btn" data-tipo="eventos">Eventos</button>
                <button class="filtro-btn" data-tipo="noticias">Notícias</button>
                <button class="filtro-btn" data-tipo="roteiros">Roteiros</button>
                <button class="filtro-btn" data-tipo="artistas">Artistas</button>
            </div>
        </section>

        <!-- Grid de Conteúdo Integrado -->
        <section class="conteudo-integrado">
            
            <?php
            // Buscar conteúdo baseado na categoria/subcategoria
            $conteudo_integrado = recifemais_buscar_conteudo_integrado($categoria_atual, $subcategoria_atual, $dados_categoria, $dados_subcategoria);
            
            if (!empty($conteudo_integrado)):
            ?>
            
            <!-- Seção de Destaques -->
            <?php if (!empty($conteudo_integrado['destaques'])): ?>
            <div class="secao-destaques">
                <h2 class="secao-titulo">Em Destaque</h2>
                <div class="grid-destaques">
                    <?php foreach ($conteudo_integrado['destaques'] as $item): ?>
                        <article class="card-destaque" data-tipo="<?php echo $item['tipo']; ?>">
                            <?php echo recifemais_render_card_integrado($item); ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Seções por Tipo de Conteúdo -->
            <?php foreach (['lugares', 'eventos', 'noticias', 'roteiros', 'artistas'] as $tipo): ?>
                <?php if (!empty($conteudo_integrado[$tipo])): ?>
                <div class="secao-tipo" data-tipo="<?php echo $tipo; ?>">
                    <div class="secao-header">
                        <h2 class="secao-titulo">
                            <?php echo recifemais_get_titulo_secao($tipo); ?>
                            <span class="contador">(<?php echo count($conteudo_integrado[$tipo]); ?>)</span>
                        </h2>
                        <a href="<?php echo recifemais_get_url_ver_todos($tipo, $categoria_atual); ?>" 
                           class="ver-todos-link">
                            Ver todos
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                    
                    <div class="grid-conteudo">
                        <?php foreach (array_slice($conteudo_integrado[$tipo], 0, 6) as $item): ?>
                            <article class="card-conteudo" data-tipo="<?php echo $tipo; ?>">
                                <?php echo recifemais_render_card_integrado($item); ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php else: ?>
            
            <!-- Estado Vazio -->
            <div class="estado-vazio">
                <div class="estado-vazio-icon">
                    <?php echo recifemais_get_icon_svg('compass'); ?>
                </div>
                <h3>Nenhum conteúdo encontrado</h3>
                <p>Não encontramos conteúdo para esta categoria ainda. Que tal explorar outras seções?</p>
                <a href="<?php echo home_url(); ?>" class="btn-voltar">Voltar ao início</a>
            </div>
            
            <?php endif; ?>
            
        </section>

        <!-- Call to Action -->
        <section class="cta-categoria" style="--cor-categoria: <?php echo $cor_categoria; ?>">
            <div class="cta-content">
                <h3>Não encontrou o que procurava?</h3>
                <p>Use nossa busca avançada ou explore outras categorias</p>
                <div class="cta-actions">
                    <button class="btn-busca" onclick="document.querySelector('.search-form input').focus()">
                        Fazer uma busca
                    </button>
                    <a href="<?php echo home_url(); ?>" class="btn-explorar">
                        Explorar categorias
                    </a>
                </div>
            </div>
        </section>

    </div>
</main>

<?php
get_footer();
?> 