<?php
session_start(); // 啟動 session 以檢查使用者登入狀態
require_once "./db_connect.php"; // 連線到資料庫



// 確認表單為 POST 提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 接收表單資料並進行基本過濾
    $hotel_id = (int)$_POST['hotel_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    // $user_id = (int)$_SESSION['user_id'];

    // 1. 日期驗證
    if (strtotime($check_in) >= strtotime($check_out)) {
        echo "<script>alert('退房日期必須晚於入住日期'); window.history.back();</script>";
        exit;
    }

    // 2. 房間可用性檢查
    // 從 hotel 表中獲取總房間數
    $sqlRooms = "SELECT total_rooms FROM hotel WHERE id = ?";
    $stmtRooms = $conn->prepare($sqlRooms);
    $stmtRooms->bind_param("i", $hotel_id);
    $stmtRooms->execute();
    $total_rooms = $stmtRooms->get_result()->fetch_assoc()['total_rooms'];

    // 查詢所選日期範圍內的現有預約數量
    $sqlCheck = "SELECT COUNT(*) as booked FROM bookings 
                 WHERE hotel_id = ? AND (
                     (? BETWEEN check_in AND check_out) OR
                     (? BETWEEN check_in AND check_out) OR
                     (check_in >= ? AND check_out <= ?)
                 )";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("issss", $hotel_id, $check_in, $check_out, $check_in, $check_out);
    $stmtCheck->execute();
    $booked = $stmtCheck->get_result()->fetch_assoc()['booked'];

    // 若預約數量已達房間總數，提示無房
    if ($booked >= $total_rooms) {
        echo "<script>alert('該日期無可用房間'); window.history.back();</script>";
        exit;
    }

    // 3. 插入預約資料到資料庫
    $sql = "INSERT INTO bookings (hotel_id, check_in, check_out, status) 
        VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $hotel_id, $check_in, $check_out);

    // Execute the statement (add this to actually perform the insert)
    if ($stmt->execute()) {
        echo "<script>alert('預約成功'); window.location.href='hotel-list.php';</script>";
    } else {
        echo "<script>alert('預約失敗'); window.history.back();</script>";
    }

    // 關閉語句
    $stmt->close();
    $stmtCheck->close();
    $stmtRooms->close();
}

// 關閉資料庫連線
mysqli_close($conn);
