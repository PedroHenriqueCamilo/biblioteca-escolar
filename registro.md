# Registro de Desenvolvimento

Este arquivo documenta todas as alterações realizadas no projeto.

---

## 2026-06-26 – CRUD de Alunos

### Data

2026-06-26

### Arquivos Criados

* database/migrations/002_create_alunos_table.sql
* app/Controllers/AlunoController.php
* app/Models/Aluno.php
* app/Views/alunos/index.php
* app/Views/alunos/form.php

### Arquivos Modificados

* public/index.php

### Alterações

Implementado o CRUD completo de Alunos:

1. **Tabela de alunos** (`database/migrations/002_create_alunos_table.sql`):
   - Colunas: id, nome, matricula (UNIQUE), email (UNIQUE), telefone, turma, ativo, created_at, updated_at
   - Soft delete via coluna `ativo`

2. **Model Aluno** (`app/Models/Aluno.php`):
   - Propriedades públicas tipadas (id, nome, matricula, email, telefone, turma, ativo)
   - Métodos estáticos: all(), findById(), search(), delete()
   - Métodos de instância: insert(), update()
   - Todos usam prepared statements via PDO

3. **AlunoController** (`app/Controllers/AlunoController.php`):
   - index() - Lista alunos com busca por nome/matrícula
   - create() - Exibe formulário de criação
   - store() - Valida e insere novo aluno
   - edit() - Exibe formulário de edição
   - update() - Valida e atualiza aluno existente
   - destroy() - Remove aluno (soft delete)
   - validate() - Valida campos obrigatórios (nome, matricula, email)

4. **Views** (`app/Views/alunos/`):
   - index.php - Tabela com alunos, busca, botões de ação
   - form.php - Formulário reutilizável para criar/editar

5. **Rotas** (`public/index.php`):
   - Adicionadas rotas GET: /alunos, /alunos/create, /alunos/edit
   - Adicionadas rotas POST: /alunos/store, /alunos/update, /alunos/delete
   - Middleware de autenticação atualizado para proteger prefixo /alunos/*
   - require do AlunoController adicionado

### Motivo

O projeto possuía apenas Login e Dashboard funcionais. O próximo passo recomendado no era implementar o CRUD de Alunos mantendo a arquitetura MVC atual com PDO e sessões.

### Próximos Passos

- Implementar CRUD de Livros
- Implementar CRUD de Empréstimos
- Menu completo do dashboard

---

## 2026-06-26 – CRUD de Livros

### Data

2026-06-26

### Arquivos Criados

* database/migrations/003_create_livros_table.sql
* app/Controllers/LivroController.php
* app/Models/Livro.php
* app/Views/livros/index.php
* app/Views/livros/form.php

### Arquivos Modificados

* public/index.php

### Alterações

Implementado o CRUD completo de Livros:

1. **Tabela de livros** (`database/migrations/003_create_livros_table.sql`):
   - Colunas: id, titulo, autor, isbn (UNIQUE), editora, ano_publicacao, quantidade, disponivel, created_at, updated_at
   - Soft delete via coluna `disponivel`

2. **Model Livro** (`app/Models/Livro.php`):
   - Propriedades públicas tipadas (id, titulo, autor, isbn, editora, ano_publicacao, quantidade, disponivel)
   - Métodos estáticos: all(), findById(), search(), delete()
   - Métodos de instância: insert(), update()
   - Todos usam prepared statements via PDO

3. **LivroController** (`app/Controllers/LivroController.php`):
   - index() - Lista livros com busca por título/autor/ISBN
   - create() - Exibe formulário de criação
   - store() - Valida e insere novo livro
   - edit() - Exibe formulário de edição
   - update() - Valida e atualiza livro existente
   - destroy() - Remove livro (soft delete)
   - validate() - Valida campos obrigatórios (titulo, autor, isbn, quantidade)

4. **Views** (`app/Views/livros/`):
   - index.php - Tabela com livros, busca, botões de ação
   - form.php - Formulário reutilizável para criar/editar

5. **Rotas** (`public/index.php`):
   - Adicionadas rotas GET: /livros, /livros/create, /livros/edit
   - Adicionadas rotas POST: /livros/store, /livros/update, /livros/delete
   - Middleware de autenticação atualizado para proteger prefixo /livros/*
   - require do LivroController e Model Livro adicionados

### Motivo

Sequência natural do desenvolvimento. Após CRUD de Alunos, o próximo módulo é o de Livros mantendo a mesma arquitetura e padrões.

### Próximos Passos

- Implementar CRUD de Empréstimos
- Menu completo do dashboard

---

## 2026-06-26 – CRUD de Empréstimos

### Data

2026-06-26

### Arquivos Criados

* database/migrations/004_create_emprestimos_table.sql
* app/Models/Emprestimo.php
* app/Controllers/EmprestimoController.php
* app/Views/emprestimos/index.php
* app/Views/emprestimos/form.php

### Arquivos Modificados

* public/index.php

### Alterações

Implementado o CRUD de Empréstimos:

1. **Tabela de empréstimos** (`database/migrations/004_create_emprestimos_table.sql`):
   - Colunas: id, aluno_id (FK), livro_id (FK), data_emprestimo, data_devolucao_prevista, data_devolucao_real, status (ativo/devolvido/atrasado)
   - Foreign keys para `alunos(id)` e `livros(id)` com RESTRICT
   - Índices para aluno_id, livro_id e status

2. **Model Emprestimo** (`app/Models/Emprestimo.php`):
   - Propriedades: id, aluno_id, livro_id, data_emprestimo, data_devolucao_prevista, data_devolucao_real, status
   - Métodos: all() com JOIN, ativos(), findById() com JOIN, insert(), devolver(), marcarAtrasado()

3. **EmprestimoController** (`app/Controllers/EmprestimoController.php`):
   - index() - Lista empréstimos com filtro (todos/ativos)
   - create() - Formulário com select de alunos e livros
   - store() - Valida e registra novo empréstimo
   - devolver() - Registra devolução com data atual
   - validate() - Valida aluno_id, livro_id, data_devolucao_prevista

4. **Views** (`app/Views/emprestimos/`):
   - index.php - Tabela com empréstimos, filtro, botão devolver
   - form.php - Formulário com selects de aluno e livro

5. **Rotas** (`public/index.php`):
   - Adicionadas rotas GET: /emprestimos, /emprestimos/create
   - Adicionadas rotas POST: /emprestimos/store, /emprestimos/devolver
   - Middleware de autenticação atualizado para proteger prefixo /emprestimos/*
   - require do EmprestimoController e Model Emprestimo adicionados

### ⚠️ AÇÃO NECESSÁRIA DO USUÁRIO — Atualizar o banco de dados

Os arquivos de migration foram criados mas **NÃO foram executados automaticamente**.
Para que o sistema funcione, execute as seguintes migrations no MySQL:

```sql
-- 1. Criar tabela de alunos (se ainda não existir)
SOURCE c:\Users\SEEMG\Documents\projeto bibilhoteca\database\migrations\002_create_alunos_table.sql;

-- 2. Criar tabela de livros (se ainda não existir)
SOURCE c:\Users\SEEMG\Documents\projeto bibilhoteca\database\migrations\003_create_livros_table.sql;

-- 3. Criar tabela de empréstimos
SOURCE c:\Users\SEEMG\Documents\projeto bibilhoteca\database\migrations\004_create_emprestimos_table.sql;
```

Ou via linha de comando:
```cmd
"C:\xampp\mysql\bin\mysql.exe" -u root biblioteca < "c:\Users\SEEMG\Documents\projeto bibilhoteca\database\migrations\004_create_emprestimos_table.sql"
```

Sem essa execução, as páginas de empréstimos e alunos apresentarão erro de tabela não encontrada.

### Motivo

Sequência natural do desenvolvimento. Após CRUD de Alunos e Livros, o próximo módulo é o de Empréstimos.

### Próximos Passos

- Atualizar menu do dashboard
- Implementar sistema de multas
- Implementar relatórios

---

