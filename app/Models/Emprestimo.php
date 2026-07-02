<?php

namespace App\Models;

use App\Database\Connection;

class Emprestimo
{
    public int $id;
    public int $aluno_id;
    public int $livro_id;
    public string $data_emprestimo;
    public string $data_devolucao_prevista;
    public ?string $data_devolucao_real;
    public string $status;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->aluno_id = (int)$data['aluno_id'];
        $this->livro_id = (int)$data['livro_id'];
        $this->data_emprestimo = $data['data_emprestimo'];
        $this->data_devolucao_prevista = $data['data_devolucao_prevista'];
        $this->data_devolucao_real = $data['data_devolucao_real'] ?? null;
        $this->status = $data['status'];
    }

    /**
     * Busca todos os empréstimos com dados de aluno e livro.
     * @return array
     */
    public static function all(): array
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->query('
            SELECT e.*, a.nome AS aluno_nome, l.titulo AS livro_titulo
            FROM emprestimos e
            JOIN alunos a ON e.aluno_id = a.id
            JOIN livros l ON e.livro_id = l.id
            ORDER BY e.id DESC
        ');
        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    /**
     * Busca empréstimos ativos.
     * @return array
     */
    public static function ativos(): array
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('
            SELECT e.*, a.nome AS aluno_nome, l.titulo AS livro_titulo
            FROM emprestimos e
            JOIN alunos a ON e.aluno_id = a.id
            JOIN livros l ON e.livro_id = l.id
            WHERE e.status = :status
            ORDER BY e.data_devolucao_prevista
        ');
        $stmt->execute(['status' => 'ativo']);
        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    /**
     * Busca um empréstimo pelo ID.
     * @param int $id
     * @return self|null
     */
    public static function findById(int $id): ?self
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('
            SELECT e.*, a.nome AS aluno_nome, l.titulo AS livro_titulo
            FROM emprestimos e
            JOIN alunos a ON e.aluno_id = a.id
            JOIN livros l ON e.livro_id = l.id
            WHERE e.id = :id
            LIMIT 1
        ');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new self($row) : null;
    }

    /**
     * Salva um novo empréstimo (INSERT).
     * @return int ID inserido
     */
    public function insert(): int
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('INSERT INTO emprestimos (aluno_id, livro_id, data_emprestimo, data_devolucao_prevista) VALUES (:aluno_id, :livro_id, :data_emprestimo, :data_devolucao_prevista)');
        $stmt->execute([
            ':aluno_id' => $this->aluno_id,
            ':livro_id' => $this->livro_id,
            ':data_emprestimo' => $this->data_emprestimo,
            ':data_devolucao_prevista' => $this->data_devolucao_prevista,
        ]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Registra devolução do livro.
     * @param int $id
     * @param string $data_devolucao
     * @return bool
     */
    public static function devolver(int $id, string $data_devolucao): bool
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('UPDATE emprestimos SET data_devolucao_real = :data_devolucao, status = :status WHERE id = :id');
        $stmt->execute([
            ':id' => $id,
            ':data_devolucao' => $data_devolucao,
            ':status' => 'devolvido',
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza status para atrasado.
     * @param int $id
     * @return bool
     */
    public static function marcarAtrasado(int $id): bool
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('UPDATE emprestimos SET status = :status WHERE id = :id AND status = :ativo');
        $stmt->execute([
            ':id' => $id,
            ':status' => 'atrasado',
            ':ativo' => 'ativo',
        ]);
        return $stmt->rowCount() > 0;
    }
}
