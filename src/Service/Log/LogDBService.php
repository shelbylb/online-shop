<?php

namespace Service\Log;

class LogDBService
{

    protected \PDO $PDO;

    public function __construct(){
        $this-> PDO = new \PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
    }

    public function log($exception, $data = null)
    {
        if ($data === null) {
            $stmt = $this->PDO->prepare("INSERT INTO logs (message, file, line) 
            VALUES (:message, :file, :line)");

            $stmt->execute([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        } else{
            $stmt = $this->PDO->prepare("INSERT INTO logs (message, file, line) 
            VALUES (:message, :file, :line)");

            $stmt->execute([
                'message' => $data . $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

    }

}