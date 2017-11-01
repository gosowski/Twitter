<?php

/*Strona wyświetlająca	wszystkie	Tweety	jakie znajdują	się	w	systemie	(	od	najnowszego	do najstarszego	).
Nad	nimi	ma	być	widoczny	formularz	do stworzenia	nowego	wpisu.
*/
session_start();

require_once (__DIR__.'./../sql/config.php');
require_once (__DIR__.'./../Model/Tweet.php');

if(isset($_SESSION['logged'])) {
    echo "Zalogowany jako ".$_SESSION['email'].'  <a href="logout.php">Wyloguj się</a>';
} else {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta lang="pl">
    <meta charset="utf8">
</head>
<body>
    <div id="newTweet">
        <form action="index.php" method="GET">
            <p>Dodaj nowy wpis:</p>
            <textarea name="newTweet" cols="40" rows="4"></textarea><br><br>
            <input type="submit" value="Opublikuj">
        </form>
    </div>
    <div id="tweets">
        <?php

        ?>
    </div>

</body>
</html>

