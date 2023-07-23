<?php
include "header.php";
if (isset($_SESSION['isLogin']) && $_SESSION['isLogin']) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
if (isset($_POST["submit"])) {
    $login_result = login($_POST["username"], $_POST["password"]);
    if ($login_result[0]) {
        $_SESSION["isLogin"] = true;
        $_SESSION["username"] = $_POST["username"];
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        alert($login_result[1]);
    }
}
?>
<head>
    <title>
        用户登录 - 资金周转管理系统
    </title>
</head>
<body class=" d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">用户登录</h2>
                <form method="post" action="login.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="username">
                            <i class="fa fa-user" style="margin-right: 5px"></i>用户名</span>
                        <input type="text" name="username" class="form-control"
                               aria-describedby="username" placeholder="请输入用户名">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="password">
                            <i class="fa fa-lock" style="margin-right: 5px"></i>密码</span>
                        <input type="password" name="password" class="form-control"
                               aria-describedby="password" placeholder="请输入密码">
                    </div>
                    <div class="form-footer">
                        <button class="btn btn-primary w-100 login-button" name="submit" type="submit">登录</button>
                    </div>
                </form>
                <div class="text-center text-muted mt-3">
                    还没有账号？<a href="register.php" tabindex="-1">注册</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>