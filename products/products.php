<?php
session_start();


require_once("../pdo_connect_bark_bijou.php");

$items_per_page = 8;
$current_page = isset($_GET["page"]) ? max(1, intval($_GET["page"])) : 1;
$offset = ($current_page - 1) * $items_per_page;

$search = $_GET["search"] ?? "";
$category_id = $_GET["category_id"] ?? "";
$sort = $_GET["sort"] ?? "id_desc"; // 預設以 ID 降序排序

// 允許的排序選項
$sort_options = [
    "id_desc" => "id DESC",
    "id_asc" => "id ASC",
    "name_asc" => "product_name ASC",
    "name_desc" => "product_name DESC",
    "price_asc" => "price ASC",
    "price_desc" => "price DESC",
    "stock_asc" => "stock ASC",
    "stock_desc" => "stock DESC",
    "status_asc" => "status ASC",
    "status_desc" => "status DESC"
];

// 確保排序參數合法
$order_by = $sort_options[$sort] ?? "id DESC";

// 取得商品類別
$categories_stmt = $db_host->prepare("SELECT * FROM product_categories");
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll();

// 取得商品數量
$sql_count = "SELECT COUNT(*) FROM products WHERE valid = 1 AND product_name LIKE :search";
$params = [":search" => "%$search%"];

if (!empty($category_id)) {
    $sql_count .= " AND category_id = :category_id";
    $params[":category_id"] = $category_id;
}

$count_stmt = $db_host->prepare($sql_count);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// 取得商品列表
$sql = "SELECT products.*, 
       vendors.vendor_name, 
       COALESCE((SELECT img_url FROM product_images WHERE product_images.product_id = products.id LIMIT 1), 'uploads/default.png') AS img_url
    FROM products
    LEFT JOIN vendors ON products.vendor_id = vendors.vendor_id
    WHERE products.valid = 1 AND products.product_name LIKE :search";

if (!empty($category_id)) {
    $sql .= " AND products.category_id = :category_id";
}

// 加入排序
$sql .= " ORDER BY $order_by LIMIT :limit OFFSET :offset";

$params[":limit"] = $items_per_page;
$params[":offset"] = $offset;

$stmt = $db_host->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>商品列表</title>

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
                        <h1 class="h3 mb-0 text-gray-800">商品列表</h1>

                        <!-- 搜尋 + 類別篩選 + 排序 -->
                        <form method="GET" action="products.php" class="d-flex justify-content-between mb-3">
                            <div class="d-flex">
                                <!-- 類別篩選 -->
                                <select name="category_id" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="">所有類別</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category["category_id"] ?>" <?= ($category_id == $category["category_id"]) ? "selected" : "" ?>>
                                            <?= htmlspecialchars($category["category_name"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- 搜尋框 -->
                                <input type="text" name="search" class="form-control me-2" placeholder="搜尋商品..." value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <form method="GET" action="products.php" class="d-flex justify-content-end mb-2">
                        <!-- 排序方式 -->
                        <div>
                            <select name="sort" class="form-select ms-2" onchange="this.form.submit()">
                                <option value="id_desc" <?= ($sort == "id_desc") ? "selected" : "" ?>>ID 降序</option>
                                <option value="id_asc" <?= ($sort == "id_asc") ? "selected" : "" ?>>ID 升序</option>
                                <option value="name_asc" <?= ($sort == "name_asc") ? "selected" : "" ?>>商品名稱 A-Z</option>
                                <option value="name_desc" <?= ($sort == "name_desc") ? "selected" : "" ?>>商品名稱 Z-A</option>
                                <option value="price_asc" <?= ($sort == "price_asc") ? "selected" : "" ?>>價格 低→高</option>
                                <option value="price_desc" <?= ($sort == "price_desc") ? "selected" : "" ?>>價格 高→低</option>
                                <option value="stock_asc" <?= ($sort == "stock_asc") ? "selected" : "" ?>>庫存 少→多</option>
                                <option value="stock_desc" <?= ($sort == "stock_desc") ? "selected" : "" ?>>庫存 多→少</option>
                                <option value="status_asc" <?= ($sort == "status_asc") ? "selected" : "" ?>>狀態（上架優先）</option>
                                <option value="status_desc" <?= ($sort == "status_desc") ? "selected" : "" ?>>狀態（下架優先）</option>
                            </select>
                        </div>
                    </form>
                    <div class="py-2">

                        <a class="btn btn-success float-end mb-3" href="create_product.php?page=<?= $current_page ?>"><i class="fa-solid fa-plus fa-fw"></i> 新增商品</a>
                    </div>
                    <?php if (count($products) > 0): ?>
                        <table class="table table-bordered table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>圖片</th>
                                    <th>商品名稱</th>
                                    <th>商品類別</th>
                                    <th>供應商</th>
                                    <th>價格 (TWD)</th>
                                    <th>庫存</th>
                                    <th>狀態</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product["id"]) ?></td>
                                        <td><img src="<?= htmlspecialchars($product['img_url']) ?>"
                                                alt="商品圖片" class="img-thumbnail" style="width: 50px; height: 50px;"></td>
                                        <td><?= htmlspecialchars($product["product_name"]) ?></td>
                                        <td><?= htmlspecialchars($product["product_name"]) ?></td>
                                        <td><?= htmlspecialchars($product["vendor_name"]) ?></td>
                                        <td><?= number_format($product["price"]) ?> TWD</td>
                                        <td><?= $product["stock"] ?></td>
                                        <td>
                                            <?php if ($product["status"] === 'active'): ?>
                                                <span class="badge bg-success">上架中</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">已下架</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="product_edit.php?id=<?= $product["id"] ?>&page=<?= $current_page ?>" class="btn btn-primary btn-sm">
                                                <i class="fa-solid fa-pen fa-fw"></i> 編輯
                                            </a>
                                            <a href="product_view.php?id=<?= $product["id"] ?>&page=<?= $current_page ?>" class="btn btn-info btn-sm">
                                                <i class="fa-solid fa-eye fa-fw"></i> 檢視
                                            </a>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="<?= $product["id"] ?>" data-name="<?= htmlspecialchars($product["product_name"]) ?>" data-page="<?= $current_page ?>">
                                                <i class="fa-solid fa-trash fa-fw"></i> 刪除
                                            </button>
                                            <!-- 刪除確認 Modal -->
                                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel">刪除商品</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            你確定要刪除 <strong id="deleteProductName"></strong> 嗎？<br>
                                                            刪除後仍可在 回收站 還原。
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                            <a href="#" id="confirmDeleteBtn" class="btn btn-danger">確認刪除</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- 分頁 -->
                        <?php if ($total_pages > 1): ?>
                            <nav>
                                <ul class="pagination justify-content-center mt-3">
                                    <!-- 上一頁 -->
                                    <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?search=<?= urlencode($search) ?>&category_id=<?= urlencode($category_id) ?>&sort=<?= urlencode($sort) ?>&page=<?= max(1, $current_page - 1) ?>">«</a>
                                    </li>

                                    <?php
                                    $max_pages_to_show = 5; // 最多顯示 5 個頁碼（包含當前頁）
                                    $start_page = max(1, $current_page - 2);
                                    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);

                                    // 如果開始頁大於 1，顯示 "1 ..." 的省略符號
                                    if ($start_page > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?search=' . urlencode($search) . '&category_id=' . urlencode($category_id) . '&sort=' . urlencode($sort) . '&page=1">1</a></li>';
                                        if ($start_page > 2) {
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                    }

                                    // 顯示頁碼
                                    for ($i = $start_page; $i <= $end_page; $i++) {
                                        echo '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '">
                        <a class="page-link" href="?search=' . urlencode($search) . '&category_id=' . urlencode($category_id) . '&sort=' . urlencode($sort) . '&page=' . $i . '">' . $i . '</a>
                    </li>';
                                    }

                                    // 如果結束頁小於總頁數，顯示 "… 總頁數"
                                    if ($end_page < $total_pages) {
                                        if ($end_page < $total_pages - 1) {
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                        echo '<li class="page-item"><a class="page-link" href="?search=' . urlencode($search) . '&category_id=' . urlencode($category_id) . '&sort=' . urlencode($sort) . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                                    }
                                    ?>

                                    <!-- 下一頁 -->
                                    <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?search=<?= urlencode($search) ?>&category_id=<?= urlencode($category_id) ?>&sort=<?= urlencode($sort) ?>&page=<?= min($total_pages, $current_page + 1) ?>">»</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        <a href="products_deleted.php?page=<?= $current_page ?>" class="btn btn-warning mb-2 float-end">
                            <i class="fa-solid fa-trash fa-fw"></i> 回收站
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning">目前沒有商品。</div>
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
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var productId = button.getAttribute('data-id');
            var productName = button.getAttribute('data-name');
            var page = button.getAttribute('data-page'); // 取得當前頁數

            var modalProductName = document.getElementById('deleteProductName');
            modalProductName.textContent = productName;

            var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            confirmDeleteBtn.href = 'product_delete.php?id=' + productId + '&page=' + page;
        });
    </script>


</body>

</html>