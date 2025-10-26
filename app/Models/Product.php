<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = 'products';

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $db = static::db();
        $db->query($sql);
        return $db->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
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
        $sql = "INSERT INTO {$this->table} (sku, title, description, price, active)
                VALUES (:sku, :title, :description, :price, :active) RETURNING id";
        $db = static::db();
        $params = [
            ':sku' => $data['sku'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':active' => $data['active']
        ];
        $db->query($sql, $params);
        $row = $db->fetchArray();
        return $row['id'] ?? false;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET sku = :sku, title = :title, description = :description, price = :price, active = :active, updated_at = now()
                WHERE id = :id";
        $db = static::db();
        $params = [
            ':sku' => $data['sku'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':active' => $data['active'],
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }

    public function delete(int $id): bool
    {
        // Soft delete (marca como inativo)
        $sql = "UPDATE {$this->table} SET active = false, updated_at = now() WHERE id = :id";
        $db = static::db();
        $params = [
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }

    public function reactivate(int $id): bool
    {
        // Soft delete (marca como inativo)
        $sql = "UPDATE {$this->table} SET active = true, updated_at = now() WHERE id = :id";
        $db = static::db();
        $params = [
            ':id' => $id
        ];
        return $db->query($sql, $params);
    }
}
