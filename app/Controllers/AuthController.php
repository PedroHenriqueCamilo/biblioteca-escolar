<?php

namespace App\Controllers;

use App\Models\Admin;

class AuthController
{
    /**
     * Exibe o formulário de login.
     */
    public function showLogin(): void
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Processa o login.
     */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }

        if (empty($senha)) {
            $errors[] = 'Senha não informada.';
        }

        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            header('Location: /login');
            exit;
        }

        // Busca administrador pelo e-mail
        $admin = Admin::findByEmail($email);

        // Verifica se existe, se está ativo e se a senha está correta
        if (!$admin || !$admin->ativo || !$admin->verifyPassword($senha)) {
            $_SESSION['login_errors'] = ['Credenciais inválidas.'];
            header('Location: /login');
            exit;
        }

        // Login realizado com sucesso
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin->id;
        $_SESSION['admin_nome'] = $admin->nome;

        header('Location: /dashboard');
        exit;
    }

    /**
     * Faz logout.
     */
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: /login');
        exit;
    }
}