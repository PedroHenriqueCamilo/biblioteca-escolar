<?php

namespace App\Controllers;

use App\Models\Livro;

class LivroController
{
    /**
     * Lista todos os livros.
     */
    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $livros = $search !== '' ? Livro::search($search) : Livro::all();
        $title = 'Livros';
        $errors = $_SESSION['livro_errors'] ?? [];
        unset($_SESSION['livro_errors']);
        require __DIR__ . '/../Views/livros/index.php';
    }

    /**
     * Exibe formulário de criação.
     */
    public function create(): void
    {
        $title = 'Novo Livro';
        $livro = null;
        $errors = $_SESSION['livro_errors'] ?? [];
        unset($_SESSION['livro_errors']);
        $old = $_SESSION['livro_old'] ?? [];
        unset($_SESSION['livro_old']);
        require __DIR__ . '/../Views/livros/form.php';
    }

    /**
     * Armazena um novo livro (POST).
     */
    public function store(): void
    {
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $editora = trim($_POST['editora'] ?? '') ?: null;
        $ano_publicacao = trim($_POST['ano_publicacao'] ?? '') ?: null;
        $quantidade = (int)($_POST['quantidade'] ?? 1);

        $errors = $this->validate($titulo, $autor, $isbn, $quantidade);

        if (!empty($errors)) {
            $_SESSION['livro_errors'] = $errors;
            $_SESSION['livro_old'] = $_POST;
            header('Location: /livros/create');
            exit;
        }

        $livro = new Livro([
            'id' => 0,
            'titulo' => $titulo,
            'autor' => $autor,
            'isbn' => $isbn,
            'editora' => $editora,
            'ano_publicacao' => $ano_publicacao,
            'quantidade' => $quantidade,
            'disponivel' => 1,
        ]);
        $livro->insert();

        $_SESSION['livro_success'] = 'Livro adicionado com sucesso.';
        header('Location: /livros');
        exit;
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $livro = Livro::findById($id);

        if (!$livro) {
            $_SESSION['livro_errors'] = ['Livro não encontrado.'];
            header('Location: /livros');
            exit;
        }

        $title = 'Editar Livro';
        $errors = $_SESSION['livro_errors'] ?? [];
        unset($_SESSION['livro_errors']);
        $old = [];
        require __DIR__ . '/../Views/livros/form.php';
    }

    /**
     * Atualiza um livro (POST /livros/update).
     */
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $editora = trim($_POST['editora'] ?? '') ?: null;
        $ano_publicacao = trim($_POST['ano_publicacao'] ?? '') ?: null;
        $quantidade = (int)($_POST['quantidade'] ?? 1);

        $errors = $this->validate($titulo, $autor, $isbn, $quantidade);

        if (!empty($errors)) {
            $_SESSION['livro_errors'] = $errors;
            $_SESSION['livro_old'] = $_POST;
            header('Location: /livros/edit?id=' . $id);
            exit;
        }

        $livro = Livro::findById($id);
        if (!$livro) {
            $_SESSION['livro_errors'] = ['Livro não encontrado.'];
            header('Location: /livros');
            exit;
        }

        $livro->titulo = $titulo;
        $livro->autor = $autor;
        $livro->isbn = $isbn;
        $livro->editora = $editora;
        $livro->ano_publicacao = $ano_publicacao ? (int)$ano_publicacao : null;
        $livro->quantidade = $quantidade;

        if ($livro->update()) {
            $_SESSION['livro_success'] = 'Livro atualizado com sucesso.';
        } else {
            $_SESSION['livro_errors'] = ['Erro ao atualizar livro.'];
        }

        header('Location: /livros');
        exit;
    }

    /**
     * Remove um livro (POST /livros/delete).
     */
    public function destroy(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if (Livro::delete($id)) {
            $_SESSION['livro_success'] = 'Livro removido com sucesso.';
        } else {
            $_SESSION['livro_errors'] = ['Erro ao remover livro.'];
        }

        header('Location: /livros');
        exit;
    }

    /**
     * Valida os dados do formulário.
     */
    private function validate(string $titulo, string $autor, string $isbn, int $quantidade): array
    {
        $errors = [];

        if (empty($titulo)) {
            $errors[] = 'Título é obrigatório.';
        }

        if (empty($autor)) {
            $errors[] = 'Autor é obrigatório.';
        }

        if (empty($isbn)) {
            $errors[] = 'ISBN é obrigatório.';
        }

        if ($quantidade < 1) {
            $errors[] = 'Quantidade deve ser pelo menos 1.';
        }

        return $errors;
    }
}
