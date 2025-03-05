<?php
session_start();
require_once("../db_connect_bark_bijou.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $now = date("Y-m-d H:i:s");
    $category_id = isset($_POST["category_id"]) ? $_POST["category_id"] : null;


    $sql = "INSERT INTO article (title, content, created_date, category_id) VALUES (?, ?, ?, ?)";


    // ✅ 準備 SQL
    $creat = $conn->prepare($sql);

    // ✅ 綁定參數
    $creat->bind_param("sssi", $title, $content, $now, $category_id);

    // ✅ 執行 SQL
    if ($creat->execute()) {
        echo "文章新增成功！";
    } else {
        echo "發生錯誤：" . $creat->error;
    }

    // ✅ 關閉連線
    $creat->close();
    $conn->close();
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

    <title>新增文章</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php include("../articleCss.php") ?>

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
                <a class="nav-link" href="article-list.php">
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
                        <h1 class="h3 mb-0 text-gray-800">新增文章</h1>
                    </div>
                    <!-- End of Page Wrapper -->
                </div>
                <div class="container">
                    <div class="pb-3">
                        <a href="../article/article-list.php"><button class="btn btn-warning text-white"><i class="fa-solid fa-arrow-left fa-fw text-white"></i>文章列表</button></a>
                    </div>
                    <form action="doCreate.php" method="post" class="" enctype="multipart/form-data">
                        <div class="d-flex justify-content-between">
                            <label class="mt-3">標題:</label>
                            <div class="dropdown mb-3">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    文章分類
                                </button>
                                <input type="hidden" name="category_id" id="category_id" value="">
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-value="1">分享專區</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="2">醫療專區</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="3">活動專區</a></li>
                                    <li><a class="dropdown-item" href="#" data-value="4">流浪專區</a></li>
                                </ul>
                            </div>
                        </div>
                        <input type="text" name="title" class="form-control mb-3" required oninvalid="this.setCustomValidity('請輸入標題')"
                            oninput="this.setCustomValidity('')">

                        <label>內容:</label>
                        <textarea name="content" class="form-control mb-3" rows="10" required oninvalid="this.setCustomValidity('請輸入內容')"
                            oninput="this.setCustomValidity('')"></textarea>

                       
                            <div class="">
                            <label for="image" class="form-label">選取檔案</label>
                            <input type="file" class="form-control" id="image" name="image">
                            </div>


                            <div class="mt-3 d-flex justify-content-end">
                                <button class="btn btn-info" type="submit">發表文章</button>
                            </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", function(event) {
                event.preventDefault(); // 阻止超連結的預設行為

                let selectedValue = this.getAttribute("data-value"); // 取得分類 ID
                let selectedText = this.innerText; // 取得分類名稱

                console.log("選擇的分類:", selectedText, "ID:", selectedValue); // 測試是否有觸發

                // 更新隱藏的 input 值
                document.getElementById("category_id").value = selectedValue;

                // 更新按鈕顯示的文字
                document.getElementById("categoryDropdown").innerHTML = selectedText + ' <span class="caret"></span>';
            });
        });
    </script>
</body>

</html>