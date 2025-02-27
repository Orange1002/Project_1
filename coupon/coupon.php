<?php
require_once("../pdo_connect_bark_bijou.php");

// 設定每頁顯示 10 筆資料
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// 取得優惠券總數
$sqlTotal = "SELECT COUNT(*) as total FROM coupon";
$stmtTotal = $db_host->query($sqlTotal);
$totalCoupons = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCoupons / $perPage);

// 取得優惠券列表
$search = $_GET['search'] ?? ""; // 取得搜尋關鍵字
$sort = $_GET['sort'] ?? "coupon_id_desc"; // 取得排序方式，預設為 ID 降序

// 允許的排序欄位（防止 SQL Injection）
$sortOptions = [
    "coupon_id_desc" => "coupon_id DESC",
    "coupon_id_asc" => "coupon_id ASC",
    "code" => "code ASC",
    "discount_type" => "discount_type ASC",
    "discount_value" => "discount_value DESC",
    "start_date" => "start_date DESC",
    "end_date" => "end_date ASC",
    "usage_limit" => "usage_limit ASC",
    "min_order_amount" => "min_order_amount ASC"
];

// 如果傳入的排序參數不在允許的範圍內，則使用預設值
$orderBy = $sortOptions[$sort] ?? "coupon_id DESC";

// 構建 SQL
$sql = "SELECT * FROM coupon WHERE valid = 1";

if (!empty($search)) {
    $sql .= " AND code LIKE :search";
}

$sql .= " ORDER BY $orderBy LIMIT :offset, :perPage";

$stmt = $db_host->prepare($sql);

if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}

$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link href="./style.css" rel="stylesheet">
    <link href="./courseStyle.css" rel="stylesheet">

    <?php include("../css.php") ?>
</head>

<body id="page-top">
    <?php if (isset($_GET['restored'])): ?>
        <script>
            alert("優惠券已還原成功！");
        </script>
    <?php endif; ?>

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
                <a class="nav-link" href="products.php">
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">優惠卷管理</h1>
                    </div>


                    <form class="d-flex justify-content-end" method="GET" action="coupon.php">
                        <select class="form-select me-2 col-2" name="sort" onchange="this.form.submit()">
                            <option value="coupon_id_desc" <?= ($_GET['sort'] ?? '') == 'coupon_id_desc' ? 'selected' : '' ?>>ID (降序)</option>
                            <option value="coupon_id_asc" <?= ($_GET['sort'] ?? '') == 'coupon_id_asc' ? 'selected' : '' ?>>ID (升序)</option>
                            <option value="code" <?= ($_GET['sort'] ?? '') == 'code' ? 'selected' : '' ?>>代碼</option>
                            <option value="discount_type" <?= ($_GET['sort'] ?? '') == 'discount_type' ? 'selected' : '' ?>>折扣類型</option>
                            <option value="discount_value" <?= ($_GET['sort'] ?? '') == 'discount_value' ? 'selected' : '' ?>>折扣值</option>
                            <option value="start_date" <?= ($_GET['sort'] ?? '') == 'start_date' ? 'selected' : '' ?>>開始日期</option>
                            <option value="end_date" <?= ($_GET['sort'] ?? '') == 'end_date' ? 'selected' : '' ?>>結束日期</option>
                            <option value="usage_limit" <?= ($_GET['sort'] ?? '') == 'usage_limit' ? 'selected' : '' ?>>使用上限</option>
                            <option value="min_order_amount" <?= ($_GET['sort'] ?? '') == 'min_order_amount' ? 'selected' : '' ?>>最低訂單金額</option>
                        </select>

                        <input class="form-control me-2 col-3" type="search" name="search" placeholder="搜尋優惠券代碼" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </form>


                    <div class="p-2">
                        <a class="btn btn-primary" href="index.php"><i class="fa-solid fa-arrow-left fa-fw"></i> 回首頁</a>

                        <a class="btn btn-success float-end" href="create_coupon.php"><i class="fa-solid fa-plus fa-fw"></i> 新增優惠券</a>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>代碼</th>
                                <th>折扣類型</th>
                                <th>折扣值</th>
                                <th>開始日期</th>
                                <th>結束日期</th>
                                <th>使用上限</th>
                                <th>最低訂單金額</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coupons as $coupon): ?>
                                <tr>
                                    <td><?= $coupon['coupon_id'] ?></td>
                                    <td><?= htmlspecialchars($coupon['code']) ?></td>
                                    <td><?= htmlspecialchars($coupon['discount_type']) ?></td>
                                    <td>
                                        <?php if ($coupon['discount_type'] == 'percentage'): ?>
                                            <?= intval($coupon['discount_value']) ?>%
                                        <?php else: ?>
                                            <?= intval($coupon['discount_value']) ?> TWD
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $coupon['start_date'] ?></td>
                                    <td><?= $coupon['end_date'] ?></td>
                                    <td><?= $coupon['usage_limit'] ?? '無限制' ?></td>
                                    <td><?= number_format($coupon['min_order_amount'], 0) ?> TWD</td>
                                    <td>
                                        <a href="coupon_edit.php?id=<?= $coupon['coupon_id'] ?>" class="btn btn-warning btn-sm">編輯</a>
                                        <a href="coupon_delete.php?id=<?= $coupon['coupon_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除嗎？')">刪除</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item<?= ($i == $page) ? ' active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>">第 <?= $i ?> 頁</a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>

                    <a class="btn btn-secondary float-end" href="coupon_disabled.php"><i class="fa-solid fa-eye"></i> 查看已停用優惠券</a>

                </div>
                <!-- End of Page Wrapper -->
            </div>
            <!-- Scroll to Top Button-->
        </div>
    </div>
    </div>



</body>

</html>