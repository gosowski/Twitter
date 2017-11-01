<?php

$conn = new PDO("mysql:host=localhost; dbname=twitter; charset=utf8", "root", "coderslab");
$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
