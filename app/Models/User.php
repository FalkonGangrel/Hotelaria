<?php

namespace App\Models;

use App\Core\clDB;
use PDO;

class User extends BaseModel
{
    public static function findByEmail($email)
    {
        $db = static::db();
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $db->query($sql);
        $row = $db->fetchArray();
        return $row ?: null;
    }
}
