<?php
require_once("../pdo_connect_bark_bijou.php");

if (!isset($_GET["id"])) {
    die("❌ 錯誤：缺少優惠券 ID");
}

$coupon_id = $_GET["id"];

// 執行永久刪除
$sql = "DELETE FROM coupon WHERE coupon_id = :coupon_id";
$stmt = $db_host->prepare($sql);
$stmt->execute([":coupon_id" => $coupon_id]);

header("Location: coupon_disabled.php?deleted=true");
exit;
?>
