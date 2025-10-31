<?php

namespace Request;
use Model\User;
class RegisrateRequest
{
    private User $userModel;

    public function __construct(private array $data)
    {
        $this->userModel = new User();

    }

    public function getName(): string
    {
        return $this->data["name"];
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
        $error = [];

        if (isset($this->data['name'])) {

            $name = $this->data["name"];
            if (strlen($name) < 2) {
                $error['name'] = 'Имя должно быть больше двух символов';
            }
        } else {
            $error['name'] = 'Поле  должно быть заполнено';
        }


        if (isset($this->data['email'])) {
            $email = $this->data["email"];
            if (strlen($email) < 2) {
                $error['email'] = 'Email должно быть больше двух символов';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $error['email'] = 'Email некорректный';
            } else {

                $user = User::getByEmail($email);

                if ($user !== null) {
                    $error['email'] = 'Email уже зарегистрирован';
                }
            }

        } else {
            $error['email'] = 'Поле  должно быть заполнено';
        }


        if (isset($this->data['password'])) {
            $password = $this->data["password"];
            if (strlen($password) < 5) {
                $error['password'] = 'Пароль должен состоять минимум из пяти символов';

                $password_repeat = $this->data["password-repeat"];
                if ($password != $password_repeat) {
                    $error['password-repeat'] = 'пароли несовпадают';
                }
            }
        } else {
            $error['password'] = 'Поле  должно быть заполнено';
        }


        return $error;
    }




}