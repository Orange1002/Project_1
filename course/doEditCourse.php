<?php
if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁");
}

require_once("../db_connect_bark_bijou.php");

$id=$_GET["id"];
$name = $_POST["name"];
$content = $_POST["content"];
$cost = $_POST["cost"];
$method = $_POST["method"];
$teacher_name = $_POST["teacher_name"];
$teacher_phone = $_POST["teacher_phone"];
$location = $_POST["location"];
$registration_start = $_POST["registration_start"];
$registration_end = $_POST["registration_end"];
$course_start = $_POST["course_start"];
$course_end = $_POST["course_end"];

if ($_FILES["image"]["error"] == 0) {
    // var_dump($_FILES["image"]);
    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $filename = time() . "." . $ext;
    // echo $ext;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], "./course_images/" . $filename)) {
        // echo "upload file success!";
    } else {
        echo "upload file fail!";
        exit;
    }
} else {
    echo "圖片上傳錯誤";
}

$sql = "UPDATE course SET name='$name', content='$content', cost='$cost', method_id='$method', location_id='$location', registration_start='$registration_start', registration_end='$registration_end', course_start='$course_start', course_end='$course_end' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
} else {
    echo "Error " . $sql . "<br>" . $conn->error;
}

$sqlimg = "UPDATE course_img SET image='$filename' WHERE course_id='$id'";
if ($conn->query($sqlimg) === TRUE) {
} else {
    echo "Error: " . $sqlimg . "<br>" . $conn->error;
    die;
}

$sqlTeacher = "UPDATE course_teacher SET name='$teacher_name', phone='$teacher_phone' WHERE course_id='$id'";
if ($conn->query($sqlTeacher) === TRUE) {
} else {
    echo "Error " . $sqlTeacher . "<br>" . $conn->error;
}

// $sql = "UPDATE users SET name='$name', phone='$phone', email='$email' WHERE id='$id'";
// echo $sql;

// if ($conn->query($sql) === TRUE) {
//     // echo "資料更新成功";

// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

// $conn->close();

header("location: course_content.php?id=$id");
