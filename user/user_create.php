<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: sign_in.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Create User</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php include("../css.php") ?>
    <style>
        .primary {
            background-color: rgba(245, 160, 23, 0.919);
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion primary" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="users.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bark & Bijou</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="users.php">
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
                        <a href="doLogout.php" class="btn btn-danger">登出</a>
                        <!-- Dropdown - User Information -->
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">新增會員</h1>
                    </div>
                    <div class="py-2">
                        <a href="users.php?p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?>&order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?>" class="fs-4 btn btn-secondary"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                    </div>
                    <div class="container">
                        <form action="doCreateUser.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 px-3">
                                        <label for="name" class="form-label fs-4">使用者名稱</label>
                                        <input type="text" class="form-control fs-4" name="name" id="name">
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="account" class="form-label fs-4">帳號</label>
                                        <input type="text" class="form-control fs-4" name="account" id="account">
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="password" class="form-label fs-4">密碼</label>
                                        <input type="password" class="form-control fs-4" name="password" id="password">
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="repassword" class="form-label fs-4">確認密碼</label>
                                        <input type="password" class="form-control fs-4" name="repassword" id="repassword">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 px-3">
                                        <label for="gender_id" class="form-label fs-4">性別</label>
                                        <select class="form-control fs-4" name="gender_id" id="gender_id">
                                            <option value="" selected>未填寫</option>
                                            <option value="1">男</option>
                                            <option value="2">女</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="phone" class="form-label fs-4">電話</label>
                                        <input type="tel" class="form-control fs-4" name="phone" id="phone">
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="email" class="form-label fs-4">email</label>
                                        <input type="email" class="form-control fs-4" name="email" id="email">
                                    </div>
                                    <div class="mb-3 px-3">
                                        <label for="birth_date" class="form-label fs-4">出生日期</label>
                                        <input type="date" class="form-control fs-4" name="birth_date" id="birth_date">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-warning mt-3 me-3 fs-4" type="submit">新增</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>
        </div>
    </div>



</body>

<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>


</html>