<?php
namespace App\Core;

/**
 * Classe personalizada para conexão com banco de dados usando PDO
 * Possui funcionalidades extras como log de erros, contagem de queries, e prepared statements
 */

use PDO;
use PDOException;
use PDOStatement;

class clDB
{
    private PDO $pdo;
    private int $queryCount = 0;
    private string $class_error = 'clDB';
    private string $log_path = __DIR__ . '/../../storage/logs/db_errors.log';
    private PDOStatement|false $stmt = false;

    public function __construct(string $host, string $usuario, string $senha, string $banco, string $charset = 'utf8mb4', string $driver = 'pgsql', string $port = '5432')
    {
        if ($driver === 'pgsql') {
            $dsn = "pgsql:host={$host};port={$port};dbname={$banco}";
        } else {
            $dsn = "mysql:host={$host};dbname={$banco};charset={$charset}";
        }
        
        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $usuario, $senha, $opcoes);
        } catch (PDOException $e) {
            $this->error("Erro ao conectar ao banco de dados: " . $e->getMessage());
            die("Erro ao conectar ao banco de dados.");
        }
    }

    public function query(string $sql, ...$params): bool
    {
        try {
            $this->stmt = $this->pdo->prepare($sql);
            if ($this->stmt === false) {
                $this->error("Erro ao preparar a query: {$sql}");
                return false;
            }
            $success = $this->stmt->execute($params);
            $this->queryCount++;
            return $success;
        } catch (PDOException $e) {
            $this->error("Erro na query: {$sql}\nErro: " . $e->getMessage());
            return false;
        }
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
        return $this->stmt ? $this->stmt->rowCount() : 0;
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

    private function error(string $mensagem): void
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
