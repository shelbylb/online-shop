<?php

namespace Model;

use PDO;

class Order extends Model
{
    private int $id;
    private string $contactName;
    private string $contactPhone;
    private string $comment;
    private string $address;
    private int $userId;
    private int $sum;
    private array $orderProducts;

    protected function getTableName(): string{
        return 'orders';
    }

    public function create(string $contactName, string $contactPhone, string $comment, string $address, int $userId)
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, address, user_id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            'name'=>$contactName,
            'phone'=>$contactPhone,
            'comment'=>$comment,
            'address'=>$address,
            'user_id'=>$userId
        ]);

        // Получаем возвращенный ID
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Возвращаем ID нового заказа
        return $result['id'] ?? null;

    }

    public function getAllByUserId(int $userId): array|null
    {
        $stmt = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :userId");
        $stmt->execute(['userId'=>$userId]);
        $result = $stmt->fetchAll();


       /* if ($result === []) {
            return null;
        }*/

        $array = [];

        foreach ($result as $order) {
            $orderObj = new self();
            $orderObj->id = $order['id'];
            $orderObj->contactName = $order['contact_name'];
            $orderObj->contactPhone = $order['contact_phone'];
            $orderObj->comment = $order['comment'];
            $orderObj->address = $order['address'];
            $array[] = $orderObj;

        }

        return $array;


    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function setSum(int $sum): void
    {
        $this->sum = $sum;
    }

    public function setOrderProducts(array $orderProducts): void
    {
        $this->orderProducts = $orderProducts;
    }

    public function getOrderProducts(): array
    {
        return $this->orderProducts;
    }









}