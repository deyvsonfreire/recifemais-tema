# 🎯 PLANO DE ENTREGA FINAL - RECIFE MAIS

**DEADLINE:** Entrega focada no essencial
**OBJETIVO:** Portal no nível Globo.com baseado no Planejamento Consolidado

---

## 📋 **ANÁLISE BASEADA NO PLANEJAMENTO CONSOLIDADO**

### **🔍 Pilares Confirmados:**
1. **📰 Notícias** (Azul #3B82F6) - Portal jornalístico
2. **🔍 Descubra** (Vermelho #E11D48) - Lugares + Eventos
3. **🗺️ Roteiros** (Verde #10B981) - Guias práticos  
4. **📅 Agenda** (Laranja #FF6B35) - Eventos em destaque
5. **📖 Histórias** (Roxo #8B5CF6) - Conteúdo aprofundado
6. **👥 Comunidade** (Amarelo #F59E0B) - Interação

---

## 🔥 **CRITICAL PATH - ARQUIVOS ESSENCIAIS**

### **✅ MANTER - CPTs Fundamentais:**
```
🎯 CORE DO PORTAL:
├── archive-eventos_festivais.php ✅ (Agenda + Descubra)
├── archive-lugares.php ✅ (Descubra principal)  
├── archive-roteiros.php ✅ (Pilar completo)
├── single.php ✅ (Universal inteligente)
├── category.php ✅ (Notícias + Histórias)
├── front-page.php ✅ (Homepage Portal)
└── archive.php ✅ (Fallback)
```

### **🤔 AVALIAR - CPTs Secundários:**
```
📝 PODEM SER POSTS NORMAIS:
├── archive-historias.php → category/historias/
├── archive-guias_tematicos.php → category/guias/
├── single-historias.php → single.php universal
└── single-guias_tematicos.php → single.php universal

🔗 RELACIONAMENTOS APENAS:
├── archive-organizadores.php (simplificar)
├── archive-agremiacoes.php (simplificar)
├── single-organizadores.php → single.php
└── single-agremiacoes.php → single.php
```

### **❌ DELETAR - Overengineered:**
```
🗑️ SINGLES COMPLEXOS DESNECESSÁRIOS:
├── single-eventos_festivais.php (35KB) ❌
├── single-lugares.php (42KB) ❌  
├── single-artistas.php (38KB) ❌
├── single-roteiros.php (41KB) ❌
└── Outros singles grandes ❌
```

---

## 🎯 **ESTRATÉGIA GLOBO.COM BASEADA NO PLANEJAMENTO**

### **Homepage Strategy (Confirmada):**
```php
// front-page.php otimizado
📰 Breaking News (3 destaques) 
📅 Eventos Hoje (API agenda)
🔍 Descubra Recife (cards rotativos)
📖 História da Semana (evergreen)
🗺️ Mapa Interativo (eventos + lugares)
🎭 Agenda Cultural (próximos 7 dias)
```

### **Cross-Content Navigation (Essencial):**
```php
// single.php inteligente com relacionamentos
🔗 Related por geolocalização (eventos → lugares)
🔗 Related por manifestação cultural (frevo → eventos+artistas+lugares)  
🔗 Related temporal (eventos durante roteiro)
🔗 Related por personagem (artista → eventos+lugares+histórias)
```

### **Sistema de Mapas (Diferencial):**
```php
// Mapas como core feature
🗺️ Mapa da matéria (cada notícia com pontos)
🗺️ Roteiros visuais (CPT roteiros em mapas)
🗺️ Eventos por localização (clustering por bairro)
🗺️ Lugares com coordenadas (Google Maps)
```

---

## 📊 **ARQUIVOS POR PRIORIDADE**

### **🔴 CRÍTICO - 48h:**
```
✅ PRIORITY 1:
├── single.php (universal) ✅ FEITO
├── front-page.php (homepage portal)
├── archive-eventos_festivais.php ✅ (agenda core)
├── archive-lugares.php ✅ (descubra core)
└── category.php (notícias + histórias)
```

### **🟡 IMPORTANTE - 24h:**
```
✅ PRIORITY 2:
├── archive-roteiros.php ✅ (pilar roteiros)
├── archive-artistas.php (cross-content)
├── Template parts meta-fields específicos
└── Integration com plugin v2
```

### **🟢 SECUNDÁRIO - Se sobrar tempo:**
```
⚪ PRIORITY 3:
├── archive-organizadores.php (simplificar)
├── archive-agremiacoes.php (simplificar)
├── Template parts específicos
└── Polish e otimizações
```

---

## 🗑️ **LIMPEZA IMEDIATA**

### **❌ DELETAR AGORA:**
```
🗑️ ARQUIVOS REDUNDANTES:
├── front-page-novo.php ✅ DELETADO
├── front-page-original.php ✅ DELETADO
├── front-page-tema.php ✅ DELETADO
├── test-homepage.php ✅ DELETADO
├── home.php.backup ✅ DELETADO

🗑️ SINGLES OVERENGINEERED:
├── single-eventos_festivais.php ✅ DELETADO
├── single-lugares.php ✅ DELETADO
├── single-artistas.php ✅ DELETADO
├── single-roteiros.php (AVALIAR - tem mapas específicos?)
├── single-organizadores.php (AVALIAR - relacionamentos?)
├── single-agremiacoes.php (AVALIAR - carnaval content?)
├── single-historias.php (PODE VIRAR POST NORMAL)
└── single-guias_tematicos.php (PODE VIRAR POST NORMAL)
```

---

## ⚡ **PLANO DE EXECUÇÃO REFINADO**

### **DIA 1 - CORE PORTAL (8h)**
```
🔴 09:00-12:00 - HOMEPAGE PORTAL
├── Simplificar front-page.php (Breaking + Featured + Pilares)
├── Otimizar template-parts/homepage/ existentes  
├── Integrar agenda cultural automática
└── Cards responsivos por pilar

🔴 14:00-17:00 - SINGLE UNIVERSAL  
├── Refinar single.php para todos os CPTs ✅ FEITO
├── Criar meta-eventos_festivais.php (datas, local, preço)
├── Criar meta-lugares.php (endereço, coordenadas, horário)
└── Criar meta-roteiros.php (duração, dificuldade, pontos)

🔴 19:00-21:00 - ARCHIVES ESSENCIAIS
├── Testar archive-eventos_festivais.php
├── Testar archive-lugares.php  
└── Otimizar category.php para notícias
```

### **DIA 2 - INTEGRAÇÃO E MAPAS (8h)**
```
🟡 09:00-12:00 - PLUGIN INTEGRATION
├── Verificar meta fields do plugin v2
├── Ajustar functions.php para compatibilidade
├── Testar formulários admin
└── Cross-content relationships

🟡 14:00-17:00 - SISTEMA DE MAPAS
├── Mapas em roteiros (rotas)
├── Mapas em lugares (single point)
├── Mapas em eventos (localização)
└── Clustering por bairro

🟡 19:00-21:00 - POLISH VISUAL
├── CSS responsivo final
├── Cores por pilar do planejamento
├── Typography globo-style
└── Loading states
```

### **DIA 3 - ENTREGA (6h)**
```
🟢 09:00-12:00 - TESTES E OTIMIZAÇÃO
├── Performance (Lighthouse >90)
├── Mobile responsivo
├── Cross-browser testing
└── Content testing

🟢 14:00-17:00 - DEPLOY E DOCS
├── Deploy staging  
├── Documentação final
├── Guia de uso para editores
└── Métricas de entrega
```

---

## 🎯 **DECISÃO FINAL - SINGLES**

### **🤔 AVALIAR ANTES DE DELETAR:**

#### **single-roteiros.php (41KB):**
```
❓ VERIFICAR:
- Tem sistema de mapas específico para rotas?
- Mostra pontos de interesse em sequência?
- Interface diferente do single.php universal?
```

#### **single-organizadores.php (40KB):**
```
❓ VERIFICAR:  
- Usado só para relacionamentos?
- Conteúdo específico importante?
- Pode ser simplificado no single.php universal?
```

#### **single-agremiacoes.php (36KB):**
```
❓ VERIFICAR:
- Conteúdo de carnaval específico?
- Relacionado ao pilar Histórias/Tradições?
- Essencial para identidade cultural?
```

---

**🚀 FOCO: PORTAL GLOBO.COM COM IDENTIDADE PERNAMBUCANA**

**📋 Baseado no Planejamento Consolidado oficial** 