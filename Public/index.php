<?php

/*Strona wyświetlająca	wszystkie	Tweety	jakie znajdują	się	w	systemie	(	od	najnowszego	do najstarszego	).
Nad	nimi	ma	być	widoczny	formularz	do stworzenia	nowego	wpisu.
*/
session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');

if(isset($_SESSION['logged'])) {
    echo "Zalogowany jako ".$_SESSION['email'].'  <a href="logout.php">Wyloguj się</a>  <a href="modifyuser.php">Mój profil</a>';

} else {
    header('Location: login.php');
}

if(isset($_POST['newTweet'])) {
    $tweet = $_POST['newTweet'];
    if(strlen($tweet) > 140) {
        echo "<span class='error'>Maksymalny rozmiar wiadomości to 140 znaków</span>";
    } else {
        $tweet = new Tweet();
        $tweet -> setText($tweet);
        $tweet -> setUserId($_SESSION['id']);
        $tweet -> setCreationDate('20171102171534');
        var_dump($tweet->getCreationDate());
        $tweet -> saveToDB($conn);
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
            <textarea name="newTweet" cols="40" rows="4"></textarea><br><br>
            <input type="submit" value="Opublikuj">
        </form>
    </div>
    <div id="tweets">
        <?php
            $tweets = Tweet::loadAllTweets($conn);
            foreach($tweets as $tweet) {
                $id = $tweet -> getId();
                $user_id = $tweet -> getUserId();
                $text = $tweet -> getText();
                $creationDate = $tweet -> getCreationDate();
                echo "<div class='tweet'>";
                echo "Utworzony: ".$creationDate." przez: ".$user_id."<br>";
                echo $text;
                echo "</div>";
            }
        ?>
    </div>

</body>
</html>

