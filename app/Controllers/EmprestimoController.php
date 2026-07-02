<?php

namespace App\Controllers;

use App\Models\Aluno;
use App\Models\Livro;
use App\Models\Emprestimo;

class EmprestimoController
{
    /**
     * Lista todos os empréstimos.
     */
    public function index(): void
    {
        $filter = $_GET['filter'] ?? 'todos';
        $emprestimos = $filter === 'ativos' ? Emprestimo::ativos() : Emprestimo::all();
        $title = 'Empréstimos';

        require __DIR__ . '/../Views/emprestimos/index.php';
    }

    /**
     * Exibe formulário de criação.
     */
    public function create(): void
    {
        $title = 'Novo Empréstimo';
        $alunos = Aluno::all();
        $livros = Livro::all();

        require __DIR__ . '/../Views/emprestimos/form.php';
    }

    /**
     * Armazena um novo empréstimo (POST).
     */
    public function store(): void
    {
        $aluno_id = (int)($_POST['aluno_id'] ?? 0);
        $livro_id = (int)($_POST['livro_id'] ?? 0);
        $data_emprestimo = $_POST['data_emprestimo'] ?? date('Y-m-d');
        $data_devolucao_prevista = $_POST['data_devolucao_prevista'] ?? '';

        $errors = $this->validate($aluno_id, $livro_id, $data_devolucao_prevista);

        if (!empty($errors)) {
            $_SESSION['emprestimo_errors'] = $errors;
            $_SESSION['emprestimo_old'] = $_POST;
            header('Location: /emprestimos/create');
            exit;
        }

        $aluno = Aluno::findById($aluno_id);
        if (!$aluno) {
            $_SESSION['emprestimo_errors'] = ['Aluno não encontrado.'];
            header('Location: /emprestimos/create');
            exit;
        }

        $livro = Livro::findById($livro_id);
        if (!$livro) {
            $_SESSION['emprestimo_errors'] = ['Livro não encontrado.'];
            header('Location: /emprestimos/create');
            exit;
        }

        $emprestimo = new Emprestimo([
            'id' => 0,
            'aluno_id' => $aluno_id,
            'livro_id' => $livro_id,
            'data_emprestimo' => $data_emprestimo,
            'data_devolucao_prevista' => $data_devolucao_prevista,
            'data_devolucao_real' => null,
            'status' => 'ativo',
        ]);
        $emprestimo->insert();

        $_SESSION['emprestimo_success'] = 'Empréstimo registrado com sucesso.';
        header('Location: /emprestimos');
        exit;
    }

    /**
     * Registra devolução (POST /emprestimos/devolver).
     */
    public function devolver(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $data_devolucao = $_POST['data_devolucao_real'] ?? date('Y-m-d');

        if (Emprestimo::devolver($id, $data_devolucao)) {
            $_SESSION['emprestimo_success'] = 'Livro devolvido com sucesso.';
        } else {
            $_SESSION['emprestimo_errors'] = ['Erro ao registrar devolução.'];
        }

        header('Location: /emprestimos');
        exit;
    }

    /**
     * Valida os dados do formulário.
     */
    private function validate(int $aluno_id, int $livro_id, string $data_devolucao_prevista): array
    {
        $errors = [];

        if ($aluno_id <= 0) {
            $errors[] = 'Selecione um aluno.';
        }

        if ($livro_id <= 0) {
            $errors[] = 'Selecione um livro.';
        }

        if (empty($data_devolucao_prevista)) {
            $errors[] = 'Data de devolução prevista é obrigatória.';
        }

        return $errors;
    }
}
