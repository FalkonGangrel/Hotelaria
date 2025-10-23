<?php

namespace App\Models;

class User extends BaseModel
{
    public static function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE login = :email LIMIT 1";
        $db = static::db();
        $db->query($sql, [':email' => $email]);
        $row = $db->fetchArray();
        return $row ?: null;
    }
}
