<?php

namespace Request;

class GetFeedbackRequest
{
    public function __construct(private array $data)
    {

    }

    public function getProductId(): int
    {
        return $this->data["productId"];
    }

}