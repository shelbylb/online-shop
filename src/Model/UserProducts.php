<?php

namespace Model;

class UserProducts extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imageUrl;
    private int $userId;
    private int $productId;
    private int $amount;

    protected function getTableName(): string{
        return 'user_products';
    }


    public function getAllUserProductsByUserId(int $userId): array|null
    {
        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE user_id = " . $userId);
        $userProducts = $stmt->fetchAll();

        if ($userProducts === []){
            return null;
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

    public function getByAmount(int $userProduct): self|null
    {
        $stmt = $this->PDO->query('SELECT * FROM products WHERE id = '. $userProduct['product_id']);

        $product = $stmt->fetch();

        if ($product === false) {
            return null;
        }

        $obj = new self();
        $obj->id = $product['id'];
        $obj->name = $product['name'];
        $obj->description = $product['description'];
        $obj->price = $product['price'];
        $obj->imageUrl = $product['image_url'];

        return $obj;

    }

    public function checkProduct(int $productId, int $userId): self | null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if ($data === []){
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

        $stmt->execute(['userId' => $_SESSION['userId'], 'productId' => $productId, 'amount' => $amount]);
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }








}