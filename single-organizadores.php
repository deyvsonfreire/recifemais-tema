<?php
/**
 * Template para exibir organizadores individuais
 * Padrão elegante inspirado no single.php
 *
 * @package RecifeMaisTema
 * @since 2.0.0
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); 
            $organizador_id = get_the_ID();
            
            // Meta dados do organizador
    $tipo_entidade = get_post_meta($organizador_id, '_tipo_entidade_organizadora', true);
    if (!$tipo_entidade) $tipo_entidade = get_post_meta($organizador_id, 'tipo_entidade', true);
    
    $website_oficial = get_post_meta($organizador_id, '_website_produtor_organizador', true);
    if (!$website_oficial) $website_oficial = get_post_meta($organizador_id, 'website', true);
    
    $contato_email = get_post_meta($organizador_id, '_contato_produtor_organizador', true);
    if (!$contato_email) $contato_email = get_post_meta($organizador_id, 'email_contato', true);
    
    $telefone_contato = get_post_meta($organizador_id, '_telefone_contato_organizador', true);
    if (!$telefone_contato) $telefone_contato = get_post_meta($organizador_id, 'telefone', true);
    
    $endereco_sede = get_post_meta($organizador_id, '_endereco_sede_organizador', true);
    if (!$endereco_sede) $endereco_sede = get_post_meta($organizador_id, 'endereco', true);
    
    $ano_fundacao = get_post_meta($organizador_id, '_ano_fundacao_organizador', true);
    $redes_sociais = get_post_meta($organizador_id, '_redes_sociais_organizador', true);
            
            // Taxonomias
    $categorias = get_the_terms($organizador_id, 'categorias_recifemais');
    $cidades = get_the_terms($organizador_id, 'cidades_pernambuco');
    
    // Eventos relacionados
    $eventos_organizados = get_posts(array(
        'post_type' => 'eventos_festivais',
        'meta_query' => array(
            array(
                'key' => '_evento_organizadores_relacionados',
                'value' => $organizador_id,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
?>

<!-- Breadcrumbs -->
<nav class="bg-recife-gray-50 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-recife-gray-600">
            <a href="<?php echo home_url(); ?>" class="hover:text-recife-primary transition-colors">
                Início
            </a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="<?php echo get_post_type_archive_link('organizadores'); ?>" class="hover:text-recife-primary transition-colors">
                Organizadores
            </a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-recife-gray-500">
                <?php echo wp_trim_words(get_the_title(), 8); ?>
            </span>
        </div>
    </div>
</nav>

<!-- Artigo Principal -->
<article class="bg-white">
    <!-- Header do Artigo -->
    <header class="py-8 lg:py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Badge do Tipo de Organização -->
                <?php if ($tipo_entidade) : ?>
                <div class="mb-6">
                    <span class="inline-block px-4 py-2 bg-cpt-organizadores text-white text-sm font-medium rounded-lg">
                        🏢 <?php echo esc_html($tipo_entidade); ?>
                    </span>
                </div>
                <?php elseif ($categorias && !is_wp_error($categorias)) : ?>
                <div class="mb-6">
                    <span class="inline-block px-4 py-2 bg-cpt-organizadores text-white text-sm font-medium rounded-lg">
                        🏢 <?php echo esc_html($categorias[0]->name); ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <!-- Título -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-recife-gray-900 leading-tight mb-6">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Subtítulo/Excerpt -->
                <?php if (has_excerpt()) : ?>
                <div class="text-xl lg:text-2xl text-recife-gray-600 leading-relaxed mb-8">
                    <?php the_excerpt(); ?>
                </div>
                <?php endif; ?>
                
                <!-- Meta Informações do Organizador -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-recife-gray-500 mb-8">
                    
                    <?php if ($cidades && !is_wp_error($cidades)) : ?>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span><?php echo esc_html($cidades[0]->name); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($ano_fundacao) : ?>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Desde <?php echo esc_html($ano_fundacao); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tipo_entidade) : ?>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span><?php echo esc_html($tipo_entidade); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($eventos_organizados)) : ?>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span><?php echo count($eventos_organizados); ?> eventos organizados</span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Compartilhamento -->
                <div class="flex items-center gap-4 pb-8 border-b border-recife-gray-200">
                    <span class="text-sm font-medium text-recife-gray-700">Compartilhar:</span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" 
                           class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" 
                           class="w-8 h-8 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        
                        <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" 
                           target="_blank" 
                           class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                        </a>
                        
                        <button onclick="copyToClipboard()" 
                                class="w-8 h-8 bg-recife-gray-600 text-white rounded-full flex items-center justify-center hover:bg-recife-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Imagem Destacada -->
    <?php if (has_post_thumbnail()) : ?>
    <div class="mb-8 lg:mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <figure class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <?php the_post_thumbnail('full', array('class' => 'w-full h-full object-cover')); ?>
                </figure>
                <?php 
                $caption = get_the_post_thumbnail_caption();
                if ($caption) : 
                ?>
                <figcaption class="text-sm text-recife-gray-500 mt-3 text-center italic">
                    <?php echo $caption; ?>
                </figcaption>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="pb-12 lg:pb-16">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
                <!-- Conteúdo do Artigo -->
                <div class="lg:col-span-8">
                    <div class="max-w-none">
                        <!-- Sobre o Organizador -->
                        <div class="prose prose-lg max-w-none 
                                    prose-headings:text-recife-gray-900 prose-headings:font-bold 
                                    prose-p:text-recife-gray-700 prose-p:leading-relaxed prose-p:mb-6
                                    prose-a:text-recife-primary prose-a:no-underline hover:prose-a:underline 
                                    prose-strong:text-recife-gray-900 prose-strong:font-semibold
                                    prose-blockquote:border-l-4 prose-blockquote:border-cpt-organizadores 
                                    prose-blockquote:bg-recife-gray-50 prose-blockquote:py-4 prose-blockquote:px-6 
                                    prose-blockquote:not-italic prose-blockquote:text-recife-gray-700
                                    prose-ul:my-6 prose-ol:my-6 prose-li:my-2
                                    prose-img:rounded-lg prose-img:shadow-md
                                    prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-4
                                    prose-h3:text-xl prose-h3:mt-6 prose-h3:mb-3
                                    prose-h4:text-lg prose-h4:mt-4 prose-h4:mb-2">
                            
                            <?php 
                            // Exibir descrição do organizador
                            the_content();
                            ?>
                            
                            <?php
                            // Paginação de posts longos
                            wp_link_pages(array(
                                'before' => '<div class="page-links mt-8 pt-6 border-t border-recife-gray-200"><span class="page-links-title text-sm font-medium text-recife-gray-700">Páginas:</span>',
                                'after'  => '</div>',
                                'link_before' => '<span class="inline-block px-3 py-1 mx-1 bg-recife-gray-100 text-recife-gray-700 rounded hover:bg-cpt-organizadores hover:text-white transition-colors">',
                                'link_after'  => '</span>',
                            ));
                            ?>
                        </div>

                        <!-- Eventos Organizados -->
                        <?php if (!empty($eventos_organizados)) : ?>
                        <div class="mt-8 p-6 bg-recife-gray-50 rounded-lg">
                            <h3 class="text-xl font-bold text-recife-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-cpt-organizadores" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Eventos Organizados (<?php echo count($eventos_organizados); ?>)
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <?php foreach (array_slice($eventos_organizados, 0, 6) as $evento) : 
                                    $data_evento = get_post_meta($evento->ID, '_data_evento_inicio', true);
                                    $local_evento = get_post_meta($evento->ID, '_evento_local_nome', true);
                                ?>
                                    <article class="flex gap-4 p-4 bg-white rounded-lg border border-recife-gray-200 hover:shadow-sm transition-shadow">
                                        <?php if (has_post_thumbnail($evento->ID)) : ?>
                                            <img src="<?php echo get_the_post_thumbnail_url($evento->ID, 'thumbnail'); ?>" 
                                                 alt="<?php echo esc_attr($evento->post_title); ?>"
                                                 class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                        <?php endif; ?>
                                        
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-recife-gray-900 text-sm mb-1 line-clamp-2">
                                                <a href="<?php echo get_permalink($evento->ID); ?>" class="hover:text-cpt-organizadores transition-colors">
                                                    <?php echo esc_html($evento->post_title); ?>
                                                </a>
                                            </h4>
                                            <?php if ($data_evento) : ?>
                                                <div class="text-xs text-recife-gray-500 mb-1">
                                                    📅 <?php echo date('d/m/Y', strtotime($data_evento)); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($local_evento) : ?>
                                                <div class="text-xs text-recife-gray-500">
                                                    📍 <?php echo esc_html($local_evento); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($eventos_organizados) > 6) : ?>
                            <div class="mt-6 text-center">
                                <a href="<?php echo get_post_type_archive_link('eventos_festivais'); ?>?organizador=<?php echo $organizador_id; ?>" 
                                   class="text-cpt-organizadores font-semibold hover:underline">
                                    Ver todos os eventos organizados →
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Tags/Taxonomias -->
                        <?php if ($categorias || $cidades) : ?>
                        <div class="mt-8 pt-6 border-t border-recife-gray-200">
                            <div class="flex flex-wrap gap-4">
                                
                                <?php if ($categorias && !is_wp_error($categorias)) : ?>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-sm font-medium text-recife-gray-700">Categorias:</span>
                                        <?php foreach ($categorias as $categoria) : ?>
                                            <a href="<?php echo get_term_link($categoria); ?>" 
                                               class="inline-block px-3 py-1 bg-cpt-organizadores text-white text-sm rounded-full hover:bg-opacity-80 transition-colors">
                                                <?php echo esc_html($categoria->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($cidades && !is_wp_error($cidades)) : ?>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-sm font-medium text-recife-gray-700">Cidades:</span>
                                        <?php foreach ($cidades as $cidade) : ?>
                                            <a href="<?php echo get_term_link($cidade); ?>" 
                                               class="inline-block px-3 py-1 bg-cpt-lugares text-white text-sm rounded-full hover:bg-opacity-80 transition-colors">
                                                <?php echo esc_html($cidade->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Navegação Entre Organizadores -->
                        <div class="mt-12 pt-8 border-t border-recife-gray-200">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            
                            <div class="flex justify-between items-center">
                                <?php if ($prev_post) : ?>
                                    <a href="<?php echo get_permalink($prev_post); ?>" 
                                       class="flex items-center gap-3 group hover:text-cpt-organizadores transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-recife-gray-500">Organizador Anterior</div>
                                            <div class="font-semibold"><?php echo wp_trim_words(get_the_title($prev_post), 6); ?></div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($next_post) : ?>
                                    <a href="<?php echo get_permalink($next_post); ?>" 
                                       class="flex items-center gap-3 group hover:text-cpt-organizadores transition-colors text-right">
                                        <div>
                                            <div class="text-sm text-recife-gray-500">Próximo Organizador</div>
                                            <div class="font-semibold"><?php echo wp_trim_words(get_the_title($next_post), 6); ?></div>
                                        </div>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <div class="space-y-8">
                        
                        <!-- Informações da Organização -->
                        <div class="bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6">
                            <h3 class="text-lg font-bold text-recife-gray-900 mb-4 flex items-center gap-2">
                                <span class="text-xl">🏢</span>
                                Informações
                            </h3>
                            
                            <div class="space-y-4">
                                <?php if ($tipo_entidade) : ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-cpt-organizadores rounded-lg flex items-center justify-center text-white">
                                        🏛️
                                    </div>
                                    <div>
                                        <div class="font-semibold text-recife-gray-900">
                                            <?php echo esc_html($tipo_entidade); ?>
                                        </div>
                                        <div class="text-sm text-recife-gray-600">Tipo de Entidade</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($ano_fundacao) : ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                        📅
                                    </div>
                                    <div>
                                        <div class="font-semibold text-recife-gray-900">
                                            <?php echo esc_html($ano_fundacao); ?>
                                        </div>
                                        <div class="text-sm text-recife-gray-600">Ano de Fundação</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($endereco_sede) : ?>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                        🏠
                                    </div>
                                    <div>
                                        <div class="font-semibold text-recife-gray-900 text-sm">
                                            <?php echo esc_html($endereco_sede); ?>
                                        </div>
                                        <div class="text-sm text-recife-gray-600">Sede</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($eventos_organizados)) : ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center text-white">
                                        ⭐
                                    </div>
                                    <div>
                                        <div class="font-semibold text-recife-gray-900">
                                            <?php echo count($eventos_organizados); ?>
                                        </div>
                                        <div class="text-sm text-recife-gray-600">Eventos Organizados</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Contatos -->
                        <?php if ($website_oficial || $contato_email || $telefone_contato) : ?>
                        <div class="bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6">
                            <h3 class="text-lg font-bold text-recife-gray-900 mb-4 flex items-center gap-2">
                                <span class="text-xl">📞</span>
                                Contatos
                            </h3>
                            
                            <div class="space-y-3">
                                <?php if ($website_oficial) : ?>
                                <a href="<?php echo esc_url($website_oficial); ?>" 
                                   target="_blank"
                                   class="flex items-center gap-3 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                        🌐
                                    </div>
                                    <span class="text-recife-gray-700 hover:text-blue-600">Site Oficial</span>
                                </a>
                                <?php endif; ?>

                                <?php if ($contato_email) : ?>
                                <a href="mailto:<?php echo esc_attr($contato_email); ?>" 
                                   class="flex items-center gap-3 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white">
                                        ✉️
                                    </div>
                                    <span class="text-recife-gray-700 hover:text-red-600 text-sm"><?php echo esc_html($contato_email); ?></span>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($telefone_contato) : ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $telefone_contato)); ?>" 
                                   class="flex items-center gap-3 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                        📱
                                    </div>
                                    <span class="text-recife-gray-700 hover:text-green-600"><?php echo esc_html($telefone_contato); ?></span>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($redes_sociais) : ?>
                                <a href="<?php echo esc_url($redes_sociais); ?>" 
                                   target="_blank"
                                   class="flex items-center gap-3 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors">
                                    <div class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center text-white">
                                        📱
                                    </div>
                                    <span class="text-recife-gray-700 hover:text-pink-600">Redes Sociais</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Organizadores Similares -->
                        <div class="bg-white rounded-lg shadow-sm border border-recife-gray-200 p-6">
                            <h3 class="text-lg font-bold text-recife-gray-900 mb-4 flex items-center gap-2">
                                <span class="text-xl">🤝</span>
                                Organizadores Similares
                            </h3>
                            
                            <?php
                            $organizadores_similares_args = array(
                                'post_type' => 'organizadores',
                                'posts_per_page' => 4,
                                'post__not_in' => array($organizador_id),
                                'orderby' => 'date',
                                'order' => 'DESC'
                            );

                            // Se tem categoria, buscar por categoria similar
                            if ($categorias && !is_wp_error($categorias)) {
                                $organizadores_similares_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'categorias_recifemais',
                                        'field' => 'term_id',
                                        'terms' => $categorias[0]->term_id
                                    )
                                );
                            }

                            $organizadores_similares = new WP_Query($organizadores_similares_args);

                            if ($organizadores_similares->have_posts()) :
                                ?>
                                <div class="space-y-4">
                                    <?php while ($organizadores_similares->have_posts()) : $organizadores_similares->the_post(); 
                                        $tipo_similar = get_post_meta(get_the_ID(), '_tipo_entidade_organizadora', true);
                                        $cidade_similar = get_the_terms(get_the_ID(), 'cidades_pernambuco');
                                        ?>
                                        <article class="flex gap-4 p-3 hover:bg-recife-gray-50 rounded-lg transition-colors">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" 
                                                     alt="<?php the_title_attribute(); ?>"
                                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            <?php endif; ?>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-recife-gray-900 text-sm mb-1 line-clamp-2">
                                                    <a href="<?php the_permalink(); ?>" class="hover:text-cpt-organizadores transition-colors">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h4>
                                                <?php if ($tipo_similar) : ?>
                                                    <span class="text-xs text-recife-gray-500 block">
                                                        🏢 <?php echo esc_html($tipo_similar); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($cidade_similar && !is_wp_error($cidade_similar)) : ?>
                                                    <span class="text-xs text-recife-gray-500">
                                                        📍 <?php echo esc_html($cidade_similar[0]->name); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </article>
                                    <?php endwhile; ?>
                                </div>
                                
                                <div class="mt-6 pt-4 border-t border-recife-gray-200">
                                    <a href="<?php echo get_post_type_archive_link('organizadores'); ?>" 
                                       class="text-cpt-organizadores font-semibold hover:underline">
                                        Ver todos os organizadores →
                                    </a>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>

                        <!-- Ações do Organizador -->
                        <div class="bg-gradient-to-br from-cpt-organizadores to-recife-secondary text-white rounded-lg p-6">
                            <h3 class="text-lg font-bold mb-4 text-center">Conecte-se</h3>
                            
                            <div class="space-y-3">
                                <?php if ($contato_email) : ?>
                                <a href="mailto:<?php echo esc_attr($contato_email); ?>" 
                                   class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Enviar Email
                                </a>
                                <?php endif; ?>
                                
                                <button onclick="saveOrganizador(<?php echo $organizador_id; ?>)" 
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    Salvar Organizador
                                </button>
                            </div>
                            
                            <p class="text-white text-opacity-90 mb-4 text-center text-sm mt-4">
                                Fique por dentro dos eventos organizados
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Scripts -->
<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link copiado para a área de transferência!');
    }, function(err) {
        console.error('Erro ao copiar: ', err);
        alert('Link: ' + window.location.href);
    });
}

function saveOrganizador(organizadorId) {
    if(localStorage.getItem('organizador_' + organizadorId)) {
        localStorage.removeItem('organizador_' + organizadorId);
        alert('Organizador removido dos salvos');
    } else {
        localStorage.setItem('organizador_' + organizadorId, JSON.stringify({
            title: '<?php echo esc_js(get_the_title()); ?>',
            url: window.location.href,
            saved_at: new Date().toISOString()
        }));
        alert('Organizador salvo com sucesso!');
    }
}
</script>

<?php endwhile; ?>

<?php get_footer(); ?>