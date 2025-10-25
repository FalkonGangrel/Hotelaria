<?php
/** @var string $title */
/** @var string $content */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Painel - Empresa'); ?></title>

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos globais -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #0d6efd;
            color: white;
            padding: 1rem;
        }
        footer {
            background-color: #0d6efd;
            color: white;
            padding: 0.5rem;
            text-align: center;
            margin-top: 2rem;
        }
        .container-main {
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<header>
    <div class="container d-flex justify-content-between align-items-center">
        <h3 class="m-0">Empresa - Painel</h3>
    </div>
</header>
