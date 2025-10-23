<?php
namespace App\Models;

abstract class BaseModel
{
    protected static function db()
    {
        return \db(); // helper definido em functions.php que retorna clDB::getInstance()
    }
}
