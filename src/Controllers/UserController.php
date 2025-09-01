<?php

namespace Controllers;

use DTO\AuthDTO;
use Model\User;
use Request\RegisrateRequest;
use Request\LoginRequest;
use Request\EditProfileRequest;


class UserController extends BaseController
{

    private User $userModel;

    public function __construct()
    {
        parent:: __construct();
        $this->userModel = new User();
    }

    public function getRegistrate()
    {
        if ($this->authService->check()) {
            header('Location: /catalog');
        }

        require_once '../Views/registration_form.php';
    }

    public function registrate(RegisrateRequest $request)
    {

        $error = $request->validate();

        if (empty($error)) {

            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            $password = password_hash($password, PASSWORD_DEFAULT);

            $this->userModel->addUser($name, $email, $password);

            //if(!$this->authService->check()){
            //$_SESSION['userId'] = $this->userModel->getId();
            //header('Location: /catalog');}


            header('Location: /login');
        }


        require_once '../Views/registration_form.php';


    }




    public function getLogin()
    {
        session_start();
        if ($this->authService->check()) {
            header('Location: /catalog');
        }

        require_once '../Views/login_form.php';
    }

    public function login(LoginRequest $request)
    {
        $errors = $request->validate();


        if (empty($errors)) {

            $dto = new AuthDTO($request->getEmail(), $request->getPassword());

            $result = $this->authService->auth($dto);

            if ($result) {

                header('Location: /catalog');
                exit();
            } else {
                $errors['autorization'] = "Неверный логин или пароль";
            }
        }

        require_once '../Views/login_form.php';
    }





    public function getProfile()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            require_once '../Views/profile.php';
        } else {

            header('Location: /login');
        }
    }


    public function getEditProfile()
    {
        if ($this->authService->check()) {
            require_once '../Views/edit-profile.php';
        } else {
            header('Location: /login_form.php');
        }
    }

    public function editProfile(EditProfileRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();

            $error = $request->validate();

            if (empty($error)) {
                $name = $request->getName();
                $email = $request->getEmail();
                $userId = $user->getId();


                if ($name !== $user->getName()) {

                    $this->userModel->updateName($name);
                }

                if (!empty($email) && $email !== $user->getEmail()) {
                    $this->userModel->updateEmail($email);

                }

                header('Location: /profile');
                exit;
            }

            require_once '../Views/edit-profile.php';
        } else{
            header("Location: /login_form.php");
            exit();
        }
    }




    public function logout()
    {
        $this->authService->logout();
        header("Location: /catalog");
        exit();

    }


}