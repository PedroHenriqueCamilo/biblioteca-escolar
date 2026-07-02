<?php
$data = $old ?? ($aluno ?? []);
$nome = htmlspecialchars($data['nome'] ?? '', ENT_QUOTES, 'UTF-8');
$matricula = htmlspecialchars($data['matricula'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8');
$telefone = htmlspecialchars($data['telefone'] ?? '', ENT_QUOTES, 'UTF-8');
$turma = htmlspecialchars($data['turma'] ?? '', ENT_QUOTES, 'UTF-8');
$acao = isset($aluno) && $aluno ? 'update' : 'store';
$titulo_acao = $acao === 'update' ? 'Editar Aluno' : 'Novo Aluno';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo_acao ?> - Biblioteca Escolar</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; padding: 2rem; color: #333; }
        h2 { margin-bottom: 1rem; color: #2c3e50; }
        .container { max-width: 600px; margin: 0 auto; }
        .error { background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
        form { background: #fff; padding: 1.5rem; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 0.3rem; font-weight: 600; margin-top: 0.75rem; }
        input { width: 100%; padding: 0.55rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.95rem; }
        input:focus { outline: none; border-color: #3498db; }
        .actions-form { margin-top: 1.5rem; display: flex; gap: 0.5rem; }
        button, .btn { padding: 0.55rem 1.2rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-primary:hover { background: #2980b9; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        nav.top { margin-bottom: 1.5rem; }
        nav.top a { margin-right: 1rem; color: #3498db; text-decoration: none; }
        nav.top a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="top">
            <a href="/dashboard">Dashboard</a>
            <a href="/alunos">Alunos</a>
        </nav>

        <h2><?= $titulo_acao ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/alunos/<?= $acao ?>">
            <?php if (!empty($data['id']) || (isset($aluno) && $aluno && $aluno->id)): ?>
                <input type="hidden" name="id" value="<?= (int)($data['id'] ?? $aluno->id ?? 0) ?>">
            <?php endif; ?>

            <label for="nome">Nome completo *</label>
            <input type="text" id="nome" name="nome" value="<?= $nome ?>" required>

            <label for="matricula">Matrícula *</label>
            <input type="text" id="matricula" name="matricula" value="<?= $matricula ?>" required>

            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" value="<?= $email ?>" required>

            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" value="<?= $telefone ?>">

            <label for="turma">Turma</label>
            <input type="text" id="turma" name="turma" value="<?= $turma ?>">

            <div class="actions-form">
                <button type="submit" class="btn-primary">Salvar</button>
                <a href="/alunos" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
