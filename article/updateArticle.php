<?php

require_once("../db_connect_bark_bijou.php");

$sql="UPDATE article SET title = '$_POST[title]', content = '$_POST[content]' WHERE id = '$_POST[id]'";
if($conn->query($sql) === TRUE){
    echo "文章編輯成功";
}else{
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
