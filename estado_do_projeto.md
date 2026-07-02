# HANDOFF TÉCNICO – Projeto Biblioteca Escolar

**Data:** 26/06/2026
# IMPORTANTE

Este documento representa o estado atual confirmado do projeto.

Antes de qualquer implementação, a IA deve:

1. Ler este arquivo completamente.
2. Ler o arquivo registro.md.
3. Nunca afirmar que modificou arquivos sem realmente gravá-los.
4. Atualizar registro.md após cada tarefa concluída.
5. Mostrar o conteúdo dos arquivos modificados quando solicitado.

As informações deste documento têm prioridade sobre suposições da IA.

## Objetivo do projeto

Desenvolver um sistema de gerenciamento de biblioteca escolar em PHP utilizando arquitetura MVC, PDO e MySQL.

---

# Estrutura atual do projeto

```
projeto bibilhoteca/
├── app/
│   ├── Controllers/
│   ├── Database/
│   ├── Models/
│   ├── Views/
│   ├── Middleware/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   ├── seeds/
│   └── schema.sql
├── public/
│   └── index.php
├── storage/
├── README.md
└── registro.md
```

---

# Funcionalidades realmente implementadas e testadas

## Banco de dados

Banco utilizado:

```
biblioteca
```

Configuração:

```
Host: localhost
Usuário: root
Senha:
Charset: utf8mb4
```

A conexão PDO está funcionando.

Arquivo:

```
app/Database/Connection.php
```

---

## Login

O sistema possui autenticação funcional.

Foi testado com:

Email:

```
admin@biblioteca.local
```

Senha:

```
admin123
```

O login foi executado com sucesso.

---

## Sessão

O sistema utiliza:

```
session_start()
```

Após login:

```
session_regenerate_id(true)
```

A sessão permanece ativa.

---

## Front Controller

Existe:

```
public/index.php
```

Com rotas simples.

Rotas existentes:

```
GET /
GET /login
POST /login
GET /logout
GET /dashboard
GET /alunos
GET /alunos/create
GET /alunos/edit
POST /alunos/store
POST /alunos/update
POST /alunos/delete
GET /livros
GET /livros/create
GET /livros/edit
POST /livros/store
POST /livros/update
POST /livros/delete
GET /emprestimos
GET /emprestimos/create
POST /emprestimos/store
POST /emprestimos/devolver
```

---

## Dashboard

Após autenticação o usuário é redirecionado para:

```
/dashboard
```

O dashboard funciona.

Atualmente exibe apenas a opção:

```
Sair
```

Ainda não possui menu completo.

---

## Model Admin

Implementado:

```
findByEmail()
```

Implementado:

```
verifyPassword()
```

Utiliza:

```
password_verify()
```

---

## AuthController

Implementado:

* showLogin()
* login()
* logout()

Funcionando.

---

## DashboardController

Implementado.

Funcionando.

---

## Model Aluno

Implementado:

```
all()
findById()
search()
insert()
update()
delete()
```

Utiliza prepared statements via PDO.

Arquivo:

```
app/Models/Aluno.php
```

---

## AlunoController

Implementado:

* index() - Listagem com busca
* create() - Formulário de criação
* store() - Inserção com validação
* edit() - Formulário de edição
* update() - Atualização com validação
* destroy() - Remoção (soft delete)

Arquivo:

```
app/Controllers/AlunoController.php
```

---

## Aluno Views

Implementado:

* index.php - Tabela com alunos e busca
* form.php - Formulário reutilizar criar/editar

Diretório:

```
app/Views/alunos/
```

---

## Model Livro

Implementado:

```
all()
findById()
search()
insert()
update()
delete()
```

Utiliza prepared statements via PDO.

Arquivo:

```
app/Models/Livro.php
```

---

## LivroController

Implementado:

* index() - Listagem com busca
* create() - Formulário de criação
* store() - Inserção com validação
* edit() - Formulário de edição
* update() - Atualização com validação
* destroy() - Remoção (soft delete)

Arquivo:

```
app/Controllers/LivroController.php
```

---

## Livro Views

Implementado:

* index.php - Tabela com livros e busca
* form.php - Formulário reutilizar criar/editar

Diretório:

```
app/Views/livros/
```

---

## Model Emprestimo

Implementado:

```
all()
ativos()
findById()
insert()
devolver()
marcarAtrasado()
```

Utiliza prepared statements via PDO.

Arquivo:

```
app/Models/Emprestimo.php
```

---

## EmprestimoController

Implementado:

* index() - Listagem com filtro (todos/ativos)
* create() - Formulário com select de alunos e livros
* store() - Valida e registra novo empréstimo
* devolver() - Registra devolução
* validate() - Valida campos obrigatórios

Arquivo:

```
app/Controllers/EmprestimoController.php
```

---

## Emprestimo Views

Implementado:

* index.php - Tabela com empréstimos, filtro, botão devolver
* form.php - Formulário com selects de aluno e livro

Diretório:

```
app/Views/emprestimos/
```

---

# Problemas encontrados

## 1.

O projeto tentava carregar:

```
vendor/autoload.php
```

O projeto não utiliza Composer.

Foi corrigido removendo esse require.

---

## 2.

O seed possuía hash incorreto.

Foi gerado um novo hash para:

```
admin123
```

Esse hash foi inserido manualmente no banco.

---

## 3.

A tabela admins estava vazia.

Foi executado o INSERT manualmente.

Após isso o login passou a funcionar.

---

## 4.

password_verify retornava false.

Descobriu-se que o hash salvo anteriormente não correspondia à senha.

Foi gerado um novo hash utilizando:

```
password_hash()
```

Após atualizar o banco:

```
password_verify()
```

passou a retornar true.

---

## 5.

O PHP não era encontrado no PATH.

Foi utilizado:

```
C:\xampp\php\php.exe
```

Servidor iniciado com:

```
& "C:\xampp\php\php.exe" -S localhost:8000 -t public
```

---

# O que NÃO está implementado

Menu completo do dashboard.

Layout compartilhado.

Sistema de multas.

Relatórios.

Testes automatizados.

---

# Sobre o registro.md

Atualizado em 2026-06-26 com os registros do CRUD de Alunos, CRUD de Livros e CRUD de Empréstimos.

---

# Sobre o README.md

Não há confirmação de que foi atualizado.

Considerar como pendente.

---

# Sobre o CRUD de alunos

Implementado e arquivos gravados:

* app/Models/Aluno.php
* app/Controllers/AlunoController.php
* app/Views/alunos/index.php
* app/Views/alunos/form.php
* database/migrations/002_create_alunos_table.sql

Rotas funcionais.

---

# Sobre o CRUD de livros

Implementado e arquivos gravados:

* app/Models/Livro.php
* app/Controllers/LivroController.php
* app/Views/livros/index.php
* app/Views/livros/form.php
* database/migrations/003_create_livros_table.sql

Rotas funcionais.

---

# Sobre o CRUD de empréstimos

Implementado e arquivos gravados:

* app/Models/Emprestimo.php
* app/Controllers/EmprestimoController.php
* app/Views/emprestimos/index.php
* app/Views/emprestimos/form.php
* database/migrations/004_create_emprestimos_table.sql

Rotas funcionais.

Atenção: As migrations precisam ser executadas manualmente no banco de dados antes de testar.

---

# Estado atual do projeto

Funciona:

✔ Login

✔ Logout

✔ Dashboard

✔ Sessão

✔ Conexão PDO

✔ Banco de dados (script pronto)

✔ Admin

✔ CRUD de Alunos

✔ CRUD de Livros

✔ CRUD de Empréstimos

Não funciona:

✘ Layout compartilhado

✘ Sistema de multas

� Relatórios

✘ Testes automatizados

---

# Regras para a próxima IA

Antes de qualquer alteração:

1. Ler registro.md.

2. Ler README.md.

3. Nunca afirmar que criou arquivos sem realmente gravá-los.

4. Após cada etapa:

* atualizar registro.md;
* atualizar estado_do_projeto.md;
* atualizar README.md.

5. Trabalhar em módulos pequenos.

Sprint 1 ✓ CRUD de Alunos

Sprint 2 ✓ CRUD de Livros

Sprint 3 ✓ CRUD de Empréstimos

Sprint 4 → Menu do dashboard + Layout compartilhado

6. Sempre mostrar o conteúdo completo dos arquivos modificados quando solicitado.

7. Nunca responder apenas com descrições dizendo que implementou algo.

---

# Próxima tarefa recomendada

Atualizar o menu do dashboard e implementar layout compartilhado entre as páginas, mantendo a estrutura atual.

Após implementar:

* atualizar registro.md;
* atualizar estado_do_projeto.md;
* atualizar README.md;
* mostrar os arquivos realmente modificados.
