<?php
session_start();
require_once("../pdo_connect_bark_bijou.php");


$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    die("❌ 錯誤：缺少商品 ID");
}


$stmt = $db_host->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

$stmt = $db_host->prepare("SELECT img_url FROM product_images WHERE product_id = :id LIMIT 1");
$stmt->execute([':id' => $product_id]);
$image = $stmt->fetch();
$product_image = $image['img_url'] ?? 'uploads/default.png';

$vendors = $db_host->query("SELECT * FROM vendors")->fetchAll();
$categories = $db_host->query("SELECT * FROM product_categories")->fetchAll();
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
                        <h1 class="h3 mb-0 text-gray-800">商品編輯</h1>
                    </div>
                    <div class="py-2">
                        <a class="btn btn-primary" href="products.php"><i class="fa-solid fa-arrow-left fa-fw"></i> 返回商品列表</a>
                    </div>
                    <form action="process_edit_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product["id"]) ?>">

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">目前商品圖片</label>
                                <div class="mb-3">
                                    <img id="imagePreview" src="<?= htmlspecialchars($product_image) ?>" class="img-fluid border">
                                </div>
                                <label class="form-label">更換圖片（選填）</label>
                                <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*" onchange="previewImage(event)">
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">商品名稱</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($product["product_name"]) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">供應商</label>
                                    <select class="form-control" id="vendor_id" name="vendor_id" required>
                                        <?php foreach ($vendors as $vendor): ?>
                                            <option value="<?= $vendor['vendor_id'] ?>" <?= $vendor['vendor_id'] == $product["vendor_id"] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($vendor['vendor_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">產品分類</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $product["category_id"] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">價格 (TWD)</label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= $product["price"] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">庫存</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="<?= $product["stock"] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">商品描述</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($product["description"]) ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save fa-fw"></i> 更新商品</button>
                                <a href="product_view.php?id=<?= $product["id"] ?>" class="btn btn-info btn">
                                    <i class="fa-solid fa-eye fa-fw"></i> 檢視
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="float-end">
                        <div>
                            <form action="process_update_status.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" name="status" value="<?= ($product['status'] === 'active') ? 'inactive' : 'active' ?>"
                                    class="btn <?= ($product['status'] === 'active') ? 'btn-danger' : 'btn-success' ?> ">
                                    <?= ($product['status'] === 'active') ? '🔴 下架商品' : '🟢 上架商品' ?>
                                </button>
                            </form>
                        </div>
                        <div class="text-end">
                            <?php if ($product["status"] === 'active'): ?>
                                <span class="badge bg-success ">上架中</span>
                            <?php else: ?>
                                <span class="badge bg-secondary ">已下架</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- End of Page Wrapper -->
            </div>
            <!-- Scroll to Top Button-->
        </div>
    </div>
    </div>

    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var imgElement = document.getElementById("imagePreview");
                imgElement.src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>