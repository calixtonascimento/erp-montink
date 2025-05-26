<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Compra Finalizada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h4 class="alert-heading">Obrigado pela compra!</h4>
            <p>Seu pedido #<?= $pedido_id ?> foi finalizado com sucesso.</p>
            <hr>
            <a href="<?= base_url('produtos') ?>" class="btn btn-primary">Voltar aos Produtos</a>
        </div>

    </div>
</body>

</html>