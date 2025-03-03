<?php
require_once("../pdo_connect_bark_bijou.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST["product_id"] ?? null;
    $new_status = $_POST["status"] ?? null;

    if (!$product_id || $new_status === null) {
        die("❌ 錯誤：缺少商品 ID 或狀態");
    }

    try {
        // ✅ 加入 Debug 訊息
        echo "正在更新商品 ID: $product_id, 新狀態: $new_status <br>";

        // ✅ 使用 `prepare` 執行 SQL 更新
        $stmt = $db_host->prepare("UPDATE products SET status = :status WHERE id = :id");
        $stmt->bindValue(':status', $new_status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "✅ 更新成功！";
        } else {
            echo "❌ 更新失敗！";
        }

        // ✅ 確保更新成功後導回 `product_edit.php`
        header("Location: product_edit.php?id=" . $product_id);
        exit;
    } catch (PDOException $e) {
        die("❌ SQL 錯誤：" . $e->getMessage());
    }
}
?>
