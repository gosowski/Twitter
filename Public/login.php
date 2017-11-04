<?php
/*
Strona ma przyjmować email użytkownika i jego hasło.
jeżeli są poprawne, to użytkownik jest przekierowany do strony głównej jeżeli nie – do strony logowania, która ma
wtedy wyświetlić komunikat o błędnym loginie lub haśle
strona logowania ma mieć też link do strony tworzenia użytkownika
 */
session_start();

require_once(__DIR__ . './../sql/config.php');
if(isset($_SESSION['logged'])) {
    if($_SESSION['logged'] == true) {
        header('Location: index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    if (isset($_POST['email']) and isset($_POST['password'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        if (strlen($email) > 0 and strlen($pass) > 0) {
            try {
                $sql = 'SELECT * FROM user WHERE email = :email';
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute(['email' => $email]);

                if($result === true && $stmt->rowCount() > 0) {
                    $res = $stmt -> fetch(PDO::FETCH_ASSOC);

                    if(password_verify($pass, $res['pass'])) {
                        $_SESSION['logged'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['id'] = $res['id'];

                        header('Location: index.php');
                    } else {
                        echo "<span class='error'>Wprowadzono złe hasło!</span>";
                    }
                } else {
                    echo "<span class='error'>Brak takiego użytkownika w bazie!</span>";
                }

            } catch (PDOException $error) {
                echo "Wystąpił nieoczekiwany błąd: ".$error->getMessage();
            }

        } else {
            echo "<span class='error'>Nie wpisano loginu lub hasła!</span>";
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
        <input type="submit" value="Zaloguj się!">
    </form>
    <div class="linki">
        <a href="newuser.php">Zarejestruj się!</a>
    </div>


</body>
</html>