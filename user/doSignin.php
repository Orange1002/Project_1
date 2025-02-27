<?php
if(isset($_POST["account"])){
    echo "請循正常管道進入此頁";
    exit;
}

$account = $_POST["account"];
$password = $_POST["password"];