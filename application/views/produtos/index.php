<!DOCTYPE html>
<html>
<head>
    <title>Produtos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h1>Produtos</h1>
    <a href="<?= base_url('produtos/novo') ?>" class="btn btn-primary mb-3">Novo Produto</a>
    <a href="<?= base_url('cupons/index') ?>" class="btn btn-secondary mb-3">Cupons</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produtos as $p): ?>
                <tr>
                    <td><?= $p->nome ?></td>
                    <td>R$ <?= number_format($p->preco, 2, ',', '.') ?></td>
                    <td>
                        <a href="<?= base_url('produtos/editar/'.$p->id) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= base_url('produtos/adicionar_ao_carrinho/' . $p->id) ?>" class="btn btn-primary btn-sm">Comprar</a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
