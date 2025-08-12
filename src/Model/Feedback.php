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


    protected function getTableName(): string
    {
        return 'feedback';
    }




    public function getAllFeedbackByProductId(int $productId): array
    {

        $stmt = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE product_id =" . $productId);

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

            $feedbackObj->date = $feedback['date'];

            $array[] = $feedbackObj;
        }

        return $array;
    }

    public function addFeedback(int $userId, int $productId, int $score, string $comment)

    {

        $stmt = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (user_id, product_id, score, comment)

        VALUES (:userId, :productId, :score, :comment)");

        $stmt->execute(['userId' => $_SESSION['userId'], 'productId' => $productId, 'score' => $score, 'comment' => $comment]);


    }

}