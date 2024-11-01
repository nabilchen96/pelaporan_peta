<?php

session_start();

// Periksa apakah user sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/beranda");
    exit;
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/pelaporan_peta/asset/login-form-02/fonts/icomoon/style.css">
    <link rel="stylesheet" href="/pelaporan_peta/asset/login-form-02/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/pelaporan_peta/asset/login-form-02/css/bootstrap.min.css">
    <link rel="stylesheet" href="/pelaporan_peta/asset/login-form-02/css/style.css">
    <title>Sistem Informasi SPI</title>
</head>

<body>
    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-2" style="background-image: url('/pelaporan_peta/asset/images/location.png');">
        </div>
        <div class="contents order-2 order-md-1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-7">
                        <h3>Login to <br><strong>Aplikasi Pelaporan</strong></h3>
                        <p>Login untuk mengakses Aplikasi</p>
                        <br>
                        <?php
                        if (isset($_SESSION['login_error'])): ?>
                            <p class="alert alert-danger"><?php echo $_SESSION['login_error']; ?></p>
                            <?php unset($_SESSION['login_error']); // Hapus session error setelah ditampilkan ?>
                        <?php endif; ?>
                        <form id="loginForm" action="/pelaporan_peta/controller/loginController.php" method="POST">
                            <div class="form-group first">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" placeholder="your-email@gmail.com" id="email"
                                    name="email" required>
                            </div>
                            <div class="form-group last mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Your Password"
                                    id="password" required>
                            </div>
                            <div class="d-flex mb-5 align-items-center">
                                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                    <input type="checkbox" checked="checked" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" id="btnLogin" class="btn btn-primary btn-lg btn-block">Sign
                                    in</button>
                                <button style="display: none; background: #0d6efd;" id="btnLoginLoading"
                                    class="btn btn-info btn-moodle text-white btn-lg btn-block" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> Loading...
                                </button>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>