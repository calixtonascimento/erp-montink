<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h1><?= isset($produto) ? 'Editar' : 'Novo' ?> Produto</h1>
    
    <form method="post" action="<?= base_url('produtos/salvar') ?>">
        <input type="hidden" name="produto_id" value="<?= isset($produto) ? $produto->id : '' ?>">
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= isset($produto) ? $produto->nome : '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Preço</label>
            <input type="number" step="0.01" name="preco" class="form-control" value="<?= isset($produto) ? $produto->preco : '' ?>" required>
        </div>

        <h4>Estoque</h4>
        <input type="hidden" name="estoque_id" value="<?= isset($estoque[0]) ? $estoque[0]->id : '' ?>">
        <div class="mb-3">
            <label>Variação</label>
            <input type="text" name="variacao" class="form-control" value="<?= isset($estoque[0]) ? $estoque[0]->variacao : '' ?>">
        </div>
        <div class="mb-3">
            <label>Quantidade</label>
            <input type="number" name="quantidade" class="form-control" value="<?= isset($estoque[0]) ? $estoque[0]->quantidade : '' ?>" required>
        </div>

        <button class="btn btn-success" type="submit">Salvar</button>
        <a href="<?= base_url('produtos') ?>" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>
