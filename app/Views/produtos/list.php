<?php
    /** @var array $products */
    ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?= $title ?></h2>
    <a href="/produtos/novo" class="btn btn-primary">Novo Produto</a>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Título</th>
            <th>Preço</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['sku']) ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td>R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
                <td><?= $p['active'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href="/produtos/editar/<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <?php
                        if ($p['active']):
                    ?>
                        <a href="/produtos/excluir/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este produto?')">Excluir</a>
                    <?php
                        else:
                    ?>
                        <a href="/produtos/reativar/<?= $p['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Reativar este produto?')">Reativar</a>
                    <?php
                        endif;
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
$title = "Produtos";
include __DIR__ . '/../layouts/main.php';
