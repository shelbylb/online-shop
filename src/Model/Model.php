<?php

namespace Model;

abstract class Model
{
    protected \PDO $PDO;

    public function __construct(){
        $this-> PDO = new \PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
    }

    abstract protected function getTableName(): string;

}