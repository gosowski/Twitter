<?php

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Message.php');
require_once (__DIR__.'./../Model/User.php');

if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

if(isset($_GET['id']) and $_GET['id'] != null) {
    $msgId = $_GET['id'];
    $showMsg = Message::loadMsgById($conn, $msgId);
    $msgText = $showMsg->getText();
    $msgDate = $showMsg->getSendDate();
    $msgSenderId = $showMsg->getSenderId();
    $msgCollectorId = $showMsg->getCollectorId();
    $msgDate = $showMsg->getSendDate();
    $msgStatus = $showMsg->getStatus();
    $userMsg = TweeterUser::loadById($conn, $msgSenderId);
    $userMsgEmail = $userMsg->getEmail();
    $userCollector = TweeterUser::loadById($conn, $msgCollectorId);
    $msgColEmail = $userCollector->getEmail();

    if($msgStatus == 0 and $_SESSION['id'] == $msgCollectorId) {
        $showMsg -> setStatus(1);
        $showMsg -> saveToDB($conn);
    }

} else {
    header('Location: index.php');
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
    <?php
        echo "<div class='singleMsg'>";
        echo "Nadawca: ".$userMsgEmail."<br>";
        echo "Odbiorca: ".$msgColEmail."<br>";
        echo "Przesłane: ".$msgDate."<br>";
        echo $msgText;
        echo "</div>";
        if($_SESSION['id'] !=$msgSenderId ) {
            echo "<a href='sendmsg.php?collectorId=$msgSenderId'>Odpowiedz</a>";
        }


    ?>
</body>
</html>