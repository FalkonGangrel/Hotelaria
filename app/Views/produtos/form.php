<?php
/** @var array|null $product */
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?= $title ?></h2>
    <a href="<?=$_ENV['APP_BASE']?>/produtos" class="btn btn-secondary">Voltar</a>
</div>

<form method="post" action="<?= isset($product) ? $_ENV['APP_BASE'].'/produtos/atualizar/' . $product['id'] : $_ENV['APP_BASE'].'/produtos/salvar' ?>">
    <div class="mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" value="<?= htmlspecialchars($product['sku'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($product['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Preço</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price'] ?? '0') ?>">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="active" class="form-check-input" <?= !isset($product) || !empty($product['active']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<?php
$content = ob_get_clean();
$title = "Produtos";
include __DIR__ . '/../layouts/main.php';
