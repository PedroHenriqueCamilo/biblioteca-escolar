<?php
// Front Controller
session_start();

// Composer será usado futuramente
// require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../app/Database/Connection.php';
require __DIR__ . '/../app/Models/Admin.php';
require __DIR__ . '/../app/Models/Aluno.php';
require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/DashboardController.php';
require __DIR__ . '/../app/Controllers/AlunoController.php';
require __DIR__ . '/../app/Models/Livro.php';
require __DIR__ . '/../app/Controllers/LivroController.php';
require __DIR__ . '/../app/Models/Emprestimo.php';
require __DIR__ . '/../app/Controllers/EmprestimoController.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AlunoController;
use App\Controllers\LivroController;
use App\Controllers\EmprestimoController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rotas
$routes = [
    'GET' => [
        '/' => fn() => (new AuthController())->showLogin(),
        '/login' => fn() => (new AuthController())->showLogin(),
        '/logout' => fn() => (new AuthController())->logout(),
        '/dashboard' => fn() => (new DashboardController())->index(),
        '/alunos' => fn() => (new AlunoController())->index(),
        '/alunos/create' => fn() => (new AlunoController())->create(),
        '/alunos/edit' => fn() => (new AlunoController())->edit(),
        '/livros' => fn() => (new LivroController())->index(),
        '/livros/create' => fn() => (new LivroController())->create(),
        '/livros/edit' => fn() => (new LivroController())->edit(),
        '/emprestimos' => fn() => (new EmprestimoController())->index(),
        '/emprestimos/create' => fn() => (new EmprestimoController())->create(),
    ],

    'POST' => [
        '/login' => fn() => (new AuthController())->login(),
        '/alunos/store' => fn() => (new AlunoController())->store(),
        '/alunos/update' => fn() => (new AlunoController())->update(),
        '/alunos/delete' => fn() => (new AlunoController())->destroy(),
        '/livros/store' => fn() => (new LivroController())->store(),
        '/livros/update' => fn() => (new LivroController())->update(),
        '/livros/delete' => fn() => (new LivroController())->destroy(),
        '/emprestimos/store' => fn() => (new EmprestimoController())->store(),
        '/emprestimos/devolver' => fn() => (new EmprestimoController())->devolver(),
    ],
];

// Middleware de autenticação
$protectedRoutes = [
    '/dashboard',
    '/alunos',
    '/alunos/create',
    '/alunos/edit',
    '/livros',
    '/livros/create',
    '/livros/edit',
    '/emprestimos',
    '/emprestimos/create',
];

$isProtected = false;
foreach ($protectedRoutes as $protected) {
    if ($uri === $protected || str_starts_with($uri, $protected . '/')) {
        $isProtected = true;
        break;
    }
}
if ($isProtected) {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /login');
        exit;
    }
}

// Impede usuário logado de acessar login
if (
    in_array($uri, ['/', '/login'], true)
    && !empty($_SESSION['admin_id'])
) {
    header('Location: /dashboard');
    exit;
}

// Executa rota
if (isset($routes[$method][$uri])) {
    $routes[$method][$uri]();
    exit;
}

// 404
http_response_code(404);
echo "<h1>404 - Página não encontrada</h1>";