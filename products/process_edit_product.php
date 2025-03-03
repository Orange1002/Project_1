<?php
require_once("../pdo_connect_bark_bijou.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST["product_id"] ?? null;
    $product_name = $_POST["product_name"] ?? null;
    $vendor_id = $_POST["vendor_id"] ?? null;
    $category_id = $_POST["category_id"] ?? null;
    $price = $_POST["price"] ?? null;
    $stock = $_POST["stock"] ?? null;
    $description = $_POST["description"] ?? null;

    if (!$product_id || !$product_name || !$vendor_id || !$category_id || !$price || !$stock) {
        die("❌ 錯誤：請填寫所有欄位！");
    }

    try {
        $stmt = $db_host->prepare("UPDATE products SET product_name = :product_name, vendor_id = :vendor_id, category_id = :category_id, price = :price, stock = :stock, description = :description, updated_at = NOW() WHERE id = :id");
        $stmt->execute([
            ':id' => $product_id,
            ':product_name' => $product_name,
            ':vendor_id' => $vendor_id,
            ':category_id' => $category_id,
            ':price' => $price,
            ':stock' => $stock,
            ':description' => $description
        ]);

        if ($_FILES["product_image"]["error"] === UPLOAD_ERR_OK) {
            $image_path = "uploads/" . basename($_FILES["product_image"]["name"]);
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $image_path);

            $stmt = $db_host->prepare("UPDATE product_images SET img_url = :img_url WHERE product_id = :product_id");
            $stmt->execute([':img_url' => $image_path, ':product_id' => $product_id]);
        }

        header("Location: products.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("❌ 錯誤：" . $e->getMessage());
    }
}
