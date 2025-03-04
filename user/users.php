<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: sign_in.php");
    exit;
}
require_once("../db_connect_bark_bijou.php");

// 預設初始化變數
$gender_id = isset($_GET['gender_id']) ? $_GET['gender_id'] : "";
$q = isset($_GET['q']) ? $_GET['q'] : "";
$order = isset($_GET['order']) ? $_GET['order'] : 1;
$idSearch = isset($_GET['idSearch']) ? $_GET['idSearch'] : "";  // 新增 ID 參數

// 先查詢所有使用者的數量
$sqlAll = "SELECT users.*, COALESCE(gender.name, '不願透露') AS gender_name 
           FROM users
           LEFT JOIN gender ON users.gender_id = gender.id
           WHERE users.valid = 1";
$resultAll = $conn->query($sqlAll);
$userCount = $resultAll->num_rows;

// 根據搜尋、性別篩選、排序和分頁處理查詢
$whereClause = "WHERE users.valid = 1";  // 基本篩選條件

// 檢查是否有性別篩選條件
if ($gender_id !== "") {
    if ($gender_id === "null") {
        $whereClause .= " AND users.gender_id IS NULL";
    } else {
        $whereClause .= " AND users.gender_id = " . (int)$gender_id;
    }
}

// 檢查是否有搜尋關鍵字
if ($q !== "") {
    $whereClause .= " AND users.name LIKE '%$q%'";
}

// 檢查是否有 ID 搜尋條件
if ($idSearch !== "") {
    $whereClause .= " AND users.id = " . (int)$idSearch;  // 增加 ID 搜尋條件
}

$orderClause = "";
// 檢查是否有排序條件
switch ($order) {
    case 1:
        $orderClause = "ORDER BY users.id ASC";
        break;
    case 2:
        $orderClause = "ORDER BY users.id DESC";
        break;
    case 3:
        $orderClause = "ORDER BY users.name DESC";
        break;
    case 4:
        $orderClause = "ORDER BY users.name ASC";
        break;
    case 5:
        $orderClause = "ORDER BY users.created_at DESC";
        break;
    case 6:
        $orderClause = "ORDER BY users.created_at ASC";
        break;
}

// 分頁處理
$perPage = 10;
$p = isset($_GET["p"]) ? $_GET["p"] : 1;
$startItem = ($p - 1) * $perPage;

// 根據條件構建最終的查詢，並獲取篩選後的用戶數量
$sqlFilteredCount = "SELECT COUNT(*) AS totalCount 
                     FROM users 
                     LEFT JOIN gender ON users.gender_id = gender.id 
                     $whereClause";
$resultFilteredCount = $conn->query($sqlFilteredCount);
$row = $resultFilteredCount->fetch_assoc();
$filteredUserCount = $row['totalCount'];

// 計算篩選後的總頁數
$totalPage = ceil($filteredUserCount / $perPage);

// 根據條件構建最終的查詢
$sql = "SELECT users.*, COALESCE(gender.name, '不願透露') AS gender_name 
        FROM users 
        LEFT JOIN gender ON users.gender_id = gender.id 
        $whereClause $orderClause 
        LIMIT $startItem, $perPage";

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// 生成查詢字串 (保持搜尋、性別篩選和排序條件)
$queryString = "order=" . urlencode($order); // 保留排序
if ($gender_id !== "") {
    $queryString .= "&gender_id=" . urlencode($gender_id); // 保留性別篩選
}
if ($q !== "") {
    $queryString .= "&q=" . urlencode($q); // 保留搜尋條件
}
if ($idSearch !== "") {
    $queryString .= "&id=" . urlencode($idSearch); // 保留 ID 篩選
}
if (!isset($_GET["q"]) && !isset($_GET["gender_id"]) && !isset($_GET["p"]) && !isset($_GET["order"]) && !isset($_GET["idSearch"])) {
    header("location: users.php?p=1&order=1");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Bark & Bijou users</title>
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

        .list-btn a {
            color: #ffc107;
            background-color: transparent;
        }

        .list-btn a:hover {
            color: #b8860b;
            background-color: transparent;
        }

        .list-btn a:focus {
            color: rgb(255, 184, 113);
            background-color: transparent;
        }

        .list-btn a:active {
            color: #b8860b;
            background-color: transparent;
        }

        .list-btn a.active {
            color: #b8860b;
            background-color: transparent;
        }

        .list-btn .btn {
            border: none;
            box-shadow: none;
            outline: none;
        }

        .pagination .page-link {
            background-color: #ffc107;
            color: white;
            border-color: #f8f9fa;
        }

        .pagination .page-link:hover {
            background-color: #ffca2c;
            border-color: #ffc720;
        }

        .pagination .page-link:focus {
            box-shadow: rgb(217, 164, 6);
        }

        .pagination .active .page-link {
            background-color: rgb(219, 161, 16);
            border-color: rgb(219, 161, 16);
        }

        .nav-pills .nav-link {
            color: #333;
            background-color: #ffc107;
        }

        .nav-pills .nav-link.active {
            color: white;
            background-color: rgb(222, 167, 0);
            font-weight: bold;
        }

        .nav-pills .nav-link:hover {
            color: #fff;
            background-color: rgb(255, 215, 95);
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
                    <div class="d-flex justify-content-between mb-1">
                        <h1 class="h3 mb-0 text-gray-800">會員管理</h1>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <div class="container-fluid">
                            <div class="py-2 row align-items-center mb-3">
                                <div class="col-md-3">
                                    <div class="hstack gap-2 align-item-center">

                                        <div>共 <?= $filteredUserCount ?> 位使用者</div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-0">
                                        <div class="col-5 d-flex justify-content-end">
                                            <ul class="nav nav-pills gap-2">
                                                <!-- 全部 -->
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 <?= (!isset($_GET["gender_id"])) ? "active" : "" ?>"
                                                        href="users.php?p=1&order=<?= $order ?><?= isset($q) && $q !== "" ? '&q=' . urlencode($q) : '' ?><?= isset($idSearch) && $idSearch !== "" ? '&idSearch=' . urlencode($idSearch) : '' ?>">
                                                        全部
                                                    </a>
                                                </li>
                                                <!-- 男性 -->
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 <?= (isset($_GET["gender_id"]) && $_GET["gender_id"] == "1") ? "active" : "" ?>"
                                                        href="users.php?p=1&order=<?= $order ?>&gender_id=1<?= isset($q) && $q !== "" ? '&q=' . urlencode($q) : '' ?><?= isset($idSearch) && $idSearch !== "" ? '&idSearch=' . urlencode($idSearch) : '' ?>">男性</a>
                                                </li>
                                                <!-- 女性 -->
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 <?= (isset($_GET["gender_id"]) && $_GET["gender_id"] == "2") ? "active" : "" ?>"
                                                        href="users.php?p=1&order=<?= $order ?>&gender_id=2<?= isset($q) && $q !== "" ? '&q=' . urlencode($q) : '' ?><?= isset($idSearch) && $idSearch !== "" ? '&idSearch=' . urlencode($idSearch) : '' ?>">女性</a>
                                                </li>
                                                <!-- 不願透漏 -->
                                                <li class="nav-item">
                                                    <a class="nav-link py-1 px-2 <?= (isset($_GET["gender_id"]) && $_GET["gender_id"] == "3") ? "active" : "" ?>"
                                                        href="users.php?p=1&order=<?= $order ?>&gender_id=3<?= isset($q) && $q !== "" ? '&q=' . urlencode($q) : '' ?><?= isset($idSearch) && $idSearch !== "" ? '&idSearch=' . urlencode($idSearch) : '' ?>">不願透漏</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-7 d-flex justify-content-between">
                                            <form action="users.php" method="get" class="me-3" onsubmit="return validateSearch()">
                                                <div class="input-group">
                                                    <!-- 頁碼設置為 1 -->
                                                    <input type="hidden" name="p" value="1">
                                                    <!-- 保留排序條件 -->
                                                    <input type="hidden" name="order" value="<?= isset($order) ? $order : 1 ?>">
                                                    <!-- 保留性別篩選條件 -->
                                                    <?php if (isset($gender_id) && $gender_id !== ""): ?>
                                                        <input type="hidden" name="gender_id" value="<?= $gender_id ?>">
                                                    <?php endif; ?>
                                                    <!-- 搜尋名稱欄位 -->
                                                    <input type="search" placeholder="透過使用者名稱搜尋" class="form-control ms-3" name="q" value="<?= isset($q) ? $q : '' ?>">
                                                    <button class="btn btn-warning rounded-end" type="submit">
                                                        <i class="fa-solid fa-magnifying-glass fa-fw"></i>
                                                    </button>
                                                    <?php if (isset($_GET["q"])) : ?>
                                                        <a class="btn btn-secondary rounded ms-2" href="users.php?p=1&order=1">清空</a>
                                                    <?php endif; ?>
                                                </div>
                                            </form>
                                            <form action="users.php" method="get" onsubmit="return validateSearch()">
                                                <div class="input-group">
                                                    <!-- 頁碼設置為 1 -->
                                                    <input type="hidden" name="p" value="1">
                                                    <!-- 保留排序條件 -->
                                                    <input type="hidden" name="order" value="<?= isset($order) ? $order : 1 ?>">
                                                    <!-- 保留性別篩選條件 -->
                                                    <?php if (isset($gender_id) && $gender_id !== ""): ?>
                                                        <input type="hidden" name="gender_id" value="<?= $gender_id ?>">
                                                    <?php endif; ?>
                                                    <!-- 搜尋 ID 欄位 -->
                                                    <input type="search" placeholder="透過ID搜尋" class="form-control" name="idSearch" value="<?= isset($idSearch) ? $idSearch : '' ?>">
                                                    <button class="btn btn-warning rounded-end" type="submit">
                                                        <i class="fa-solid fa-magnifying-glass fa-fw"></i>
                                                    </button>
                                                    <?php if (isset($_GET["idSearch"])) : ?>
                                                        <a class="btn btn-secondary rounded ms-2" href="users.php?p=1&order=1">清空</a>
                                                    <?php endif; ?>
                                                </div>
                                            </form>
                                            <div>
                                                <a href="user_create.php?id=<?= htmlspecialchars($row['id'] ?? '') ?>&p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?>&order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['idSearch']) ? '&idSearch=' . $_GET['idSearch'] : '' ?>" class="btn btn-warning"><i class="fa-solid fa-user-plus fa-fw"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($userCount > 0): ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">
                                                <div class="row g-0">
                                                    <div class="d-flex align-items-center justify-content-end col-7 p-0">
                                                        id
                                                    </div>
                                                    <?php
                                                    $next_order = ($order == 1) ? 2 : 1; // 切換排序
                                                    $icon = ($order == 1) ? "fa-caret-down" : "fa-caret-up"; // 切換圖標
                                                    ?>
                                                    <div class="col-5 list-btn">
                                                        <a href="users.php?p=<?= $p ?>&order=<?= $next_order ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?>"
                                                            class="d-flex btn p-0 <?= ($order == 1 || $order == 2) ? "active" : "" ?>">
                                                            <i class="fa-solid <?= $icon ?>"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-middle">
                                                <div class="row g-0">
                                                    <div class="d-flex align-items-center justify-content-end col-8 p-0">
                                                        使用者名稱
                                                    </div>
                                                    <?php
                                                    // 確定當前排序狀態，並計算下一次點擊的排序值
                                                    $next_order = ($order == 4) ? 3 : 4; // 4=升冪，3=降冪
                                                    $icon = ($order == 4) ? "fa-caret-down" : "fa-caret-up"; // 圖標變更
                                                    ?>
                                                    <div class="col-4 list-btn">
                                                        <a href="users.php?p=<?= $p ?>&order=<?= $next_order ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?>"
                                                            class="d-flex btn p-0 <?= ($order == 3 || $order == 4) ? "active" : "" ?>">
                                                            <i class="fa-solid <?= $icon ?>"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="align-middle text-center">性別</th>
                                            <th class="align-middle text-center">手機號碼</th>
                                            <th class="align-middle text-center">電子信箱</th>
                                            <th class="align-middle text-center">
                                                <div class="row g-0">
                                                    <div class="d-flex align-items-center justify-content-end col-7 p-0">
                                                        加入時間
                                                    </div>
                                                    <?php
                                                    // 計算下一個排序狀態
                                                    $next_order = ($order == 6) ? 5 : 6; // 6=升冪，5=降冪
                                                    $icon = ($order == 6) ? "fa-caret-down" : "fa-caret-up"; // 根據當前狀態變更圖示
                                                    ?>
                                                    <div class="col-4 list-btn">
                                                        <a href="users.php?p=<?= $p ?>&order=<?= $next_order ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?>"
                                                            class="d-flex btn p-0 <?= ($order == 5 || $order == 6) ? "active" : "" ?>">
                                                            <i class="fa-solid <?= $icon ?>"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row): ?>
                                            <!-- Modal (為每個使用者生成獨立的 Modal) -->
                                            <div class="modal fade" id="infoModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">系統資訊</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            確認 <span class="fw-bold text-danger">刪除</span> 會員 <?= htmlspecialchars($row["name"]) ?> ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <?php
                                                            // 取得當前網址參數，並去除 id 避免影響刪除操作
                                                            $queryParams = $_GET;
                                                            unset($queryParams["id"]); // 刪除 id 避免影響後續跳轉

                                                            // 重新組合查詢字串
                                                            $queryString = http_build_query($queryParams);
                                                            ?>
                                                            <a role="button" class="btn btn-danger" href="doDeleteUser.php?id=<?= $row["id"] ?>&<?= $queryString ?>">確認</a>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <tr>
                                                <td class="align-middle text-center"><?= $row["id"] ?></td>
                                                <td class="align-middle text-center"><?= $row["name"] ?></td>
                                                <td class="align-middle text-center"><?= $row["gender_name"] ?></td>
                                                <td class="align-middle text-center"><?= $row["phone"] ?></td>
                                                <td class="align-middle text-center"><?= $row["email"] ?></td>
                                                <td class="align-middle text-center"><?= $row["created_at"] ?></td>
                                                <td class="align-middle text-center p-0">
                                                    <a href="user_edit.php?id=<?= $row['id'] ?>&p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?>&order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?><?= isset($_GET['idSearch']) ? '&idSearch=' . $_GET['idSearch'] : '' ?>" class="btn btn-success btn-sm"><i class="fa-solid fa-fw fa-pen"></i></a>

                                                    <a href="user_view.php?id=<?= $row['id'] ?>&p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?>&order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?><?= isset($_GET['idSearch']) ? '&idSearch=' . $_GET['idSearch'] : '' ?>" class="btn btn-primary btn-sm"><i class="fa-regular fa-eye fa-fw"></i></a>

                                                    <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal<?= $row['id'] ?>"><i class="fa-solid fa-trash fa-fw"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <a href="user_deleted.php?p=1&oeder=1" class="btn btn-danger">已刪除會員</a>
                                </div>
                                <?php
                                // 取得當前的頁碼
                                $p = isset($_GET["p"]) ? $_GET["p"] : 1;
                                // 取得排序方式
                                $order = isset($_GET["order"]) ? $_GET["order"] : 1;
                                // 取得篩選的 gender_id
                                $gender_id = isset($_GET["gender_id"]) ? $_GET["gender_id"] : "";
                                // 取得搜尋關鍵字 q 和 idSearch
                                $q = isset($_GET["q"]) ? $_GET["q"] : "";
                                $idSearch = isset($_GET["idSearch"]) ? $_GET["idSearch"] : "";

                                // 組合 URL 查詢字串
                                $queryString = "order={$order}";
                                if ($gender_id !== "") {
                                    $queryString .= "&gender_id={$gender_id}";
                                }
                                if ($q !== "") {
                                    $queryString .= "&q={$q}";
                                }
                                if ($idSearch !== "") {
                                    $queryString .= "&idSearch={$idSearch}";
                                }
                                ?>
                                <?php if (isset($_GET["p"])): ?>
                                    <div class="d-flex justify-content-center">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                <!-- 第一頁 -->
                                                <?php if ($p > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link fs-5" href="users.php?p=1&<?= $queryString ?>">&lt;&lt;</a>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- 上一頁 -->
                                                <?php if ($p > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link fs-5" href="users.php?p=<?= max(1, $p - 1) ?>&<?= $queryString ?>" aria-label="Previous">&lt;</a>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- 動態頁碼顯示 -->
                                                <?php
                                                $start = max(1, $p - 2);
                                                $end = min($totalPage, $p + 2);
                                                for ($i = $start; $i <= $end; $i++): ?>
                                                    <li class="page-item <?= ($i == $p) ? "active" : "" ?>">
                                                        <a class="page-link fs-5" href="users.php?p=<?= $i ?>&<?= $queryString ?>"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <!-- 下一頁 -->
                                                <?php if ($p < $totalPage): ?>
                                                    <li class="page-item">
                                                        <a class="page-link fs-5" href="users.php?p=<?= min($totalPage, $p + 1) ?>&<?= $queryString ?>" aria-label="Next">&gt;</a>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- 最後一頁 -->
                                                <?php if ($p < $totalPage): ?>
                                                    <li class="page-item">
                                                        <a class="page-link fs-5" href="users.php?p=<?= $totalPage ?>&<?= $queryString ?>">&gt;&gt;</a>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- 搜尋框 -->
                                                <li class="page-item ms-3">
                                                    <form action="users.php" method="GET" class="d-flex">
                                                        <input type="hidden" name="order" value="<?= $order ?>">
                                                        <?php if ($gender_id !== ""): ?>
                                                            <input type="hidden" name="gender_id" value="<?= $gender_id ?>">
                                                        <?php endif; ?>
                                                        <input type="number" name="p" class="form-control rounded-0 p-0 text-warning fw-bold fs-5" min="1" max="<?= $totalPage ?>" value="<?= $p ?>" style="width: 70px; text-align: center;">
                                                        <button type="submit" class="btn bg-light text-warning btn-sm rounded-0 fw-bold fs-5 ms-2">Go</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
    <script>
        function validateSearch() {
            var q = document.getElementsByName("q")[0].value;
            var idSearch = document.getElementsByName("idSearch")[0].value;

            // 如果 q 和 idSearch 都是空的，則顯示警告並不提交表單
            if (q.trim() === "" && idSearch.trim() === "") {
                alert("請至少輸入一個搜尋條件");
                return false; // 阻止表單提交
            }
            return true; // 允許提交表單
        }
    </script>
</body>

</html>