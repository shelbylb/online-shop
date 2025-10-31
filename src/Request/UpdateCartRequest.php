<?php

namespace Request;

class UpdateCartRequest
{
    private array $data;

    public function __construct(array $postData)
    {
        $this->data = $postData;
    }

    public function getProductId(): ?int
    {
        return $this->data['productId'] ?? null;
    }

    public function getAmount(): ?int
    {
        return $this->data['amount'] ?? null;
    }


}