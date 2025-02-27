<?php

require_once("../db_connect_bark_bijou.php");

if (!isset($_POST["name"])) {
    die("請循正常管道進入此頁");
}

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

// print_r($_POST);

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

$sql = "INSERT INTO course (name, content, cost, method_id, location_id, registration_start, registration_end, course_start, course_end, valid) VALUES('$name', '$content', '$cost','$method','$location','$registration_start','$registration_end','$course_start','$course_end', 1)";

if ($conn->query($sql) === TRUE) {
    $course_id = $conn->insert_id;
} else {
    echo "Error " . $sql . "<br>" . $conn->error;
}

$sqlimg = "INSERT INTO course_img (image,course_id) VALUES ('$filename','$course_id')";
if ($conn->query($sqlimg) === TRUE) {
} else {
    echo "Error: " . $sqlimg . "<br>" . $conn->error;
    die;
}


$sqlTeacher = "INSERT INTO course_teacher (name,phone,course_id) VALUES('$teacher_name', '$teacher_phone','$course_id')";

if ($conn->query($sqlTeacher) === TRUE) {
} else {
    echo "Error " . $sqlTeacher . "<br>" . $conn->error;
}


$conn->close();

header("location: course.php");
