<?php

namespace Request;

class HandleCheckOutRequest
{
    public function __construct(private array $data)
    {

    }

    public function getContactName(): string
    {
        return $this->data["contact_name"];
    }

    public function getContactPhone(): string
    {
        return $this->data["contact_phone"];
    }

    public function getComment(): string
    {
        return $this->data["comment"];
    }

    public function getAddress(): string
    {
        return $this->data["address"];
    }



    public function validate(): array
    {

        $errors = [];

        if (isset($this->data['contact_name'])) {

            $name = $this->data['contact_name'];
            if (strlen($name) < 2) {
                $errors['name'] = 'Имя должно быть больше двух символов';
            }
        } else {
            $errors['name'] = 'Поле  должно быть заполнено';
        }


        if (isset($this->data['contact_phone'])) {
            $contactPhone = $this->data["contact_phone"];
            if (strlen($contactPhone) !== 11) {
                $errors['contact_phone'] = 'Номер должен состоять из 11 симовлов';

            }
        } else {
            $errors['contact_phone'] = 'Поле  должно быть заполнено';
        }

        if (!isset($this->data['address'])) {


            $errors['address'] = 'Поле  должно быть заполнено';
        }

        return $errors;
    }

}