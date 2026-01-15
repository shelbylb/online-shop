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

    protected static function getTableName(): string
    {
        return 'orders';
    }

    public static function create(string $contactName, string $contactPhone, string $comment, string $address, int $userId)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare(
            "INSERT INTO $tableName (contact_name, contact_phone, comment, address, user_id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            'name' => $contactName,
            'phone' => $contactPhone,
            'comment' => $comment,
            'address' => $address,
            'user_id' => $userId
        ]);

        // Получаем возвращенный ID
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Возвращаем ID нового заказа
        return $result['id'] ?? null;

    }

    public static function getAllByUserId(int $userId): array
    {
        $tableName = static::getTableName();

        $stmt = static::getPDO()->prepare(
            "SELECT
            o.id AS o_id,
            o.contact_name,
            o.contact_phone,
            o.comment,
            o.address,
            o.user_id,
            op.id AS op_id,
            op.order_id,
            op.product_id,
            op.amount,
            p.id AS p_id,
            p.name,
            p.description,
            p.price,
            p.image_url
        FROM $tableName o
        INNER JOIN order_products op ON o.id = op.order_id
        INNER JOIN products p ON op.product_id = p.id
        WHERE o.user_id = :userId"
        );
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === []) {
            return [];
        }


        $groupedOrders = [];
        foreach ($result as $row) {
            $orderId = $row['o_id'];

            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_data' => $row,
                    'products' => []
                ];
            }


            $groupedOrders[$orderId]['products'][] = $row;
        }


        $array = [];
        foreach ($groupedOrders as $orderData) {
            $array[] = self::createObj($orderData);
        }

        return $array;
    }

    public static function createObj(array $orderData): self
    {
        $orderRow = $orderData['order_data'];
        $productsRows = $orderData['products'];

        $orderProductObj = new self();
        $orderProductObj->id = $orderRow['o_id'];
        $orderProductObj->contactName = $orderRow['contact_name'];
        $orderProductObj->contactPhone = $orderRow['contact_phone'];
        $orderProductObj->comment = $orderRow['comment'];
        $orderProductObj->address = $orderRow['address'];

        $orderProducts = [];

        foreach ($productsRows as $productRow) {
            $products = [
                'op_id' => $productRow['op_id'],
                'order_id' => $orderProductObj->id,
                'product_id' => $productRow['product_id'],
                'amount' => $productRow['amount'],
                'p_id' => $productRow['p_id'],
                'name' => $productRow['name'],
                'description' => $productRow['description'],
                'price' => $productRow['price'],
                'image_url' => $productRow['image_url']
            ];

            $oderProduct = OrderProduct::createObj($products);
            $orderProducts[] = $oderProduct;
        }

        $orderProductObj->setOrderProducts($orderProducts);


        return $orderProductObj;
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