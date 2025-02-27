<?php
require_once("../pdo_connect_bark_bijou.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST["product_name"] ?? null;
    $vendor_id = $_POST["vendor_id"] ?? null;
    $category_id = $_POST["category_id"] ?? null;
    $price = $_POST["price"] ?? null;
    $stock = $_POST["stock"] ?? null;
    $description = $_POST["description"] ?? null;

    if (!$product_name || !$vendor_id || !$category_id || !$price || !$stock) {
        die("❌ 錯誤：請填寫所有欄位！");
    }

    try {
        $stmt = $db_host->prepare("INSERT INTO products (product_name, vendor_id, category_id, price, stock, sales, status, description, created_at, updated_at) 
                              VALUES (:product_name, :vendor_id, :category_id, :price, :stock, 0, 'active', :description, NOW(), NOW())");

        $stmt->execute([
            ':product_name' => $product_name,
            ':vendor_id' => $vendor_id,
            ':category_id' => $category_id,
            ':price' => $price,
            ':stock' => $stock,
            ':description' => $description
        ]);

        $product_id = $db_host->lastInsertId(); 

        if ($_FILES["product_image"]["error"] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); 
            }

            $image_file = $target_dir . basename($_FILES["product_image"]["name"]);
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $image_file);

            $stmt = $db_host->prepare("INSERT INTO product_images (product_id, img_url) VALUES (:product_id, :img_url)");
            $stmt->execute([
                ':product_id' => $product_id,
                ':img_url' => $image_file
            ]);
        }

    
        header("Location: products.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("❌ 資料庫錯誤：" . $e->getMessage());
    }
} else {
    die("❌ 錯誤：請使用 POST 方法提交表單。");
}
