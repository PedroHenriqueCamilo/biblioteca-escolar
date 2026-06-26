<?php
// Exibir mensagens de erro, se houver
$errors = $_SESSION['login_errors'] ?? [];
unset($_SESSION['login_errors']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca Escolar</title>
    <style>
        body {font-family: Arial, sans-serif; margin: 2rem;}
        .error {color: red; margin-bottom: 1rem;}
        .form {max-width: 320px;}
        label {display: block; margin-top: 0.5rem;}
        input {width: 100%; padding: 0.4rem;}
        button {margin-top: 1rem; padding: 0.5rem 1rem;}
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err): ?>
                <p><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form class="form" method="POST" action="/login">
        <label for="email">E‑mail</label>
        <input type="email" id="email" name="email" required>
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
?>