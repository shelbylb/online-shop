<?php

namespace DTO;

class AuthDTO
{

    public function __construct(
        private string $email,
        private string $password,
        private string $name)
    {

    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }




}