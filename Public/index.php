<?php

/*Strona wyświetlająca	wszystkie	Tweety	jakie znajdują	się	w	systemie	(	od	najnowszego	do najstarszego	).
Nad	nimi	ma	być	widoczny	formularz	do stworzenia	nowego	wpisu.
*/
session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');
require_once (__DIR__.'./../Model/User.php');

if(isset($_SESSION['logged'])) {
    echo "Zalogowany jako ".$_SESSION['email'].'  <a href="logout.php">Wyloguj się</a>  <a href="modifyuser.php">Mój profil</a>';

} else {
    header('Location: login.php');
}

if(isset($_POST['newTweet'])) {
    $tweetText = $_POST['newTweet'];
        $tweet = new Tweet();
        $tweet -> setText($tweetText);
        $tweet -> setUserId($_SESSION['id']);
        $tweet -> saveToDB($conn);
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
    <div id="newTweet">
        <form action="index.php" method="POST">
            <p>Dodaj nowy wpis:</p>
            <textarea name="newTweet" cols="40" rows="4" maxlength="140"></textarea><br><br>
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
                $user = TweeterUser::loadById($conn, $user_id);
                $userEmail = $user -> getEmail();
                echo "<div class='tweet'>";
                echo "Utworzony: ".$creationDate." przez: ".$userEmail."<br>";
                echo $text;
                echo "<div>";
                echo '<a href="showtweet.php?id='.$id.'">'.'Pokaż</a>';
                echo '</div>';
                echo "</div>";
            }
        ?>
    </div>

</body>
</html>

