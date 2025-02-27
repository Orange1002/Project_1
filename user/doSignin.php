<?php
require_once("../db_connect_bark_bijou.php");
session_start();

if (!isset($_POST["account"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$account = $_POST["account"];
$password = $_POST["password"];

if (strlen($account) < 4 || strlen($account) > 20) {
    $error = ("請輸入4~20字元的帳號");
    $_SESSION["error"]["message"] = $error;
    header("location: sign_in.php");
    exit;
}

if (strlen($password) < 4 || strlen($password) > 20) {
    $error = ("請輸入4~20字元的密碼");
    $_SESSION["error"]["message"] = $error;
    header("location: sign_in.php");
    exit;
}

$password = md5($password);
$sql = "SELECT account, name, email, phone FROM users WHERE account='$account' AND password='$password'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userCount = $result->num_rows;

if ($userCount == 0) {
    if (!isset($_SESSION["error"]["times"])) {
        $_SESSION["error"]["times"] = 1;
    } else {
        $_SESSION["error"]["times"]++;
    }

    $error = "帳號或密碼錯誤";
    $_SESSION["error"]["message"] = $error;
    header("location: sign_in.php");
    exit;
}

unset($_SESSION["error"]);
$_SESSION["user"] = $row;
$conn->close();
header("location: homepage.php");
