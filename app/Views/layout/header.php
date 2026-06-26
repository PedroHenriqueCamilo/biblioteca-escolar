<?php
// Simple reusable header
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Biblioteca Escolar' ?></title>
    <style>
        body {font-family: Arial, sans-serif; margin: 2rem;}
        nav a {margin-right: 1rem;}
        table {border-collapse: collapse; width: 100%;}
        th, td {border: 1px solid #ccc; padding: 0.5rem; text-align: left;}
        .error {color: red;}
    </style>
</head>
<body>
    <nav>
        <a href="/dashboard">Dashboard</a>
        <a href="/alunos">Alunos</a>
        <a href="/livros">Livros</a>
        <a href="/emprestimos">Empréstimos</a>
        <a href="/logout">Sair</a>
    </nav>
    <hr>
?>