<?php

class Message {
    private $id;
    private $sender_id;
    private $collector_id;
    private $text;
    private $sendDate;
    private $status;

    public function __construct()
    {
        $this -> id = null;
        $this -> sender_id = '';
        $this -> collector_id = '';
        $this -> text = '';
        $this -> sendDate = '';
        $this -> status = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSenderId()
    {
        return $this->sender_id;
    }

    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
        return $this;
    }

    public function getCollectorId()
    {
        return $this->collector_id;
    }

    public function setCollectorId($collector_id)
    {
        $this->collector_id = $collector_id;
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

    public function getSendDate()
    {
        return $this->sendDate;
    }

    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    static public function checkMsg(PDO $conn, $collId) {
        $stmt = $conn -> prepare('SELECT * FROM messages WHERE collector_id=:collId AND status = 0');
        $res = $stmt -> execute(['collId' => $collId]);
        if($res && $stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    static public function loadMsgById(PDO $conn, $msgId) {
        $stmt = $conn->prepare('SELECT * FROM messages WHERE id=:id');
        $res = $stmt->execute(['id'=>$msgId]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $msg = new Message();
            $msg -> id = $row['id'];
            $msg -> setSenderId($row['sender_id']);
            $msg -> setCollectorId($row['collector_id']);
            $msg -> setText($row['text']);
            $msg -> setSendDate($row['sendDate']);
            $msg -> setStatus($row['status']);

            return $msg;
        }
    }

    static public function loadAllMsgBySender(PDO $conn, $senderId) {
        $res = [];
        $stmt =	$conn->prepare('SELECT	* FROM messages WHERE sender_id=:sender_id ORDER  BY sendDate DESC');
        $result	= $stmt->execute(['sender_id' => $senderId]);
        if	($result === true && $stmt->rowCount() > 0)	{
            foreach($stmt as $row) {
                $msg = new Message();
                $msg -> id = $row['id'];
                $msg -> setSenderId($row['sender_id']);
                $msg -> setCollectorId($row['collector_id']);
                $msg -> setText($row['text']);
                $msg -> setSendDate($row['sendDate']);
                $msg -> setStatus($row['status']);
                $res[] = $msg;
            }
            return $res;
        } else {
            return false;
        }

    }

    static public function loadAllMsgByCollector(PDO $conn, $collectorId) {
        $res = [];
        $stmt =	$conn->prepare('SELECT	* FROM messages WHERE collector_id=:collector_id ORDER  BY sendDate DESC');
        $result	= $stmt->execute(['collector_id' => $collectorId]);
        if	($result === true && $stmt->rowCount() > 0)	{
            foreach($stmt as $row) {
                $msg = new Message();
                $msg -> id = $row['id'];
                $msg -> setSenderId($row['sender_id']);
                $msg -> setCollectorId($row['collector_id']);
                $msg -> setText($row['text']);
                $msg -> setSendDate($row['sendDate']);
                $msg -> setStatus($row['status']);
                $res[] = $msg;
            }
            return $res;
        } else {
            return false;
        }

    }

    public function saveToDB(PDO $conn) {
        if(!$this->getId()) {
            $stmt = $conn->prepare(
                'INSERT INTO messages (sender_id, collector_id, text, sendDate, status) 
                          VALUES (:sender_id, :collector_id, :text, NOW(), :status)'
            );
            $res = $stmt->execute([
                'sender_id' => $this->getSenderId(),
                'collector_id' => $this->getCollectorId(),
                'text' => $this->getText(),
                'status' => $this->getStatus()
            ]);
            if($res !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt =$conn->prepare(
                'UPDATE messages 
                          SET sender_id=:sender_id, collector_id=:collector_id, text=:text, sendDate=NOW(), status=:status 
                          WHERE id=:id'
            );
            $res = $stmt->execute([
                'sender_id' => $this->getSenderId(),
                'collector_id' => $this->getCollectorId(),
                'text' => $this->getText(),
                'status' => $this->getStatus(),
                'id' => $this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }
}
