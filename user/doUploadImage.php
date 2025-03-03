<?php
require_once("../db_connect_bark_bijou.php");
session_start();

// 檢查是否有傳遞必要的資料
// if (!isset($_POST["name"])) {
//     echo "請循正常管道進入此頁";
//     exit;
// }

// 確認圖片格式和大小
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_size = 5 * 1024 * 1024;  // 5MB

// 預設頭像或自訂頭像的處理
if (isset($_POST['default_avatar'])) {
    // 使用者選擇了預設頭像
    $avatar = $_POST['default_avatar'];
} elseif ($_FILES["user_upload_image"]["error"] == 0) {
    // 使用者上傳了圖片
    $file_name = $_FILES["user_upload_image"]["name"];  // 檔案名稱
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);  // 取得副檔名
    $new_file_name = time() . "_" . uniqid() . "." . $ext;  // 生成新檔案名稱

    // 上傳檔案
    $upload_dir = "./user_images/";
    if (move_uploaded_file($_FILES["user_upload_image"]["tmp_name"], $upload_dir . $new_file_name)) {
        $avatar = $new_file_name; // 儲存檔案名稱
    } else {
        echo "圖片上傳錯誤";
        exit;
    }
} else {
    // 如果沒有選擇預設頭像或上傳檔案，顯示錯誤
    echo "請選擇預設頭像或上傳您的圖片";
    exit;
}

$user_id = $_SESSION['user_id'];
// 使用參數化查詢以避免 SQL 注入
$sql = "INSERT INTO user_images (image, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $avatar, $user_id); // "si" 表示第一個是字串，第二個是整數

if ($stmt->execute()) {
    echo "頭像儲存成功!";
} else {
    echo "資料儲存錯誤: " . $conn->error;
    die;
}

$stmt->close();
$conn->close();

header("Location: sign_in.php");
