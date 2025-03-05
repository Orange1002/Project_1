<?php
require_once("../db_connect_bark_bijou.php");

if (!isset($_FILES["image"])) {
    echo "請循正常管道進入此頁";
    exit;
}
// $sql="SELECT * FROM `article` WHERE `id` = '".$_POST["pic"]."'";
// $result = mysqli_query($link, $sql);
// $row = mysqli_fetch_assoc($result);

$pic = $_FILES["image"];
if ($_FILES["image"]["error"] == 0) {
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $newName = time() . "." . $ext;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], "../img/" . $newName)) {
        echo "上傳成功";
    } else {
        echo "上傳失敗";
    }
} else {
    echo "上傳失敗";
}
