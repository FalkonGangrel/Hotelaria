<?php

namespace App\Models;

use App\Core\clDB;

class ProductSupplier
{
    private clDB $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function link(int $productId, int $supplierId): bool
    {
        $sql = "INSERT INTO product_supplier (product_id, supplier_id) VALUES (?, ?)";
        return $this->db->query($sql, $productId, $supplierId);
    }

    public function unlink(int $productId, int $supplierId): bool
    {
        $sql = "DELETE FROM product_supplier WHERE product_id = ? AND supplier_id = ?";
        return $this->db->query($sql, $productId, $supplierId);
    }

    public function getSuppliersForProduct(int $productId): array
    {
        $sql = "SELECT s.* FROM suppliers s
                JOIN product_supplier ps ON ps.supplier_id = s.id
                WHERE ps.product_id = ?";
        $this->db->query($sql, $productId);
        return $this->db->fetchAll();
    }
}
