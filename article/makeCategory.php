<?php
require_once("../db_connect_bark_bijou.php");

$sql="SELECT * FROM article_category";
$result=$conn->query($sql);

$row=$result->fetch_all();

echo"<pre>";
print_r($row);
echo"</pre>";

$conn->close();