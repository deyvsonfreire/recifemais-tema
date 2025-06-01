<?php
/**
 * Template Name: Demo - Dropdowns Geográficos
 * 
 * Página de demonstração dos dropdowns inteligentes
 * 
 * @package RecifeMais
 * @since 2.0.0
 */

get_header(); ?>

<div class="recifemais-container recifemais-section">
    
    <header class="demo-header" style="text-align: center; margin-bottom: 3rem;">
        <h1 style="color: #e11d48; margin-bottom: 1rem;">
            🌍 Demo - Dropdowns Geográficos Inteligentes
        </h1>
        <p style="font-size: 1.125rem; color: #6b7280; max-width: 600px; margin: 0 auto;">
            Demonstração do sistema de dropdowns cascata <strong>Cidade → Bairro</strong> 
            usando dados reais dos CSVs do RecifeMais.
        </p>
    </header>

    <div class="demo-content">
        
        <!-- Exemplo 1: Formulário de Busca de Eventos -->
        <section class="demo-section" style="margin-bottom: 3rem;">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                📅 Exemplo 1: Busca de Eventos
            </h2>
            <p style="margin-bottom: 1.5rem; color: #6b7280;">
                Formulário completo com dropdowns geográficos para buscar eventos por localização.
            </p>
            
            <?php echo do_shortcode('[recifemais_busca_eventos]'); ?>
        </section>

        <!-- Exemplo 2: Dropdown Simples -->
        <section class="demo-section" style="margin-bottom: 3rem;">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                🎯 Exemplo 2: Dropdown Simples
            </h2>
            <p style="margin-bottom: 1.5rem; color: #6b7280;">
                Dropdown básico usando shortcode com parâmetros personalizados.
            </p>
            
            <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                <?php echo do_shortcode('[recifemais_dropdown_geografico cidade_label="Escolha sua cidade" bairro_label="Escolha seu bairro" required="true"]'); ?>
            </div>
        </section>

        <!-- Exemplo 3: Uso Programático -->
        <section class="demo-section" style="margin-bottom: 3rem;">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                ⚙️ Exemplo 3: Uso Programático
            </h2>
            <p style="margin-bottom: 1.5rem; color: #6b7280;">
                Dropdown criado diretamente via função PHP com configurações avançadas.
            </p>
            
            <div style="background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                <?php 
                recifemais_render_dropdown_geografico([
                    'container_class' => 'demo-dropdown-avancado',
                    'cidade_name' => 'local_cidade',
                    'bairro_name' => 'local_bairro',
                    'cidade_id' => 'local_cidade',
                    'bairro_id' => 'local_bairro',
                    'cidade_label' => 'Cidade do Local',
                    'bairro_label' => 'Bairro do Local',
                    'cidade_value' => 'recife', // Pré-selecionado
                    'required' => true,
                    'show_labels' => true
                ]); 
                ?>
            </div>
        </section>

        <!-- Exemplo 4: Múltiplos Dropdowns -->
        <section class="demo-section" style="margin-bottom: 3rem;">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                🔄 Exemplo 4: Múltiplos Dropdowns Independentes
            </h2>
            <p style="margin-bottom: 1.5rem; color: #6b7280;">
                Vários dropdowns na mesma página funcionando independentemente.
            </p>
            
            <div class="recifemais-grid recifemais-grid--2" style="gap: 2rem;">
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                    <h4 style="margin-bottom: 1rem; color: #e11d48;">Local de Origem</h4>
                    <?php 
                    recifemais_render_dropdown_geografico([
                        'cidade_name' => 'origem_cidade',
                        'bairro_name' => 'origem_bairro',
                        'cidade_id' => 'origem_cidade',
                        'bairro_id' => 'origem_bairro',
                        'cidade_label' => 'Cidade de Origem',
                        'bairro_label' => 'Bairro de Origem'
                    ]); 
                    ?>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                    <h4 style="margin-bottom: 1rem; color: #e11d48;">Local de Destino</h4>
                    <?php 
                    recifemais_render_dropdown_geografico([
                        'cidade_name' => 'destino_cidade',
                        'bairro_name' => 'destino_bairro',
                        'cidade_id' => 'destino_cidade',
                        'bairro_id' => 'destino_bairro',
                        'cidade_label' => 'Cidade de Destino',
                        'bairro_label' => 'Bairro de Destino'
                    ]); 
                    ?>
                </div>
                
            </div>
        </section>

        <!-- Informações Técnicas -->
        <section class="demo-section" style="margin-bottom: 3rem;">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                🔧 Informações Técnicas
            </h2>
            
            <div class="recifemais-grid recifemais-grid--2" style="gap: 2rem;">
                
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #e11d48;">
                    <h4 style="margin-bottom: 1rem; color: #e11d48;">Funcionalidades</h4>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #374151;">
                        <li>✅ Carregamento dinâmico via AJAX</li>
                        <li>✅ Cache inteligente (24h)</li>
                        <li>✅ Fallback para dados estáticos</li>
                        <li>✅ Múltiplos dropdowns independentes</li>
                        <li>✅ Eventos JavaScript customizados</li>
                        <li>✅ Acessibilidade completa</li>
                        <li>✅ Responsivo e mobile-friendly</li>
                    </ul>
                </div>
                
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #0ea5e9;">
                    <h4 style="margin-bottom: 1rem; color: #0ea5e9;">Dados Disponíveis</h4>
                    <ul style="margin: 0; padding-left: 1.5rem; color: #374151;">
                        <li><strong>Recife:</strong> 78 bairros</li>
                        <li><strong>Olinda:</strong> 36 bairros</li>
                        <li><strong>Jaboatão:</strong> 55 bairros</li>
                        <li><strong>Total:</strong> 169 bairros</li>
                        <li><strong>Fonte:</strong> CSVs oficiais</li>
                        <li><strong>Atualização:</strong> Automática</li>
                    </ul>
                </div>
                
            </div>
        </section>

        <!-- Como Usar -->
        <section class="demo-section">
            <h2 style="color: #0ea5e9; margin-bottom: 1rem;">
                📖 Como Usar
            </h2>
            
            <div style="background: #1f2937; color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <h4 style="color: #60a5fa; margin-bottom: 1rem;">1. Via Shortcode:</h4>
                <code style="color: #fbbf24;">[recifemais_dropdown_geografico cidade_label="Cidade" bairro_label="Bairro" required="true"]</code>
            </div>
            
            <div style="background: #1f2937; color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <h4 style="color: #60a5fa; margin-bottom: 1rem;">2. Via Função PHP:</h4>
                <code style="color: #fbbf24;">
                    &lt;?php<br>
                    recifemais_render_dropdown_geografico([<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;'cidade_label' => 'Sua Cidade',<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;'bairro_label' => 'Seu Bairro',<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;'required' => true<br>
                    ]);<br>
                    ?&gt;
                </code>
            </div>
            
            <div style="background: #1f2937; color: #f9fafb; padding: 1.5rem; border-radius: 0.5rem;">
                <h4 style="color: #60a5fa; margin-bottom: 1rem;">3. Via Widget:</h4>
                <p style="color: #d1d5db; margin: 0;">
                    Disponível em <strong>Aparência → Widgets</strong> como "RecifeMais - Dropdown Geográfico"
                </p>
            </div>
        </section>

    </div>
    
</div>

<script>
// Demonstração de eventos JavaScript personalizados
document.addEventListener('DOMContentLoaded', function() {
    // Escutar mudanças de cidade
    document.addEventListener('cidadeAlterada', function(e) {
        console.log('🏙️ Cidade alterada:', e.detail.cidade);
    });
    
    // Escutar atualizações de bairros
    document.addEventListener('bairrosAtualizados', function(e) {
        console.log('🏘️ Bairros atualizados para', e.detail.cidade + ':', e.detail.bairros.length + ' bairros');
    });
    
    // Adicionar indicador visual
    const selects = document.querySelectorAll('[data-dropdown="cidade"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = '#10b981';
            } else {
                this.style.borderColor = '#d1d5db';
            }
        });
    });
});
</script>

<style>
.demo-section {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 2rem;
}

.demo-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

code {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 1rem;
}

.recifemais-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.recifemais-select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background-color: white;
    transition: border-color 0.2s ease-in-out;
}

.recifemais-select:focus {
    outline: none;
    border-color: #e11d48;
    box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
}

.recifemais-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    background-color: #e11d48;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.recifemais-btn:hover {
    background-color: #be123c;
}

.opacity-50 {
    opacity: 0.5;
}
</style>

<?php get_footer(); ?> 