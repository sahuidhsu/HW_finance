<?php
include "../include/common.php";
session_start();
if (!isset($_SESSION['isLogin'])) {
    $_SESSION['isLogin'] = false;
}
if ((!$_SESSION['isLogin'])) {
    echo "<script>window.location.href='../login.php';</script>"; // 如果未登录，重定向到登录页面
    exit;
}
if (!isAdmin($_SESSION["username"])) {
    echo "<script>alert('您不是管理员，无法访问此页面');window.location.href='../index.php';</script>";
}
global $conn;
if (php_self() != "login.php" && php_self() != "register.php")  {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">管理面板</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="user.php">用户管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="department.php">部门管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="fee.php">费用管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="project.php">项目管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="review.php">审核</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">返回主页</a>
                </li>
                </ul>
            已登录管理员账号：' . $_SESSION["username"] . '
            <a style="margin-left: 10px" href="../index.php?logout" class="btn btn-danger">登出</a>
        </div>
    </div>
</nav>';
}
