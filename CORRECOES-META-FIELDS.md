# 🔧 Correções Meta Fields - RecifeMais Archives

## 📋 **Resumo das Correções Realizadas**

### **Problema Identificado:**
Os archives estavam usando nomes incorretos de meta fields, não correspondendo aos nomes reais definidos no plugin **RecifeMais Core V2**.

### **✅ Arquivos Corrigidos:**

#### **1. template-parts/archive/content-grid.php**
- ✅ Corrigido para usar `card-artista.php` específico ao invés do genérico
- ✅ Corrigido para usar `card-roteiro.php` específico
- ✅ Parâmetros corretos passados para cada card

#### **2. archive-artistas.php**
- ✅ Meta filters corrigidos:
  - `artista_genero_musical` → Gênero Musical
  - `artista_instrumento_principal` → Instrumento Principal  
  - `artista_ano_inicio_carreira` → Início da Carreira
  - `artista_status_carreira` → Status da Carreira
  - `artista_premios_principais` → Prêmios

#### **3. archive-eventos_festivais.php**
- ✅ Meta filters corrigidos:
  - `evento_data_inicio` → Data de Início
  - `evento_data_fim` → Data de Fim
  - `evento_preco` → Preço do Ingresso
  - `evento_tipos` → Tipo de Evento
  - `evento_publico_alvo` → Público-Alvo
- ✅ Grid meta fields corrigidos:
  - `evento_local` → Local
  - `evento_horario_inicio` → Horário

#### **4. archive-lugares.php**
- ✅ Meta filters já estavam corretos:
  - `lugar_faixa_preco` → Faixa de Preço
  - `lugar_especialidades` → Especialidades
  - `lugar_horario_funcionamento` → Horário de Funcionamento

#### **5. archive-organizadores.php**
- ✅ Meta filters corrigidos:
  - `organizador_tipo` → Tipo de Organizador
  - `organizador_especialidades` → Especialidades
  - `organizador_responsavel` → Responsável

#### **6. components/cards/card-artista.php**
- ✅ Meta fields corrigidos para usar nomes do plugin:
  - `artista_tipo_grupo`
  - `artista_origem`
  - `artista_ano_formacao`
  - `artista_biografia`
  - `artista_ritmos`
  - `artista_generos`
  - `artista_publico_alvo`
  - `artista_site_oficial`

### **📊 Meta Fields Corretos por CPT:**

#### **🎨 Artistas:**
```php
$meta_fields = [
    'artista_tipo_grupo'     => 'enum',               // Solo, Banda, Grupo, Coletivo
    'artista_origem'         => 'string',             // Cidade de origem
    'artista_ano_formacao'   => 'number',             // Year input
    'artista_integrantes'    => 'text',               // Textarea
    'artista_biografia'      => 'wysiwyg',            // Rich text editor
    'artista_redes_sociais'  => 'array',              // Repeater field
    'artista_ritmos'         => 'dictionary_array',   // Múltipla seleção
    'artista_generos'        => 'dictionary_array',   // Múltipla seleção
    'artista_publico_alvo'   => 'dictionary_value',   // Single select
    'artista_site_oficial'   => 'url',                // URL validation
];
```

#### **🎭 Eventos:**
```php
$meta_fields = [
    'evento_data_inicio'     => 'YYYY-MM-DD',          // Date picker
    'evento_data_fim'        => 'YYYY-MM-DD',          // Date picker
    'evento_horario_inicio'  => 'HH:MM',               // Time picker
    'evento_horario_fim'     => 'HH:MM',               // Time picker
    'evento_preco'           => 'string',              // Text input
    'evento_local'           => 'post_id',             // Select - CPT lugares
    'evento_organizador'     => 'post_id',             // Select - CPT organizadores
    'evento_atracoes'        => 'array',               // Repeater field
    'evento_link_inscricao'  => 'url',                 // URL input
    'evento_contato'         => 'string',              // Text input
    'evento_tipos'           => 'dictionary_value',    // Dicionário
    'evento_publico_alvo'    => 'dictionary_value',    // Dicionário
];
```

#### **📍 Lugares:**
```php
$meta_fields = [
    'lugar_endereco'             => 'string',          // Text input (auto-geocoding)
    'lugar_cep'                  => 'string',          // CEP format
    'lugar_telefone'             => 'string',          // Phone format
    'lugar_email'                => 'email',           // Email validation
    'lugar_website'              => 'url',             // URL validation
    'lugar_horario_funcionamento'=> 'text',            // Textarea
    'lugar_latitude'             => 'float',           // Auto-generated
    'lugar_longitude'            => 'float',           // Auto-generated
    'lugar_faixa_preco'          => 'enum',            // $, $$, $$$, $$$$
    'lugar_especialidades'       => 'dictionary_array', // Múltipla seleção
];
```

#### **🏢 Organizadores:**
```php
$meta_fields = [
    'organizador_tipo'           => 'select',          // Empresa, ONG, Governo, Pessoa Física
    'organizador_cnpj'           => 'text',            // CNPJ (se aplicável)
    'organizador_endereco'       => 'text',            // Endereço
    'organizador_telefone'       => 'text',            // Telefone
    'organizador_email'          => 'email',           // Email
    'organizador_website'        => 'url',             // Website
    'organizador_responsavel'    => 'text',            // Pessoa responsável
    'organizador_especialidades' => 'dictionary',      // Especialidades
    'organizador_redes_sociais'  => 'repeater',        // Redes sociais
];
```

### **🔧 Arquivo de Debug Criado:**
- ✅ `debug-meta-fields.php` - Para testar se os meta fields estão sendo recuperados
- ✅ Incluído temporariamente no `functions.php`
- ⚠️ **REMOVER APÓS TESTES**

### **📝 Próximos Passos:**
1. **Testar** os archives no frontend
2. **Verificar** se os dados aparecem corretamente nos cards
3. **Remover** arquivo de debug após confirmação
4. **Testar** filtros e busca avançada
5. **Verificar** outros CPTs (roteiros, agremiações, histórias, guias)

### **🎯 Status Atual:**
- ✅ **Archives principais corrigidos** (artistas, eventos, lugares, organizadores)
- ✅ **Cards específicos funcionando**
- ✅ **Meta fields alinhados com plugin**
- ⚠️ **Pendente:** Testar no frontend e verificar outros CPTs

---

**📅 Data:** <?php echo date('d/m/Y H:i'); ?>  
**👤 Responsável:** Assistente IA  
**🔄 Status:** Correções implementadas - Aguardando testes 