<?php
session_start();
if (!isset($_GET["id"])) {
    header("location: hotel-list.php");
}

$id = $_GET["id"];
require_once "../db_connect_bark_bijou.php";


$sql =
    "SELECT hotel.* , type.name AS type_name
 FROM hotel JOIN type ON hotel.type_id=type.id  WHERE hotel.id = $id AND valid=1";
$result = $conn->query($sql);
$hotel = $result->fetch_assoc();
$hotelCount = $result->num_rows;


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bark & Bijou</title>

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
            overflow: hidden;
        }

        .card-body {
            padding: 20px;
        }

        .list-group-item {
            border: none;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-success {
            color: #28a745 !important;
        }

        .img-fluid {
            transition: transform 0.3s ease;
        }

        .img-fluid:hover {
            transform: scale(1.05);
        }

        /* 自定義按鈕顏色（紅色，仿 Airbnb） */
        .btn-custom {
            background-color: rgba(17, 136, 179, 0.96);
            border-color: rgba(17, 136, 179, 0.96);
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: rgba(245, 160, 23, 0.919);
            border-color: rgba(245, 160, 23, 0.919);
            color: #fff;
            text-decoration: none;
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
                <a class="nav-link" href="../course/course.php">
                    <i class="fa-solid fa-user"></i>
                    <span>課程管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="hotel-list.php">
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
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->


                    <div class="container">
                        <div class="text-left mb-3 position-absolute">
                            <a href="hotel-list.php" class="btn btn-custom">
                                <i class="fa-solid fa-arrow-left fa-fw"></i> 返回列表
                            </a>
                        </div>

                        <?php if ($hotelCount > 0): ?>
                            <!-- 旅館名稱作為標題 -->
                            <h2 class="mb-4 text-center"><?= htmlspecialchars($hotel['hotel_name']); ?></h2>

                            <!-- 卡片式布局 -->
                            <div class="card shadow-sm m-2">
                                <div class="row g-0">
                                    <!-- 圖片區塊 -->
                                    <div class="col-md-6">
                                        <?php
                                        $image_src = (!empty($hotel['image_path']) && file_exists($hotel['image_path']))
                                            ? htmlspecialchars($hotel['image_path'])
                                            : './uploads/default_hotels.jpg';
                                        ?>
                                        <img src="<?= $image_src; ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($hotel['hotel_name']); ?>" style="max-height: 400px; object-fit: cover; width: 100%;">
                                    </div>
                                    <!-- 資訊區塊 -->
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h5 class="card-title">旅館資訊</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">ID</span>
                                                    <span><?= htmlspecialchars($hotel['id']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">類型</span>
                                                    <span><?= htmlspecialchars($hotel['type_name']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">簡介</span>
                                                    <span><?= htmlspecialchars($hotel['introduction']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">每晚價格</span>
                                                    <span class="text-success fw-bold">$<?= number_format($hotel['price_per_night']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">狀態</span>
                                                    <span class=" fw-bold"><?php
                                                                            // 修改 SQL 查詢以正確判斷當天占用的房間
                                                                            $sqlBooked = "SELECT COUNT(*) FROM bookings WHERE hotel_id = ? AND check_in <= CURDATE() AND check_out > CURDATE()";
                                                                            $stmtBooked = $conn->prepare($sqlBooked);
                                                                            $stmtBooked->bind_param("i", $hotel['id']);
                                                                            $stmtBooked->execute();
                                                                            $booked = $stmtBooked->get_result()->fetch_row()[0];

                                                                            // 計算可用房間數，假設 $hotel['total_rooms'] 已正確設定
                                                                            $available = $hotel['total_rooms'] - $booked;

                                                                            // 根據可用房間數顯示結果
                                                                            if ($available > 0) {
                                                                                echo "有空房";
                                                                            } else {
                                                                                echo "無空房";
                                                                            }
                                                                            ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">地址</span>
                                                    <span><?= htmlspecialchars($hotel['address']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">電話</span>
                                                    <span><?= htmlspecialchars($hotel['phone']); ?></span>
                                                </li>

                                            </ul>
                                            <!-- 操作按鈕 -->
                                            <div class="mt-3 text-end">
                                                <a href="hotel-edit.php?id=<?= $hotel['id']; ?>" class="btn btn-custom">
                                                    <i class="fa-solid fa-pen-to-square fa-fw"></i> 編輯
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <h2 class="text-center mt-5">旅館不存在</h2>
                        <?php endif; ?>
                    </div>
                    <!-- Scroll to Top Button-->
                </div>
            </div>
        </div>



    </div>


</body>
<?php include("./js.php") ?>

</html>
<?php
mysqli_close($conn);
?>