<?php

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Message.php');
require_once (__DIR__.'./../Model/User.php');

if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
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
    <div class="messages">
        Wiadomości wysłane:
        <?php
        $messagesSended = Message::loadAllMsgBySender($conn, $_SESSION['id']);
        if($messagesSended) {
            foreach($messagesSended as $messageSend) {
                $id = $messageSend -> getId();
                $collector_id = $messageSend -> getCollectorId();
                $text = $messageSend -> getText();
                $sendDate = $messageSend -> getSendDate();
                $status = $messageSend -> getStatus();
                $user = TweeterUser::loadById($conn, $collector_id);
                $userEmail = $user -> getEmail();
                echo "<div class='msg'>";
                echo "Utworzony: ".$sendDate."<br>";
                if(strlen($text) > 30) {
                    echo substr($text, 0, 30)."...";
                } else {
                    echo $text;
                }
                echo "<div class='linki'>";
                echo '<a href="singlemsg.php?id='.$id.'">'.'Pokaż</a>';
                echo '</div>';
                echo "</div>";
            }
        } else {
            echo "<span class='error'>Brak wysłanych wiadomości</span>";
        }
        ?>
    </div>

    <div class="messages">
        Wiadomości otrzymane:
        <?php

        $messagesReceived = Message::loadAllMsgByCollector($conn, $_SESSION['id']);
        if($messagesReceived) {
            foreach($messagesReceived as $messageReceived) {
                $id = $messageReceived -> getId();
                $sender_id = $messageReceived -> getSenderId();
                $text = $messageReceived -> getText();
                $sendDate = $messageReceived -> getSendDate();
                $status = $messageReceived -> getStatus();
                $user = TweeterUser::loadById($conn, $sender_id);
                $userEmail = $user -> getEmail();
                echo "<div class='msg'>";
                if($status == 0) {
                    echo "<strong>Wiadomość nieprzeczytana</strong><br>";
                }
                echo "Utworzony: ".$sendDate." przez: ".$userEmail."<br>";
                if(strlen($text) > 30) {
                    echo substr($text, 0, 30)."...";
                } else {
                    echo $text;
                }
                echo "<div class='linki'>";
                echo '<a href="singlemsg.php?id='.$id.'">'.'Pokaż</a>';
                echo '</div>';
                echo "</div>";
            }
        } else {
            echo "<span class='error'>Brak odebranych wiadomości</span>";
        }
        ?>
    </div>
    <div class="linki">
        <a href="sendmsg.php">Wyślij wiadomość</a>
    </div>
    <div class="linki">
        <a href="index.php">Powrót do strony głównej</a>
    </div>


</body>
</html>