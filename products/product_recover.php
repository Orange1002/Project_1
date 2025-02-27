<?php
require_once("../pdo_connect_bark_bijou.php");

$product_id = $_GET["id"] ?? null;

if (!$product_id) {
    die("❌ 錯誤：缺少商品 ID");
}

try {
    $stmt = $db_host->prepare("UPDATE products SET valid = 1 WHERE id = :id");
    $stmt->execute([':id' => $product_id]);

    header("Location: products.php?success=1");
    exit;
} catch (PDOException $e) {
    die("❌ 錯誤：" . $e->getMessage());
}
?>
