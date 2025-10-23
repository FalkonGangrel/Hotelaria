<?php
// app/Helpers/functions.php
session_start();

use App\Core\clDB;

// Verifica se a função ainda não foi declarada
if (!function_exists('env')) {
    /**
     * Recupera uma variável de ambiente com valor padrão opcional.
     *
     * @param string $key     Nome da variável de ambiente.
     * @param mixed  $default Valor padrão retornado caso a variável não exista.
     * @return mixed
     */
    function env(string $key, $default = null) {
        // Verifica se a variável está definida no ambiente
        if (array_key_exists($key, $_ENV)) return $_ENV[$key];

        // Verifica se a chave está definida no ambiente e retorna o valor
        $val = getenv($key);
        if ($val !== false) return $val;

        // Retorna valor padrão caso não esteja definida
        return $default;
    }
}

if (!function_exists('view')) {
    /**
     * Carrega uma view e passa variáveis para ela.
     */
    function view(string $path, array $data = [])
    {
        extract($data);

        // Corrige separador de diretório e adiciona extensão .php
        $viewFile = __DIR__ . '/../Views/' . str_replace('.', '/', $path) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View não encontrada: {$viewFile}");
        }

        require $viewFile;
    }
}

/**
 * Retorna a instância única de clDB (wrapper)
 * Uso: $db = db(); $db->query(...);
 */
function db(): \App\Core\clDB {
    // usa a instância criada em init.php
    if (!empty($GLOBALS['db']) && $GLOBALS['db'] instanceof \App\Core\clDB) {
        return $GLOBALS['db'];
    }
    // fallback: cria nova instância a partir das env
    return \App\Core\clDB::getInstance([
        'driver' => env('DB_DRIVER', 'my_driver'),
        'host'   => env('DB_HOST', 'my_host'),
        'port'   => env('DB_PORT', 'my_port'),
        'name'   => env('DB_NAME', 'my_db'),
        'user'   => env('DB_USER', 'my_user'),
        'pass'   => env('DB_PASS', 'my_pass')
    ]);
}

function redirect(string $url) {
    header('Location: ' . $url);
    exit;
}


function generate_uuid(): string {
    // simples fallback se ramsey/uuid não estiver instalado
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes(16));
    }
    return uniqid('', true);
}

function logErro($mensagem) {
    $logFile = __DIR__ . '/../../storage/logs/errors.log';;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $mensagem" . PHP_EOL, FILE_APPEND);
}