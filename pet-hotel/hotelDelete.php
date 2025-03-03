<?php
require_once "../db_connect_bark_bijou.php";

$id = $_GET["id"];
$sql = "UPDATE hotel SET valid=0 WHERE id = $id";
$result = $conn->query($sql);

header("location: hotel-list.php");
