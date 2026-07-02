<?php
$data = $old ?? ($livro ?? []);
$titulo = htmlspecialchars($data['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
$autor = htmlspecialchars($data['autor'] ?? '', ENT_QUOTES, 'UTF-8');
$isbn = htmlspecialchars($data['isbn'] ?? '', ENT_QUOTES, 'UTF-8');
$editora = htmlspecialchars($data['editora'] ?? '', ENT_QUOTES, 'UTF-8');
$ano_publicacao = htmlspecialchars($data['ano_publicacao'] ?? '', ENT_QUOTES, 'UTF-8');
$quantidade = htmlspecialchars((string)($data['quantidade'] ?? 1), ENT_QUOTES, 'UTF-8');
$acao = isset($livro) && $livro ? 'update' : 'store';
$titulo_acao = $acao === 'update' ? 'Editar Livro' : 'Novo Livro';
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
            <a href="/livros">Livros</a>
        </nav>

        <h2><?= $titulo_acao ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/livros/<?= $acao ?>">
            <?php if (!empty($data['id']) || (isset($livro) && $livro && $livro->id)): ?>
                <input type="hidden" name="id" value="<?= (int)($data['id'] ?? $livro->id ?? 0) ?>">
            <?php endif; ?>

            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="<?= $titulo ?>" required>

            <label for="autor">Autor *</label>
            <input type="text" id="autor" name="autor" value="<?= $autor ?>" required>

            <label for="isbn">ISBN *</label>
            <input type="text" id="isbn" name="isbn" value="<?= $isbn ?>" required>

            <label for="editora">Editora</label>
            <input type="text" id="editora" name="editora" value="<?= $editora ?>">

            <label for="ano_publicacao">Ano de Publicação</label>
            <input type="number" id="ano_publicacao" name="ano_publicacao" value="<?= $ano_publicacao ?>" min="1000" max="2099">

            <label for="quantidade">Quantidade *</label>
            <input type="number" id="quantidade" name="quantidade" value="<?= $quantidade ?>" min="1" required>

            <div class="actions-form">
                <button type="submit" class="btn-primary">Salvar</button>
                <a href="/livros" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
