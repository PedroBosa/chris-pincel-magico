# Estrutura do Banco de Dados - Chris Pincel Mágico

php artisan serve --host=127.0.0.1 --port=8000

## 📊 Informações Gerais
- **Database:** chris_pincel
- **Total de Tabelas:** 22
- **Tamanho Total:** 624 KB
- **Status:** ✅ Todas migrations executadas com sucesso

---

## 🗄️ Tabelas do Sistema

### 1. **users** (Sistema Laravel + Customizações)
```
- id
- name
- email
- email_verified_at
- password
- remember_token
- is_admin (BOOLEAN) - Flag de administrador
- timestamps
```
**Propósito:** Gerenciamento de usuários (clientes e administradores)

---

### 2. **servicos**
```
- id
- nome
- slug (UNIQUE)
- descricao (TEXT)
- duracao (INTEGER - minutos)
- preco (DECIMAL 10,2)
- ativo (BOOLEAN)
- imagem (nullable)
- timestamps
```
**Propósito:** Catálogo de serviços oferecidos (maquiagem, penteados, etc.)

---

### 3. **agendamentos**
```
- id
- user_id (FK → users)
- servico_id (FK → servicos)
- agendamento_original_id (FK → agendamentos, nullable)
- data_hora_inicio (DATETIME)
- data_hora_fim (DATETIME)
- status (STRING 30 - 'PENDENTE', 'CONFIRMADO', 'CONCLUIDO', 'CANCELADO')
- tipo (STRING 20 - 'NORMAL', default)
- valor_total (DECIMAL 10,2)
- valor_sinal (DECIMAL 10,2, default 0)
- taxa_cancelamento (DECIMAL 10,2, default 0)
- pagamento_confirmado (BOOLEAN, default false)
- lembrete_enviado (BOOLEAN, default false)
- canal_origem (STRING 30, default 'SITE')
- metadados (JSON, nullable)
- observacoes (TEXT, nullable)
- timestamps
- INDEX: (data_hora_inicio, servico_id)
- INDEX: (status, data_hora_inicio)
```
**Propósito:** Agendamentos dos clientes com os serviços

---

### 4. **pagamentos**
```
- id
- agendamento_id (FK → agendamentos)
- metodo_pagamento (STRING)
- status_pagamento (ENUM: 'PENDENTE', 'PAGO', 'CANCELADO')
- valor (DECIMAL 10,2)
- data_pagamento (nullable)
- comprovante (nullable)
- timestamps
```
**Propósito:** Controle financeiro dos pagamentos

---

### 5. **disponibilidades**
```
- id
- dia_semana (TINYINT - 0=Domingo, 6=Sábado)
- hora_inicio (TIME)
- hora_fim (TIME)
- ativo (BOOLEAN)
- timestamps
- UNIQUE: (dia_semana, hora_inicio, hora_fim)
```
**Propósito:** Define horários de funcionamento semanais

---

### 6. **bloqueios_horario**
```
- id
- data_inicio
- data_fim
- hora_inicio (TIME)
- hora_fim (TIME)
- motivo (TEXT, nullable)
- timestamps
```
**Propósito:** Bloquear horários específicos (férias, feriados, compromissos pessoais)

---

### 7. **promocoes**
```
- id
- titulo
- slug (UNIQUE)
- descricao (TEXT, nullable)
- tipo (ENUM: 'VALOR', 'PERCENTUAL')
- valor_desconto (DECIMAL 10,2, nullable)
- percentual_desconto (TINYINT, nullable)
- codigo_cupom (STRING, nullable, UNIQUE)
- inicio_vigencia (DATETIME, nullable)
- fim_vigencia (DATETIME, nullable)
- limite_uso (INTEGER, nullable)
- usos_realizados (INTEGER, default 0)
- ativo (BOOLEAN)
- restricoes (JSON, nullable)
- timestamps
```
**Propósito:** Sistema de promoções e cupons de desconto

---

### 8. **pontos_fidelidade**
```
- id
- user_id (FK → users)
- total_pontos (INTEGER, default 0)
- pontos_disponiveis (INTEGER, default 0)
- nivel_fidelidade (STRING, nullable)
- timestamps
```
**Propósito:** Programa de fidelidade dos clientes

---

### 9. **transacoes_pontos**
```
- id
- pontos_fidelidade_id (FK → pontos_fidelidade)
- tipo (ENUM: 'CREDITO', 'DEBITO')
- pontos (INTEGER)
- descricao (TEXT, nullable)
- agendamento_id (FK → agendamentos, nullable)
- timestamps
```
**Propósito:** Histórico de ganho/uso de pontos

---

### 10. **notificacoes**
```
- id
- user_id (FK → users)
- tipo (STRING)
- titulo
- mensagem (TEXT)
- lida (BOOLEAN, default false)
- data_leitura (TIMESTAMP, nullable)
- timestamps
```
**Propósito:** Sistema de notificações para usuários

---

### 11. **avaliacoes**
```
- id
- user_id (FK → users)
- agendamento_id (FK → agendamentos)
- nota (TINYINT - 1 a 5)
- comentario (TEXT, nullable)
- resposta (TEXT, nullable)
- data_resposta (TIMESTAMP, nullable)
- timestamps
```
**Propósito:** Avaliações dos serviços pelos clientes

---

### 12. **fotos_clientes**
```
- id
- user_id (FK → users)
- agendamento_id (FK → agendamentos, nullable)
- caminho_foto
- descricao (TEXT, nullable)
- visivel_galeria (BOOLEAN, default false)
- timestamps
```
**Propósito:** Galeria de fotos dos trabalhos realizados

---

### 13. **lista_espera**
```
- id
- user_id (FK → users)
- servico_id (FK → servicos)
- data_desejada
- horario_preferencia (STRING, nullable)
- notificado (BOOLEAN, default false)
- status (ENUM: 'AGUARDANDO', 'NOTIFICADO', 'AGENDADO', 'CANCELADO')
- timestamps
```
**Propósito:** Lista de espera para horários indisponíveis

---

### 14. **configuracoes**
```
- id
- chave (STRING, UNIQUE)
- valor (TEXT, nullable)
- tipo (STRING) - text, number, boolean, json, etc.
- descricao (TEXT, nullable)
- timestamps
```
**Propósito:** Configurações gerais do sistema (horários, contatos, etc.)

---

## 🔧 Tabelas do Sistema Laravel

### 15. **cache**
- Sistema de cache do Laravel

### 16. **cache_locks**
- Controle de locks do cache

### 17. **jobs**
- Fila de trabalhos (jobs) do Laravel

### 18. **failed_jobs**
- Registro de jobs que falharam

### 19. **password_reset_tokens**
- Tokens para reset de senha

### 20. **sessions**
- Gerenciamento de sessões

### 21. **migrations**
- Controle de migrations executadas

---

## 🔗 Relacionamentos Principais

```
users (1) ──→ (N) agendamentos
users (1) ──→ (1) pontos_fidelidade
users (1) ──→ (N) avaliacoes
users (1) ──→ (N) fotos_clientes
users (1) ──→ (N) notificacoes
users (1) ──→ (N) lista_espera

servicos (1) ──→ (N) agendamentos
servicos (1) ──→ (N) lista_espera

agendamentos (1) ──→ (1) pagamentos
agendamentos (1) ──→ (1) avaliacoes
agendamentos (1) ──→ (N) fotos_clientes
agendamentos (1) ──→ (N) transacoes_pontos

pontos_fidelidade (1) ──→ (N) transacoes_pontos
```

---

## ✅ Status das Rotas Admin

### Rotas Implementadas (com páginas redesignadas):
- ✅ `/admin/dashboard` → DashboardController
- ✅ `/admin/agenda` → AgendaController@index (timeline do dia com filtros)
- ✅ `/admin/agendamentos` → AdminAgendamentoController@index (tabela + filtros)
- ✅ `/admin/servicos` → AdminServicoController@index (grid de cards)
- ✅ `/admin/clientes` → ClienteController@index (tabela + busca)

### Rotas Existentes (páginas pendentes):
- ⚠️ `/admin/agendamentos/{id}` → AdminAgendamentoController@show (detalhes)
- ⚠️ `/admin/clientes/{id}` → ClienteController@show (perfil completo)
- ⚠️ `/admin/servicos/create` → AdminServicoController@create
- ⚠️ `/admin/servicos/{id}/edit` → AdminServicoController@edit
- ⚠️ `/admin/promocoes` → AdminPromocaoController (CRUD completo)
- ⚠️ `/admin/financeiro` → FinanceiroController (relatórios financeiros)
- ⚠️ `/admin/relatorios/faturamento` → RelatorioController
- ⚠️ `/admin/relatorios/agendamentos` → RelatorioController
- ⚠️ `/admin/configuracoes` → ConfiguracaoController

### Tabelas SEM Interface Admin:
- ❌ **disponibilidades** - Importante para gestão de horários
- ❌ **bloqueios_horario** - Importante para férias/feriados
- ❌ **pontos_fidelidade** - Ver saldo dos clientes
- ❌ **transacoes_pontos** - Histórico de pontos
- ❌ **avaliacoes** - Moderar avaliações
- ❌ **fotos_clientes** - Gerenciar galeria
- ❌ **lista_espera** - Gerenciar lista de espera
- ❌ **notificacoes** - Histórico de notificações enviadas

---

## 🎯 Recomendações

### Prioridade Alta:
1. **Disponibilidades** - Criar CRUD para gerenciar horários de funcionamento
2. **Bloqueios de Horário** - Permitir bloquear dias/horários específicos
3. **Avaliacoes** - Interface para visualizar e responder avaliações

### Prioridade Média:
4. **Fotos Clientes** - Galeria admin para aprovar/reprovar fotos
5. **Lista Espera** - Notificar clientes quando vaga abrir
6. **Promocoes** - Completar CRUD (create/edit pendentes)

### Prioridade Baixa:
7. **Pontos Fidelidade** - Dashboard de pontos por cliente
8. **Notificacoes** - Log de notificações enviadas

---

## 🔍 Migrations Status

✅ **Todas as 17 migrations executadas com sucesso** (Batch 1 e 2)

**Duplicatas Removidas:**
- ❌ `2025_10_15_210100_create_disponibilidades_table.php` (mantida 000130 com unique constraint)
- ❌ `2025_10_15_000150_create_promocoes_table.php` (mantida 210000 com enum)

**Última Atualização:** $(date +"%Y-%m-%d %H:%M")
