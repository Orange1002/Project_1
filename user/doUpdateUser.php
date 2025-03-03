<?php
if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁");
}

require_once("../db_connect_bark_bijou.php");

$id = $_POST["id"];
$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$gender_id = $_POST["gender_id"];
$birth_date = $_POST["birth_date"];
$avatar = ""; // 預設為空

// 更新 users 表
$sql = "UPDATE users SET name='$name', phone='$phone', email='$email', gender_id='$gender_id', birth_date='$birth_date' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    echo "使用者資料更新成功<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 檢查使用者是否選擇了預設頭像或上傳了新圖片
if (isset($_POST['default_avatar'])) {
    // 使用者選擇了預設頭像
    $avatar = $_POST['default_avatar'];
} elseif (!empty($_FILES["user_upload_image"]["name"]) && $_FILES["user_upload_image"]["error"] == 0) {
    // 使用者上傳了圖片
    $targetDir = "../user_images/"; // 設定上傳路徑
    $file_name = $_FILES["user_upload_image"]["name"];  // 原始檔名
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // 副檔名
    $newFileName = "user_" . $id . "_" . time() . "." . $imageFileType; // 生成新檔名
    $targetFilePath = $targetDir . $newFileName; // 完整路徑

    // 限制允許的圖片格式
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["user_upload_image"]["tmp_name"], $targetFilePath)) {
            echo "圖片上傳成功: $newFileName<br>";
            $avatar = $newFileName; // 儲存新圖片檔案名稱
        } else {
            echo "圖片上傳失敗<br>";
            exit;
        }
    } else {
        echo "不支援的圖片格式<br>";
        exit;
    }
}

// 更新或插入 user_images 資料表
if (!empty($avatar)) {
    $checkSql = "SELECT * FROM user_images WHERE user_id='$id'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // 若已存在，則更新圖片路徑
        $updateSql = "UPDATE user_images SET image='$avatar' WHERE user_id='$id'";
        if ($conn->query($updateSql) === TRUE) {
            echo "圖片更新成功<br>";
        } else {
            echo "圖片更新錯誤: " . $conn->error;
        }
    } else {
        // 若不存在，則插入新圖片資料
        $insertSql = "INSERT INTO user_images (user_id, image) VALUES ('$id', '$avatar')";
        if ($conn->query($insertSql) === TRUE) {
            echo "圖片新增成功<br>";
        } else {
            echo "圖片新增錯誤: " . $conn->error;
        }
    }
}

// 更新完成後重新導向
header("location: user_view.php?id=$id");

$conn->close();
