<?php
/**
 * Template para páginas de categoria hub (Descubra, Histórias, etc.)
 * Exibe conteúdo integrado baseado na estrutura de navegação
 *
 * @package RecifeMais Tema
 */

get_header();

// Detecta qual categoria estamos visualizando
$categoria_atual = get_post_meta( get_the_ID(), '_recifemais_categoria_principal', true );
$estrutura_nav = recifemais_get_estrutura_navegacao();
$categoria_data = isset( $estrutura_nav[$categoria_atual] ) ? $estrutura_nav[$categoria_atual] : null;

if ( ! $categoria_data ) {
    // Fallback para página normal
    get_template_part( 'page' );
    get_footer();
    return;
}
?>

<main id="main" class="site-main categoria-hub-page">
    <div class="container">
        
        <!-- Hero da Categoria -->
        <div class="categoria-hero" style="background: linear-gradient(135deg, <?php echo esc_attr( $categoria_data['color'] ); ?>15, <?php echo esc_attr( $categoria_data['color'] ); ?>05);">
            <div class="categoria-hero-content">
                <div class="categoria-icon" style="color: <?php echo esc_attr( $categoria_data['color'] ); ?>">
                    <?php echo recifemais_get_icon_svg( $categoria_data['icon'] ); ?>
                </div>
                <h1 class="categoria-title"><?php echo esc_html( $categoria_data['title'] ); ?></h1>
                <p class="categoria-description"><?php echo esc_html( $categoria_data['description'] ); ?></p>
                
                <!-- Navegação de Subcategorias -->
                <?php if ( isset( $categoria_data['subcategorias'] ) ) : ?>
                    <div class="subcategorias-nav">
                        <?php foreach ( $categoria_data['subcategorias'] as $sub_slug => $sub_data ) : 
                            $sub_url = recifemais_get_url_categoria( $categoria_atual, $sub_slug );
                        ?>
                            <a href="<?php echo esc_url( $sub_url ); ?>" class="subcategoria-link">
                                <span class="subcategoria-title"><?php echo esc_html( $sub_data['title'] ); ?></span>
                                <?php if ( isset( $sub_data['description'] ) ) : ?>
                                    <span class="subcategoria-desc"><?php echo esc_html( $sub_data['description'] ); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Conteúdo da Página -->
        <?php while ( have_posts() ) : the_post(); ?>
            <?php if ( get_the_content() ) : ?>
                <div class="categoria-content">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>

        <!-- Conteúdo Dinâmico por Categoria -->
        <?php
        switch ( $categoria_atual ) {
            case 'descubra':
                recifemais_render_descubra_hub( $categoria_data );
                break;
            case 'historias':
                recifemais_render_historias_hub( $categoria_data );
                break;
            case 'roteiros':
                recifemais_render_roteiros_hub( $categoria_data );
                break;
            case 'noticias':
                recifemais_render_noticias_hub( $categoria_data );
                break;
            case 'agenda':
                recifemais_render_agenda_hub( $categoria_data );
                break;
            case 'comunidade':
                recifemais_render_comunidade_hub( $categoria_data );
                break;
            default:
                recifemais_render_categoria_generica( $categoria_data );
                break;
        }
        ?>

    </div>
</main>

<?php
get_footer();

/**
 * Renderiza o hub da categoria Descubra
 */
function recifemais_render_descubra_hub( $categoria_data ) {
    ?>
    <div class="descubra-hub-content">
        
        <!-- Lugares em Destaque -->
        <section class="hub-section">
            <div class="section-header">
                <h2>Lugares em Destaque</h2>
                <p>Descubra os melhores lugares de Pernambuco</p>
            </div>
            
            <div class="grid grid-3">
                <?php
                $lugares_destaque = new WP_Query( array(
                    'post_type' => 'lugares',
                    'posts_per_page' => 6,
                    'meta_key' => '_destaque_homepage',
                    'meta_value' => '1',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ) );
                
                if ( $lugares_destaque->have_posts() ) :
                    while ( $lugares_destaque->have_posts() ) : $lugares_destaque->the_post();
                        echo recifemais_render_card( get_the_ID(), 'lugares' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            
            <div class="section-footer">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'lugares' ) ); ?>" class="btn btn-outline">
                    Ver Todos os Lugares
                </a>
            </div>
        </section>

        <!-- Por Subcategoria -->
        <?php foreach ( $categoria_data['subcategorias'] as $sub_slug => $sub_data ) : ?>
            <section class="hub-section subcategoria-section">
                <div class="section-header">
                    <h2><?php echo esc_html( $sub_data['title'] ); ?></h2>
                    <?php if ( isset( $sub_data['description'] ) ) : ?>
                        <p><?php echo esc_html( $sub_data['description'] ); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-4">
                    <?php
                    // Query baseada no tipo de subcategoria
                    $query_args = recifemais_get_query_args_subcategoria( $sub_data );
                    $query_args['posts_per_page'] = 4;
                    
                    $subcategoria_query = new WP_Query( $query_args );
                    
                    if ( $subcategoria_query->have_posts() ) :
                        while ( $subcategoria_query->have_posts() ) : $subcategoria_query->the_post();
                            $post_type = get_post_type();
                            echo recifemais_render_card( get_the_ID(), $post_type );
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                
                <div class="section-footer">
                    <a href="<?php echo recifemais_get_url_categoria( 'descubra', $sub_slug ); ?>" class="btn btn-outline">
                        Ver Mais em <?php echo esc_html( $sub_data['title'] ); ?>
                    </a>
                </div>
            </section>
        <?php endforeach; ?>
        
    </div>
    <?php
}

/**
 * Renderiza o hub da categoria Histórias
 */
function recifemais_render_historias_hub( $categoria_data ) {
    ?>
    <div class="historias-hub-content">
        
        <!-- Histórias em Destaque -->
        <section class="hub-section">
            <div class="section-header">
                <h2>Histórias em Destaque</h2>
                <p>Conteúdo aprofundado sobre a cultura pernambucana</p>
            </div>
            
            <div class="grid grid-2">
                <?php
                $historias_destaque = new WP_Query( array(
                    'post_type' => 'post',
                    'posts_per_page' => 4,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => array( 'pessoas', 'projetos', 'tradicoes', 'memoria' )
                        )
                    ),
                    'meta_key' => '_destaque_homepage',
                    'meta_value' => '1'
                ) );
                
                if ( $historias_destaque->have_posts() ) :
                    while ( $historias_destaque->have_posts() ) : $historias_destaque->the_post();
                        echo recifemais_render_card( get_the_ID(), 'post' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>

        <!-- Artistas em Destaque -->
        <section class="hub-section">
            <div class="section-header">
                <h2>Artistas & Atrações</h2>
                <p>Conheça quem faz a cultura pernambucana</p>
            </div>
            
            <div class="grid grid-3">
                <?php
                $artistas_destaque = new WP_Query( array(
                    'post_type' => 'artistas',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ) );
                
                if ( $artistas_destaque->have_posts() ) :
                    while ( $artistas_destaque->have_posts() ) : $artistas_destaque->the_post();
                        echo recifemais_render_card( get_the_ID(), 'artistas' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
        
    </div>
    <?php
}

/**
 * Renderiza o hub da categoria Roteiros
 */
function recifemais_render_roteiros_hub( $categoria_data ) {
    ?>
    <div class="roteiros-hub-content">
        
        <section class="hub-section">
            <div class="section-header">
                <h2>Roteiros Populares</h2>
                <p>Guias práticos para explorar Pernambuco</p>
            </div>
            
            <div class="grid grid-3">
                <?php
                $roteiros_populares = new WP_Query( array(
                    'post_type' => 'roteiros',
                    'posts_per_page' => 9,
                    'orderby' => 'comment_count',
                    'order' => 'DESC'
                ) );
                
                if ( $roteiros_populares->have_posts() ) :
                    while ( $roteiros_populares->have_posts() ) : $roteiros_populares->the_post();
                        echo recifemais_render_card( get_the_ID(), 'roteiros' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
        
    </div>
    <?php
}

/**
 * Renderiza o hub da categoria Notícias
 */
function recifemais_render_noticias_hub( $categoria_data ) {
    ?>
    <div class="noticias-hub-content">
        
        <!-- Últimas Notícias -->
        <section class="hub-section">
            <div class="section-header">
                <h2>Últimas Notícias</h2>
                <p>Fique por dentro do que acontece em Pernambuco</p>
            </div>
            
            <div class="grid grid-3">
                <?php
                $noticias_recentes = new WP_Query( array(
                    'post_type' => 'post',
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ) );
                
                if ( $noticias_recentes->have_posts() ) :
                    while ( $noticias_recentes->have_posts() ) : $noticias_recentes->the_post();
                        echo recifemais_render_card( get_the_ID(), 'post' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
        
    </div>
    <?php
}

/**
 * Renderiza o hub da categoria Agenda
 */
function recifemais_render_agenda_hub( $categoria_data ) {
    ?>
    <div class="agenda-hub-content">
        
        <!-- Eventos Próximos -->
        <section class="hub-section">
            <div class="section-header">
                <h2>Próximos Eventos</h2>
                <p>Não perca o que está acontecendo</p>
            </div>
            
            <div class="grid grid-3">
                <?php
                $eventos_proximos = new WP_Query( array(
                    'post_type' => 'eventos_festivais',
                    'posts_per_page' => 9,
                    'meta_query' => array(
                        array(
                            'key' => '_data_inicio_evento_festival',
                            'value' => date( 'Y-m-d' ),
                            'compare' => '>='
                        )
                    ),
                    'meta_key' => '_data_inicio_evento_festival',
                    'orderby' => 'meta_value',
                    'order' => 'ASC'
                ) );
                
                if ( $eventos_proximos->have_posts() ) :
                    while ( $eventos_proximos->have_posts() ) : $eventos_proximos->the_post();
                        echo recifemais_render_card( get_the_ID(), 'eventos_festivais' );
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>
        
    </div>
    <?php
}

/**
 * Renderiza o hub da categoria Comunidade
 */
function recifemais_render_comunidade_hub( $categoria_data ) {
    ?>
    <div class="comunidade-hub-content">
        
        <section class="hub-section">
            <div class="section-header">
                <h2>Comunidade RecifeMais</h2>
                <p>Conecte-se com outros apaixonados pela cultura pernambucana</p>
            </div>
            
            <div class="comunidade-features">
                <div class="feature-card">
                    <h3>Fórum de Discussões</h3>
                    <p>Participe de conversas sobre cultura, eventos e lugares</p>
                    <a href="#" class="btn btn-primary">Participar</a>
                </div>
                
                <div class="feature-card">
                    <h3>Compartilhe Experiências</h3>
                    <p>Envie suas fotos e relatos de eventos e lugares</p>
                    <a href="#" class="btn btn-primary">Compartilhar</a>
                </div>
                
                <div class="feature-card">
                    <h3>Eventos da Comunidade</h3>
                    <p>Encontros organizados pela comunidade RecifeMais</p>
                    <a href="#" class="btn btn-primary">Ver Eventos</a>
                </div>
            </div>
        </section>
        
    </div>
    <?php
}

/**
 * Renderiza categoria genérica
 */
function recifemais_render_categoria_generica( $categoria_data ) {
    ?>
    <div class="categoria-generica-content">
        <p>Conteúdo em desenvolvimento para <?php echo esc_html( $categoria_data['title'] ); ?></p>
    </div>
    <?php
}

/**
 * Gera argumentos de query baseados na subcategoria
 */
function recifemais_get_query_args_subcategoria( $sub_data ) {
    $args = array();
    
    // Define post type
    if ( isset( $sub_data['cpt'] ) ) {
        if ( is_array( $sub_data['cpt'] ) ) {
            $args['post_type'] = $sub_data['cpt'];
        } else {
            $args['post_type'] = $sub_data['cpt'];
        }
    } elseif ( isset( $sub_data['posts_category'] ) ) {
        $args['post_type'] = 'post';
        $args['category_name'] = $sub_data['posts_category'];
    }
    
    // Define taxonomia se especificada
    if ( isset( $sub_data['taxonomy'] ) && isset( $sub_data['terms'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $sub_data['taxonomy'],
                'field' => 'slug',
                'terms' => $sub_data['terms']
            )
        );
    }
    
    return $args;
}
?> 