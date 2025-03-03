


<?php

if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁面");
}
require_once "./db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收表單資料
    $name = $conn->real_escape_string($_POST['name']);
    $introduction = $conn->real_escape_string($_POST['introduction']);
    $type_id = (int)$_POST['type_id'];
    $price_per_night = (float)$_POST['price_per_night'];
    $total_rooms = (int)$_POST['total_rooms'];
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $now = date("Y-m-d H:i:s");

    // 處理圖片上傳
    $upload_dir = './uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_path = null;
    if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['hotel_image']['tmp_name'];
        $file_name = basename($_FILES['hotel_image']['name']);
        $unique_name = uniqid() . '_' . $file_name;
        $image_path = $upload_dir . $unique_name;

        if (!move_uploaded_file($file_tmp, $image_path)) {
            echo "圖片上傳失敗";
            exit;
        }
    }

    // 插入資料庫
    $sql = "INSERT INTO hotel (hotel_name, introduction, type_id, price_per_night, address, phone, total_rooms, image_path, create_time, valid) 
            VALUES ('$name', '$introduction', $type_id, $price_per_night, '$address', '$phone', '$total_rooms', '$image_path', '$now', 1)";

    if ($conn->query($sql)) {
        header("location: hotel-list.php");
        exit;
    } else {
        echo "新增旅館失敗: " . $conn->error;
    }
}
?>