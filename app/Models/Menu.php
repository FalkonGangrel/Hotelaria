<?php

namespace App\Models;

use App\Core\clDB;

class Menu
{
    private clDB $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getMenusByRole(int $roleId): array
    {
        $sql = "
            SELECT m.*
            FROM menus m
            JOIN role_menu rm ON rm.menu_id = m.id
            WHERE rm.role_id = :role_id
                AND rm.can_view = TRUE
                AND m.active = TRUE
            ORDER BY COALESCE(m.parent_id, 0), m.sort_order ASC
        ";
        $this->db->query($sql, ["role_id" => $roleId]);
        return $this->db->fetchAll();
    }
}
