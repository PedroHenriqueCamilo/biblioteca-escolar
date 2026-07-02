<?php

namespace App\Models;

use App\Database\Connection;

class Livro
{
    public int $id;
    public string $titulo;
    public string $autor;
    public string $isbn;
    public ?string $editora;
    public ?int $ano_publicacao;
    public int $quantidade_total;
    public int $quantidade_disponivel;
    public bool $ativo;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->titulo = $data['titulo'];
        $this->autor = $data['autor'];
        $this->isbn = $data['isbn'];
        $this->editora = $data['editora'] ?? null;
        $this->ano_publicacao = isset($data['ano_publicacao']) ? (int)$data['ano_publicacao'] : null;
        $this->quantidade_total = (int)$data['quantidade_total'];
        $this->quantidade_disponivel = (int)$data['quantidade_disponivel'];
        $this->ativo = (bool)$data['ativo'];
    }

    public static function all(): array
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->query("
            SELECT *
            FROM livros
            WHERE ativo = 1
            ORDER BY titulo
        ");

        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    public static function findById(int $id): ?self
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare("
            SELECT *
            FROM livros
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $row = $stmt->fetch();

        return $row ? new self($row) : null;
    }

    public static function search(string $texto): array
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare("
            SELECT *
            FROM livros
            WHERE ativo = 1
            AND (
                titulo LIKE ?
                OR autor LIKE ?
                OR isbn LIKE ?
            )
            ORDER BY titulo
        ");

        $busca = "%{$texto}%";

        $stmt->execute([$busca, $busca, $busca]);

        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    public function insert(): int
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare("
            INSERT INTO livros
            (
                titulo,
                autor,
                isbn,
                editora,
                ano_publicacao,
                quantidade_total,
                quantidade_disponivel
            )
            VALUES
            (
                ?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $this->titulo,
            $this->autor,
            $this->isbn,
            $this->editora,
            $this->ano_publicacao,
            $this->quantidade_total,
            $this->quantidade_disponivel
        ]);

        return (int)$pdo->lastInsertId();
    }

    public function update(): bool
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare("
            UPDATE livros
            SET
                titulo=?,
                autor=?,
                isbn=?,
                editora=?,
                ano_publicacao=?,
                quantidade_total=?,
                quantidade_disponivel=?
            WHERE id=?
        ");

        $stmt->execute([
            $this->titulo,
            $this->autor,
            $this->isbn,
            $this->editora,
            $this->ano_publicacao,
            $this->quantidade_total,
            $this->quantidade_disponivel,
            $this->id
        ]);

        return true;
    }

    public static function delete(int $id): bool
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare("
            UPDATE livros
            SET ativo = 0
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return true;
    }
}