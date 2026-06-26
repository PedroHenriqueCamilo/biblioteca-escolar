<?php
// $nome já foi escapado no controller
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Biblioteca Escolar</title>
    <style>
        body {font-family: Arial, sans-serif; margin: 2rem;}
        a {margin-top: 1rem; display: inline-block;}
    </style>
</head>
<body>
    <h2>Dashboard</h2>
    <p>Bem‑vindo, <?php echo $nome; ?>!</p>
    <a href="/logout">Sair</a>
</body>
</html>
?>