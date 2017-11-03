<?php

class Comment {

    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    public function __construct()
    {
        $this -> id = null;
        $this -> userId = '';
        $this -> postId = '';
        $this -> creationDate = '';
        $this -> text = '';
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    static public function loadCommentById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM comments WHERE id=:id');
        $res = $stmt->execute(['id'=>$id]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $comment = new Comment();
            $comment -> id = $row["id"];
            $comment -> setUserId($row["user_id"]);
            $comment -> setPostId($row["post_id"]);
            $comment -> setText($row['text']);
            $comment -> setCreationDate($row['creationDate']);

            return $comment;
        }
    }

    static public function loadAllCommentsByPostId(PDO $conn,	$post_id) {
        $res = [];
        $stmt =	$conn->prepare('SELECT	* FROM comments WHERE post_id=:post_id ORDER  BY creationDate DESC');
        $result	= $stmt->execute(['post_id' => $post_id]);
        if	($result === true && $stmt->rowCount() > 0)	{
            foreach($stmt as $row) {
                $comment = new Comment();
                $comment -> id = $row['id'];
                $comment -> setUserId($row['user_id']);
                $comment -> setText($row['text']);
                $comment -> setPostId($row["post_id"]);
                $comment -> setCreationDate($row['creationDate']);
                $res[] = $comment;
            }
            return $res;
        } else {
            return false;
        }
    }

    public function saveToDB (PDO $conn) {
        if(!$this->getId()) {
            $stmt = $conn->prepare(
                'INSERT INTO comments (user_id, post_id, creationDate, text) VALUES (:user_id, :post_id, NOW(), :text)'
            );
            $res = $stmt->execute([
                'user_id' => $this->getUserId(),
                'post_id' => $this->getPostId(),
                'text' => $this->getText()
            ]);
            if($res !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt =$conn->prepare(
                'UPDATE comments SET user_id=:user_id, post_id=:post_id, creationDate=NOW(), text=:text WHERE id=:id'
            );
            $res = $stmt->execute([
                'user_id' => $this->getUserId(),
                'post_id' => $this->getPostId(),
                'text' => $this->getText(),
                'id' => $this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }

}
