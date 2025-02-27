<?php
require_once("../db_connect_bark_bijou.php");
// if (!isset($_POST["id"]) || !isset($_POST["category"])) {
//     die("請循正常管道進入此頁面");
// }
$sql = "INSERT INTO article_category (name) VALUES ('全部文章');";

// $sql .= "INSERT INTO article_category (name) VALUES ('醫療專區');";

// $sql .= "INSERT INTO article_category (name) VALUES ('活動專區');";

// $sql .= "INSERT INTO article_category (name) VALUES ('流浪專區');";

if ($conn->multi_query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
