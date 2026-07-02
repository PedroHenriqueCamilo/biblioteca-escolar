<?php
$success = $_SESSION['emprestimo_success'] ?? '';
unset($_SESSION['emprestimo_success']);
$errors = $_SESSION['emprestimo_errors'] ?? [];
unset($_SESSION['emprestimo_errors']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Empréstimos - Biblioteca Escolar</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; padding: 2rem; color: #333; }
        h2 { margin-bottom: 1rem; color: #2c3e50; }
        .container { max-width: 1000px; margin: 0 auto; }
        .success { background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 0.5rem; flex-wrap: wrap; }
        .filter-links a { margin-right: 1rem; color: #3498db; text-decoration: none; padding: 0.3rem 0.6rem; border-radius: 4px; }
        .filter-links a.active { background: #3498db; color: #fff; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="number"], select {
            padding: 0.45rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem;
        }
        button, .btn { padding: 0.45rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-primary:hover { background: #2980b9; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-success:hover { background: #219a52; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #2c3e50; color: #fff; }
        tr:hover { background: #f8f9fa; }
        .actions { display: flex; gap: 0.4rem; }
        .actions button { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
        .status-ativo { color: #27ae60; font-weight: 600; }
        .status-devolvido { color: #7f8c8d; }
        .status-atrasado { color: #e74c3c; font-weight: 600; }
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
            <a href="/livros">Livros</a>
            <a href="/emprestimos">Empréstimos</a>
            <a href="/login">Sair</a>
        </nav>

        <h2>Empréstimos</h2>

        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar">
            <div class="filter-links">
                <a href="/emprestimos" class="<?= ($filter === 'todos') ? 'active' : '' ?>">Todos</a>
                <a href="/emprestimos?filter=ativos" class="<?= ($filter === 'ativos') ? 'active' : '' ?>">Ativos</a>
            </div>
            <a href="/emprestimos/create" class="btn-primary">+ Novo Empréstimo</a>
        </div>

        <?php if (empty($emprestimos)): ?>
            <p style="text-align:center; padding:2rem; color:#7f8c8d;">Nenhum empréstimo registrado.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Livro</th>
                        <th>Data Empréstimo</th>
                        <th>Devolução Prevista</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprestimos as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$emp->id, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($emp->aluno_nome ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($emp->livro_titulo ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($emp->data_emprestimo, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($emp->data_devolucao_prevista, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="status-<?= $emp->status ?>">
                                <?= htmlspecialchars(ucfirst($emp->status), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td class="actions">
                            <?php if ($emp->status === 'ativo' || $emp->status === 'atrasado'): ?>
                                <form method="POST" action="/emprestimos/devolver" style="display:inline;" onsubmit="return confirm('Confirmar devolução?');">
                                    <input type="hidden" name="id" value="<?= $emp->id ?>">
                                    <input type="hidden" name="data_devolucao_real" value="<?= date('Y-m-d') ?>">
                                    <button type="submit" class="btn-success">Devolver</button>
                                </form>
                            <?php else: ?>
                                <em>Devolvido</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
