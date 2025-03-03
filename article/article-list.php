<?php
    require_once("../db_connect_bark_bijou.php");

    $p = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
    $cid = isset($_GET["category_id"]) ? (int)$_GET["category_id"] : 0;
    $perpage = 4; // 每頁顯示的文章數量
    $startItem = ($p - 1) * $perpage;

    // 預設分類過濾條件為空
    $category_filter = "";
    if (isset($_GET["category_id"])) {
        $category_id = (int)$_GET["category_id"];
        $category_filter = " AND article.category_id = $category_id";
    }

    // 統一 SQL 查詢，JOIN article_category
    $sql = "SELECT article.*, article_category.name AS category_name 
            FROM article  
            JOIN article_category ON article.category_id = article_category.id
            WHERE article.valid = 1 $category_filter
            ORDER BY article.created_date DESC";

    // 獲取文章總數
    $resultAll = $conn->query($sql);
    $articleCount = $resultAll->num_rows;
    $totalPage = ceil($articleCount / $perpage);

    // 加上 LIMIT 進行分頁
    $sql .= " LIMIT $startItem, $perpage";
    $result = $conn->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $category_name = "全部文章";
    // 查詢已刪除文章數量
    $sqlDeleted = "SELECT COUNT(*) as deleted_count FROM article WHERE valid = 0";
    $resultDeleted = $conn->query($sqlDeleted);
    $deletedCount = $resultDeleted->fetch_assoc()["deleted_count"];

    if ($cid > 0) {
        $sqlCategory = "SELECT name FROM article_category WHERE id = $cid";
        $resultCategory = $conn->query($sqlCategory);

        if ($resultCategory->num_rows > 0) {
            $categoryData = $resultCategory->fetch_assoc();
            $category_name = $categoryData["name"];
        }
    }
    // 如果 p 參數不存在，預設跳轉到第一頁
    if (!isset($_GET["p"])) {
        header("Location: article-list.php?p=1");
        exit;
    }
    // $sqlAll = "SELECT article.*,  article_category.id AS category_id FROM article  
    //  JOIN article_category ON article.category_id = article_category.id
    // WHERE valid = 1  ORDER BY created_date DESC";
    // $resultAll = $conn->query($sqlAll);
    // $articleCount = $resultAll->num_rows;

    // if (isset($_GET["p"])) {
    //     $p = $_GET["p"];
    //     $perpage = 4;
    //     $startItem = ($p - 1) * $perpage;
    //     $totalPage = ceil($articleCount / $perpage);

    //     $sql = "SELECT * FROM article WHERE valid = 1 ORDER BY created_date DESC LIMIT $startItem, $perpage";
    // } else {
    //     header("Location: article-list.php?p=1");
    //     // $sql = "SELECT * FROM article WHERE valid = 1  ORDER BY created_date DESC";
    // }

    // $result = $conn->query($sql);
    // $rows = $result->fetch_all(MYSQLI_ASSOC);
    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>

     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="">
     <meta name="author" content="">


     <title>文章列表</title>

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
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                     <!-- Page Heading -->
                     <div class="d-sm-flex align-items-center justify-content-between mb-4">
                         <h1 class="h3 mb-0 text-gray-800">文章列表</h1>
                     </div>
                     <div class="d-flex justify-content-between">
                         <div class="py-2">
                             <a href="creat-article.php" class="btn btn-warning text-white">新增文章</a>
                         </div>

                         <div class="dropdown py-2">
                             <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <?= $category_name ?>
                             </a>

                             <ul class="dropdown-menu">
                                 <li><a class="dropdown-item active" href="article-list.php?p=<?= $p ?>">全部</a></li>
                                 <li><a class="dropdown-item" href="article-list.php?category_id=1&p=<?= $p ?>">分享專區</a></li>
                                 <li><a class="dropdown-item" href="article-list.php?category_id=2&p=<?= $p ?>">醫療專區</a></li>
                                 <li><a class="dropdown-item" href="article-list.php?category_id=3&p=<?= $p ?>">活動專區</a></li>
                                 <li><a class="dropdown-item" href="article-list.php?category_id=4&p=<?= $p ?>">流浪專區</a></li>
                             </ul>
                         </div>

                     </div>
                     <div class="py-2">
                         目前有<?= $articleCount ?>篇文章
                         <?php if ($deletedCount > 0): ?>
                             已刪除文章共<?= $deletedCount ?>篇
                         <?php endif; ?>
                     </div>
                     <div>
                         <table class="table table-bordered">
                             <thead>
                                 <tr>
                                     <th>標題</th>
                                     <th>內文</th>
                                     <th>發表時間</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php foreach ($rows as $row): ?>

                                     <!-- 刪除彈出式Modal -->
                                     <div class="modal fade" id="infoModal<?= $row["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                         <div class="modal-dialog modal-sm">
                                             <div class="modal-content">
                                                 <div class="modal-header">
                                                     <h1 class="modal-title fs-5" id="exampleModalLabel">系統資訊</h1>
                                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                 </div>
                                                 <div class="modal-body">
                                                     確認刪除此文章?
                                                 </div>
                                                 <div class="modal-footer">
                                                     <a role="button" class="btn btn-danger" href="articleDelete.php?id=<?= $row["id"] ?>">確認</a>
                                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>

                                                 </div>
                                             </div>
                                         </div>
                                     </div>

                                     <tr>
                                         <td><?= htmlspecialchars($row["title"]) ?></td>
                                         <td><?= htmlspecialchars(mb_substr($row["content"], 0, 50, 'UTF-8')) ?>...<a class="ps-1" style="color:rgb(241, 162, 97);" href="article-detail.php?id=<?= $row['id'] ?>">查看更多<i class="fa-solid fa-angles-right fa-fw"></i></a></td>
                                         <td><?= $row["created_date"] ?></td>
                                         <td class="d-flex gap-2">
                                             <a href="article-edit.php?id=<?= $row["id"] ?>" class=""><button class="btn btn-primary text-white "><i class="fa-solid fa-pen-to-square fa-fw"></i></button></a>
                                             <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#infoModal<?= $row["id"] ?>"><i class="fa-solid fa-trash fa-fw"></i></a>
                                         </td>
                                     </tr>
                                 <?php endforeach; ?>
                             </tbody>
                         </table>
                         <?php if (isset($_GET["p"])): ?>
                             <div>
                                 <nav aria-label="Page navigation example">
                                     <ul class="pagination">
                                         <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                             <?php

                                                $active = ($i == $_GET["p"]) ?
                                                    "active" : "";

                                                ?>
                                             <li class="page-item <?= $active ?>">
                                                 <a class="page-link" href="article-list.php?p=<?= $i ?><?= ($cid ? "&category_id=$cid" : "") ?>"><?= $i ?></a>
                                             </li>
                                         <?php endfor; ?>
                                     </ul>
                                 </nav>
                             </div>
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
         let article = <?= json_encode($rows) ?>;
         console.log(article);
     </script>


 </body>

 </html>