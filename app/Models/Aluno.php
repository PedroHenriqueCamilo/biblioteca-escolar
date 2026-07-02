<?php

namespace App\Models;

use App\Database\Connection;

class Aluno
{
    public int $id;
    public string $nome;
    public string $matricula;
    public string $email;
    public ?string $telefone;
    public ?string $turma;
    public bool $ativo;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->nome = $data['nome'];
        $this->matricula = $data['matricula'];
        $this->email = $data['email'];
        $this->telefone = $data['telefone'] ?? null;
        $this->turma = $data['turma'] ?? null;
        $this->ativo = (bool)$data['ativo'];
    }

    /**
     * Busca todos os alunos ativos.
     * @return self[]
     */
    public static function all(): array
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->query('SELECT * FROM alunos WHERE ativo = 1 ORDER BY nome');
        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    /**
     * Busca um aluno pelo ID.
     * @param int $id
     * @return self|null
     */
    public static function findById(int $id): ?self
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM alunos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new self($row) : null;
    }

    /**
     * Busca alunos por nome ou matrícula.
     * @param string $termo
     * @return self[]
     */
    public static function search(string $termo): array
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM alunos WHERE ativo = 1 AND (nome LIKE :termo OR matricula LIKE :termo ORDER BY nome');
        $stmt->execute(['termo' => '%' . $termo . '%']);
        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    /**
     * Salva um novo aluno (INSERT).
     * @return int ID inserido
     */
    public function insert(): int
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('INSERT INTO alunos (nome, matricula, email, telefone, turma) VALUES (:nome, :matricula, :email, :telefone, :turma)');
        $stmt->execute([
            ':nome' => $this->nome,
            ':matricula' => $this->matricula,
            ':email' => $this->email,
            ':telefone' => $this->telefone,
            ':turma' => $this->turma,
        ]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Atualiza um aluno existente (UPDATE).
     * @return bool
     */
    public function update(): bool
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('UPDATE alunos SET nome = :nome, matricula = :matricula, email = :email, telefone = :telefone, turma = :turma WHERE id = :id');
        $stmt->execute([
            ':id' => $this->id,
            ':nome' => $this->nome,
            ':matricula' => $this->matricula,
            ':email' => $this->email,
            ':telefone' => $this->telefone,
            ':turma' => $this->turma,
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Remove um aluno (soft delete - desativa).
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('UPDATE alunos SET ativo = 0 WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
