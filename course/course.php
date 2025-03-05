<?php
session_start();

require_once("../db_connect_bark_bijou.php");

$sqlAll = $sql = "SELECT * FROM course WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$courseCount = $resultAll->num_rows;
$p = 1;
$order = 2;

if (isset($_GET["q"]) && isset($_GET["category"])) {
    $q = $_GET["q"];
    $category_id = $_GET["category"];
    $WhereClause = "AND name LIKE'%$q%' AND course.method_id= $category_id";
    $sqlNew = "SELECT * FROM course WHERE valid=1 $WhereClause";
    $resultNew = $conn->query($sqlNew);
    $courseCount = $resultNew->num_rows;
} else if (isset($_GET["category"])) {
    $category_id = $_GET["category"];
    $WhereClause = "AND course.method_id= $category_id";
    $sqlNew = "SELECT * FROM course WHERE valid=1 $WhereClause";
    $resultNew = $conn->query($sqlNew);
    $courseCount = $resultNew->num_rows;
} else if (isset($_GET["q"]) && $_GET["q"] !== "") {
    $q = $_GET["q"];
    $WhereClause = "AND name LIKE'%$q%'";
    $p = 1;
    $order = 2;
    $sqlNew = "SELECT * FROM course WHERE valid=1 $WhereClause";
    $resultNew = $conn->query($sqlNew);
    $courseCount = $resultNew->num_rows;
} else if (isset($_GET["q"]) && $_GET["q"] === "") {
    header("location: course.php?p=1&order=2");
} else {
    $WhereClause = "";
}

$orderClause = "";

if (isset($_GET["order"])) {
    $order = $_GET["order"];
    switch ($order) {
        case 1:
            $orderClause = "ORDER BY registration_start ASC";
            break;
        case 2:
            $orderClause = "ORDER BY registration_start DESC";
            break;
        case 3:
            $orderClause = "ORDER BY cost ASC";
            break;
        case 4:
            $orderClause = "ORDER BY cost DESC";
            break;
    }
}

if (isset($_GET["p"])) {
    $p = $_GET["p"];
}

$perPage = 5;
$startItem = ($p - 1) * $perPage;
$totalPage = ceil($courseCount / $perPage);

if (!isset($_GET["q"]) && !isset($_GET["category"]) && !isset($_GET["p"]) && !isset($_GET["order"])) {
    header("location: course.php?p=1&order=2");
}

$sqlImg = "SELECT course.*, 
       (SELECT image FROM course_img WHERE course_img.course_id = course.id LIMIT 1) AS image
FROM course
WHERE course.valid = 1 $WhereClause $orderClause LIMIT $startItem,$perPage
";
$resultImg = $conn->query($sqlImg);
$rowImg = $resultImg->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>課程列表</title>

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
    <?php include("../css.php") ?>
    <link href="./style.css" rel="stylesheet">

    <style>
        .box1 {
            height: 100px;
        }

        .imgsize {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }

        .btn-orange:link,
        .btn-orange:visited {
            color: #ffffff;
            background: rgb(255, 115, 0);
        }

        .btn-orange:hover,
        .btn-orange:active {
            color: #ffffff;
            background: rgba(255, 115, 0, 0.9);
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
                <a class="nav-link" href="course.php">
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
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="mx-4">
                    <!-- Page Heading -->


                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h1 mb-0 text-gray-800 fw-bold">課程列表</h1>
                        <a class="btn btn-orange" href="add_course.php">新增課程</a>
                    </div>
                    <div></div>
                    <div class="d-sm-flex align-items-center mb-4">
                        <?php if (isset($_GET["q"])): ?>
                            <a class="btn btn-orange me-2" href="course.php"><i class="fa-solid fa-arrow-left fa-fw"></i></a>
                        <?php endif; ?>
                        總共<?= $courseCount ?>筆
                        <form action="" class="ms-3">
                            <div class="input-group">
                                <input type="search" class="form-control" name="q"
                                    <?php
                                    $q = "";
                                    // if(isset($_GET["q"])){
                                    //     $q = $_GET["q"];
                                    // } 第一種

                                    // $q = (isset($_GET["q"]))?
                                    // $_GET["q"] :""; 第二種(一定要有if,else)

                                    $q = $_GET["q"] ?? ""; //第三種(一定要有if,else)           
                                    ?>
                                    value="<?= $q ?>">
                                <button class="btn btn-orange"><i class="fa-solid fa-magnifying-glass fa-fw" type="submit"></i></button>
                            </div>
                        </form>
                        <div class="dropdown ms-auto">
                            <a class="btn btn-orange dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown">
                                <?php
                                if (isset($category_id)) {
                                    switch ($category_id) {
                                        case 1:
                                            echo "線上";
                                            break;
                                        case 2:
                                            echo "線下";
                                            break;
                                    }
                                } else {
                                    echo "全部";
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu btn-orange">
                                <li><a class="dropdown-item" href="course.php?p=1<?php if (isset($_GET["order"]) || isset($order)) {
                                                                                        echo '&order=' . $order;
                                                                                    } ?><?php if (isset($_GET["q"])) {
                                                                                            echo '&q=' . $q;
                                                                                        } ?>">全部</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1<?php if (isset($_GET["order"]) || isset($order)) {
                                                                                        echo '&order=' . $order;
                                                                                    } ?>&category=1<?php if (isset($_GET["q"])) {
                                                                                                        echo '&q=' . $q;
                                                                                                    } ?>">線上</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1<?php if (isset($_GET["order"]) || isset($order)) {
                                                                                        echo '&order=' . $order;
                                                                                    } ?>&category=2<?php if (isset($_GET["q"])) {
                                                                                                        echo '&q=' . $q;
                                                                                                    } ?>">線下</a></li>
                            </ul>
                        </div>
                        <div class="dropdown ms-3">
                            <a class="btn btn-orange dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown">
                                <?php
                                switch ($order) {
                                    case 1:
                                        echo "按時間排序 ↓";
                                        break;
                                    case 2:
                                        echo "按時間排序 ↑";
                                        break;
                                    case 3:
                                        echo "按價格排序 ↓";
                                        break;
                                    case 4:
                                        echo "按價格排序 ↑";
                                        break;
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu btn-orange">
                                <li><a class="dropdown-item" href="course.php?p=1&order=1<?php if (isset($_GET["category"])) {
                                                                                                echo '&category=' . $category_id;
                                                                                            } ?><?php if (isset($_GET["q"])) {
                                                                                                    echo '&q=' . $q;
                                                                                                } ?>">按時間排序 ↓</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=2<?php if (isset($_GET["category"])) {
                                                                                                echo '&category=' . $category_id;
                                                                                            } ?><?php if (isset($_GET["q"])) {
                                                                                                    echo '&q=' . $q;
                                                                                                } ?>">按時間排序 ↑</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=3<?php if (isset($_GET["category"])) {
                                                                                                echo '&category=' . $category_id;
                                                                                            } ?><?php if (isset($_GET["q"])) {
                                                                                                    echo '&q=' . $q;
                                                                                                } ?>">按價格排序 ↓</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=4<?php if (isset($_GET["category"])) {
                                                                                                echo '&category=' . $category_id;
                                                                                            } ?><?php if (isset($_GET["q"])) {
                                                                                                    echo '&q=' . $q;
                                                                                                } ?>">按價格排序 ↑</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- courss-list -->
                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-1">課程名稱</th>
                                    <th class="col-2">課程內容</th>
                                    <th class="col-1">課程縮圖</th>
                                    <th class="col-1">課程金額</th>
                                    <th class="col-1">課程方法</th>
                                    <th class="col-1">上架時間</th>
                                    <th class="col-1">狀態</th>
                                    <th class="col-1">功能鈕</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rowImg as $course): ?>
                                    <tr class="box1 text-center">
                                        <td class="align-middle"><?= $course["name"] ?></td>
                                        <td class="align-middle"><?= $course["content"] ?></td>
                                        <td><img class="imgsize" src="./course_images/<?= $course["image"] ?>"></td>
                                        <td class="align-middle">$<?= number_format($course["cost"]) ?></td>
                                        <td class="align-middle">
                                            <?php
                                            switch ($course["method_id"]) {
                                                case 1:
                                                    echo "線上";
                                                    break;
                                                case 2:
                                                    echo "線下";
                                                    break;
                                            } ?></td>
                                        <td class="align-middle"><?= $course["registration_start"] ?></td>
                                        <td class="align-middle"><?php
                                                                    // 假設 $course["registration_start"] 是一個日期字串
                                                                    $registration_start = $course["registration_start"];
                                                                    $registration_end = $course["registration_end"];

                                                                    // 取得今天的日期
                                                                    $today = new DateTime();

                                                                    // 將 $registration_start 轉換為 DateTime 物件
                                                                    $registration_date = new DateTime($registration_start);
                                                                    $registration_dateEnd = new DateTime($registration_end);

                                                                    // 比較日期
                                                                    if (($registration_date->format('Y-m-d') <= $today->format('Y-m-d')) && ($registration_dateEnd->format('Y-m-d') >= $today->format('Y-m-d'))) {
                                                                        // 如果 registration_start 是今天或之前
                                                                        echo "可報名";
                                                                    } else {
                                                                        // 如果是其他情況（例如未來某天）
                                                                        echo "不可報名";
                                                                    }
                                                                    ?>

                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a class="btn btn-success" href="course_content.php?id=<?= $course["id"] ?>&p=<?= $p ?>&order=<?= $order ?><?php if (isset($q)) {
                                                                                                                                                                echo "&q=$q";
                                                                                                                                                            } ?><?php if (isset($category_id)) {
                                                                                                                                                                    echo "&category_id=$category_id";
                                                                                                                                                                } ?>"><i class="fa-solid fa-eye"></i></i></a>
                                                <a class="btn btn-primary ms-1" href="course_edit.php?id=<?= $course["id"] ?>&p=<?= $p ?>&order=<?= $order ?><?php if (isset($q)) {
                                                                                                                                                                    echo "&q=$q";
                                                                                                                                                                } ?><?php if (isset($category_id)) {
                                                                                                                                                                        echo "&category_id=$category_id";
                                                                                                                                                                    } ?>"><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
                                                <a class="btn btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-solid fa-trash fa-fw"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">系統資訊</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    確認刪除課程?
                                                </div>
                                                <div class="modal-footer">
                                                    <a role="button" type="button" class="btn btn-danger" href="courseDelete.php?id=<?= $course["id"] ?>">確認</a>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (isset($_GET["p"])): ?>
                        <div>
                            <nav aria-label="">
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <?php
                                        $active = ($i == $_GET["p"]) ? "active" : "";
                                        ?>
                                        <li class="page-item <?= $active ?>">
                                            <a class="page-link" href="course.php?p=<?= $i ?>&order=<?= $order ?><?php if (isset($_GET["category"])) {
                                                                                                                        echo '&category=' . $category_id;
                                                                                                                    } ?><?php if (isset($_GET["q"])) {
                                                                                                                            echo '&q=' . $q;
                                                                                                                        } ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($_GET["p"]) && isset($p)): ?>
                        <div>
                            <nav aria-label="">
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <?php
                                        $active = ($i == $p) ? "active" : "";
                                        ?>

                                        <li class="page-item <?= $active ?>">
                                            <a class="page-link" href="course.php?p=<?= $i ?>&order=<?= $order ?><?php if (isset($_GET["category"])) {
                                                                                                                        echo '&category=' . $category_id;
                                                                                                                    } ?><?php if (isset($_GET["q"])) {
                                                                                                                            echo '&q=' . $q;
                                                                                                                        } ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>
        </div>
    </div>
</body>


<?php include("../js.php") ?>
<script>

</script>

</html>