<?php
namespace App\Models;

use App\Core\clDB;

abstract class BaseModel
{
    protected static function db()
    {
        return new clDB(
            $_ENV['DB_HOST'] ?? 'my_host',
            $_ENV['DB_USER'] ?? 'my_user',
            $_ENV['DB_PASS'] ?? 'my_pass',
            $_ENV['DB_NAME'] ?? 'my_db'
        );
    }
}