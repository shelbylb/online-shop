<?php

namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    protected function getTableName(): string{
        return 'users';
    }

    public function addUser(string $name, string $email, string $password)
    {
        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);


    }

    public function getByEmail(string $email): self|null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE email= :email");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();
        if ($user === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];

        return $obj;
    }

    public function getById(string  $userId): self|null
    {
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE id = " . $userId);

        $user = $stmt->fetch();
        if ($user === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];

        return $obj;
    }

    public function updateName( string $name)
    {
        $smt = $this->PDO->prepare("UPDATE {$this->getTableName()} SET name = :name WHERE id = " . $_SESSION['userId']);
        $smt->execute(['name' => $name]);

    }

    public function updateEmail(string $email)
    {
        $smt = $this->PDO->prepare("UPDATE {$this->getTableName()} SET email = :email WHERE id = " . $_SESSION['userId']);
        $smt->execute(['email' => $email]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }




}