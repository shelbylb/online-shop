<?php

namespace Request;
use Model\User;

class EditProfileRequest
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


    public function validate(): array
    {
        $error = [];

        if (isset($this->data['name'])) {

            $name = $this->data["name"];
            if (!empty($email) && strlen($name) < 2) {
                $error['name'] = 'Имя должно быть больше двух символов';
            }
        }


        if (isset($this->data['email'])) {
            $email = $this->data["email"];
            if (!empty($email) && strlen($email) < 2) {
                $error['email'] = 'Email должно быть больше двух символов';
            } elseif (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $error['email'] = 'Email некорректный';
            } else {
                $user = $this->userModel->getByEmail($email);

                $userId = $_SESSION['userId'];

                if ($user !== null) {
                    if ($user->getId() !== $userId) {
                        $error['email'] = 'Email уже зарегистрирован';
                    }
                }
            }

        }
        return $error;
    }



}