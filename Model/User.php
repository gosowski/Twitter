<?php

class TweeterUser {

    private $id;
    private $email;
    private $pass;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if(strlen($email) > 0) {
            $this->email = $email;
            return $this;
        } else {
            echo "Nie wpisano adresu e-mail";
        }
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = password_hash($pass,PASSWORD_BCRYPT,['cost'=>11]);
        return $this;
    }

    public function setDirectPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function saveToDB(\PDO $conn) {
        if(!$this->getId()) {
            $stmt = $conn->prepare(
                'INSERT INTO user (email, pass) VALUES (:email, :pass)'
            );
            $res = $stmt->execute([
                'email' => $this->getEmail(),
                'pass' => $this->getPass()
            ]);
            if($res !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt =$conn->prepare(
                'UPDATE user SET email=:email, pass=:pass WHERE id=:id'
            );
            $res = $stmt->execute([
                'email' => $this->getEmail(),
                'pass' => $this->getPass(),
                'id' => $this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }

    static public function loadById(\PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM user WHERE id=:id');
        $res = $stmt->execute(['id'=>$id]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $user = new TweeterUser();
            $user->id = $row["id"];
            $user->setEmail($row["email"])
                ->setDirectPass($row["pass"]);
            return $user;
        }
        return null;
    }

    static public function loadByEmail(\PDO $conn, $email) {
        $stmt = $conn->prepare('SELECT * FROM user WHERE email=:email');
        $res = $stmt->execute(['email'=>$email]);
        if($res && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $user = new TweeterUser();
            $user->id = $row["id"];
            $user->setEmail($row["email"])
                ->setDirectPass($row["pass"]);
            return $user;
        }
        return null;
    }

    static public function loadAll(\PDO $conn) {
        $stmt = $conn->query('SELECT * FROM user');
        $res = [];
        foreach ($stmt->fetchAll() as $row) {
            $user = new TweeterUser();
            $user->id = $row["id"];
            $user->setEmail($row["email"])
                ->setDirectPass($row["pass"]);
            $res[] = $user;
        }
        return $res;
    }

    public function delete(\PDO $conn) {
        if($this->getId()) {
            $stmt = $conn->prepare('DELETE FROM user WHERE id=:id');
            $res = $stmt->execute(['id'=>$this->getId()]);
            if($res) {
                $this->id = null;
                return true;
            }
        }
        return false;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail()
        ];
    }
}


