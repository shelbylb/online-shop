<?php

namespace Model;

class Feedback extends Model
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $score;
    private string $comment;
    private string $date;


    protected static function getTableName(): string
    {
        return 'feedback';
    }




    public static function getAllFeedbackByProductId(int $productId): array
    {
        $tableName = static::getTableName();

        $stmt = static::getPDO()->query("SELECT * FROM $tableName WHERE product_id =" . $productId);

        $feedbacks = $stmt->fetchAll();

        if ($feedbacks === []) {

            return [];

        }

        $array = [];

        foreach ($feedbacks as $feedback) {

            $feedbackObj = new self();

            $feedbackObj->id = $feedback['id'];

            $feedbackObj->userId = $feedback['user_id'];

            $feedbackObj->productId = $feedback['product_id'];

            $feedbackObj->score = $feedback['score'];

            $feedbackObj->comment = $feedback['comment'];

            $array[] = $feedbackObj;
        }

        return $array;
    }

    public static function addFeedback(int $userId, int $productId, string $comment, int $score)

    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("INSERT INTO $tableName (user_id, product_id, score, comment)

        VALUES (:userId, :productId, :score, :comment)");

        $stmt->execute(['userId' => $_SESSION['userId'], 'productId' => $productId, 'score' => $score, 'comment' => $comment]);
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getDate(): string
    {
        return $this->date;
    }




}