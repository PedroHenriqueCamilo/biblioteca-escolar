<?php
$old = $_SESSION['emprestimo_old'] ?? [];
unset($_SESSION['emprestimo_old']);
$errors = $_SESSION['emprestimo_errors'] ?? [];
unset($_SESSION['emprestimo_errors']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Empréstimo - Biblioteca Escolar</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; padding: 2rem; color: #333; }
        h2 { margin-bottom: 1rem; color: #2c3e50; }
        .container { max-width: 600px; margin: 0 auto; }
        .error { background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
        form { background: #fff; padding: 1.5rem; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 0.3rem; font-weight: 600; margin-top: 0.75rem; }
        input, select { width: 100%; padding: 0.55rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.95rem; }
        input:focus, select:focus { outline: none; border-color: #3498db; }
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
            <a href="/emprestimos">Empréstimos</a>
        </nav>

        <h2>Novo Empréstimo</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/emprestimos/store">
            <label for="aluno_id">Aluno *</label>
            <select id="aluno_id" name="aluno_id" required>
                <option value="">Selecione um aluno</option>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?= $aluno->id ?>" <?= (isset($old['aluno_id']) && $old['aluno_id'] == $aluno->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($aluno->nome, ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($aluno->matricula, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="livro_id">Livro *</label>
            <select id="livro_id" name="livro_id" required>
                <option value="">Selecione um livro</option>
                <?php foreach ($livros as $livro): ?>
                    <option value="<?= $livro->id ?>" <?= (isset($old['livro_id']) && $old['livro_id'] == $livro->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($livro->titulo, ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($livro->autor, ENT_QUOTES, 'UTF-8') ?> (ISBN: <?= htmlspecialchars($livro->isbn, ENT_QUOTES, 'UTF-8') ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="data_emprestimo">Data do Empréstimo</label>
            <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?= htmlspecialchars($old['data_emprestimo'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>">

            <label for="data_devolucao_prevista">Data de Devolução Prevista *</label>
            <input type="date" id="data_devolucao_prevista" name="data_devolucao_prevista" value="<?= htmlspecialchars($old['data_devolucao_prevista'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

            <div class="actions-form">
                <button type="submit" class="btn-primary">Registrar</button>
                <a href="/emprestimos" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
