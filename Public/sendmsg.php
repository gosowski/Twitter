<?php

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Message.php');
require_once (__DIR__.'./../Model/User.php');

if(!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

function users(PDO $conn) {
    $usersList = TweeterUser::loadAll($conn);
    foreach($usersList as $user) {
        $email = $user -> getEmail();
        $id = $user -> getId();
        echo "<option value='$id'>".$email."</option>";
    }
}
if(isset($_GET['collectorId'])) {
    $_POST['collector'] = $_GET['collectorId'];
}

if(isset($_POST['privateMsg'])) {
    $privMsg = $_POST['privateMsg'];
    var_dump($_POST['collector']);

    if($_POST['privateMsg'] != null) {
        if(isset($_POST['collector']) and $_POST['collector'] != $_SESSION['id']) {
            $message = new Message();
            $message -> setStatus(0);
            $message -> setSenderId($_SESSION['id']);
            $message -> setCollectorId($_POST['collector']);
            $message -> setText($privMsg);
            $message -> saveToDB($conn);
            header('Location: sendmsg.php');
        } else {
            echo "<span class='error'>Nie możesz wysłać wiadomości sam do siebie!</span>";
        }
    } else {
        echo "<span class='error'>Wpisz treść wiadomości!</span>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta lang="pl">
    <meta charset="utf8">
    <link href="./../CSS/span.css" type="text/css" rel="stylesheet">
</head>
<body>
    <form action="" method="POST">
        <select name="collector">
            <option value="">--Wybierz odbiorcę--</option>
            <?php
                users($conn);
            ?>
        </select>
        <br>
        <textarea name="privateMsg" cols="40" rows="10" maxlength="255"></textarea>
        <br>
        <input type="submit" value="Wyślij">
    </form>

    <div class="linki">
        <a href="index.php">Powrót do strony głównej</a>
    </div>

</body>
</html>
