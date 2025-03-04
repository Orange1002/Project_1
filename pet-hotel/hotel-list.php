<?php
require_once "./db_connect_bark_bijou.php";

// 排序處理
$sort = isset($_GET['sort']) && in_array(strtoupper($_GET['sort']), ['ASC', 'DESC'])
    ? strtoupper($_GET['sort'])
    : 'ASC';
$sortColumn = isset($_GET['sort_column']) && in_array($_GET['sort_column'], ['id', 'price_per_night'])
    ? $_GET['sort_column']
    : 'id';

// 分頁參數
$perpage = 10;
$p = isset($_GET["p"]) ? max(1, (int)$_GET["p"]) : 1;
$startItem = ($p - 1) * $perpage;

// 基礎 SQL
$sqlBase = "SELECT hotel.*, type.name AS type_name 
            FROM hotel 
            JOIN type ON hotel.type_id = type.id 
            WHERE valid = 1";
$whereClause = "";

// 搜尋條件
if (isset($_GET["q"])) {
    $q = $conn->real_escape_string($_GET["q"]);
    $whereClause .= " AND (hotel_name LIKE '%$q%' OR address LIKE '%$q%')";
}

// 過濾條件（例如按類型）
if (isset($_GET["filter"])) {
    $filter = $conn->real_escape_string($_GET["filter"]);
    $whereClause .= " AND type.name = '$filter'";
}

// 篩選有空房的旅館
if (isset($_GET["available"]) && $_GET["available"] == 1) {
    $whereClause .= " AND (hotel.total_rooms - (
        SELECT COUNT(*) 
        FROM bookings 
        WHERE bookings.hotel_id = hotel.id 
        AND bookings.check_in <= CURDATE() 
        AND bookings.check_out > CURDATE()
    )) > 0";
}

// 計算總數
$countSql = "SELECT COUNT(*) as total 
             FROM hotel 
             JOIN type ON hotel.type_id = type.id 
             WHERE valid = 1" . $whereClause;
$resultAll = $conn->query($countSql);
$hotelCount = $resultAll->fetch_assoc()['total'];
$totalPage = ceil($hotelCount / $perpage);

// 最終 SQL
$sql = "$sqlBase $whereClause ORDER BY hotel.$sortColumn $sort LIMIT $startItem, $perpage";

// 執行查詢
$result = $conn->query($sql);
$hotels = $result->fetch_all(MYSQLI_ASSOC);
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
    <?php include("../pet-hotel/css2.php") ?>
    <?php include("../css.php") ?>
    <style>
        .primary {
            background-color: rgba(245, 160, 23, 0.919);
        }

        .btn-primary {
            background-color: rgba(17, 136, 179, 0.96);
            border-color: rgba(17, 136, 179, 0.96);
        }

        .btn-primary:hover {
            background-color: rgba(245, 160, 23, 0.919);
            border-color: rgba(245, 160, 23, 0.919);
            color: #fff;
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
                    <?php
                    if (isset($_GET['error']) && $_GET['error'] == 'fully_booked') {
                        echo '<div class="alert alert-danger">該時段房間已滿，請選擇其他日期或旅館</div>';
                    }
                    if (isset($_GET['success']) && $_GET['success'] == 'booking_confirmed') {
                        echo '<div class="alert alert-success">預約成功！</div>';
                    }
                    if (isset($_GET['error']) && $_GET['error'] == 'booking_failed') {
                        echo '<div class="alert alert-danger">預約失敗，請稍後再試</div>';
                    }
                    ?>
                    <div class="text-center">
                        <div class="text-left">
                            <a href="hotel-create.php" class="btn btn-primary position-absolute">新增旅館</a>
                        </div>
                        <h4 class="my-4">寵物旅館列表</h4>
                        <?php if (isset($_GET["q"])): ?>
                            <div class="position-absolute">
                                <a href="hotel-list.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                            </div>
                        <?php endif; ?>
                        <form action="" method="get">
                            <div class="filters mb-3">
                                <a href="?" class="btn <?php echo !isset($_GET['filter']) && !isset($_GET['available']) ? 'btn-primary' : 'btn-light'; ?>">全部</a>
                                <a href="?filter=迷你犬<?php echo isset($_GET['q']) ? '&q=' . htmlspecialchars($_GET['q']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : ''; ?><?php echo isset($_GET['available']) ? '&available=' . htmlspecialchars($_GET['available']) : ''; ?>"
                                    class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] === '迷你犬') ? 'btn-primary' : 'btn-light'; ?>">迷你犬</a>
                                <a href="?filter=小型犬<?php echo isset($_GET['q']) ? '&q=' . htmlspecialchars($_GET['q']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : ''; ?><?php echo isset($_GET['available']) ? '&available=' . htmlspecialchars($_GET['available']) : ''; ?>"
                                    class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] === '小型犬') ? 'btn-primary' : 'btn-light'; ?>">小型犬</a>
                                <a href="?filter=中型犬<?php echo isset($_GET['q']) ? '&q=' . htmlspecialchars($_GET['q']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : ''; ?><?php echo isset($_GET['available']) ? '&available=' . htmlspecialchars($_GET['available']) : ''; ?>"
                                    class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] === '中型犬') ? 'btn-primary' : 'btn-light'; ?>">中型犬</a>
                                <a href="?filter=大型犬<?php echo isset($_GET['q']) ? '&q=' . htmlspecialchars($_GET['q']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : ''; ?><?php echo isset($_GET['available']) ? '&available=' . htmlspecialchars($_GET['available']) : ''; ?>"
                                    class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] === '大型犬') ? 'btn-primary' : 'btn-light'; ?>">大型犬</a>
                                <!-- 新增「有空房」按鈕 -->
                                <a href="?available=1<?php echo isset($_GET['filter']) ? '&filter=' . htmlspecialchars($_GET['filter']) : ''; ?><?php echo isset($_GET['q']) ? '&q=' . htmlspecialchars($_GET['q']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : ''; ?><?php echo isset($_GET['sort_column']) ? '&sort_column=' . htmlspecialchars($_GET['sort_column']) : ''; ?>"
                                    class="btn <?php echo (isset($_GET['available']) && $_GET['available'] == 1) ? 'btn-primary' : 'btn-light'; ?>">有空房</a>
                                <input type="text" name="q" placeholder="搜尋..." value="<?= htmlspecialchars($_GET["q"] ?? ""); ?>">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass fa-fw"></i></button>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="position-absolute">共 <?= $hotelCount ?> 間旅館</div>
                                </div>
                            </div>
                        </form>

                        <!-- 表格部分 -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">
                                            <a class="text-decoration-none" href="?sort_column=id&sort=<?= $sort == 'ASC' ? 'DESC' : 'ASC' ?>&<?= http_build_query(array_diff_key($_GET, ['sort' => '', 'sort_column' => ''])) ?>">
                                                <?= $sortColumn == 'id' && $sort == 'ASC' ? 'ID↑' : ($sortColumn == 'id' ? 'ID↓' : 'ID') ?>
                                            </a>
                                        </th>
                                        <th scope="col">圖片</th>
                                        <th scope="col">飯店名稱</th>
                                        <th scope="col">簡介</th>
                                        <th scope="col">類型</th>
                                        <th scope="col">
                                            <a class="text-decoration-none" href="?sort_column=price_per_night&sort=<?= $sort == 'ASC' ? 'DESC' : 'ASC' ?>&<?= http_build_query(array_diff_key($_GET, ['sort' => '', 'sort_column' => ''])) ?>">
                                                <?= $sortColumn == 'price_per_night' && $sort == 'ASC' ? '價格↑' : ($sortColumn == 'price_per_night' ? '價格↓' : '價格') ?>
                                            </a>
                                        </th>
                                        <th scope="col">地址</th>
                                        <th scope="col">電話</th>
                                        <th scope="col">狀態</th>
                                        <th scope="col">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hotels as $hotel): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($hotel['id']); ?></td>
                                            <td>
                                                <?php
                                                $image_src = (!empty($hotel['image_path']) && file_exists($hotel['image_path']))
                                                    ? htmlspecialchars($hotel['image_path'])
                                                    : './uploads/default_hotels.jpg';
                                                ?>
                                                <img src="<?= $image_src; ?>" alt="<?= htmlspecialchars($hotel['hotel_name']); ?>" class="img-thumbnail" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                            </td>
                                            <td><?= htmlspecialchars($hotel['hotel_name']); ?></td>
                                            <td><?= htmlspecialchars($hotel['introduction']); ?></td>
                                            <td><?= htmlspecialchars($hotel['type_name']); ?></td>
                                            <td class="text-success fw-bold">$<?= number_format($hotel['price_per_night']); ?></td>
                                            <td><?= htmlspecialchars($hotel['address']); ?></td>
                                            <td><?= htmlspecialchars($hotel['phone']); ?></td>
                                            <td>
                                                <?php
                                                $sqlBooked = "SELECT COUNT(*) FROM bookings WHERE hotel_id = ? AND check_in <= CURDATE() AND check_out > CURDATE()";
                                                $stmtBooked = $conn->prepare($sqlBooked);
                                                $stmtBooked->bind_param("i", $hotel['id']);
                                                $stmtBooked->execute();
                                                $booked = $stmtBooked->get_result()->fetch_row()[0];
                                                $stmtBooked->close();

                                                $available = $hotel['total_rooms'] - $booked;

                                                if ($available == 0) {
                                                    echo '<span class="badge bg-danger">無空房</span>';
                                                } elseif ($available <= 3) {
                                                    echo '<span class="badge bg-warning">有空房（剩 ' . $available . ' 間）</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">有空房（剩 ' . $available . ' 間）</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="hotel-edit.php?id=<?= $hotel['id'] ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-edit fa-fw"></i></a>
                                                    <a href="hotel.php?id=<?= $hotel['id'] ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-eye fa-fw"></i></a>
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal" onclick="setHotelId(<?= $hotel['id'] ?>)">
                                                        <i class="fa-solid fa-calendar-check fa-fw"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- 預約彈窗 -->
                        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bookingModalLabel">預約旅館</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="bookingForm" action="doBook.php" method="post">
                                            <input type="hidden" name="hotel_id" id="hotel_id" value="">
                                            <div class="mb-3">
                                                <label for="check_in" class="form-label">入住日期</label>
                                                <input type="date" class="form-control" id="check_in" name="check_in" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="check_out" class="form-label">退房日期</label>
                                                <input type="date" class="form-control" id="check_out" name="check_out" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">提交預約</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function setHotelId(id) {
                                document.getElementById('hotel_id').value = id;
                            }
                        </script>

                        <!-- 分頁 -->
                        <div class="mt-3">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <?php
                                        $params = $_GET; // 複製當前頁面的所有 GET 參數
                                        $params['p'] = $i; // 更新頁碼參數
                                        $queryString = http_build_query($params); // 將參數轉為 URL 查詢字串
                                        $active = ($i == $p) ? "active" : ""; // 判斷當前頁面是否為活動頁
                                        ?>
                                        <li class="page-item <?= $active ?>">
                                            <a class="page-link" href="hotel-list.php?<?= $queryString ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>



        </div>


</body>
<?php include("../js.php") ?>

</html>
<?php
mysqli_close($conn);
?>