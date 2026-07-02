<?php

namespace App\Controllers;

use App\Models\Aluno;

class AlunoController
{
    /**
     * Lista todos os alunos.
     */
    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $alunos = $search !== '' ? Aluno::search($search) : Aluno::all();
        $title = 'Alunos';
        $errors = $_SESSION['aluno_errors'] ?? [];
        unset($_SESSION['aluno_errors']);
        require __DIR__ . '/../Views/alunos/index.php';
    }

    /**
     * Exibe formulário de criação.
     */
    public function create(): void
    {
        $title = 'Novo Aluno';
        $aluno = null;
        $errors = $_SESSION['aluno_errors'] ?? [];
        unset($_SESSION['aluno_errors']);
        $old = $_SESSION['aluno_old'] ?? [];
        unset($_SESSION['aluno_old']);
        require __DIR__ . '/../Views/alunos/form.php';
    }

    /**
     * Armazena um novo aluno (POST).
     */
    public function store(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $matricula = trim($_POST['matricula'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '') ?: null;
        $turma = trim($_POST['turma'] ?? '') ?: null;

        $errors = $this->validate($nome, $matricula, $email);

        if (!empty($errors)) {
            $_SESSION['aluno_errors'] = $errors;
            $_SESSION['aluno_old'] = $_POST;
            header('Location: /alunos/create');
            exit;
        }

        $aluno = new Aluno([
            'id' => 0,
            'nome' => $nome,
            'matricula' => $matricula,
            'email' => $email,
            'telefone' => $telefone,
            'turma' => $turma,
            'ativo' => 1,
        ]);
        $aluno->insert();

        $_SESSION['aluno_success'] = 'Aluno adicionado com sucesso.';
        header('Location: /alunos');
        exit;
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $aluno = Aluno::findById($id);

        if (!$aluno) {
            $_SESSION['aluno_errors'] = ['Aluno não encontrado.'];
            header('Location: /alunos');
            exit;
        }

        $title = 'Editar Aluno';
        $errors = $_SESSION['aluno_errors'] ?? [];
        unset($_SESSION['aluno_errors']);
        $old = [];
        require __DIR__ . '/../Views/alunos/form.php';
    }

    /**
     * Atualiza um aluno (POST /alunos/update).
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $matricula = trim($_POST['matricula'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '') ?: null;
        $turma = trim($_POST['turma'] ?? '') ?: null;

        $errors = $this->validate($nome, $matricula, $email, $id);

        if (!empty($errors)) {
            $_SESSION['aluno_errors'] = $errors;
            $_SESSION['aluno_old'] = $_POST;
            header('Location: /alunos/edit?id=' . $id);
            exit;
        }

        $aluno = Aluno::findById($id);
        if (!$aluno) {
            $_SESSION['aluno_errors'] = ['Aluno não encontrado.'];
            header('Location: /alunos');
            exit;
        }

        $aluno->nome = $nome;
        $aluno->matricula = $matricula;
        $aluno->email = $email;
        $aluno->telefone = $telefone;
        $aluno->turma = $turma;

        if ($aluno->update()) {
            $_SESSION['aluno_success'] = 'Aluno atualizado com sucesso.';
        } else {
            $_SESSION['aluno_errors'] = ['Erro ao atualizar aluno.'];
        }

        header('Location: /alunos');
        exit;
    }

    /**
     * Remove um aluno (POST /alunos/delete).
     */
    public function destroy(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if (Aluno::delete($id)) {
            $_SESSION['aluno_success'] = 'Aluno removido com sucesso.';
        } else {
            $_SESSION['aluno_errors'] = ['Erro ao remover aluno.'];
        }

        header('Location: /alunos');
        exit;
    }

    /**
     * Valida os dados do formulário.
     */
    private function validate(string $nome, string $matricula, string $email, int $excludeId = 0): array
    {
        $errors = [];

        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório.';
        }

        if (empty($matricula)) {
            $errors[] = 'Matrícula é obrigatória.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }

        return $errors;
    }
}
