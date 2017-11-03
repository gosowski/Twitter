<?php
/*
Ta strona ma wyświetlać:
wpis
autora wpisu
wszystkie komentarze do każdego z wpisów
formularz do tworzenia nowego komentarza dla wpisu
 */

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');
require_once (__DIR__.'./../Model/User.php');

if(!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

if(isset($_GET['id']) and $_GET['id'] != null) {
    $tweetId = $_GET['id'];
    $showTweet = Tweet::loadTweetById($conn, $tweetId);
    $singleText = $showTweet -> getText();
    $singleDate = $showTweet -> getCreationDate();
    $singleUserId = $showTweet -> getUserId();
    $userTweet = TweeterUser::loadById($conn, $singleUserId);
    $userTweetEmail = $userTweet -> getEmail();
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
        echo '<div class="tweet">';
        echo 'Utworzony: '.$singleDate.' przez: '.$userTweetEmail.'<br>';
        echo $singleText;
        echo '</div>';
    ?>
<div class="linki">
    <a href="index.php">Powrót do strony głównej</a>
</div>
</body>
</html>