<?php

namespace DTO;
//use Model\User;

class AuthDTO
{

    public function __construct(
        private string $email,
        private string $password,
        /*private User   $userId*/)
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



}