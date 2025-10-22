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
        $this->db->query($sql, $id);
        $row = $this->db->fetchArray();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
        return $this->db->query($sql, $data['name'], $data['description'], $data['price']);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
        return $this->db->query($sql, $data['name'], $data['description'], $data['price'], $id);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = ?";
        return $this->db->query($sql, $id);
    }

    public function suppliers(int $productId): array
    {
        $sql = "SELECT s.* FROM suppliers s
                JOIN product_supplier ps ON ps.supplier_id = s.id
                WHERE ps.product_id = ?";
        $this->db->query($sql, $productId);
        return $this->db->fetchAll();
    }
}
