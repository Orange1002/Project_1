<?php
session_start();
$id = (int)$_GET["id"]; // 轉為整數，增強安全性

if (!isset($_GET["id"])) {
    header("location: hotel-list.php");
    exit;
}

require_once "../db_connect_bark_bijou.php";

// 查詢當前旅館的詳細資料
$sql = "SELECT hotel.*, type.name AS type_name 
        FROM hotel 
        JOIN type ON hotel.type_id = type.id 
        WHERE hotel.id = $id AND hotel.valid = 1";
$result = $conn->query($sql);
$hotel = $result->fetch_assoc();
$hotelCount = $result->num_rows;

// 查詢所有類型以生成下拉選單
$typeSql = "SELECT id, name FROM type";
$typeResult = $conn->query($typeSql);
$types = $typeResult->fetch_all(MYSQLI_ASSOC);
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

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php include("../css.php") ?>
    <style>
        .primary {
            background-color: rgba(245, 160, 23, 0.919);
        }

        .card {
            border: none;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card .p-4 {
            padding: 24px;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: rgba(17, 136, 179, 0.96);
            box-shadow: 0 0 0 0.1rem rgba(17, 136, 179, 0.96);
        }

        .img-fluid {
            border-radius: 8px;
            transition: transform 0.3s ease;
            /* width: 100%; */
            /* max-height: 300px; */
            object-fit: cover;
        }

        .img-fluid:hover {
            transform: scale(1.03);
        }

        .fw-bold {
            font-weight: bold;
            color: #333;
        }

        .btn-custom {
            background-color: rgba(17, 136, 179, 0.96);
            border-color: rgba(17, 136, 179, 0.96);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: rgba(245, 160, 23, 0.919);
            border-color: rgba(245, 160, 23, 0.919);
            color: #fff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
            color: #fff;
        }

        .image-container {
            position: relative;
            cursor: pointer;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }

        .image-container:hover .image-overlay {
            opacity: 1;
        }
    </style>
</head>

<body id="page-top">
    <!-- Modal Delete -->
    <div class="modal fade" id="infoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">系統資訊</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    確認刪除?
                </div>
                <div class="modal-footer">
                    <a role="button" class="btn btn-danger" href="hotelDelete.php?id=<?= $hotel["id"] ?>">確認</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion primary" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../user/users.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bark & Bijou</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active"><a class="nav-link" href="../user/users.php"><i class="fa-solid fa-user"></i><span>會員專區</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../products/products.php"><i class="fa-solid fa-user"></i><span>商品列表</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../course/course.php"><i class="fa-solid fa-user"></i><span>課程管理</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="hotel-list.php"><i class="fa-solid fa-user"></i><span>旅館管理</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../article/article-list.php"><i class="fa-solid fa-user"></i><span>文章管理</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="../coupon/coupon.php"><i class="fa-solid fa-user"></i><span>優惠券管理</span></a></li>
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
                    <div class="container">
                        <div class="text-left mb-4 position-absolute">
                            <a href="hotel.php?id=<?= $hotel['id'] ?>" class="btn btn-custom">
                                <i class="fa-solid fa-arrow-left fa-fw"></i> 返回
                            </a>
                        </div>

                        <?php if ($hotel): ?>
                            <h2 class="mb-4 text-center">編輯旅館 - <?= htmlspecialchars($hotel['hotel_name']); ?></h2>

                            <!-- 卡片式表單 -->
                            <div class="card shadow-sm p-4">
                                <form action="doUpdate.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $hotel['id'] ?>">

                                    <!-- ID -->
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label fw-bold">ID</label>
                                        <div class="col-md-9">
                                            <p class="form-control-plaintext"><?= htmlspecialchars($hotel['id']); ?></p>
                                        </div>
                                    </div>

                                    <!-- 旅館名稱 -->
                                    <div class="row mb-3">
                                        <label for="hotel_name" class="col-md-3 col-form-label fw-bold">旅館名稱</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="hotel_name" name="hotel_name" value="<?= htmlspecialchars($hotel['hotel_name']); ?>" required>
                                        </div>
                                    </div>

                                    <!-- 圖片編輯 -->
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label fw-bold">圖片</label>
                                        <div class="col-md-9">
                                            <div class="image-container">
                                                <?php
                                                $image_src = (!empty($hotel['image_path']) && file_exists($hotel['image_path']))
                                                    ? htmlspecialchars($hotel['image_path'])
                                                    : './uploads/default_hotels.jpg';
                                                ?>
                                                <img src="<?= $image_src; ?>" class="img-fluid rounded mb-2" id="imagePreview" alt="<?= htmlspecialchars($hotel['hotel_name']); ?>">
                                                <div class="image-overlay">點擊更換圖片</div>
                                            </div>
                                            <input type="file" class="form-control d-none" id="hotel_image" name="hotel_image" accept="image/*" onchange="previewImage(event)">
                                        </div>
                                    </div>

                                    <!-- 類型 -->
                                    <div class="row mb-3">
                                        <label for="type_id" class="col-md-3 col-form-label fw-bold">類型</label>
                                        <div class="col-md-9">
                                            <select class="form-control" id="type_id" name="type_id" required>
                                                <?php foreach ($types as $type): ?>
                                                    <option value="<?= $type['id']; ?>" <?= $type['id'] == $hotel['type_id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($type['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 簡介 -->
                                    <div class="row mb-3">
                                        <label for="introduction" class="col-md-3 col-form-label fw-bold">簡介</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" id="introduction" name="introduction" rows="3"><?= htmlspecialchars($hotel['introduction']); ?></textarea>
                                        </div>
                                    </div>

                                    <!-- 每晚價格 -->
                                    <div class="row mb-3">
                                        <label for="price_per_night" class="col-md-3 col-form-label fw-bold">每晚價格</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" id="price_per_night" name="price_per_night" value="<?= htmlspecialchars($hotel['price_per_night']); ?>" required>
                                        </div>
                                    </div>
                                    <!-- 每晚價格 -->
                                    <div class="row mb-3">
                                        <label for="total_rooms" class="col-md-3 col-form-label fw-bold">房間數量</label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" id="total_rooms" name="total_rooms" value="<?= htmlspecialchars($hotel['total_rooms']); ?>" required>
                                        </div>
                                    </div>

                                    <!-- 地址 -->
                                    <div class="row mb-3">
                                        <label for="address" class="col-md-3 col-form-label fw-bold">地址</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($hotel['address']); ?>" required>
                                        </div>
                                    </div>

                                    <!-- 電話 -->
                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-3 col-form-label fw-bold">電話</label>
                                        <div class="col-md-9">
                                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($hotel['phone']); ?>" required>
                                        </div>
                                    </div>

                                    <!-- 操作按鈕 -->
                                    <div class="d-flex justify-content-between mt-4">
                                        <button class="btn btn-custom" type="submit">
                                            <i class="fa-solid fa-floppy-disk fa-fw"></i> 儲存
                                        </button>
                                        <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#infoModal" href="hotelDelete.php?id=<?= $hotel['id']; ?>">
                                            <i class="fa-solid fa-trash fa-fw"></i> 刪除
                                        </a>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <h2 class="text-center mt-5">旅館不存在</h2>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("./js.php") ?>
    <script>
        // 點擊圖片觸發檔案上傳
        document.querySelector('.image-container').addEventListener('click', function() {
            document.getElementById('hotel_image').click();
        });

        // 預覽新上傳的圖片
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
<?php
mysqli_close($conn);
?>