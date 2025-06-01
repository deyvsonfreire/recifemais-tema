<?php
/**
 * Template: Homepage RecifeMais - Estilo Globo.com
 * 
 * Layout inspirado na Globo.com utilizando componentes modulares
 * para um design limpo e profissional.
 * 
 * @package RecifeMais_Tema
 * @since 1.0.0
 */

get_header();

/**
 * Função auxiliar para exibir cabeçalhos de seção com estilo consistente
 * 
 * @param string $title     Título da seção
 * @param string $color     Cor da barra e do texto (ex: 'red', 'green')
 * @param string $link_url  URL do link "Ver mais"
 */
function recifemais_section_header($title, $color, $link_url) {
    ?>
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 flex items-center gap-4">
            <span class="w-1 h-8 bg-<?php echo esc_attr($color); ?>-600 rounded-full"></span>
            <span class="text-<?php echo esc_attr($color); ?>-600"><?php echo esc_html($title); ?></span>
        </h2>
        <a href="<?php echo esc_url($link_url); ?>" 
           class="text-<?php echo esc_attr($color); ?>-600 hover:text-<?php echo esc_attr($color); ?>-700 font-semibold transition-colors">
            Ver mais →
        </a>
    </div>
    <?php
}

/**
 * Função auxiliar para exibir mensagem quando não há posts
 * 
 * @param string $message   Mensagem principal
 * @param string $link_text Texto do link
 * @param string $link_url  URL do link
 * @param string $color     Cor do link (ex: 'red', 'green')
 */
function recifemais_no_posts_message($message, $link_text, $link_url, $color) {
    ?>
    <div class="col-span-full text-center py-8">
        <p class="text-gray-500"><?php echo esc_html($message); ?></p>
        <a href="<?php echo esc_url($link_url); ?>" 
           class="text-<?php echo esc_attr($color); ?>-600 hover:text-<?php echo esc_attr($color); ?>-700 font-semibold">
            <?php echo esc_html($link_text); ?>
        </a>
    </div>
    <?php
}

/**
 * Função para buscar e exibir posts com base nos parâmetros definidos
 *
 * @param array  $query_args    Argumentos para WP_Query
 * @param string $template_part Caminho para o template part a ser usado
 * @param string $no_posts_msg  Mensagem quando não há posts
 * @param string $link_text     Texto do link quando não há posts
 * @param string $link_url      URL do link quando não há posts
 * @param string $color         Cor do link quando não há posts
 */
function recifemais_display_posts($query_args, $template_part, $no_posts_msg, $link_text, $link_url, $color) {
    $query = new WP_Query($query_args);
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part($template_part);
        endwhile;
        wp_reset_postdata();
    else :
        recifemais_no_posts_message($no_posts_msg, $link_text, $link_url, $color);
    endif;
}
?>

<main id="homepage-main" class="bg-gray-50" role="main">
    
    <!-- ===== HERO + SIDEBAR LAYOUT (Estilo Globo) ===== -->
    <section class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- HERO PRINCIPAL (2/3 da largura) -->
                <div class="lg:col-span-2">
                    <?php
                    // Buscar post principal (featured ou mais recente)
                    $hero_args = array(
                        'posts_per_page' => 1,
                        'meta_key' => '_featured_post',
                        'meta_value' => '1',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    );
                    
                    $hero_post = new WP_Query($hero_args);
                    
                    // Se não houver featured, pegar o mais recente
                    if (!$hero_post->have_posts()) {
                        $hero_args = array(
                            'posts_per_page' => 1,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        );
                        $hero_post = new WP_Query($hero_args);
                    }
                    
                    if ($hero_post->have_posts()) :
                        while ($hero_post->have_posts()) : $hero_post->the_post();
                            get_template_part('template-parts/homepage/hero-breaking');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                
                <!-- SIDEBAR NOTÍCIAS (1/3 da largura) -->
                <div class="lg:col-span-1">
                    <?php get_template_part('template-parts/homepage/sidebar-widgets'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SEÇÃO: AGENDA (Eventos) ===== -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <?php recifemais_section_header('Agenda', 'red', home_url('/eventos_festivais')); ?>
            
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $eventos_args = array(
                    'post_type' => 'eventos_festivais',
                    'posts_per_page' => 4,
                    'meta_key' => 'evento_data_inicio',
                    'orderby' => 'meta_value',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'evento_data_inicio',
                            'value' => date('Y-m-d'),
                            'compare' => '>='
                        )
                    )
                );
                
                recifemais_display_posts(
                    $eventos_args,
                    'components/cards/card-evento',
                    'Nenhum evento encontrado.',
                    'Ver todos os eventos',
                    home_url('/eventos_festivais'),
                    'red'
                );
                ?>
            </div>
        </div>
    </section>

    <!-- ===== SEÇÃO: DESCUBRA (Lugares) ===== -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <?php recifemais_section_header('Descubra', 'green', home_url('/lugares')); ?>
            
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $lugares_args = array(
                    'post_type' => 'lugares',
                    'posts_per_page' => 6,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                recifemais_display_posts(
                    $lugares_args,
                    'components/cards/card-lugar',
                    'Nenhum lugar encontrado.',
                    'Ver todos os lugares',
                    home_url('/lugares'),
                    'green'
                );
                ?>
            </div>
        </div>
    </section>

    <!-- ===== SEÇÃO: ROTEIROS ===== -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <?php recifemais_section_header('Roteiros', 'orange', home_url('/roteiros')); ?>
            
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $roteiros_args = array(
                    'post_type' => 'roteiros',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                recifemais_display_posts(
                    $roteiros_args,
                    'components/cards/card-roteiro',
                    'Nenhum roteiro encontrado.',
                    'Ver todos os roteiros',
                    home_url('/roteiros'),
                    'orange'
                );
                ?>
            </div>
        </div>
    </section>

    <!-- ===== SEÇÃO: CULTURA (Artistas) ===== -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <?php recifemais_section_header('Cultura', 'purple', home_url('/artistas')); ?>
            
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $artistas_args = array(
                    'post_type' => 'artistas',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                recifemais_display_posts(
                    $artistas_args,
                    'components/cards/card-artista',
                    'Nenhum artista encontrado.',
                    'Ver todos os artistas',
                    home_url('/artistas'),
                    'purple'
                );
                ?>
            </div>
        </div>
    </section>

    <!-- ===== SEÇÃO: HISTÓRIAS ===== -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <?php recifemais_section_header('Histórias', 'indigo', home_url('/historias')); ?>
            
            <!-- Layout Horizontal para Histórias -->
            <div class="space-y-4">
                <?php
                $historias_args = array(
                    'post_type' => 'historias',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                $historias = new WP_Query($historias_args);
                
                if ($historias->have_posts()) :
                    while ($historias->have_posts()) : $historias->the_post();
                        get_template_part('components/cards/card-horizontal');
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">Nenhuma história encontrada.</p>
                        <a href="<?php echo esc_url(home_url('/historias')); ?>" 
                           class="text-indigo-600 hover:text-indigo-700 font-semibold">
                            Ver todas as histórias
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== NEWSLETTER ===== -->
    <section class="py-12 bg-gray-900">
        <div class="container mx-auto px-4">
            <?php get_template_part('template-parts/homepage/newsletter-signup'); ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
