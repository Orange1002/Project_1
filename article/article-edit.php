<?php
if (!isset($_GET["id"])) {
    header("location:article-list.php");
}
$id = $_GET["id"];

require_once("../db_connect_bark_bijou.php");
$sql = "SELECT article.*, article_category.name AS category_name FROM article JOIN article_category ON article.category_id = article_category.id WHERE article.id =$id AND article.valid=1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$articleCount = $result->num_rows;


if (isset($_GET["category_id"])) {
    $category_id = (int)$_GET["category_id"];
    $category_filter = " AND article.category_id = $category_id";
}

$sqlCategory = "SELECT * FROM article_category";
$resultCategory = $conn->query($sqlCategory);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>編輯文章</title>

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

        .article-detail p {
            word-break: break-word;
            white-space: normal;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .article-detail {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
        }

        .category-select.active {
            background-color: #4e73df;
            color: white;
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
                <a class="nav-link" href="/homepage/article/article-list.php">
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
                <!-- 公版結束 -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">編輯文章</h1>
                    </div>

                </div>
                <div class="container">
                    <div class="pb-3">
                        <a href="article-detail.php?id=<?= $row["id"] ?>"><button class="btn btn-warning text-white"><i class="fa-solid fa-arrow-left fa-fw text-white"></i>返回</button></a>
                    </div>
                    <?php if ($articleCount > 0): ?>
                        <form action="doUpdateArticle.php" method="post" class="" enctype="multipart/form-data">
                            <div class="d-flex justify-content-between">
                                <label class="mt-3">標題:</label>

                                

                                <div class="dropdown mb-3">
                                    <a id="article_title" class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= $row["category_name"] ?? "文章分類" ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php while ($category = $resultCategory->fetch_assoc()): ?>
                                            <?php if ($category["name"] !== "全部文章"): ?>
                                                <li>
                                                    <a class="dropdown-item category-select <?= ($row["category_id"] == $category["id"]) ? "active" : "" ?>"
                                                        href="#" data-id="<?= $category["id"] ?>">
                                                        <?= $category["name"] ?>
                                                    </a>

                                                </li>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </ul>
                                    <input type="hidden" name="category_id" id="category_id" value="<?= $row["category_id"] ?>">
                                </div>

                            </div>
                            <input type="text" name="title" class="form-control mb-3" required value="<?= $row["title"] ?>">

                            <label>內容:</label>
                            <textarea name="content" class="form-control mb-3" rows="10" required><?= htmlspecialchars($row["content"]) ?></textarea>
                            <input type="hidden" name="id" value="<?= $row["id"] ?>">

                            <div class="">
                                <label for="image" class="form-label">選取檔案</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                            <div class="mt-3 d-flex justify-content-end">
                                <button class="btn btn-info" type="submit">更新完成</button>
                            </div>

                        </form>
                    <?php else: ?>
                        <h1>文章不存在</h1>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll(".category-select").forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault();

                // 切換選中的分類名稱
                document.querySelector(".dropdown-toggle").innerText = this.innerText;
                document.querySelector("#category_id").value = this.dataset.id;

                // 移除其他 active 樣式
                document.querySelectorAll(".category-select").forEach(a => a.classList.remove("active"));
                this.classList.add("active");

                // 更新右側顯示名稱 (直接找到顯示的 span 或標籤)
                document.querySelector("#article_title").innerText = this.innerText;

                // Bootstrap 5 API 收回下拉選單
                bootstrap.Dropdown.getOrCreateInstance(this.closest(".dropdown")).hide();
            });
        });
    </script>

</body>

</html>