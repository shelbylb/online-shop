<?php

namespace Request;
use Model\User;

class LoginRequest
{
    private User $userModel;

    public function __construct(private array $data)
    {
        $this->userModel = new User();

    }


    public function getEmail(): string
    {
        return $this->data["email"];
    }

    public function getPassword(): string
    {
        return $this->data["password"];
    }

    public function validate(): array
    {
        $errors = [];
        if (!isset($this->data['email'])) {
            $errors['email'] = 'Поле должно  быть заполнено';
        }

        if (!isset($this->data['password'])) {
            $errors['password'] = 'Поле должно  быть заполнено';
        }

        return $errors;
    }

}