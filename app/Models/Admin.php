<?php
namespace App\Models;

use App\Database\Connection;

class Admin
{
    public int $id;
    public string $nome;
    public string $email;
    public string $senha_hash;
    public string $cargo;
    public bool $ativo;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->nome = $data['nome'];
        $this->email = $data['email'];
        $this->senha_hash = $data['senha_hash'];
        $this->cargo = $data['cargo'];
        $this->ativo = (bool)$data['ativo'];
    }

    /**
     * Busca um administrador pelo e‑mail.
     * @param string $email
     * @return self|null
     */
    public static function findByEmail(string $email): ?self
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row) {
            return new self($row);
        }
        return null;
    }

    /**
     * Verifica a senha em texto plano contra o hash armazenado.
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->senha_hash);
    }
}
?>