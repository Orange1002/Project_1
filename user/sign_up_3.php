<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <title>註冊 - 步驟 3</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php include("../css.php") ?>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-4 col-md-6">
                <h2>選擇您的頭像</h2>
                <div class="mt-3">
                    <form action="doUploadImage.php" method="post" enctype="multipart/form-data">
                        
                        <div class="mb-2">
                            <label class="form-label">選擇預設頭像</label>
                            <div class="d-flex">
                                <!-- 預設頭像選項 -->
                                <label>
                                    <input type="radio" name="default_avatar" value="avatar1.png" checked>
                                    <img src="./user_images/default_1.png" class="rounded-circle" width="120">
                                </label>
                                <label>
                                    <input type="radio" name="default_avatar" value="default_1.png">
                                    <img src="./user_images/default_2.png" class="rounded-circle" width="120">
                                </label>
                                <label>
                                    <input type="radio" name="default_avatar" value="default_2.png">
                                    <img src="./user_images/default_3.png" class="rounded-circle" width="120">
                                </label>
                                <label>
                                    <input type="radio" name="default_avatar" value="default_3.png">
                                    <img src="./user_images/default_4.png" class="rounded-circle" width="120">
                                </label>
                            </div>
                        </div>
                        <!-- 上傳自訂頭像 -->
                        <div class="mb-2">
                            <label for="" class="form-lable">或上傳您的頭像</label>
                            <input type="file" class="form-control" name="user_upload_image">
                        </div>
                        <button class="btn btn-warning" type="submit">送出</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>