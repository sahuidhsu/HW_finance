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