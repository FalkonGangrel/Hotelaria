<?php
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <small class="text-muted">Visão geral do sistema</small>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Produtos Ativos</h5>
                <h2 class="text-success">124</h2>
                <p class="text-muted mb-0">+12% em relação ao mês anterior</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Pedidos Recentes</h5>
                <h2 class="text-primary">58</h2>
                <p class="text-muted mb-0">Últimos 7 dias</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Faturamento Mensal</h5>
                <h2 class="text-warning">R$ 48.750</h2>
                <p class="text-muted mb-0">Janeiro 2025</p>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <!-- Gráfico 1 -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Vendas por Mês</div>
            <div class="card-body">
                <canvas id="chartVendas"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico 2 -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Produtos Mais Vendidos</div>
            <div class="card-body">
                <canvas id="chartProdutos"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico 3 -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Distribuição de Pedidos por Status</div>
            <div class="card-body">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <!-- Tabela 1 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Top Clientes</div>
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Pedidos</th>
                            <th>Total (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Maria Silva</td><td>12</td><td>8.540,00</td></tr>
                        <tr><td>João Pereira</td><td>9</td><td>6.230,00</td></tr>
                        <tr><td>Ana Souza</td><td>8</td><td>5.970,00</td></tr>
                        <tr><td>Pedro Lima</td><td>7</td><td>4.410,00</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabela 2 -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Estoque Crítico</div>
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Toalha de Banho Premium</td><td>3</td><td><span class="badge bg-danger">Crítico</span></td></tr>
                        <tr><td>Sabonete Natural</td><td>7</td><td><span class="badge bg-warning">Baixo</span></td></tr>
                        <tr><td>Shampoo Vegano</td><td>15</td><td><span class="badge bg-success">Ok</span></td></tr>
                        <tr><td>Condicionador Hidratante</td><td>5</td><td><span class="badge bg-danger">Crítico</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxVendas = document.getElementById('chartVendas').getContext('2d');
new Chart(ctxVendas, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Vendas (R$)',
            data: [12000, 14500, 13800, 16000, 19000, 18750],
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.2)',
            fill: true,
            tension: 0.3
        }]
    }
});

const ctxProdutos = document.getElementById('chartProdutos').getContext('2d');
new Chart(ctxProdutos, {
    type: 'bar',
    data: {
        labels: ['Toalha', 'Sabonete', 'Shampoo', 'Condicionador', 'Velas'],
        datasets: [{
            label: 'Unidades Vendidas',
            data: [320, 280, 250, 180, 150],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
        }]
    }
});

const ctxStatus = document.getElementById('chartStatus').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Entregues', 'Pendentes', 'Cancelados'],
        datasets: [{
            data: [65, 25, 10],
            backgroundColor: ['#198754', '#ffc107', '#dc3545']
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

<?php
$content = ob_get_clean();
$title = "Dashboard";
include __DIR__ . '/../layouts/main.php';
