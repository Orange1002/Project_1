<?php
if (!isset($_POST["hotel_name"])) {
    die("請循正常管道進入此頁");
}
require_once "../db_connect_bark_bijou.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $hotel_name = $conn->real_escape_string($_POST['hotel_name']);
    $type_id = (int)$_POST['type_id'];
    $total_rooms = (int)$_POST['total_rooms'];
    $introduction = $conn->real_escape_string($_POST['introduction']);
    $price_per_night = (float)$_POST['price_per_night'];
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // 處理圖片上傳
    $image_path = null;
    if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // 若資料夾不存在則創建
        }
        $file_tmp = $_FILES['hotel_image']['tmp_name'];
        $file_name = basename($_FILES['hotel_image']['name']);
        $unique_name = uniqid() . '_' . $file_name; // 生成唯一檔案名稱
        $image_path = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp, $image_path)) {
            // 刪除舊圖片（可選）
            $old_image_sql = "SELECT image_path FROM hotel WHERE id = $id";
            $old_image_result = $conn->query($old_image_sql);
            $old_image = $old_image_result->fetch_assoc()['image_path'];
            if ($old_image && file_exists($old_image)) {
                unlink($old_image);
            }
        } else {
            echo "圖片上傳失敗";
            exit;
        }
    }

    // 更新資料庫
    $sql = "UPDATE hotel SET 
            hotel_name = '$hotel_name',
            type_id = $type_id,
            introduction = '$introduction',
            price_per_night = $price_per_night,
            total_rooms = $total_rooms,
            address = '$address',
            phone = '$phone'";
    if ($image_path) {
        $sql .= ", image_path = '$image_path'";
    }
    $sql .= " WHERE id = $id AND valid = 1";

    if ($conn->query($sql)) {
        header("Location: hotel.php?id=$id"); // 返回詳細頁面
        exit;
    } else {
        echo "更新失敗: " . $conn->error;
    }
}



$conn->close();
