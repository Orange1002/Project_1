<?php
if (!isset($_POST["title"])) {
    die("請循正常管道進入此頁");
}

require_once("../db_connect_bark_bijou.php");

$id = $_POST["id"];
$title = $_POST["title"];
$content = $_POST["content"];
$category_id = $_POST["category_id"];

$sql = "UPDATE article SET title = '$title', content = '$content', category_id = $category_id WHERE id = $id";


if ($conn->query($sql) === TRUE) {
    // echo "文章編輯成功";

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $newName = time() . "." . $ext;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], "../acticleImg/" . $newName)) {
        // **插入圖片資訊到 article_img**
        $sql_img = "INSERT INTO article_img (article_id, image) VALUES ('$id', '$newName')";
        if ($conn->query($sql_img) !== TRUE) {
            echo "圖片存入資料庫時發生錯誤：" . $conn->error;
            $conn->close();
            exit();
        }
    } else {
        echo "圖片存入資料夾時發生錯誤";
        $conn->close();
        exit();
    }
}
header("location:article-detail.php?id=$id");
$conn->close();
exit();
