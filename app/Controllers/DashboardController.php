<?php
namespace App\Controllers;

class DashboardController
{
    public function index(): void
    {
        // Garantir que a sessão contém admin_nome (middleware já protege a rota)
        $adminName = $_SESSION['admin_nome'] ?? 'Desconhecido';
        // Incluir a view passando a variável
        $nome = htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8');
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}
?>