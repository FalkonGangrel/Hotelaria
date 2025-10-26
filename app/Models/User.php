<?php

namespace App\Models;

class User extends BaseModel
{
    public static function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE login = :email LIMIT 1";
        $db = static::db();
        $params = [
            ':email' => $email
        ];
        $db->query($sql, $params);
        $row = $db->fetchArray();
        return $row ?: null;
    }

    public function all()
    {
        $sql = "SELECT * FROM users ORDER BY nome ASC";
        $db = static::db();
        $db->query($sql);
        return $db->fetchAll();
    }
}
