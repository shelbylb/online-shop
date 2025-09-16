<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;
    private int $sum;
    private Product $product;


    protected function getTableName(): string
    {
        return 'order_products';
    }


    public function create(string $orderId, string $productId, string $amount)
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO {$this->getTableName()} ( order_id, product_id, amount) 
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
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $oderProducts = $stmt->fetchAll();

       /* if ($oderProducts === []) {
            return null;
        }*/

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

    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }

    public function getSum(): int
    {
        return $this->sum;
    }


    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }




}