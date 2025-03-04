<?php
require_once("../db_connect_bark_bijou.php");

// 確保 id 存在且為數字
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("location: user_deleted.php"); // 無效 ID 則回到列表
    exit;
}

$id = (int)$_GET["id"];
$sql = "DELETE FROM users WHERE id = $id";
$conn->query($sql);
$conn->close();

// 取得當前網址的其他參數（去掉 id）
$queryParams = $_GET;
unset($queryParams["id"]); // 避免 id 影響

// 重新組合網址參數
$queryString = http_build_query($queryParams);
$redirectUrl = "user_deleted.php" . (!empty($queryString) ? "?" . $queryString : "");

// 保留篩選條件並返回
header("Location: $redirectUrl");
exit;
