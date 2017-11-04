<?php

session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');
require_once (__DIR__.'./../Model/User.php');
require_once (__DIR__.'./../Model/Comment.php');

if(!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

if(isset($_GET['id']) and $_GET['id'] != null) {
    $tweetId = $_GET['id'];
    $showTweet = Tweet::loadTweetById($conn, $tweetId);
    $singleText = $showTweet->getText();
    $singleDate = $showTweet->getCreationDate();
    $singleUserId = $showTweet->getUserId();
    $userTweet = TweeterUser::loadById($conn, $singleUserId);
    $userTweetEmail = $userTweet->getEmail();
} else {
    header('Location: index.php');
}

if(isset($_POST['newComm'])) {
    if($_POST['newComm'] != null) {
        $commentText = $_POST['newComm'];
        $comment = new Comment();
        $comment -> setText($commentText);
        $comment -> setUserId($_SESSION['id']);
        $comment -> setPostId($tweetId);
        $comment -> saveToDB($conn);
        header("Location: showtweet.php?id=$tweetId");
    } else {
        echo "<span class='error'> Wpisz treść komentarza</span>";
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
    <?php
        echo '<div class="tweet">';
        echo 'Utworzony: '.$singleDate.' przez: '.$userTweetEmail.'<br>';
        echo $singleText;
        echo '</div>';
    ?>
    <div class="comments">
        Komentarze:
        <?php
        $comments = Comment::loadAllCommentsByPostId($conn, $tweetId);
        if($comments) {
            foreach($comments as $comment) {
                $id = $comment -> getId();
                $user_id = $comment -> getUserId();
                $text = $comment -> getText();
                $creationDate = $comment -> getCreationDate();
                $user = TweeterUser::loadById($conn, $user_id);
                $userEmail = $user -> getEmail();
                echo "<div class='tweet'>";
                echo "Utworzony: ".$creationDate." przez: ".$userEmail."<br>";
                echo $text;
                echo "</div>";
            }
        } else {
            echo "<span class='error'>Brak komentarzy do tego tweeta</span>";
        }

        ?>
    </div>
    <div id="newComm">
        <form action="" method="POST">
            <p>Dodaj komentarz:</p>
            <textarea name="newComm" cols="40" rows="4"></textarea><br><br>
            <input type="submit" value="Opublikuj">
        </form>
    </div>

    <div class="linki">
        <a href="index.php">Powrót do strony głównej</a>
    </div>
</body>
</html>