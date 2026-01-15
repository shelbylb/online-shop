<?php

namespace Model;

class OrderProduct extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;
    private int $sum;
    private int $itemSum;
    private Product $product;


    protected static function getTableName(): string
    {
        return 'order_products';
    }


    public static function create(string $orderId, string $productId, string $amount)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare(
            "INSERT INTO $tableName ( order_id, product_id, amount) 
                   VALUES (:orderId, :productId, :amount)"
        );

        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'amount' => $amount]);
    }

    /**
     * @param int $orderId
     * @return OrderProduct[]|null
     */
    public static function getAllByOrderId(int $orderId): array
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $oderProducts = $stmt->fetchAll();

        if ($oderProducts === [])
        {
            return [];
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

    public static function createObj(array $orderProduct): self|array
    {

        if ($orderProduct === [])
        {
            return [];
        }

        $orderObj = new self();
        $orderObj->id = $orderProduct['op_id'];
        $orderObj->orderId = $orderProduct['order_id'];
        $orderObj->productId = $orderProduct['product_id'];
        $orderObj->amount = $orderProduct['amount'];

        $productData = [
            'id' => $orderProduct['p_id'],
            'name' => $orderProduct['name'],
            'description' => $orderProduct['description'],
            'price' => $orderProduct['price'],
            'image_url' => $orderProduct['image_url']

        ];


        $product = Product::createObj($productData);
        $orderObj->setProduct($product);

        return $orderObj;

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

    public function getItemSum(): int
    {
        return $this->itemSum;
    }

    public function setItemSum(int $itemSum): void
    {
        $this->itemSum = $itemSum;
    }




}