<?php
// Front Controller
session_start();

// Composer será usado futuramente
// require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../app/Database/Connection.php';
require __DIR__ . '/../app/Models/Admin.php';
require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/DashboardController.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rotas
$routes = [
    'GET' => [
        '/' => fn() => (new AuthController())->showLogin(),
        '/login' => fn() => (new AuthController())->showLogin(),
        '/logout' => fn() => (new AuthController())->logout(),
        '/dashboard' => fn() => (new DashboardController())->index(),
    ],

    'POST' => [
        '/login' => fn() => (new AuthController())->login(),
    ],
];

// Middleware de autenticação
$protectedRoutes = [
    '/dashboard'
];

if (in_array($uri, $protectedRoutes, true)) {
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