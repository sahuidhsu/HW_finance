<?php
include "include/common.php";
session_start();
if (!isset($_SESSION['isLogin'])) {
    $_SESSION['isLogin'] = false;
}
if ((!$_SESSION['isLogin']) && php_self() != "login.php" && php_self() != "register.php") {
    echo "<script>window.location.href='login.php';</script>"; // 如果未登录，重定向到登录页面
    exit;
}
if (isset($_GET['logout'])) {
    logout();
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
global $conn;
if (php_self() != "login.php" && php_self() != "register.php")  {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">首页</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="out.php">支出</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="in.php">收入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="detail.php">明细</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="password.php">修改密码</a>
                </li>';
                $sql = "SELECT admin FROM user WHERE username=:username;";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['username' => $_SESSION["username"]]);
                $result = $stmt->fetch();
                if ($result["admin"] == 1) {
                    echo '<li class="nav-item">
                    <a class="nav-link" href="/admin">管理面板</a>
                    </li>';
                }
    echo '</ul>
            已登录用户：' . $_SESSION["username"] . '
            <a style="margin-left: 10px" href="index.php?logout" class="btn btn-danger">登出</a>
        </div>
    </div>
</nav>';
}
