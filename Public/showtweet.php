<?php
/*
Ta strona ma wyświetlać:
wpis
autora wpisu
wszystkie komentarze do każdego z wpisów
formularz do tworzenia nowego komentarza dla wpisu
 */

session_start();

if(!isset($_SESSION['logged'])) {
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
    content testowy
</body>
</html>