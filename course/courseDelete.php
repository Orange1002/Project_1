<?php

require_once("../db_connect_bark_bijou.php");

$id = $_GET["id"];
$sql= "UPDATE course SET valid=0 WHERE id=$id";
$result = $conn->query($sql);

$conn->close();

header("location: course.php");