<?php
use App\Helpers\Auth;

$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Painel - Empresa'); ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== Layout base ===== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .content {
            flex-grow: 1;
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
            margin-left: <?= $user ? '250px' : '0' ?>; /* Compensa sidebar */
        }

        /* ===== Header ===== */
        header {
            background-color: #0d6efd;
            color: white;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1001;
            height: 60px;
        }

        header h4 {
            margin: 0;
        }

        header .btn {
            font-size: 0.9rem;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            background-color: #ffffff;
            width: 250px;
            height: 100vh;
            border-right: 1px solid #dee2e6;
            padding: 1rem;
            position: fixed;
            top: 60px; /* abaixo do header fixo */
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar.hidden {
            left: -250px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar a {
            display: block;
            color: #333;
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 0.4rem;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .sidebar a.active {
            background-color: #e7f1ff;
            border-left: 4px solid #0d6efd;
            color: #0d6efd;
            font-weight: 600;
        }

        .sidebar .bi {
            font-size: 1.1rem;
        }

        .sidebar ul ul {
            margin-top: 0.25rem;
            padding-left: 1.25rem;
            border-left: 1px dashed #dee2e6;
        }

        .sidebar ul ul a {
            font-size: 0.95rem;
        }

        /* ===== Footer ===== */
        footer {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            padding: 0.5rem;
            position: relative;
            z-index: 900;
        }

        /* ===== Responsivo ===== */
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                top: 60px;
                height: calc(100vh - 60px);
                left: -250px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="d-flex align-items-center">
        <?php if ($user): ?>
            <button class="toggle-btn d-lg-none me-3" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        <?php endif; ?>
        <h4 class="m-0">Painel - Empresa</h4>
    </div>

    <?php if ($user): ?>
        <nav>
            <a href="<?= $_ENV['APP_BASE'] ?>/logout" class="btn btn-light btn-sm">Sair</a>
        </nav>
    <?php endif; ?>
</header>

<div class="layout">
    <?php if ($user): ?>
        <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <div class="content">
