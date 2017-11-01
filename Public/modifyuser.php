<?php
/*
Użytkownik ma mieć możliwość edycji informacji o sobie i zmiany hasła.
Pamiętaj o tym, że użytkownik może edytować tylko i wyłącznie swoje informacje.
*/

session_start();

require_once (__DIR__ . './../sql/config.php');
require_once (__DIR__.'./../Model/User.php');

if (!isset($_SESSION['logged'])) {
    header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['submit']) {
        case 'reEmail':
            echo "zmiana adresu email";
            break;
        case 'rePass':
            echo "zmiana hasła";
            break;
        case 'delAcc':
            if (isset($_POST['delete'])) {
                $delete = $_POST['delete'];
                $id = $_POST['id'];
                if ($delete === 'deleteYes') {
                    $user = TweeterUser::loadById($conn, $id);
                    $user -> delete($conn);
                    echo "Konto usunięte";
                } elseif ($delete === 'deleteNo') {
                    break;
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
    <input type="radio" name="delete" value="deleteNo">Nie<br><br>
    <button type="submit" name="submit" value="delAcc">Wyślij</button>
</form>
<p>

    <a href="logout.php">Wyloguj się</a>
</p>

</body>
</html>