<?php

namespace App\Models;

class Supplier extends BaseModel
{
    protected $table = 'suppliers';

    public function all()
    {
        $sql = "SELECT s.*, u.name AS user_name
                FROM {$this->table} s
                LEFT JOIN users u ON s.user_id = u.id
                ORDER BY s.company_name ASC";
        $db = static::db();
        $db->query($sql);
        return $db->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $db = static::db();
        $params = [
            ':id' => $id
        ];
        $db->query($sql, $params);
        $row = $db->fetchArray();
        return $row ?: null;
    }

    public function create(array $data): int|false
    {
        $sql = "INSERT INTO {$this->table} (user_id, company_name)
                VALUES (:user_id, :company_name) RETURNING id";
        $db = static::db();
        $params = [
            ':company_name' => $data['company_name'],
            ':user_id' => $_SESSION['user']['id']
        ];
        $db->query($sql, $params);
        $row = $db->fetchArray();
        return $row['id'] ?? false;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET company_name = :company_name WHERE id = :id";
        $db = static::db();
        $params = [
            ':company_name' => $data['company_name'],
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $db = static::db();
        $params = [
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }

    public function reactivate(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $db = static::db();
        $params = [
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }
}
