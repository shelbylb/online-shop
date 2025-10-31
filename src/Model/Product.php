<?php

namespace Model;



class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imageUrl;

    protected static function getTableName(): string{
        return 'products';
    }

    public static function catalog() : array|null
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->query("SELECT * FROM $tableName");
        $products = $stmt->fetchAll();

        if ($products === []) {
            return null;
        }

        $array = [];

        foreach ($products as $product) {
            $array[] = static::createObj($product);

        }

        return $array;

    }

    public static function getOneById(int $productId) : self|null
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->query("SELECT * FROM $tableName WHERE id = $productId");
        $product = $stmt->fetch();

        if ($product === false) {
            return null;
        }

        return static::createObj($product);
    }

    public static function createObj(array $product):self|null
    {
        if(!$product){
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