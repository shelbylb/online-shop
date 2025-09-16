<?php

namespace Service\Auth;

use DTO\AuthDTO;
use Model\User;

class AuthCookieService implements AuthInterface
{
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function check():bool
    {
        return isset($_COOKIE['userId']);

    }


    public function getCurrentUser(): ?User
    {

        if($this->check()){
            $userId = $_COOKIE['userId'];

            return $this->userModel->getById($userId);
        } else {
            return null;
        }

    }


    public function auth(AuthDTO $data) :bool
    {

        $user = $this->userModel->getByEmail($data->getEmail());


        $errors = [];
        if ($user === null)
        {
            return false;
        } else {
            $passwordDB = $user->getPassword();


            if (password_verify($data->getPassword(), $passwordDB)) {
                setcookie("userId", $user->getId(), time() + (86400 * 30), "/");

                return true;
            } else {
                return false;
            }
        }


    }

    public function logout()
    {
        setcookie("userId", "", time() - (86400 * 30), "/");
        unset($_COOKIE["userId"]);

    }

}