<?php
    /** @var array $products */
    ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><?= $title ?></h2>
    <a href="<?=$_ENV['APP_BASE']?>/fornecedores/novo" class="btn btn-primary">Novo Fornecedor</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Empresa</th>
            <th>Cadastrado por</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($suppliers as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['company_name']) ?></td>
                <td><?= htmlspecialchars($s['user_name'] ?? '-') ?></td>
                <td><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                <td>
                    <a href="<?=$_ENV['APP_BASE']?>/fornecedores/editar/<?= $s['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <!-- <a href="<?//=$_ENV['APP_BASE']?>/fornecedores/deletar/<?//=$s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este fornecedor?')">Excluir</a> -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
$content = ob_get_clean();
$title = "Fornecedores";
include __DIR__ . '/../layouts/main.php';
