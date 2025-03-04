<?php
require_once("../pdo_connect_bark_bijou.php");

// 取得商品 ID
$product_id = $_GET["id"] ?? null;
$page = $_GET['page'] ?? 1;

if (!$product_id) {
    die("❌ 錯誤：缺少商品 ID");
}

// 查詢商品資訊
$stmt = $db_host->prepare("
    SELECT products.*, 
           vendors.vendor_name, 
           product_categories.category_name,
           COALESCE((SELECT img_url FROM product_images WHERE product_images.product_id = products.id LIMIT 1), 'uploads/default.png') AS img_url
    FROM products
    LEFT JOIN vendors ON products.vendor_id = vendors.vendor_id
    LEFT JOIN product_categories ON products.category_id = product_categories.category_id
    WHERE products.id = :id
");
$stmt->execute([":id" => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("❌ 找不到該商品");
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

    <title>商品詳細資訊 - <?= htmlspecialchars($product["product_name"]) ?></title>

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
                <a class="nav-link" href="products.php">
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
                        <a href="../user/doLogout.php" class="btn btn-danger">登出</a>
                        <!-- Dropdown - User Information -->
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-1">
                        <h1 class="h3 mb-0 text-gray-800">商品詳細資訊</h1>
                    </div>
                    <div class="container mt-5">
                        <a href="products.php?page=<?= $page ?>" class="btn btn-primary mb-3">
                            <i class="fa-solid fa-arrow-left"></i> 返回商品列表
                        </a>

                        <h2 class="mb-4"><?= htmlspecialchars($product["product_name"]) ?></h2>

                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?= htmlspecialchars($product['img_url']) ?>" class="img-fluid border rounded"
                                    alt="商品圖片" style="max-width: 100%;">
                            </div>

                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>商品名稱</th>
                                            <td><?= htmlspecialchars($product["product_name"]) ?></td>
                                        </tr>
                                        <tr>
                                            <th>價格</th>
                                            <td><?= number_format($product["price"]) ?> TWD</td>
                                        </tr>
                                        <tr>
                                            <th>庫存</th>
                                            <td><?= $product["stock"] ?> 件</td>
                                        </tr>
                                        <tr>
                                            <th>供應商</th>
                                            <td><?= htmlspecialchars($product["vendor_name"]) ?></td>
                                        </tr>
                                        <tr>
                                            <th>分類</th>
                                            <td><?= htmlspecialchars($product["category_name"]) ?></td>
                                        </tr>
                                        <tr>
                                            <th>狀態</th>
                                            <td>
                                                <?php if ($product["status"] === 'active'): ?>
                                                    <span class="badge bg-success">上架中</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">已下架</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>描述</th>
                                            <td><?= nl2br(htmlspecialchars($product["description"])) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <a href="product_edit.php?id=<?= $product["id"] ?>&page=<?= $page ?>" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i> 編輯商品
                        </a>

                        <a href="product_delete.php?id=<?= $product["id"] ?>" class="btn btn-danger" onclick="return confirm('確定要刪除這個商品嗎？');">
                            <i class="fa-solid fa-trash"></i> 刪除商品
                        </a>
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