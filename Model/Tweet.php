<?php

class Tweet
{
    private $id;
    private $userId;
    private $text = 'text';
    private $creationDate = '20170605 172345';

    public function __construct()
    {
        $this -> id = -1;
        $this -> userId = 0;
        $this -> text = '';
        $this -> creationDate = '';


    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserId($newUserId) {
        if(is_integer($newUserId)) {
            $this -> userId = $newUserId;
            return $this;
        } else {
            return "Błędny user_id!";
        }
    }

    public function getUserId () {
        return $this -> userId;
    }

    public function setText ($newText) {
        if(strlen($newText) <= 140) {
            $this -> text = $newText;
            return $this;
        } else {
            return "Twój wpis jest zbyt długi! Maksymalna długość tekstu to 140znaków.";
        }
    }

    public function getText () {
        return $this -> text;
    }

    public function setCreationDate ($newCreationDate) {
        $setDate = date('Y.m.d H:i:s', strtotime($newCreationDate));
        $this -> creationDate = $setDate;
    }

    public function getCreationDate () {
        return $this -> creationDate;
    }

    static public function loadAllTweetsByUserId(PDO $conn,	$user_id) {
        $res = [];
        $stmt =	$conn->prepare('SELECT	`text` FROM tweets WHERE	user_id=:user_id');
        $result	= $stmt->execute(['user_id' => $user_id]);
        if	($result === true && $stmt->rowCount() > 0)	{
            foreach($stmt as $row) {
                $res[] = $row['text'];
            }
            return $res;
        }
    }

    static public function loadAllTweets (PDO $conn) {
        $res =[];
        $sql = 'SELECT text FROM tweets';
        $stmt = $conn -> query($sql);
        foreach ($stmt as $row) {
            $res[] = $row['text'];
        }
        return $res;
    }

    public function saveToDB () {

    }

}
$conn = new PDO("mysql:host=localhost; dbname=twitter; charset=utf8", "root", "coderslab" );
$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




