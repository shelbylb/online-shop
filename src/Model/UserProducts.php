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


    protected function getTableName(): string{
        return 'user_products';
    }


    public function getAllUserProductsByUserId(int $userId): array
    {
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE user_id = " . $userId);
        $userProducts = $stmt->fetchAll();

        if ($userProducts === []){
            return [];
        }

        $array = [];

        foreach ($userProducts as $userProduct){
            $productObg = new self();
            $productObg->id = $userProduct['id'];
            $productObg->userId = $userProduct['user_id'];
            $productObg->productId = $userProduct['product_id'];
            $productObg->amount = $userProduct['amount'];

            $array[] = $productObg;

        }

        return $array;

    }


    public function checkProduct(int $userId, int $productId): self | null
    {

        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE (product_id = :productId AND user_id = :userId)");
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

    public function add(int $userId, int $productId, int $amount)
    {
        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, amount) 
        VALUES (:userId, :productId, :amount)");

        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
    }

    public function update(int $userId, int $productId, int $amount)
    {
        $stmt = $this->PDO->prepare("UPDATE {$this->getTableName()} SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);

    }

    public function deleteByUserId(int $userId){
        $stmt = $this->PDO->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }

    public function deleteProduct(int $userId, int $productId){
        $stmt = $this->PDO->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :userId AND product_id = :productId");
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