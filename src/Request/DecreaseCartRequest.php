<?php

namespace Request;

class DecreaseCartRequest
{
    public function __construct(private array $data)
    {

    }

    public function getProductId(): int
    {
        return $this->data["productId"];
    }
    public function getAmount(): int
    {
        return $this->data["amount"];
    }

    public function validate()
    {
        $errors = [];


        if (isset($this->data['amount'])) {

            $amount = (int)$this->data['amount'];

            if ($amount < 0 || $amount > 100) {
                $errors['amount'] = 'Введите колличество товара от 1 до 100';
            }
        }

        return $errors;
    }

}