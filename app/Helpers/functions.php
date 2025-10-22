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
    function env(string $key, $default = null)
    {
        // Verifica se a variável está definida no ambiente
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        // Retorna valor padrão caso não esteja definida
        return $default;
    }
}


function view(string $path, array $data = []) {
    extract($data);
    require __DIR__ . "/../Views/{$path}";
}


function redirect(string $url) {
    header('Location: ' . $url);
    exit;
}


function auth(): ?array {
    return $_SESSION['user'] ?? null;
}


function assert_role(array $roles = []) {
    $u = auth();
    if (!$u) {
        http_response_code(401); echo 'Unauthorized'; exit;
    }
    if (!empty($roles) && !in_array($u['role'], $roles)) {
        http_response_code(403); echo 'Forbidden'; exit;
    }
}


function generate_uuid(): string {
    // simples fallback se ramsey/uuid não estiver instalado
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes(16));
    }
    return uniqid('', true);
}

function db(): clDB
{
    return new clDB(
        $_ENV['DB_HOST'] ?? 'my_host',
        $_ENV['DB_USER'] ?? 'my_user',
        $_ENV['DB_PASS'] ?? 'my_pass',
        $_ENV['DB_NAME'] ?? 'my_db'
    );
}

function logErro($mensagem) {
    $logFile = __DIR__ . '/../../storage/logs/errors.log';;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $mensagem" . PHP_EOL, FILE_APPEND);
}