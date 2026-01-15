<?php

namespace Service\Auth;

use DTO\AuthDTO;
use Model\User;

class AuthSessionService implements AuthInterface
{


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

            return User::getById($userId);
        } else {
            return null;
        }

    }


    public function auth(AuthDTO $data) :bool
    {

        $user = User::getByEmail($data->getEmail());


        $errors = [];
        if ($user === null)
        {
            return false;
        } else {
            $passwordDB = $user->getPassword();


            if (password_verify($data->getPassword(), $passwordDB)) {
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

        if(session_status() !== PHP_SESSION_ACTIVE) {

            session_start();

        }

    }

}