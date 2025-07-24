<?php

namespace Service;

use Model\User;

class AuthService
{
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function check():bool
    {
        $this->startSession();
        return isset($_SESSION['userId']);

    }


    public function getCurrentUser(): ?User
    {
        $this->startSession();
        if($this->check()){
            $userId = $_SESSION['userId'];

            return $this->userModel->getById($userId);
        } else {
            return null;
        }

    }


    public function auth(string $email, string $password) :bool
    {

        $user = $this->userModel->getByEmail($email);


        $errors = [];
        if ($user === null)
        {
            return false;
        } else {
            $passwordDB = $user->getPassword();


            if (password_verify($password, $passwordDB)) {
                $this->startSession();
                $_SESSION['userId'] = $user->getId();

                return true;
            } else {
                return false;
            }
        }


    }

    public function logout()
    {
        $this->startSession();

        session_destroy();
    }


    private function startSession()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

    }

}