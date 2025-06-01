<?php
/**
 * Template Part: Comments Section
 * 
 * Sistema de comentários moderno com:
 * - Formulário de comentário responsivo
 * - Lista de comentários com threading
 * - Sistema de respostas aninhadas
 * - Moderação e aprovação
 * - Integração com redes sociais
 * 
 * @package RecifeMais
 * @since 1.0.0
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Verifica se comentários estão habilitados
if (!comments_open() && get_comments_number() == 0) {
    return;
}

// Dados do post
$post_id = get_the_ID();
$comments = get_comments(['post_id' => $post_id, 'status' => 'approve']);
$comments_count = get_comments_number();

// Configurações
$args = wp_parse_args($args ?? [], [
    'show_comment_form' => true,
    'show_comment_count' => true,
    'show_social_login' => true,
    'enable_threading' => true,
    'max_depth' => 3,
    'show_avatars' => true,
    'show_comment_meta' => true,
    'enable_voting' => false,
    'require_login' => false,
    'layout' => 'modern' // modern, classic, minimal
]);

// Verifica se usuário está logado
$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
?>

<section class="comments-section <?php echo esc_attr('layout-' . $args['layout']); ?> bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-8">
    
    <!-- Cabeçalho dos Comentários -->
    <div class="comments-header mb-8">
        <?php if ($args['show_comment_count']): ?>
            <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <svg class="w-6 h-6 text-recife-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                </svg>
                
                <?php if ($comments_count == 0): ?>
                    Seja o primeiro a comentar
                <?php elseif ($comments_count == 1): ?>
                    1 Comentário
                <?php else: ?>
                    <?php echo number_format($comments_count); ?> Comentários
                <?php endif; ?>
            </h3>
            
            <?php if ($comments_count > 0): ?>
                <p class="text-gray-600">
                    Participe da conversa e compartilhe sua opinião sobre este conteúdo.
                </p>
            <?php else: ?>
                <p class="text-gray-600">
                    Seja o primeiro a deixar um comentário e iniciar a discussão.
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <?php if ($args['show_comment_form'] && comments_open()): ?>
        <!-- Formulário de Comentário -->
        <div class="comment-form-container mb-8">
            
            <?php if (!$args['require_login'] || $is_logged_in): ?>
                
                <?php if ($is_logged_in): ?>
                    <!-- Usuário Logado -->
                    <div class="flex items-center gap-3 mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <?php if ($args['show_avatars']): ?>
                            <img src="<?php echo get_avatar_url($current_user->ID, ['size' => 40]); ?>" 
                                 alt="<?php echo esc_attr($current_user->display_name); ?>"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                        <?php endif; ?>
                        
                        <div>
                            <p class="font-semibold text-gray-900">
                                Olá, <?php echo esc_html($current_user->display_name); ?>!
                            </p>
                            <p class="text-sm text-gray-600">
                                Você está comentando como usuário logado.
                                <a href="<?php echo wp_logout_url(get_permalink()); ?>" 
                                   class="text-recife-primary hover:text-recife-primary/80 transition-colors">
                                    Sair
                                </a>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Formulário -->
                <form id="comment-form" class="comment-form space-y-4" method="post" action="<?php echo site_url('/wp-comments-post.php'); ?>">
                    
                    <?php if (!$is_logged_in): ?>
                        <!-- Campos para usuários não logados -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome *
                                </label>
                                <input type="text" 
                                       id="author" 
                                       name="author" 
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-colors"
                                       placeholder="Seu nome completo">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-colors"
                                       placeholder="seu@email.com">
                            </div>
                        </div>
                        
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                Website (opcional)
                            </label>
                            <input type="url" 
                                   id="url" 
                                   name="url"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-colors"
                                   placeholder="https://seusite.com">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Campo de Comentário -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Comentário *
                        </label>
                        <textarea id="comment" 
                                  name="comment" 
                                  rows="5" 
                                  required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-colors resize-vertical"
                                  placeholder="Compartilhe sua opinião, experiência ou dúvida sobre este conteúdo..."></textarea>
                        
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">
                                Mínimo 10 caracteres. Seja respeitoso e construtivo.
                            </p>
                            <span id="char-count" class="text-xs text-gray-400">0 caracteres</span>
                        </div>
                    </div>
                    
                    <!-- Política de Comentários -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" 
                                   id="comment-policy" 
                                   name="comment_policy" 
                                   required
                                   class="mt-1 w-4 h-4 text-recife-primary border-gray-300 rounded focus:ring-recife-primary">
                            <label for="comment-policy" class="text-sm text-gray-700">
                                Concordo com a 
                                <a href="#" class="text-recife-primary hover:text-recife-primary/80 transition-colors">
                                    política de comentários
                                </a> 
                                e entendo que meu comentário pode ser moderado antes da publicação.
                            </label>
                        </div>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            
                            <?php if ($args['show_social_login'] && !$is_logged_in): ?>
                                <!-- Login Social -->
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-600">Ou entre com:</span>
                                    <button type="button" 
                                            onclick="loginWithFacebook()"
                                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </button>
                                    
                                    <button type="button" 
                                            onclick="loginWithGoogle()"
                                            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                        Google
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" 
                                id="submit-comment"
                                class="bg-recife-primary hover:bg-recife-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                </svg>
                                Publicar Comentário
                            </span>
                        </button>
                    </div>
                    
                    <!-- Campos ocultos -->
                    <input type="hidden" name="comment_post_ID" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    <?php wp_nonce_field('comment_form', 'comment_nonce'); ?>
                </form>
                
            <?php else: ?>
                <!-- Usuário precisa fazer login -->
                <div class="text-center p-8 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                    </svg>
                    
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                        Faça login para comentar
                    </h4>
                    <p class="text-gray-600 mb-4">
                        Você precisa estar logado para deixar um comentário.
                    </p>
                    
                    <div class="flex items-center justify-center gap-4">
                        <a href="<?php echo wp_login_url(get_permalink()); ?>" 
                           class="bg-recife-primary hover:bg-recife-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Fazer Login
                        </a>
                        <a href="<?php echo wp_registration_url(); ?>" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                            Criar Conta
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($comments)): ?>
        <!-- Lista de Comentários -->
        <div class="comments-list">
            <div class="space-y-6">
                <?php
                wp_list_comments([
                    'walker' => new RecifeMais_Comment_Walker(),
                    'style' => 'div',
                    'short_ping' => true,
                    'avatar_size' => 50,
                    'max_depth' => $args['max_depth'],
                    'format' => 'html5'
                ], $comments);
                ?>
            </div>
            
            <!-- Paginação de Comentários -->
            <?php if (get_comment_pages_count() > 1): ?>
                <div class="comments-pagination mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Página <?php echo get_query_var('cpage') ?: 1; ?> de <?php echo get_comment_pages_count(); ?>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <?php
                            paginate_comments_links([
                                'prev_text' => '← Anterior',
                                'next_text' => 'Próxima →',
                                'type' => 'array'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
    <?php elseif (!comments_open()): ?>
        <!-- Comentários Fechados -->
        <div class="text-center p-8 bg-gray-50 rounded-lg border border-gray-200">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
            
            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                Comentários fechados
            </h4>
            <p class="text-gray-600">
                Os comentários para este conteúdo foram desabilitados.
            </p>
        </div>
    <?php endif; ?>
</section>

<script>
// Contador de caracteres
document.addEventListener('DOMContentLoaded', function() {
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    const submitButton = document.getElementById('submit-comment');
    
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + ' caracteres';
            
            if (submitButton) {
                submitButton.disabled = length < 10;
            }
        });
    }
});

// Função para responder comentário
function replyToComment(commentId, authorName) {
    const form = document.getElementById('comment-form');
    const parentField = document.getElementById('comment_parent');
    const commentField = document.getElementById('comment');
    
    if (form && parentField && commentField) {
        parentField.value = commentId;
        commentField.placeholder = `Respondendo para ${authorName}...`;
        commentField.focus();
        
        // Scroll para o formulário
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Adicionar botão de cancelar resposta
        if (!document.getElementById('cancel-reply')) {
            const cancelButton = document.createElement('button');
            cancelButton.id = 'cancel-reply';
            cancelButton.type = 'button';
            cancelButton.className = 'text-sm text-gray-600 hover:text-gray-800 transition-colors ml-4';
            cancelButton.textContent = 'Cancelar resposta';
            cancelButton.onclick = function() {
                parentField.value = '0';
                commentField.placeholder = 'Compartilhe sua opinião, experiência ou dúvida sobre este conteúdo...';
                this.remove();
            };
            
            const submitButton = document.getElementById('submit-comment');
            if (submitButton) {
                submitButton.parentNode.insertBefore(cancelButton, submitButton);
            }
        }
    }
}

// Login social (implementar conforme necessário)
function loginWithFacebook() {
    console.log('Login com Facebook');
    // Implementar integração com Facebook Login
}

function loginWithGoogle() {
    console.log('Login com Google');
    // Implementar integração com Google Login
}

// Votação em comentários (se habilitado)
function voteComment(commentId, type) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'comment_vote', {
            'comment_id': commentId,
            'vote_type': type
        });
    }
    
    // Implementar lógica de votação via AJAX
    console.log('Voto:', type, 'Comentário:', commentId);
}
</script>

<style>
.comments-section.layout-modern {
    @apply bg-white rounded-lg shadow-sm border border-gray-200;
}

.comments-section.layout-classic {
    @apply bg-gray-50 border-t border-gray-200;
}

.comments-section.layout-minimal {
    @apply bg-transparent border-0 shadow-none;
}

.comment-form textarea:focus {
    @apply ring-2 ring-recife-primary border-transparent;
}

.comment-form input:focus {
    @apply ring-2 ring-recife-primary border-transparent;
}

@media (max-width: 768px) {
    .comments-section {
        @apply p-4;
    }
    
    .comment-form .grid {
        @apply grid-cols-1;
    }
}
</style> 