<?php
/** @var array|null $product */
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?= $title ?></h2>
    <a href="<?=$_ENV['APP_BASE']?>/fornecedores" class="btn btn-secondary">Voltar</a>
</div>

<form action="<?= isset($supplier) ? $_ENV['APP_BASE'].'/fornecedores/atualizar/'.$supplier['id'] : $_ENV['APP_BASE'].'/fornecedores/salvar' ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">Nome da Empresa</label>
        <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($supplier['company_name'] ?? '') ?>" required>
    </div>

    <?php if(isset($supplier)): ?>
    <div class="mb-3">
        <label class="form-label">Usuário Responsável</label>
        <select name="user_id" class="form-select">
            <option value="">-- Nenhum --</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= (isset($supplier['user_id']) && $supplier['user_id'] == $u['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<?php
$content = ob_get_clean();
$title = "Fornecedores";
include __DIR__ . '/../layouts/main.php';
