<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location: sign_in.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET["id"])) {
    header("location: users.php");
    exit();
}

$id = $_GET["id"];
require_once("../db_connect_bark_bijou.php");
$sql = "SELECT users.*, 
               gender.name AS gender_name, 
               gender.id AS gender_id, 
               user_images.image AS image_path 
        FROM users
        LEFT JOIN gender ON users.gender_id = gender.id
        LEFT JOIN user_images ON users.id = user_images.user_id
        WHERE users.id = $id";

// 之後要進入在網址後加 ?id=
$result = $conn->query($sql);
// $rows=$result->fetch_all(MYSQLI_ASSOC);
// var_dump($rows[0]);
$row = $result->fetch_assoc();
$userCount = $result->num_rows;

// var_dump($row);
?>
<!doctype html>
<html lang="zh-TW">

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
                        <h1 class="h3 mb-0 text-gray-800">會員資料修改</h1>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">系統資訊</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    確認刪除使用者?
                                </div>
                                <div class="modal-footer">
                                    <a role="button" type="button" class="btn btn-danger" href="doDeleteUser.php?id=<?= $row["id"] ?>">確認</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="py-2 ms-3">
                            <a href="users.php?order=<?= isset($_GET['order']) ? $_GET['order'] : 1 ?><?= isset($_GET['gender_id']) ? '&gender_id=' . $_GET['gender_id'] : '' ?>&id=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>&p=<?= isset($_GET['p']) ? $_GET['p'] : 1 ?><?= isset($_GET['q']) ? '&q=' . $_GET['q'] : '' ?><?= isset($_GET['idSearch']) ? '&idSearch=' . $_GET['idSearch'] : '' ?>" class="fs-4 btn btn-secondary"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                        </div>
                        <div class="row justify-content-center">
                            <?php if ($userCount > 0): ?>
                                <form action="doUpdateUser.php" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <!-- 顯示使用者圖片和上傳圖片的表單 -->
                                            <div class="d-flex mb-5 px-3">
                                                <div class="me-3">
                                                    <?php if (!empty($row["image_path"])): ?>
                                                        <img src="./user_images/<?= htmlspecialchars($row["image_path"]) ?>" alt="使用者圖片"
                                                            style="width: 170px; height: 170px; object-fit: cover; border-radius: 50%;" id="imagePreview">
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-user" style="font-size: 170px; color: #ccc;"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <label class="form-label">選擇預設頭像</label>
                                                    <div class="d-flex">
                                                        <!-- 預設頭像選項 -->
                                                        <label>
                                                            <input type="radio" name="default_avatar" value="default_1.png" onclick="previewDefaultAvatar('default_1.png')">
                                                            <img src="./user_images/default_1.png" class="rounded-circle" width="60">
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="default_avatar" value="default_2.png" onclick="previewDefaultAvatar('default_2.png')">
                                                            <img src="./user_images/default_2.png" class="rounded-circle" width="60">
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="default_avatar" value="default_3.png" onclick="previewDefaultAvatar('default_3.png')">
                                                            <img src="./user_images/default_3.png" class="rounded-circle" width="60">
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="default_avatar" value="default_4.png" onclick="previewDefaultAvatar('default_4.png')">
                                                            <img src="./user_images/default_4.png" class="rounded-circle" width="60">
                                                        </label>
                                                    </div>
                                                    <label for="user_upload_image" class="form-label text-nowrap">或是上傳新頭像</label>
                                                    <input type="file" class="form-control" name="user_upload_image" id="user_upload_image" onchange="previewImage(event)" accept=".jpg, .jpeg, .png, .gif">
                                                </div>
                                            </div>

                                            <!-- 使用者基本資訊 -->
                                            <div class="mb-3 px-3">
                                                <label class="form-label fs-4">ID</label>
                                                <input type="text" class="form-control-plaintext bg-gray border border-secondary-subtle rounded fs-4 ps-2" value="<?= $row["id"] ?>" readonly>
                                            </div>
                                            <div class="mb-3 px-3">
                                                <label class="form-label fs-4">使用者名稱</label>
                                                <input type="text" class="form-control fs-4 ps-2" name="name" value="<?= $row["name"] ?>">
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
                                                <input type="tel" class="form-control fs-4 ps-2" name="phone" value="<?= $row["phone"] ?>">
                                            </div>
                                            <div class="mb-3 px-3">
                                                <label class="form-label fs-4">email</label>
                                                <input type="email" class="form-control fs-4 ps-2" name="email" value="<?= $row["email"] ?>">
                                            </div>
                                            <div class="mb-3 px-3">
                                                <label class="form-label fs-4">性別</label>
                                                <select class="form-control-plaintext bg-white border border-secondary-subtle rounded fs-4 ps-2" name="gender_id">
                                                    <option value="" <?= $row["gender_id"] == "" ? "selected" : "" ?>>未填寫</option>
                                                    <option value="1" <?= $row["gender_id"] == "1" ? "selected" : "" ?>>男</option>
                                                    <option value="2" <?= $row["gender_id"] == "2" ? "selected" : "" ?>>女</option>
                                                </select>
                                            </div>
                                            <div class="mb-5 px-3">
                                                <label class="form-label fs-4">出生日期</label>
                                                <input type="date" class="form-control fs-4 ps-2" value="<?= $row["birth_date"] ?>" name="birth_date">
                                            </div>
                                            <div class="d-flex justify-content-between mx-3">
                                                <a class="btn btn-danger fs-4" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-trash fa-fw"></i></a>
                                                <button class="fs-4 btn btn-success" type="submit"><i class="fa-solid fa-floppy-disk fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php else: ?>
                                <h2>使用者不存在</h2>
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
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const image = document.getElementById("imagePreview");
                image.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
                // 取消預設頭像選擇
                document.querySelectorAll('input[name="default_avatar"]').forEach(radio => {
                    radio.checked = false;
                });
            }
        }

        function previewDefaultAvatar(avatar) {
            const image = document.getElementById("imagePreview");
            image.src = './user_images/' + avatar;

            // 清除已選擇的上傳圖片
            const fileInput = document.getElementById("user_upload_image");
            fileInput.value = ""; // 清空檔案輸入框
        }
    </script>
</body>

</html>