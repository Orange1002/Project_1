<?php
require_once "../db_connect_bark_bijou.php";
// 查詢所有類型，用於下拉選單
$typeSql = "SELECT id, name FROM type";
$typeResult = $conn->query($typeSql);
$types = $typeResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_name = $conn->real_escape_string($_POST['hotel_name']);
    $type_id = (int)$_POST['type_id'];
    $address = $conn->real_escape_string($_POST['address']);



    // 處理圖片上傳
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); // 建立資料夾如果不存在
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $unique_name = uniqid() . '_' . $file_name; // 避免檔案名稱重複
        $image_path = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp, $image_path)) {
            // 成功上傳
        } else {
            echo "圖片上傳失敗";
            exit;
        }
    }


    // 插入資料庫
    $sql = "INSERT INTO hotel (hotel_name, type_id, address, image_path, valid) 
            VALUES ('$hotel_name', $type_id, '$address', '$image_path', 1)";
    if ($conn->query($sql)) {
        header("Location: hotel-list.php");
        exit;
    } else {
        echo "新增旅館失敗: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Create hotel</title>

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
    <style>
        .primary {
            background-color: rgba(245, 160, 23, 0.919);
        }

        .card {
            border: none;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card .p-4 {
            padding: 24px;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: rgba(17, 136, 179, 0.96);
            box-shadow: 0 0 0 0.1rem rgba(17, 136, 179, 0.96);
        }

        .fw-bold {
            font-weight: bold;
            color: #333;
        }

        /* 自定義按鈕顏色（紅色，仿 Airbnb） */
        .btn-custom {
            background-color: rgba(17, 136, 179, 0.96);
            border-color: rgba(17, 136, 179, 0.96);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: rgba(245, 160, 23, 0.919);
            border-color: rgba(245, 160, 23, 0.919);
            color: #fff;
        }

        h2 {
            color: #333;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion primary" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bark & Bijou</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>會員專區</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>商品列表</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>課程管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>旅館管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>文章管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
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
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - Alerts -->
                        <!-- Nav Item - Messages -->
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->


                    <div class="container">


                        <div class="mb-4 position-absolute">
                            <a href="hotel-list.php" class="btn btn-custom">
                                <i class="fa-solid fa-arrow-left fa-fw"></i> 旅館列表
                            </a>
                        </div>

                        <div class="text-center mb-4">
                            <h2 class="fw-bold">新增寵物旅館</h2>
                        </div>

                        <!-- 卡片式表單 -->
                        <div class="card shadow-sm p-4">
                            <form action="doCreate.php" method="post" enctype="multipart/form-data">
                                <!-- 旅館名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-3 col-form-label fw-bold">旅館名稱</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>

                                <!-- 圖片 -->
                                <div class="row mb-3">
                                    <label for="hotel_image" class="col-md-3 col-form-label fw-bold">圖片</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" id="hotel_image" name="hotel_image" accept="image/*" required>
                                    </div>
                                </div>

                                <!-- 簡介 -->
                                <div class="row mb-3">
                                    <label for="introduction" class="col-md-3 col-form-label fw-bold">簡介</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="introduction" name="introduction" rows="3" required></textarea>
                                    </div>
                                </div>

                                <!-- 類型 -->
                                <div class="row mb-3">
                                    <label for="type_id" class="col-md-3 col-form-label fw-bold">類型</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="type_id" name="type_id" required>
                                            <option value="">請選擇類型</option>
                                            <?php foreach ($types as $type): ?>
                                                <option value="<?= $type['id']; ?>">
                                                    <?= htmlspecialchars($type['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- 價格 -->
                                <div class="row mb-3">
                                    <label for="price_per_night" class="col-md-3 col-form-label fw-bold">價格</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                                    </div>
                                </div>

                                <!-- 房間數量 -->
                                <div class="row mb-3">
                                    <label for="total_rooms" class="col-md-3 col-form-label fw-bold">房間數量</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" id="total_rooms" name="total_rooms" required>
                                    </div>
                                </div>

                                <!-- 地址 -->
                                <div class="row mb-3">
                                    <label for="address" class="col-md-3 col-form-label fw-bold">地址</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="address" name="address" required>
                                    </div>
                                </div>

                                <!-- 電話 -->
                                <div class="row mb-3">
                                    <label for="phone" class="col-md-3 col-form-label fw-bold">電話</label>
                                    <div class="col-md-9">
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>

                                <!-- 提交按鈕 -->
                                <div class="text-end mt-4">
                                    <button class="btn btn-custom" type="submit">
                                        <i class="fa-solid fa-check fa-fw"></i> 送出
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>



        </div>
    </div>




    <?php include("./js.php") ?>
</body>

</html>