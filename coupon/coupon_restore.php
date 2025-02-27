<?php
require_once("../pdo_connect_bark_bijou.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $coupon_id = intval($_GET['id']);

    // 確保優惠券存在
    $check_stmt = $db_host->prepare("SELECT * FROM coupon WHERE coupon_id = :id");
    $check_stmt->execute([':id' => $coupon_id]);
    $coupon = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coupon) {
        header("Location: coupon_disabled.php?error=優惠券不存在");
        exit;
    }

    // 執行還原（將 valid 設回 1）
    $stmt = $db_host->prepare("UPDATE coupon SET valid = 1 WHERE coupon_id = :id");
    if ($stmt->execute([':id' => $coupon_id])) {
        header("Location: coupon.php?restored=1");

        exit;
    } else {
        header("Location: coupon_disabled.php?error=優惠券還原失敗");
        exit;
    }
} else {
    header("Location: coupon_disabled.php");
    exit;
}
