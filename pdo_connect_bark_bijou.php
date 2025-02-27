<?php
$servername = "localhost";
$username = "admin";
$password = "12345";
$dbname = "bark_bijou";

try {
    $db_host = new PDO(
        "mysql:host={$servername};dbname={$dbname};charset=utf8",
        $username,
        $password
    );
} catch (PDOException $e) {
    echo "資料庫連線失敗<br>";
    echo $e->getMessage();
}
