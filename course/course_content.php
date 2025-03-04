<?php
session_start();

if (!isset($_GET["id"])) {
    header("location: course.php");
}
$id = $_GET["id"];

if (isset($_GET["p"]) && isset($_GET["order"])) {
    $p = $_GET["p"];
    $order = $_GET["order"];
}

if (isset($_GET["q"])) {
    $q = $_GET["q"];
}

if (isset($_GET["category_id"])) {
    $category_id = $_GET["category_id"];
}



require_once("../db_connect_bark_bijou.php");
$sql = "SELECT * FROM course WHERE id = $id AND valid=1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sqlImg = "SELECT course.*, 
       (SELECT image FROM course_img WHERE course_img.course_id = course.id LIMIT 1) AS image
FROM course
WHERE course.id = $id AND course.valid = 1;
";
$resultImg = $conn->query($sqlImg);
$rowImg = $resultImg->fetch_assoc();

$sqlTeacher = "SELECT course.*, 
       (SELECT name FROM course_teacher WHERE course_teacher.course_id = course.id LIMIT 1) AS teacher_name, 
       (SELECT phone FROM course_teacher WHERE course_teacher.course_id = course.id LIMIT 1) AS phone
FROM course
WHERE course.id = $id AND course.valid = 1;
";
$resultTeacher = $conn->query($sqlTeacher);
$rowTeacher = $resultTeacher->fetch_assoc();

$sqlMethod = "SELECT course.*,course_method.name AS method_name 
    FROM course 
    JOIN course_method ON course.method_id = course_method.id
    WHERE course.id = $id AND course.valid = 1;
";
$resultMethod = $conn->query($sqlMethod);
$rowsMethod = $resultMethod->fetch_assoc();


$sqlLocation = "SELECT course.*,adress
    FROM course 
    JOIN course_location ON course.location_id = course_location.id
    WHERE course.id = $id AND course.valid = 1;
";
$resultLocation = $conn->query($sqlLocation);
$rowsLocation = $resultLocation->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>課程內容</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php include("../css.php") ?>
    <link href="./style.css" rel="stylesheet">

    <style>
        .box1 {
            height: 100px;
        }

        .px-12 {
            padding-inline: 12px;
        }

        .btn-orange:link,
        .btn-orange:visited {
            color: #ffffff;
            background: rgb(255, 115, 0);
        }

        .btn-orange:hover,
        .btn-orange:active {
            color: #ffffff;
            background: rgba(255, 115, 0, 0.9);
        }

        .img-size1 {
            max-width: 500px;
            max-height: 500px;
            object-fit: cover;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion primary" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../user/users.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bark & Bijou</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="../user/users.php">
                    <i class="fa-solid fa-user"></i>
                    <span>會員專區</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../products/products.php">
                    <i class="fa-solid fa-user"></i>
                    <span>商品列表</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="course.php">
                    <i class="fa-solid fa-user"></i>
                    <span>課程管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../pet-hotel/hotel-list.php">
                    <i class="fa-solid fa-user"></i>
                    <span>旅館管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../article/article-list.php">
                    <i class="fa-solid fa-user"></i>
                    <span>文章管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../coupon/coupon.php">
                    <i class="fa-solid fa-user"></i>
                    <span>優惠券管理</span></a>
            </li>
            <hr class="sidebar-divider">
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Search -->
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-warning" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - User Information -->
                        <span class="fs-5 me-3">Hi, <?= $_SESSION["user"]["account"] ?></span>
                        <a href="../user/doLogout.php" class="btn btn-danger">登出</a>
                        <!-- Dropdown - User Information -->
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <div class="container mb-4 text-center">
                    <h1 class="h1 mb-0 text-gray-800 fw-bold">課程內容</h1>
                    <div class="d-flex justify-content-center mt-3">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程名稱</label>
                        <div class="col-6 bg-info d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $row["name"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程內容</label>
                        <div class="col-6 bg-primary d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $row["content"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程照片</label>
                        <div class="col-6 bg-info d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white text-start "><img class="img-size1" src="./course_images/<?= $rowImg["image"] ?>"></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程金額</label>
                        <div class="col-6 bg-primary d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start">$<?= number_format($row["cost"]) ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程方法</label>
                        <div class="col-6 bg-info d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $rowsMethod["method_name"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程地點</label>
                        <div class="col-6 bg-primary d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $rowsLocation["adress"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">教師資訊</label>
                        <div class="col-6 p-0">
                            <div class="bg-info d-flex align-items-center py-3 px-12">
                                <h4 class="mb-0 bg-white col text-start"><?= $rowTeacher["teacher_name"] ?></h4>
                            </div>
                            <div class="bg-info d-flex align-items-center py-3 px-12">
                                <h4 class="mb-0 bg-white col text-start"><?= $rowTeacher["phone"] ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">報名日期</label>
                        <div class="col-6 bg-primary d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $row["registration_start"] ?></h4>
                            <h4 class="mx-2 text-white">~</h4>
                            <h4 class="mb-0 bg-white col text-start"><?= $row["registration_end"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center">課程日期</label>
                        <div class="col-6 bg-info d-flex align-items-center py-3">
                            <h4 class="mb-0 bg-white col text-start"><?= $row["course_start"] ?></h4>
                            <h4 class="mx-2 text-white">~</h4>
                            <h4 class="mb-0 bg-white col text-start"><?= $row["course_end"] ?></h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <label for="" class="form-label col-1 bg-secondary text-white mb-0 h5 d-flex align-items-center justify-content-center">編輯</label>
                        <div class="col-6 bg-primary d-flex align-items-center justify-content-between py-3">
                            <a href="course.php?p=<?= $p ?>&order=<?= $order ?><?php if(isset($q) && $_GET["q"] !== ""){echo "&q=$q";}?><?php if(isset($category_id)){echo "&category=$category_id";}?>" class="btn btn-orange">返回</a>
                            <a class="btn btn-orange" href="course_edit.php?id=<?= $row["id"] ?>&p=<?= $p ?>&order=<?= $order ?><?php if(isset($q) && $_GET["q"] !== ""){echo "&q=$q";}?><?php if(isset($category_id)){echo "&category=$category_id";}?>">編輯</a>
                        </div>
                    </div>

                </div>
                <!-- Begin Page Content -->

            </div>
        </div>
</body>


<?php include("../js.php") ?>
<script>

</script>

</html>