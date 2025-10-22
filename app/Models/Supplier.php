<?php

namespace App\Models;

use App\Core\clDB;

class Supplier
{
    private clDB $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function all(): array
    {
        $sql = "SELECT * FROM suppliers ORDER BY id DESC";
        $this->db->query($sql);
        return $this->db->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM suppliers WHERE id = ?";
        $this->db->query($sql, $id);
        $row = $this->db->fetchArray();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO suppliers (name, email, phone) VALUES (?, ?, ?)";
        return $this->db->query($sql, $data['name'], $data['email'], $data['phone']);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE suppliers SET name = ?, email = ?, phone = ? WHERE id = ?";
        return $this->db->query($sql, $data['name'], $data['email'], $data['phone'], $id);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM suppliers WHERE id = ?";
        return $this->db->query($sql, $id);
    }
}
