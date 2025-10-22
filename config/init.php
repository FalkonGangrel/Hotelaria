<?php
use App\Core\clDotEnv;
use App\Core\clDB;

require_once __DIR__ . '/../vendor/autoload.php';

// 1️⃣ Carregar variáveis de ambiente
$dotenv = new clDotEnv(__DIR__ . '/../.env');
$dotenv->load();

// 2️⃣ Configurar conexão com o banco
$driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_DATABASE'] ?? 'meu_comercio';
$user = $_ENV['DB_USERNAME'] ?? 'postgres';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$port = $_ENV['DB_PORT'] ?? '5432';

// 3️⃣ Criar instância de conexão usando sua classe personalizada
try {
    $db = new clDB($host, $user, $pass, $dbname, 'utf8', $driver, $port);
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// 4️⃣ Disponibilizar globalmente
$GLOBALS['db'] = $db;
