<?php
require_once("../pdo_connect_bark_bijou.php");

$product_id = $_GET["id"] ?? null;
$page = $_GET['page'] ?? 1; // 預設回到第 1 頁

if (!$product_id) {
    die("❌ 錯誤：缺少商品 ID");
}

try {
    $stmt = $db_host->prepare("UPDATE products SET valid = 1 WHERE id = :id");
    $stmt->execute([':id' => $product_id]);

    header("Location: products_deleted.php?page=$page");
    exit;
} catch (PDOException $e) {
    die("❌ 錯誤：" . $e->getMessage());
}
?>
