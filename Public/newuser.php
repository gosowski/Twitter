<?php
/*
Strona tworzenia użytkownika
Strona ma pobierać email i hasło.
Jeżeli takiego adresu email nie ma jeszcze w systemie (tabeli w bazie), to rejestrujemy użytkownika i logujemy (przekierowanie na
stronę główną).
Jeżeli taki adres email jest, to przekierowujemy do strony tworzenia użytkownika (ta sama strona) i wyświetlamy
komunikat o zajętym adresie email.
*/
session_start();

require_once(__DIR__ . './../sql/config.php');
require_once(__DIR__ . './../Model/User.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) and isset($_POST['password']) and isset($_POST['repass'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $rePass = $_POST['repass'];

        if (strlen($email) > 0 and strlen($pass) > 0 and $pass === $rePass) {
            $sql = 'SELECT * FROM user WHERE email = :email';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $count = $stmt->rowCount();
            if ($count == 0) {
                $user = new TweeterUser();
                $user->setEmail($email);
                $user->setPass($pass);
                $user -> saveToDB($conn);

                $_SESSION['logged'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user -> getId();

                header('Location: index.php');
            } else {
                foreach ($stmt as $row) {
                    if ($row['email'] == $email) {
                        echo "<span class='error'>Podany adres e-mail widnieje już w bazie.</span>";
                    }
                }
            }

        } elseif ($pass != $rePass) {
            echo "<span class='error'>Wpisane hasła nie zgadzają się!</span>";
        } else {
            echo "<span class='error'>Nie wpisano loginu lub hasła</span>";
        }
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
<form action="" method="POST">
    Wpisz adres e-mail:<br>
    <input type="text" name="email"><br><br>
    Wpisz hasło: <br>
    <input type="password" name="password"><br><br>
    Powtórz hasło: <br>
    <input type="password" name="repass"><br><br>
    <input type="submit" value="Zarejestruj się!">
</form>

</body>
</html>