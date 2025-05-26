<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Carrinho de Compras</h2>

        <?php if (empty($carrinho)): ?>
            <div class="alert alert-info">Seu carrinho está vazio.</div>
            <a href="<?= base_url('produtos') ?>" class="btn btn-primary">Voltar aos Produtos</a>
        <?php else: ?>
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Produto</th>
                        <th>Preço unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrinho as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="<?= base_url('produtos/alterar_quantidade/' . $item['produto_id'] . '/-1') ?>" class="btn btn-sm btn-outline-secondary" title="Diminuir quantidade">-</a>
                                    <span><?= $item['quantidade'] ?></span>
                                    <a href="<?= base_url('produtos/alterar_quantidade/' . $item['produto_id'] . '/1') ?>" class="btn btn-sm btn-outline-secondary" title="Aumentar quantidade">+</a>
                                </div>
                            </td>
                            <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                            <td>
                                <a href="<?= base_url('produtos/remover_do_carrinho/' . $item['produto_id']) ?>" class="btn btn-sm btn-danger" title="Remover item">&times;</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form action="<?= base_url('produtos/finalizar_pedido') ?>" method="post">
                <div class="d-flex justify-content-end flex-column flex-md-row gap-3">

                    <div>
                        <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                        <p><strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?></p>
                        <?php if (isset($desconto) && $desconto > 0): ?>
                            <p><strong>Desconto:</strong> - R$ <?= number_format($desconto, 2, ',', '.') ?></p>
                        <?php endif; ?>
                        <h5><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></h5>
                    </div>

                    <div class="ms-md-2">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" id="cep" class="form-control" maxlength="9" placeholder="Digite o CEP" name="cep" required />
                        <small id="endereco" class="form-text text-muted"></small>
                    </div>

                    <div class="ms-md-2">
                        <label for="cupom" class="form-label">Cupom de Desconto</label>
                        <div class="input-group">
                            <input type="text" id="cupom" class="form-control" name="cupom" placeholder="Digite o cupom (opcional)">
                            <button type="button" id="btn-aplicar-cupom" class="btn btn-outline-primary">Aplicar</button>
                        </div>
                        <small id="mensagem-cupom" class="form-text"></small>
                    </div>


                    <div class="ms-md-4 d-flex flex-column gap-2">
                        <a href="<?= base_url('produtos') ?>" class="btn btn-secondary">Continuar Comprando</a>
                        <button type="submit" class="btn btn-success">Finalizar Compra</button>
                    </div>
                </div>
            </form>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#cep').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $('#endereco').text('Consultando endereço...');
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!('erro' in data)) {
                        var endereco = data.logradouro + ', ' + data.bairro + ', ' + data.localidade + ' - ' + data.uf;
                        $('#endereco').text(endereco);
                    } else {
                        $('#endereco').text('CEP não encontrado.');
                    }
                });
            } else {
                $('#endereco').text('CEP inválido.');
            }
        });

        $('#btn-aplicar-cupom').on('click', function() {
            var codigo = $('#cupom').val().trim();
            if (!codigo) {
                $('#mensagem-cupom').text('Digite um código de cupom.').removeClass('text-success').addClass('text-danger');
                return;
            }

            $.post('<?= base_url('produtos/validar_cupom') ?>', {
                cupom: codigo
            }, function(data) {
                if (data.valido) {
                    $('#mensagem-cupom').text(data.mensagem).removeClass('text-danger').addClass('text-success');
                    var subtotal = <?= $subtotal ?>;
                    var frete = <?= $frete ?>;
                    var desconto = parseFloat(data.desconto);

                    var total = subtotal - desconto + frete;

                    // Atualiza valores na tela
                    $('p:contains("Subtotal")').html('<strong>Subtotal:</strong> R$ ' + subtotal.toFixed(2).replace('.', ','));
                    $('p:contains("Frete")').html('<strong>Frete:</strong> R$ ' + frete.toFixed(2).replace('.', ','));

                    if ($('#desconto-carrinho').length === 0) {
                        $('<p id="desconto-carrinho"><strong>Desconto:</strong> R$ ' + desconto.toFixed(2).replace('.', ',') + '</p>').insertAfter('p:contains("Frete")');
                    } else {
                        $('#desconto-carrinho').html('<strong>Desconto:</strong> R$ ' + desconto.toFixed(2).replace('.', ','));
                    }

                    $('h5:contains("Total")').html('<strong>Total:</strong> R$ ' + total.toFixed(2).replace('.', ','));
                } else {
                    $('#mensagem-cupom').text(data.mensagem).removeClass('text-success').addClass('text-danger');
                }
            }, 'json');
        });
    </script>
</body>

</html>