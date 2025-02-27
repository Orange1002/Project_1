<?php
require_once("../db_connect_bark_bijou.php");

if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁");
}

$name = $_POST['name'];
$gender_id = $_POST['gender_id'];
$account = $_POST['account'];
$password = $_POST['password'];
$repassword = $_POST["repassword"];
$email = $_POST['email'];
$phone = $_POST['phone'];
$birth_date = $_POST['birth_date'];
$now = date("Y-m-d");


$sql = "INSERT INTO users (name, gender_id, account, password, email, phone, birth_date, created_at, valid)
	VALUES ('$name', '$gender_id', '$account', 'password', '$email', '$phone', '$birth_date', '$now', 1)";


// echo $sql;
if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("location: user-view.php?id=$last_id");
// header("location: users.php?p=1&order=1");

