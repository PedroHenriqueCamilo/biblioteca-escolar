<?php
$success = $_SESSION['livro_success'] ?? '';
unset($_SESSION['livro_success']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Livros - Biblioteca Escolar</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; padding: 2rem; color: #333; }
        h2 { margin-bottom: 1rem; color: #2c3e50; }
        .container { max-width: 960px; margin: 0 auto; }
        .success { background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; gap: 0.5rem; flex-wrap: wrap; }
        .toolbar form { display: flex; gap: 0.5rem; align-items: center; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="number"] { padding: 0.45rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; }
        button, .btn { padding: 0.45rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-primary:hover { background: #2980b9; }
        .btn-warning { background: #f39c12; color: #fff; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-danger:hover { background: #c0392b; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #2c3e50; color: #fff; }
        tr:hover { background: #f8f9fa; }
        .actions { display: flex; gap: 0.4rem; }
        .actions button, .actions a { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
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
            <a href="/login">Sair</a>
        </nav>

        <h2>Livros</h2>

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
            <form method="GET" action="/livros">
                <input type="text" name="q" placeholder="Buscar por título, autor ou ISBN..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn-secondary">Buscar</button>
                <?php if (!empty($search)): ?>
                    <a href="/livros" class="btn-warning" style="padding:0.45rem 1rem;">Limpar</a>
                <?php endif; ?>
            </form>
            <a href="/livros/create" class="btn-primary">+ Novo Livro</a>
        </div>

        <?php if (empty($livros)): ?>
            <p style="text-align:center; padding:2rem; color:#7f8c8d;">Nenhum livro cadastrado.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Editora</th>
                        <th>Ano</th>
                        <th>Qtd</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livros as $livro): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$livro->id, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($livro->titulo, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($livro->autor, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($livro->isbn, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($livro->editora ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($livro->ano_publicacao ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)$livro->quantidade, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="actions">
                            <a href="/livros/edit?id=<?= $livro->id ?>" class="btn-warning">Editar</a>
                            <form method="POST" action="/livros/delete" style="display:inline;" onsubmit="return confirm('Deseja remover este livro?');">
                                <input type="hidden" name="id" value="<?= $livro->id ?>">
                                <button type="submit" class="btn-danger">Remover</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
