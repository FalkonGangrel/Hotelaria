<?php
    if(isset($_SESSION['user'])){
        include __DIR__ . '/../layouts/header.php';
    } else {
        include __DIR__ . '/../layouts/header2.php';
    }
?>

<main class="container container-main">
    <?= $content ?? '' ?>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
