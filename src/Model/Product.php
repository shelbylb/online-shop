<?php

namespace Model;



class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imageUrl;

    public function catalog() : array|null
    {
        $stmt = $this->PDO->query('SELECT * FROM products');
        $products = $stmt->fetchAll();

        if ($products === []) {
            return null;
        }

        $array = [];

        foreach ($products as $catalog) {
            $orderObj = new self();
            $orderObj->id = $catalog['id'];
            $orderObj->name = $catalog['name'];
            $orderObj->description = $catalog['description'];
            $orderObj->price = $catalog['price'];
            $orderObj->imageUrl = $catalog['image_url'];
            $array[] = $orderObj;

        }

        return $array;

    }

    public function getOneById(int $productId) : self|null
    {
        $stmt = $this->PDO->query("SELECT * FROM products WHERE id = $productId");
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






}