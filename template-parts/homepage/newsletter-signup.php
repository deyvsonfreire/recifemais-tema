<?php
/**
 * Newsletter Signup - Homepage RecifeMais
 * Componente de inscrição na newsletter com design moderno
 * 
 * @package RecifeMais_Tema
 * @version 2.0
 */

// Configurações do newsletter
$newsletter_config = [
    'show_benefits' => true,
    'show_privacy_note' => true,
    'show_social_proof' => true,
    'enable_analytics' => true
];

// Estatísticas da newsletter (simuladas - integrar com sistema real)
$newsletter_stats = [
    'subscribers' => 12847,
    'weekly_opens' => 89,
    'satisfaction' => 4.8
];

// Benefícios da newsletter
$newsletter_benefits = [
    [
        'icon' => '📅',
        'title' => 'Agenda Semanal',
        'description' => 'Receba os melhores eventos da semana'
    ],
    [
        'icon' => '🎭',
        'title' => 'Conteúdo Exclusivo',
        'description' => 'Acesso antecipado a festivais e shows'
    ],
    [
        'icon' => '💰',
        'title' => 'Descontos Especiais',
        'description' => 'Promoções exclusivas para assinantes'
    ],
    [
        'icon' => '🏆',
        'title' => 'Dicas de Especialistas',
        'description' => 'Recomendações da nossa equipe cultural'
    ]
];
?>

<section class="newsletter-signup py-12 lg:py-16 bg-gradient-to-br from-recife-primary/5 via-recife-secondary/5 to-recife-creative/5 relative overflow-hidden" 
         id="newsletter-signup"
         data-component="newsletter-signup">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-64 h-64 bg-recife-primary rounded-full -translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-recife-secondary rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/2 w-32 h-32 bg-recife-creative rounded-full -translate-x-16 -translate-y-16"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium text-recife-primary mb-4">
                <span class="w-2 h-2 bg-recife-primary rounded-full animate-pulse"></span>
                Newsletter RecifeMais
            </div>
            
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Não Perca Nada da 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-recife-primary to-recife-secondary">
                    Cultura Pernambucana
                </span>
            </h2>
            
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Receba semanalmente os melhores eventos, lugares e experiências culturais 
                diretamente no seu e-mail. Seja o primeiro a saber de tudo que acontece!
            </p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Formulário de Inscrição -->
                <div class="order-2 lg:order-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-10 border border-gray-100">
                        
                        <!-- Social Proof -->
                        <?php if ($newsletter_config['show_social_proof']) : ?>
                        <div class="flex items-center gap-4 mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                            <div class="flex -space-x-2">
                                <?php for ($i = 1; $i <= 4; $i++) : ?>
                                <div class="w-8 h-8 bg-gradient-to-br from-recife-primary to-recife-secondary rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold">
                                    <?php echo chr(64 + $i); ?>
                                </div>
                                <?php endfor; ?>
                            </div>
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">
                                    +<?php echo number_format($newsletter_stats['subscribers']); ?> pessoas
                                </div>
                                <div class="text-gray-600">já recebem nossa newsletter</div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Formulário -->
                        <form class="newsletter-form space-y-4" 
                              data-newsletter-form
                              data-analytics-category="Newsletter"
                              data-analytics-action="Subscribe">
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="newsletter-name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome Completo
                                    </label>
                                    <input type="text" 
                                           id="newsletter-name" 
                                           name="name" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                           placeholder="Seu nome completo">
                                </div>

                                <div>
                                    <label for="newsletter-email" class="block text-sm font-medium text-gray-700 mb-2">
                                        E-mail
                                    </label>
                                    <input type="email" 
                                           id="newsletter-email" 
                                           name="email" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                           placeholder="seu@email.com">
                                </div>

                                <div>
                                    <label for="newsletter-interests" class="block text-sm font-medium text-gray-700 mb-2">
                                        Interesses (opcional)
                                    </label>
                                    <select id="newsletter-interests" 
                                            name="interests[]" 
                                            multiple
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-recife-primary focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                            data-placeholder="Selecione seus interesses">
                                        <option value="eventos">Eventos & Festivais</option>
                                        <option value="gastronomia">Gastronomia</option>
                                        <option value="musica">Música</option>
                                        <option value="arte">Arte & Cultura</option>
                                        <option value="turismo">Turismo Cultural</option>
                                        <option value="historia">História</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Checkbox de Privacidade -->
                            <?php if ($newsletter_config['show_privacy_note']) : ?>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" 
                                       id="newsletter-privacy" 
                                       name="privacy_accepted" 
                                       required
                                       class="mt-1 w-4 h-4 text-recife-primary border-gray-300 rounded focus:ring-recife-primary">
                                <label for="newsletter-privacy" class="text-sm text-gray-600">
                                    Aceito receber e-mails do RecifeMais e concordo com a 
                                    <a href="/politica-privacidade" class="text-recife-primary hover:underline">
                                        Política de Privacidade
                                    </a>
                                </label>
                            </div>
                            <?php endif; ?>

                            <!-- Botão de Inscrição -->
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-recife-primary to-recife-secondary text-white font-semibold py-4 px-6 rounded-xl hover:from-recife-primary/90 hover:to-recife-secondary/90 focus:ring-4 focus:ring-recife-primary/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                                    data-submit-btn>
                                <span class="flex items-center justify-center gap-2">
                                    <span data-default-text>Quero Receber a Newsletter</span>
                                    <span data-loading-text class="hidden">
                                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Inscrevendo...
                                    </span>
                                    <span data-success-text class="hidden">✓ Inscrito com Sucesso!</span>
                                </span>
                            </button>

                            <!-- Mensagem de Status -->
                            <div class="newsletter-status hidden" data-status-message>
                                <div class="p-4 rounded-xl text-sm font-medium" data-status-content></div>
                            </div>
                        </form>

                        <!-- Garantia -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600">🔒</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">100% Seguro e Sem Spam</div>
                                    <div>Cancele a qualquer momento com um clique</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefícios -->
                <div class="order-1 lg:order-2">
                    <?php if ($newsletter_config['show_benefits']) : ?>
                    <div class="space-y-6">
                        <div class="text-center lg:text-left">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                O que você vai receber:
                            </h3>
                        </div>

                        <div class="grid gap-4">
                            <?php foreach ($newsletter_benefits as $index => $benefit) : ?>
                            <div class="flex items-start gap-4 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/80 transition-all duration-200"
                                 data-aos="fade-up" 
                                 data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="w-12 h-12 bg-gradient-to-br from-recife-primary/10 to-recife-secondary/10 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                                    <?php echo esc_html($benefit['icon']); ?>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">
                                        <?php echo esc_html($benefit['title']); ?>
                                    </h4>
                                    <p class="text-gray-600 text-sm">
                                        <?php echo esc_html($benefit['description']); ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Estatísticas -->
                        <div class="grid grid-cols-3 gap-4 pt-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-recife-primary">
                                    <?php echo number_format($newsletter_stats['subscribers']); ?>+
                                </div>
                                <div class="text-sm text-gray-600">Inscritos</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-recife-secondary">
                                    <?php echo $newsletter_stats['weekly_opens']; ?>%
                                </div>
                                <div class="text-sm text-gray-600">Taxa de Abertura</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-recife-creative">
                                    <?php echo $newsletter_stats['satisfaction']; ?>★
                                </div>
                                <div class="text-sm text-gray-600">Satisfação</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.querySelector('[data-newsletter-form]');
    const submitBtn = document.querySelector('[data-submit-btn]');
    const statusMessage = document.querySelector('[data-status-message]');
    const statusContent = document.querySelector('[data-status-content]');
    
    if (!newsletterForm) return;

    // Configurar select múltiplo
    const interestsSelect = document.getElementById('newsletter-interests');
    if (interestsSelect) {
        // Simular funcionalidade de select múltiplo customizado
        interestsSelect.addEventListener('change', function() {
            const selectedOptions = Array.from(this.selectedOptions).map(option => option.text);
            if (selectedOptions.length > 0) {
                this.setAttribute('title', selectedOptions.join(', '));
            }
        });
    }

    // Submissão do formulário
    newsletterForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Coletar interesses selecionados
        const interests = Array.from(interestsSelect.selectedOptions).map(option => option.value);
        data.interests = interests;

        // Estado de loading
        setLoadingState(true);
        hideStatus();

        try {
            // Simular envio (integrar com sistema real)
            await simulateNewsletterSubscription(data);
            
            // Sucesso
            showStatus('success', 'Inscrição realizada com sucesso! Verifique seu e-mail.');
            setSuccessState();
            
            // Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'newsletter_subscribe', {
                    event_category: 'Newsletter',
                    event_label: 'Homepage',
                    value: 1
                });
            }
            
            // Reset form após 3 segundos
            setTimeout(() => {
                newsletterForm.reset();
                resetFormState();
            }, 3000);
            
        } catch (error) {
            console.error('Erro na inscrição:', error);
            showStatus('error', 'Erro ao processar inscrição. Tente novamente.');
            setLoadingState(false);
        }
    });

    function setLoadingState(loading) {
        const defaultText = submitBtn.querySelector('[data-default-text]');
        const loadingText = submitBtn.querySelector('[data-loading-text]');
        
        if (loading) {
            defaultText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            defaultText.classList.remove('hidden');
            loadingText.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    function setSuccessState() {
        const defaultText = submitBtn.querySelector('[data-default-text]');
        const loadingText = submitBtn.querySelector('[data-loading-text]');
        const successText = submitBtn.querySelector('[data-success-text]');
        
        defaultText.classList.add('hidden');
        loadingText.classList.add('hidden');
        successText.classList.remove('hidden');
        
        submitBtn.classList.remove('from-recife-primary', 'to-recife-secondary');
        submitBtn.classList.add('bg-green-500', 'hover:bg-green-600');
    }

    function resetFormState() {
        const defaultText = submitBtn.querySelector('[data-default-text]');
        const loadingText = submitBtn.querySelector('[data-loading-text]');
        const successText = submitBtn.querySelector('[data-success-text]');
        
        defaultText.classList.remove('hidden');
        loadingText.classList.add('hidden');
        successText.classList.add('hidden');
        
        submitBtn.classList.add('from-recife-primary', 'to-recife-secondary');
        submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
        submitBtn.disabled = false;
        
        hideStatus();
    }

    function showStatus(type, message) {
        statusMessage.classList.remove('hidden');
        statusContent.textContent = message;
        
        statusContent.className = 'p-4 rounded-xl text-sm font-medium';
        
        if (type === 'success') {
            statusContent.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
        } else if (type === 'error') {
            statusContent.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
        }
    }

    function hideStatus() {
        statusMessage.classList.add('hidden');
    }

    async function simulateNewsletterSubscription(data) {
        // Simular delay de rede
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Simular possível erro (5% de chance)
        if (Math.random() < 0.05) {
            throw new Error('Erro simulado');
        }
        
        // Aqui você integraria com seu sistema de newsletter
        // Exemplo: MailChimp, ConvertKit, etc.
        console.log('Newsletter subscription data:', data);
        
        return { success: true };
    }

    // Animações de entrada
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('[data-aos]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>

<style>
.newsletter-signup {
    position: relative;
}

.newsletter-form input:focus,
.newsletter-form select:focus {
    box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
}

.newsletter-form select[multiple] {
    min-height: 120px;
}

.newsletter-form select[multiple] option {
    padding: 8px 12px;
    margin: 2px 0;
    border-radius: 6px;
}

.newsletter-form select[multiple] option:checked {
    background: linear-gradient(135deg, #E11D48, #0EA5E9);
    color: white;
}

@media (max-width: 768px) {
    .newsletter-signup .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .newsletter-signup .grid {
        gap: 2rem;
    }
}
</style> 