<?php

session_start();

require_once (__DIR__ . './../sql/config.php');
require_once (__DIR__.'./../Model/User.php');
require_once (__DIR__.'./../Model/Tweet.php');

if(!isset($_GET['userId']) or $_GET['userId'] == null or !isset($_SESSION['logged'])) {
    header('Location: index.php');
} else if($_SESSION['id'] != $_GET['userId'])
    header('Location: showuser.php?userId='.$_SESSION['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta lang="pl">
    <meta charset="utf8">
    <link href="./../CSS/span.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="rectangle">
    <?php

    $tweets = Tweet::loadAllTweetsByUserId($conn, $_SESSION['id']);
    if($tweets) {
        foreach($tweets as $tweet) {
            $id = $tweet -> getId();
            $text = $tweet -> getText();
            $creationDate = $tweet -> getCreationDate();
            echo "<div class='tweet'>";
            echo "Utworzony: ".$creationDate."<br>";
            echo $text;
            echo "<div>";
            echo '<a href="showtweet.php?id='.$id.'">'.'Pokaż</a>';
            echo '</div>';
            echo "</div>";
        }
    } else {
        echo "<span class='error'>Nie napisałeś jeszcze żadnych tweetów</span>";
    }
    ?>
</div>
<div class="linki">

    <a href="logout.php">Wyloguj się</a><br>
    <a href="index.php">Powrót do strony głównej</a><br>
    <a href="modifyuser.php">Mój profil</a>
</div>

</body>
</html>