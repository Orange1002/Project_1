<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: sign_in.php");
    exit;
}
if (!isset($_GET["id"])) {
    header("location: users.php?p=1&order=1");
}

$id = $_GET["id"];
require_once("../db_connect_bark_bijou.php");
$sql = "SELECT users.*, user_images.image AS image_path
FROM users
LEFT JOIN user_images ON users.id = user_images.user_id
WHERE users.id = $id AND users.valid = 1;";
// 之後要進入在網址後加 ?id=
$result = $conn->query($sql);
// $rows=$result->fetch_all(MYSQLI_ASSOC);
// var_dump($rows[0]);
$row = $result->fetch_assoc();
$userCount = $result->num_rows;
// var_dump($row["image_path"]);

?>
<!doctype html>
<html lang="en">

<head>
    <title>user</title>
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
                <a class="nav-link" href="../coupon/coupon.php">
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
                        <h1 class="h3 mb-0 text-gray-800">會員資料</h1>
                    </div>
                    <div class="container">
                        <div class="py-2 ms-3">
                            <a href="users.php?order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?>&p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?><?= isset($_GET['idSearch']) ? '&idSearch=' . $_GET['idSearch'] : '' ?>" class="fs-4 btn btn-secondary"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                        </div>
                        <?php if ($userCount > 0): ?>
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-center mb-5">
                                            <?php if (!empty($row["image_path"])): ?>
                                                <img src="./user_images/<?= $row["image_path"] ?>" alt="使用者圖片"
                                                    style="width: 170px; height: 170px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <i class="fa-solid fa-user" style="font-size: 170px; color: #ccc;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">ID</label>
                                            <input type="text" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" value="<?= $row["id"] ?>" readonly>
                                        </div>
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">使用者名稱</label>
                                            <input type="text" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" name="name" value="<?= $row["name"] ?>" readonly>
                                        </div>
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">帳號</label>
                                            <input type="text" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" value="<?= $row["account"] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">加入時間</label>
                                            <input type="date" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" value="<?= $row["created_at"] ?>" readonly>
                                        </div>
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">電話</label>
                                            <input type="tel" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" name="phone" value="<?= $row["phone"] ?>" readonly>
                                        </div>
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">email</label>
                                            <input type="email" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" name="email" value="<?= $row["email"] ?>" readonly>
                                        </div>
                                        <div class="mb-3 px-3">
                                            <label class="form-label fs-4">性別</label>
                                            <select class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" name="gender_id" disabled>
                                                <option value="" <?= $row["gender_id"] == "" ? "selected" : "" ?>>未填寫</option>
                                                <option value="1" <?= $row["gender_id"] == "1" ? "selected" : "" ?>>男</option>
                                                <option value="2" <?= $row["gender_id"] == "2" ? "selected" : "" ?>>女</option>
                                            </select>
                                        </div>
                                        <div class="mb-5 px-3">
                                            <label class="form-label fs-4">出生日期</label>
                                            <input type="date" class="form-control-plaintext bg-light border border-secondary-subtle rounded fs-4 ps-2" value="<?= $row["birth_date"] ?>" readonly>
                                        </div>
                                        <div class="d-flex justify-content-end me-3">
                                            <a href="user_edit.php?id=<?= $row["id"] ?>" class="fs-4 btn btn-warning"><i class="fa-solid fa-pen fa-solid fa-fw"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <h2>使用者不存在</h2>
                        <?php endif; ?>
                    </div>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
    <script>

    </script>

</body>

</html>