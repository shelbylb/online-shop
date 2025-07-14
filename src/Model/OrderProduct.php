<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;


    public function create(string $orderId, string $productId, string $amount)
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO order_products ( order_id, product_id, amount) 
                   VALUES (:orderId, :productId, :amount)"
        );

        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }

    /**
     * @param int $orderId
     * @return OrderProduct[]|null
     */
    public function getAllByOrderId(int $orderId): array|null
    {
        $stmt = $this->PDO->prepare('SELECT * FROM order_products WHERE order_id = :orderId');
        $stmt->execute(['orderId' => $orderId]);
        $oderProducts = $stmt->fetchAll();

        if ($oderProducts === []) {
            return null;
        }

        $array = [];

        foreach ($oderProducts as $oderProduct) {
            $orderObj = new self();
            $orderObj->id = $oderProduct['id'];
            $orderObj->orderId = $oderProduct['order_id'];
            $orderObj->productId = $oderProduct['product_id'];
            $orderObj->amount = $oderProduct['amount'];
            $array[] = $orderObj;
        }

        return $array;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }



}