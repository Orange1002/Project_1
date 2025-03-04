<?php
require_once "../db_connect_bark_bijou.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_id = $_POST['hotel_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // 獲取酒店的最大房間數
    $sqlTotalRooms = "SELECT total_rooms FROM hotel WHERE id = ?";
    $stmtTotalRooms = $conn->prepare($sqlTotalRooms);
    $stmtTotalRooms->bind_param("i", $hotel_id);
    $stmtTotalRooms->execute();
    $total_rooms = $stmtTotalRooms->get_result()->fetch_assoc()['total_rooms'];
    $stmtTotalRooms->close();

    // 檢查日期範圍內是否有任何一天預約已滿
    $sqlCheckAvailability = "
        SELECT COUNT(*) as booked
        FROM bookings
        WHERE hotel_id = ?
        AND check_in < ?
        AND check_out > ?
        GROUP BY DATE(check_in)
        HAVING booked >= ?
    ";
    $stmtCheck = $conn->prepare($sqlCheckAvailability);
    $stmtCheck->bind_param("issi", $hotel_id, $check_out, $check_in, $total_rooms);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $isFullyBooked = $result->num_rows > 0;
    $stmtCheck->close();

    if ($isFullyBooked) {
        header("Location: hotel-list.php?error=fully_booked");
        exit();
    }

    // 執行預約插入
    $sqlInsert = "INSERT INTO bookings (hotel_id, check_in, check_out, status) 
                  VALUES (?, ?, ?, 'confirmed')";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iss", $hotel_id, $check_in, $check_out);

    if ($stmtInsert->execute()) {
        header("Location: hotel-list.php?success=booking_confirmed");
    } else {
        header("Location: hotel-list.php?error=booking_failed");
    }
    $stmtInsert->close();
}

$conn->close();
