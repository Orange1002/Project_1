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
    <div class="vh-100 bg-img d-flex justify-content-center align-items-center">
        <div class="login-painel">
            <img class="logo mb-2" src="../images/" alt="">
            <h1 class="text-light">Please sign in</h1>
            <form action="doSignin.php" method="post">
                <div class="input-area">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="account" name="account">
                        <label for="floatingInput">account</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                </div>
                <div class="form-check my-2">
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
            <div class="mt-3">
                <p> &copy; 2025 </p>
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
</body>

</html>