<?php

session_start();

require_once (__DIR__ . './../sql/config.php');
require_once (__DIR__.'./../Model/User.php');
require_once (__DIR__.'./../Model/Tweet.php');

if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['id'];
    switch ($_POST['submit']) {
        case 'reEmail':
            if(isset($_POST['changeEmail']) and $_POST['changeEmail'] != null) {
                $user = TweeterUser::loadByEmail($conn, $_SESSION['email']);
                $user -> setEmail($_POST['changeEmail']);
                $user -> saveToDB($conn);
                $_SESSION['email'] = $_POST['changeEmail'];
            } else {
                echo "<span class='error'>Wpisz poprawny adres e-mail</span>";
            }
            break;
        case 'rePass':
            if(isset($_POST['oldPass']) and isset($_POST['newPass']) and isset($_POST['reNewPass'])) {
                if($_POST['oldPass'] == null or $_POST['newPass'] == null or $_POST['reNewPass'] == null) {
                    echo "<span class='error'>Nie wpisano hasła!</span>";
                } else {
                    $user = TweeterUser::loadById($conn, $id);
                    if(password_verify($_POST['oldPass'],$user->getPass())) {
                        if($_POST['newPass'] === $_POST['reNewPass']) {
                            $user -> setPass($_POST['newPass']);
                            $user -> saveToDB($conn);
                            echo "Hasło zostało zmienione. Zaloguj się ponownie";
                        } else {
                            echo "<span class='error'>Podane hasła nie są takie same!</span>";
                        }
                    } else {
                        echo "<span class='error'>Podano niepoprawne hasło!</span>";
                    }
                }
            }
            break;
        case 'delAcc':
            if (isset($_POST['delete'])) {
                $delete = $_POST['delete'];

                if ($delete === 'deleteYes') {
                    $user = TweeterUser::loadById($conn, $id);
                    $user -> delete($conn);
                    echo "Konto usunięte";
                    session_unset();
                    header('Location: index.php');
                } elseif ($delete === 'deleteNo') {
                    echo "Twoje konto NIE zostanie usunięte";
                }
            }
            break;
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
<div class="rectangle">
    <div class="form">
        <form action="" method="POST" id="changeEmail">
            Zmiana adresu email:<br>
            <input type="text" name="changeEmail"><br>
            <button type="submit" name="submit" value="reEmail">Wyślij</button>
        </form>
        <br><br>
        <form action="" method="POST" id="changePass">
            Zmiana hasła: <br>
            Stare hasło:
            <input type="password" name="oldPass"><br>
            Nowe hasło:
            <input type="password" name="newPass"><br>
            Powtórz nowe hasło:
            <input type="password" name="reNewPass"><br>

            <button type="submit" name="submit" value="rePass">Wyślij</button>
        </form>
        <form action="" method="POST" id="deleteAccount">
            <input type="radio" name="delete" value="deleteYes">Tak<br>
            <input type="radio" name="delete" value="deleteNo">Nie<br>
            <button type="submit" name="submit" value="delAcc">Wyślij</button>
        </form>
    </div>
    <div class="linki">
        <a href="logout.php">Wyloguj się</a>
    </div>
    <div class="linki">
        <a href="index.php">Powrót do strony głównej</a>
    </div>
    <div class="linki">
        <?php
            echo '<a href="showuser.php?userId='.$_SESSION['id'].'">Pokaż moje tweety</a>';
        ?>
    </div>
</div>

</body>
</html>