<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Cupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Gerenciar Cupons</h2>

        <!-- Formulário para adicionar novo cupom -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Cadastrar Novo Cupom</h5>
                <form action="<?= base_url('cupons/salvar') ?>" method="post" class="row g-3">
                    <div class="col-md-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="col-md-2">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="percentual">Percentual (%)</option>
                            <option value="fixo">Valor Fixo (R$)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                    </div>
                    <div class="col-md-2">
                        <label for="quantidade" class="form-label">Qtd. Usos</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="validade" class="form-label">Validade</label>
                        <input type="date" class="form-control" id="validade" name="validade">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Salvar Cupom</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de cupons cadastrados -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cupons Cadastrados</h5>
                <?php if (empty($cupons)): ?>
                    <div class="alert alert-info">Nenhum cupom cadastrado.</div>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Qtd. Usos</th>
                                <th>Validade</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cupons as $cupom): ?>
                                <tr>
                                    <td><?= $cupom['id'] ?></td>
                                    <td><?= htmlspecialchars($cupom['codigo']) ?></td>
                                    <td><?= ucfirst($cupom['tipo']) ?></td>
                                    <td>
                                        <?= $cupom['tipo'] == 'percentual' ? $cupom['valor'] . '%' : 'R$ ' . number_format($cupom['valor'], 2, ',', '.') ?>
                                    </td>
                                    <td><?= $cupom['quantidade'] ?></td>
                                    <td><?= $cupom['validade'] ? date('d/m/Y', strtotime($cupom['validade'])) : 'Sem validade' ?></td>
                                    <td><?= date('d/m/Y', strtotime($cupom['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('cupons/excluir/' . $cupom['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cupom?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?= base_url('produtos') ?>" class="btn btn-secondary">Voltar aos Produtos</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>