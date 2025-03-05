<?php
session_start();
require_once("../pdo_connect_bark_bijou.php");

$coupon_id = $_GET['id'] ?? null;
$page = $_GET['page'] ?? 1;

if (!$coupon_id) {
    die("請提供有效的優惠券 ID！");
}

// 查詢優惠券資料
$sql = "SELECT * FROM coupon WHERE coupon_id = :coupon_id";
$stmt = $db_host->prepare($sql);
$stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);
$stmt->execute();
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coupon) {
    die("找不到此優惠券！");
}

// 當使用者提交表單時
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $usage_limit = isset($_POST['usage_limit']) && $_POST['usage_limit'] !== "" ? $_POST['usage_limit'] : NULL;
    $min_order_amount = $_POST['min_order_amount'];

    // 確保開始時間早於結束時間
    if ($start_date >= $end_date) {
        $error = "開始日期必須早於結束日期！";
    } else {
        // 確保優惠碼不重複（排除目前這筆資料）
        $check_sql = "SELECT COUNT(*) FROM coupon WHERE code = :code AND coupon_id != :coupon_id";
        $check_stmt = $db_host->prepare($check_sql);
        $check_stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $check_stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);
        $check_stmt->execute();
        $code_exists = $check_stmt->fetchColumn();

        if ($code_exists) {
            $error = "優惠碼已存在，請使用其他代碼！";
        } else {
            // 更新資料
            $update_sql = "UPDATE coupon 
                           SET code = :code, discount_type = :discount_type, discount_value = :discount_value, 
                               start_date = :start_date, end_date = :end_date, usage_limit = :usage_limit, min_order_amount = :min_order_amount
                           WHERE coupon_id = :coupon_id";

            $update_stmt = $db_host->prepare($update_sql);
            $update_stmt->bindParam(':code', $code);
            $update_stmt->bindParam(':discount_type', $discount_type);
            $update_stmt->bindParam(':discount_value', $discount_value, PDO::PARAM_INT);
            $update_stmt->bindParam(':start_date', $start_date);
            $update_stmt->bindParam(':end_date', $end_date);
            $update_stmt->bindValue(':usage_limit', $usage_limit, is_null($usage_limit) ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $update_stmt->bindParam(':min_order_amount', $min_order_amount, PDO::PARAM_INT);
            $update_stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                header("Location: coupon.php?page=$page&edited=1");
                exit;
            } else {
                $error = "更新失敗！";
            }
        }
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

    <title>編輯優惠券</title>

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
    <link href="./style.css" rel="stylesheet">
    <link href="./courseStyle.css" rel="stylesheet">

    <?php include("../css.php") ?>
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
                <a class="nav-link" href="coupon.php
">
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">優惠卷管理</h1>
                    </div>

                    <div class="container mt-5">
                        <h2>編輯優惠券</h2>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">優惠碼</label>
                                <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($coupon['code']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">折扣類型</label>
                                <select name="discount_type" class="form-select">
                                    <option value="percentage" <?= $coupon['discount_type'] == 'percentage' ? 'selected' : '' ?>>百分比折扣</option>
                                    <option value="fixed" <?= $coupon['discount_type'] == 'fixed' ? 'selected' : '' ?>>固定金額折扣</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">折扣值</label>
                                <input type="number" name="discount_value" class="form-control" value="<?= intval($coupon['discount_value']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">開始日期</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $coupon['start_date'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">結束日期</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $coupon['end_date'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">使用上限</label>
                                <input type="number" name="usage_limit" class="form-control" value="<?= $coupon['usage_limit'] ?? '' ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">最低訂單金額 (TWD)</label>
                                <input type="number" name="min_order_amount" class="form-control" value="<?= $coupon['min_order_amount'] ?>">
                            </div>

                            <button type="submit" class="btn btn-primary">更新優惠券</button>
                            <a class="btn btn-danger" href="coupon.php?page=<?= $page ?>">取消編輯</a>
                        </form>
                    </div>
                </div>
                <!-- End of Page Wrapper -->
            </div>
            <!-- Scroll to Top Button-->
        </div>
    </div>
    </div>



</body>

</html>