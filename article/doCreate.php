<?php
require_once("../db_connect_bark_bijou.php");
if (!isset($_POST["title"]) || !isset($_POST["content"])) {
    die("請循正常管道進入此頁面");
}
$title = $_POST["title"];
$content = $_POST["content"];
date_default_timezone_set("Asia/Taipei");
$now = date("Y-m-d H:i:s");
$category_id = $_POST["category_id"];
$pic = $_FILES["image"];

if (empty($title)) {
    die("標題不得為空");
}
if (empty($content)) {
    die("內容不得為空");
}
// if (empty($category_id)) {
//     die("請選擇分類");
// }
$sql = "INSERT INTO article (title, content, created_date, category_id) 
VALUES ('$title','$content','$now' ,'$category_id')";

if($conn->query($sql) === TRUE){
    // $last_id = $conn->insert_id;
    // echo "文章新增成功, id 為 $last_id";
    $article_id = $conn->insert_id;
} else{
    echo "發生錯誤：" . $conn->error;
       $conn->close();
       exit();
}

// 上傳照片
if ($_FILES["image"]["error"] == 0) {
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $newName = time() . "." . $ext;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], "../img/" . $newName)) {
        // **插入圖片資訊到 article_img**
        $sql_img = "INSERT INTO article_img (article_id, image) VALUES ('$article_id', '$newName')";
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




$conn->close();
header("Location:article-list.php");
exit();