<?php
// if (!isset($_POST["id"]) || !isset($_POST["category"])) {
//     die("請循正常管道進入此頁面");
// }
require_once("../db_connect_bark_bijou.php");
$id = $_GET["id"];

$sql = "UPDATE article SET valid=1 WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: article-list.php");
} else {
    echo "恢復失敗: " . $conn->error;
}
$conn->close();
?>