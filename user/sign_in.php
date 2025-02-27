<!doctype html>
<html lang="en">

<head>
    <title>Sign in</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css.php") ?>
    <style>
        body {
            background-image: url(../images/25.);
            background-size: cover;

        }

        .logo {
            height: 48px;
        }

        .login-painel {
            width: 280px;
        }

        .input-area {
            .form-floating {
                &:first-child {
                    .form-control {
                        position: relative;
                        border-end-start-radius: 0;
                        border-end-end-radius: 0;
                    }
                }

                &:last-child {
                    .form-control {
                        position: relative;
                        top: -1px;
                        border-top-left-radius: 0;
                        border-top-right-radius: 0;
                    }
                }

                .form-control:focus {
                    z-index: 1;
                }
            }
        }
    </style>
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="login-painel">
            <img class="logo mb-2" src="../images/TripAdvisor_Logo.svg.png" alt="">
            <h1 class="text-dark">Please sign in</h1>
            <?php if (isset($_SESSION["error"]["times"]) && $_SESSION["error"]["times"] > 5): ?>
                <div class="alert alert-danger" role="alert">
                    錯誤次數太多,請稍後再嘗試
                </div>
            <?php else: ?>
                <form action="doSignin.php" method="post">
                    <div class="input-area">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Account" name="account">
                            <label for="floatingInput">Account</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                            <label for="floatingPassword">Password</label>
                        </div>
                    </div>
                    <?php if (isset($_SESSION["error"]["message"])): ?>
                        <div
                            class="alert alert-danger"
                            role="alert">
                            <?= $_SESSION["error"]["message"] ?>
                        </div>
                    <?php
                        unset($_SESSION["error"]["message"]);
                    endif; ?>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" value="" id="Remember">
                        <label class="text-dark form-check-label" for="Remember">
                            Remember Me
                        </label>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-warning">
                            Sign in
                        </button>
                    </div>
                </form>
            <?php endif; ?>
            <div class="mt-3">
                <p> &copy; 2017-2024 </p>
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
</body>

</html>