<?php

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');
require_once (__DIR__.'./../Model/User.php');
require_once (__DIR__.'./../Model/Message.php');

if(isset($_SESSION['logged'])) {
    echo "<div class='header'>";
    echo "Zalogowany jako ".$_SESSION['email'];
    echo ' <a href="logout.php">Wyloguj się</a>';
    echo ' <a href="modifyuser.php">Mój profil</a>';
    echo ' <a href="messages.php">Wiadomości</a>';
    echo '</div>';

    $checkMsg = Message::checkMsg($conn, $_SESSION['id']);
    if($checkMsg) {
        echo "<br><strong>Masz nieprzeczytaną wiadomość</strong>";
    }
} else {
    header('Location: login.php');
}
if(isset($_POST['newTweet'])) {
    if($_POST['newTweet'] != null) {
        $tweetText = $_POST['newTweet'];
        $tweet = new Tweet();
        $tweet -> setText($tweetText);
        $tweet -> setUserId($_SESSION['id']);
        $tweet -> saveToDB($conn);
        header('Location: index.php');
    } else {
        echo "<span class='error'> Wpisz treść tweeta</span>";
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
<div id="newTweet">
    <form action="index.php" method="POST">
        <p>Dodaj nowy wpis:</p>
        <textarea name="newTweet" maxlength="140"></textarea><br><br>
        <input type="submit" value="Opublikuj">
    </form>
</div>
<div id="tweets">
    Ostatnio dodane tweety:

    <?php
    $tweets = Tweet::loadAllTweets($conn);
    foreach($tweets as $tweet) {
        $id = $tweet -> getId();
        $user_id = $tweet -> getUserId();
        $text = $tweet -> getText();
        $creationDate = $tweet -> getCreationDate();
        $user = TweeterUser::loadById($conn, $user_id);
        $userEmail = $user -> getEmail();
        echo "<div class='tweet'>";
        echo "Utworzony: ".$creationDate." przez: ".$userEmail."<br>";
        echo $text;
        echo "<div class='linki'>";
        echo '<a href="showtweet.php?id='.$id.'">'.'Pokaż</a>';
        echo '</div>';
        echo "</div>";
    }
    ?>
</div>

</body>
</html>