<?php

namespace Model;

class UserProducts extends Model
{
    private int $id;
    private int $amount;
    private int $totalSum;
    private int $productId;
    private Product $product;
    private int $userId;


    protected static function getTableName(): string{
        return 'user_products';
    }


    public static function getAllUserProductsByUserId(int $userId): array
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->query("SELECT * FROM $tableName WHERE user_id = " . $userId);
        $userProducts = $stmt->fetchAll();

        if ($userProducts === []){
            return [];
        }

        $array = [];

        foreach ($userProducts as $userProduct){
            $productObj = new self();
            $productObj->id = $userProduct['id'];
            $productObj->userId = $userProduct['user_id'];
            $productObj->productId = $userProduct['product_id'];
            $productObj->amount = $userProduct['amount'];

            $array[] = $productObj;

        }

        return $array;

    }

    public static function getAllByUserIdWithProducts(int $userId): array
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->query
        ("SELECT up.id as up_id, up.*, p.id as p_id, p.* FROM $tableName up INNER JOIN products p ON up.product_id = p.id WHERE up.user_id = $userId");
        $userProducts = $stmt->fetchAll(); // up.id as up_id, up.*, p.id as p_id, p.* алиасы для столбцов меняют названия для столбцов с одинаковым названием


        $array = [];

        foreach ($userProducts as $userProduct){

            $obj = new self();
            $array[] = $obj->createObj($userProduct);

        }

        return $array;

    }

    public static function createObj(array $userProduct): self|array
    {

        if ($userProduct === [])
        {
            return [];
        }

        $productObj = new self();
        $productObj->id = $userProduct['up_id'];
        $productObj->userId = $userProduct['user_id'];
        $productObj->productId = $userProduct['product_id'];
        $productObj->amount = $userProduct['amount'];

        $productData = [
            'id' => $userProduct['p_id'],
            'name' => $userProduct['name'],
            'description' => $userProduct['description'],
            'price' => $userProduct['price'],
            'image_url' => $userProduct['image_url']

        ];


        $product = Product::createObj($productData);
        $productObj->setProduct($product);

        return $productObj;

    }


    public static function checkProduct(int $userId, int $productId): self | null
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("SELECT * FROM $tableName WHERE (product_id = :productId AND user_id = :userId)");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();


        if ($data === false){
            return null;
        }

        $productObg = new self();
        $productObg->id = $data['id'];
        $productObg->userId = $data['user_id'];
        $productObg->productId = $data['product_id'];
        $productObg->amount = $data['amount'];

        return $productObg;
    }

    public static function add(int $userId, int $productId, int $amount)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("INSERT INTO $tableName (user_id, product_id, amount) 
        VALUES (:userId, :productId, :amount)");

        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
    }

    public static function update(int $userId, int $productId, int $amount)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("UPDATE $tableName SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);

    }

    public static function deleteByUserId(int $userId)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("DELETE FROM $tableName WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }

    public static function deleteProduct(int $userId, int $productId)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("DELETE FROM $tableName WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['userId' => $userId, 'productId' => $productId]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getTotalSum(): int
    {
        return $this->totalSum;
    }

    public function setTotalSum(int $totalSum): void
    {
        $this->totalSum = $totalSum;
        //print_r($totalSum);
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }















}