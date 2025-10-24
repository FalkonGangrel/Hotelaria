<?php

namespace App\Models;

use App\Core\clDB;

class Product
{
    private clDB $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function all(): array
    {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $this->db->query($sql);
        return $this->db->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $this->db->query($sql, [$id]);
        $row = $this->db->fetchArray();
        return $row ?: null;
    }

    public function create(array $data): int|false
    {
        $sql = "INSERT INTO products (sku, title, description, price, active) 
                VALUES (?, ?, ?, ?, ?) RETURNING id";
        $this->db->query($sql, [$data['sku'], $data['title'], $data['description'], $data['price'], $data['active']]);
        $row = $this->db->fetchArray();
        return $row['id'] ?? false;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products 
                SET sku = ?, title = ?, description = ?, price = ?, active = ?, updated_at = now()
                WHERE id = ?";
        return $this->db->query($sql, [$data['sku'], $data['title'], $data['description'], $data['price'], $data['active'], $id]);
    }

    public function delete(int $id): bool
    {
        // Soft delete (marca como inativo)
        $sql = "UPDATE products SET active = false, updated_at = now() WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function reactivate(int $id): bool
    {
        // Soft delete (marca como inativo)
        $sql = "UPDATE products SET active = true, updated_at = now() WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
