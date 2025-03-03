<?php
require_once("../pdo_connect_bark_bijou.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $coupon_id = intval($_GET['id']);

    // 確保優惠券存在
    $check_stmt = $db_host->prepare("SELECT * FROM coupon WHERE coupon_id = :id");
    $check_stmt->execute([':id' => $coupon_id]);
    $coupon = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coupon) {
        header("Location: coupon.php?error=優惠券不存在");
        exit;
    }

    // 執行軟刪除（將 valid 設為 0）
    $stmt = $db_host->prepare("UPDATE coupon SET valid = 0 WHERE coupon_id = :id");
    if ($stmt->execute([':id' => $coupon_id])) {
        header("Location: coupon.php?success=優惠券已停用");
        exit;
    } else {
        header("Location: coupon.php?error=優惠券停用失敗");
        exit;
    }
} else {
    header("Location: coupon.php");
    exit;
}
?>
