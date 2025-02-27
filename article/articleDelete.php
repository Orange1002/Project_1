<?php

require_once("../db_connect_bark_bijou.php");
$id = $_GET["id"];
$sql = "UPDATE article SET valid = 0 WHERE id=$id";
// $result = $conn->query($sql);
if ($conn->query($sql) === TRUE) {
    // echo "文章刪除成功";
    header("Location: article-list.php"); 
    exit();
} else {
    echo "發生錯誤：" . $conn->error;
}

$conn->close();
