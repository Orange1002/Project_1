<?php
require_once("../db_connect_bark_bijou.php");

// 確保 id 參數存在且有效
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("location: users.php?p=1&order=1"); // 若無 ID 或 ID 非數字，則返回使用者列表
    exit;
}

$id = (int)$_GET["id"]; // 強制轉換為整數，避免 SQL 注入
$sql = "UPDATE users SET valid=0 WHERE id=$id";
$result = $conn->query($sql);

$conn->close();

// 取得當前 URL 的參數（不包含 id）
$queryParams = $_GET;
unset($queryParams["id"]); // 移除 id 參數，避免影響其他操作

// 重新組合查詢字串
$queryString = http_build_query($queryParams);

// 若 queryString 為空，則不加 '?'，否則加上 '?'
$redirectUrl = "users.php" . (!empty($queryString) ? "?" . $queryString : "");

// 重新導向到 users.php 並保留篩選條件
header("Location: $redirectUrl");
exit;
