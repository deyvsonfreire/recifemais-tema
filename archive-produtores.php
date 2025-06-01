<?php
/**
 * Template Archive Produtores (Redirect para Organizadores)
 * Este arquivo redireciona para o archive correto de organizadores
 * 
 * @package RecifeMaisTema
 * @since 2.0.0
 */

// Redirect permanente para o archive correto
wp_redirect(get_post_type_archive_link('organizadores'), 301);
exit;