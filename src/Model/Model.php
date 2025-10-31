<?php

namespace Model;

abstract class Model
{
    protected static \PDO $PDO;

    public static function getPDO(): \PDO
    {
        static::$PDO = new \PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
        return static::$PDO;
    }

    abstract static protected function getTableName(): string;

}