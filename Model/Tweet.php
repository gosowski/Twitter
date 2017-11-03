<?php

class Tweet
{
    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this -> id = null;
        $this -> userId = 0;
        $this -> text = '';
        $this -> creationDate = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserId($newUserId) {
            $this -> userId = $newUserId;
            return $this;
    }

    public function getUserId () {
        return $this -> userId;
    }

    public function setText ($newText) {
            $this -> text = $newText;
            return $this;
    }

    public function getText () {
        return $this -> text;
    }

    public function setCreationDate ($newCreationDate) {
        $this -> creationDate = $newCreationDate;
        return $this;
    }

    public function getCreationDate () {
        return $this -> creationDate;
    }

    static public function loadTweetById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM tweets WHERE id=:id');
        $res = $stmt->execute(['id'=>$id]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $tweet = new Tweet();
            $tweet->id = $row["id"];
            $tweet->setUserId($row["user_id"]);
            $tweet -> setText($row['text']);
            $tweet -> setCreationDate($row['creationDate']);

            return $tweet;
        }
    }

    static public function loadAllTweetsByUserId(PDO $conn,	$user_id) {
        $res = [];
        $stmt =	$conn->prepare('SELECT	* FROM tweets WHERE user_id=:user_id');
        $result	= $stmt->execute(['user_id' => $user_id]);
        if	($result === true && $stmt->rowCount() > 0)	{
            foreach($stmt as $row) {
                $tweet = new Tweet();
                $tweet -> id = $row['id'];
                $tweet -> setUserId($row['user_id']);
                $tweet -> setText($row['text']);
                $tweet -> setCreationDate($row['creationDate']);
                $res[] = $tweet;
            }
            return $res;
        }
    }

    static public function loadAllTweets (PDO $conn) {
        $stmt = $conn->query('SELECT * FROM tweets ORDER BY creationDate DESC');
        $res = [];
        foreach ($stmt->fetchAll() as $row) {
            $tweet = new Tweet();
            $tweet->id = $row["id"];
            $tweet->setUserId($row["user_id"]);
            $tweet -> setText($row['text']);
            $tweet -> setCreationDate($row['creationDate']);
            $res[] = $tweet;
        }
        return $res;
    }

    public function saveToDB (PDO $conn) {
        if(!$this->getId()) {
            $stmt = $conn->prepare(
                'INSERT INTO tweets (user_id, text, creationDate) VALUES (:user_id, :text, NOW())'
            );
            $res = $stmt->execute([
                'user_id' => $this->getUserId(),
                'text' => $this->getText()
            ]);
            if($res !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt =$conn->prepare(
                'UPDATE tweets SET user_id=:user_id, text=:text, creationDate=NOW() WHERE id=:id'
            );
            $res = $stmt->execute([
                'user_id' => $this->getUserId(),
                'text' => $this->getText(),
                'id' => $this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }
}







