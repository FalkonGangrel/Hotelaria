<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class clDB
{
    private PDO $pdo;
    private int $queryCount = 0;
    private string $class_error = 'clDB';
    private string $log_path = __DIR__ . '/../../storage/logs/db_errors.log';
    private ?PDOStatement $stmt = null;

    /** singleton instance per-request */
    private static ?clDB $instance = null;

    /** config snapshot */
    private array $config;

    /**
     * Construtor privado opcional — a criação preferida é via getInstance()
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $driver = $config['driver'] ?? 'pgsql';
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? ($driver === 'pgsql' ? '5432' : '3306');
        $name = $config['name'] ?? '';
        $user = $config['user'] ?? '';
        $pass = $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8';

        if ($driver === 'pgsql') {
            $dsn = "pgsql:host={$host};port={$port};dbname={$name}";
        } else {
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // registra e relança para o caller tratar (init.php irá capturar)
            $this->logError("Erro ao conectar ao banco de dados: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retorna instancia singleton (cria se necessário)
     * $config é usado apenas na primeira chamada.
     */
    public static function getInstance(array $config = []): clDB
    {
        if (self::$instance === null) {
            if (empty($config)) {
                // tenta criar a partir de env
                $config = [
                    'driver' => $_ENV['DB_DRIVER'] ?? 'pgsql',
                    'host'   => $_ENV['DB_HOST'] ?? 'localhost',
                    'port'   => $_ENV['DB_PORT'] ?? '5432',
                    'name'   => $_ENV['DB_NAME'] ?? ($_ENV['DB_DATABASE'] ?? ''),
                    'user'   => $_ENV['DB_USER'] ?? ($_ENV['DB_USERNAME'] ?? ''),
                    'pass'   => $_ENV['DB_PASS'] ?? ($_ENV['DB_PASSWORD'] ?? ''),
                    'charset'=> $_ENV['DB_CHARSET'] ?? 'utf8'
                ];
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /** expõe o PDO (quando necessário) */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    /** wrapper simples para prepare-execute */
    public function query(string $sql, ...$params): bool
    {
        try {
            $this->stmt = $this->pdo->prepare($sql);
            if ($this->stmt === false) {
                $this->logError("Erro ao preparar a query: {$sql}");
                return false;
            }
            $success = $this->stmt->execute($params);
            $this->queryCount++;
            return $success;
        } catch (PDOException $e) {
            $this->logError("Erro na query: {$sql} - " . $e->getMessage());
            return false;
        }
    }

    /** prepara e retorna PDOStatement para uso avançado */
    public function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function fetchAll(): array
    {
        return $this->stmt ? $this->stmt->fetchAll() : [];
    }

    public function fetchArray(): array
    {
        return $this->stmt ? ($this->stmt->fetch() ?: []) : [];
    }

    public function numRows(): int
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }

    public function affectedRows(): int
    {
        return $this->numRows();
    }

    public function lastInsertID(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function queryCount(): int
    {
        return $this->queryCount;
    }

    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    /**
     * centraliza o log de erros em arquivo
     */
    public function logError(string $mensagem): void
    {
        $data = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'localhost';
        $log = "[{$data}] [{$ip}] [{$this->class_error}] {$mensagem}\n";

        // Garante que o diretório existe
        $dir = dirname($this->log_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($this->log_path) && filesize($this->log_path) > 5 * 1024 * 1024) {
            $timestamp = date("Ymd_His");
            rename($this->log_path, $this->log_path . ".{$timestamp}.log");
        }

        file_put_contents($this->log_path, $log, FILE_APPEND);
    }
}
