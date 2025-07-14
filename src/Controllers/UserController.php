<?php

namespace Controllers;

use Model\User;

class UserController
{

    private User $userModel;

    public function __construct(){
        $this->userModel = new User();
    }

    public function getRegistrate()
    {

        session_start();
        if (isset($_SESSION['userId'])) {
            header('Location: /catalog');
        }

        require_once '../Views/registration_form.php';
    }
    public function registrate()
    {

        $error = $this -> validateRegistrate($_POST);

        if (empty($error)) {

            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["psw"];

            $password = password_hash($password, PASSWORD_DEFAULT);

            $this ->userModel ->addUser($name, $email, $password);


            header('Location: /login');
        }


        require_once '../Views/registration_form.php';


    }

    private function validateRegistrate(array $data): array
    {
        $error = [];

        if (isset($data['name'])) {

            $name = $data["name"];
            if (strlen($name) < 2) {
                $error['name'] = 'Имя должно быть больше двух символов';
            }
        } else {
            $error['name'] = 'Поле  должно быть заполнено';
        }


        if (isset($data['email'])) {
            $email = $data["email"];
            if (strlen($email) < 2) {
                $error['email'] = 'Email должно быть больше двух символов';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $error['email'] = 'Email некорректный';
            } else {

                $user = $this->userModel->getByEmail($email);

                if ($user !== null) {
                    $error['email'] = 'Email уже зарегистрирован';
                }
            }

        } else {
            $error['email'] = 'Поле  должно быть заполнено';
        }


        if (isset($data['psw'])) {
            $password = $data["psw"];
            if (strlen($password) < 5) {
                $error['psw'] = 'Пароль должен состоять минимум из пяти символов';

                $password_repeat = $data["psw-repeat"];
                if ($password != $password_repeat) {
                    $error['psw-repeat'] = 'пароли несовпадают';
                }
            }
        } else {
            $error['psw'] = 'Поле  должно быть заполнено';
        }


        return $error;
    }



    public function getLogin()
    {
        session_start();
        if (isset($_SESSION['userId'])) {
            header('Location: /catalog');
        }

        require_once '../Views/login_form.php';
    }
    public function login()
    {
        $errors = $this -> validateLogin($_POST);


        if (empty($errors))
        {
            $username = $_POST["username"];
            $password = $_POST["password"];

            $user = $this->userModel->getByEmail($username);


            $errors = [];
            if ($user === null) {
                $errors['username'] = "Неверный логин или пароль";
            } else {
                $passwordDB = $user->getPassword();


                if (password_verify($password, $passwordDB)) {
                    session_start();
                    $_SESSION['userId'] = $user->getId();

                    header('Location: /catalog');
                } else {
                    $errors['username'] = "Неверный логин или пароль";
                }
            }
        }

        require_once '../Views/login_form.php';
    }


    private function validateLogin(array $data): array
    {
        $errors = [];
        if (!isset($data['username'])){
            $errors['username'] = 'Поле должно  быть заполнено';
        }

        if (!isset($data['password'])){
            $errors['password'] = 'Поле должно  быть заполнено';
        }

        return $errors;
    }


    public function getProfile()
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
        } else {

            require_once '../Views/profile.php';
        }
    }


    public function getEditProfile()
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            header('Location: /login_form.php');
        } else {

            require_once '../Views/edit-profile.php';
        }
    }

    public function editProfile()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['userId'])){
            header("Location: /login_form.php");
            exit();
        }

        $error = $this -> validateEditProfile($_POST);

        if(empty($error))
        {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $userId = $_SESSION['userId'];


            $user = $this->userModel->getById($userId);

            if($name !== $user->getName())
            {

                $this->userModel->updateName($name);
            }

            if(!empty($email) && $email !== $user->getEmail())
            {
                $this->userModel->updateEmail($email);

            }

            header('Location: /profile');
            exit;
        }


        require_once '../Views/edit-profile.php';
    }


    private function validateEditProfile(array $data): array
    {
        $error = [];

        if (isset($data['name']))
        {

            $name = $data["name"];
            if (!empty($email) && strlen($name) < 2)
            {
                $error['name'] = 'Имя должно быть больше двух символов';
            }
        }


        if (isset($data['email']))
        {
            $email = $data["email"];
            if (!empty($email) && strlen($email) < 2)
            {
                $error['email'] = 'Email должно быть больше двух символов';
            } elseif (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false)
            {
                $error['email'] = 'Email некорректный';
            } else
            {
                $user = $this->userModel->getByEmail($email);

                $userId = $_SESSION['userId'];

                if($user !== null) {
                    if ($user->getId() !== $userId) {
                        $error['email'] = 'Email уже зарегистрирован';
                    }
                }
            }

        }
        return $error;
    }


    public function logout()
    {
        session_start();

        session_destroy();


        header("Location: /catalog");


    }




}