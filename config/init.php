<?php
use App\Core\clDotEnv;
use App\Core\clDB;

require_once __DIR__ . '/../vendor/autoload.php';

// Carrega .env via sua clDotEnv (ou use phpdotenv)
if($_SERVER['SERVER_NAME'] === 'projetos.hotelaria'){
    $fEnv = '/../.envh';
} else {
    $fEnv = '/../.env';
}
$dotenv = new clDotEnv(__DIR__ . $fEnv);
$dotenv->load();

// --- Normaliza chaves (aceita DB_USERNAME ou DB_USER, etc.)
if (!isset($_ENV['DB_USER']) && isset($_ENV['DB_USERNAME'])) {
    $_ENV['DB_USER'] = $_ENV['DB_USERNAME'];
}
if (!isset($_ENV['DB_PASS']) && isset($_ENV['DB_PASSWORD'])) {
    $_ENV['DB_PASS'] = $_ENV['DB_PASSWORD'];
}
if (!isset($_ENV['DB_NAME']) && isset($_ENV['DB_DATABASE'])) {
    $_ENV['DB_NAME'] = $_ENV['DB_DATABASE'];
}
if (!isset($_ENV['DB_DRIVER'])) {
    $_ENV['DB_DRIVER'] = $_ENV['DB_DRIVER'] ?? 'pgsql';
}
if (!isset($_ENV['DB_HOST'])) {
    $_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
}
if (!isset($_ENV['DB_PORT'])) {
    $_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? '5432';
}

// Inicia sessão cedo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cria a instância única e a expõe em $GLOBALS['db'] via clDB::getInstance()
try {
    $db = \App\Core\clDB::getInstance([
        'driver' => $_ENV['DB_DRIVER'],
        'host'   => $_ENV['DB_HOST'],
        'port'   => $_ENV['DB_PORT'],
        'name'   => $_ENV['DB_NAME'],
        'user'   => $_ENV['DB_USER'],
        'pass'   => $_ENV['DB_PASS'],
        'charset'=> $_ENV['DB_CHARSET'] ?? 'utf8'
    ]);
} catch (\Exception $e) {
    // Mostra mensagem simplificada em dev; em prod, log e mensagem amigável
    echo "Erro de configuração do banco: " . $e->getMessage();
    exit;
}

$GLOBALS['db'] = $db;
