<?php
include "header.php";
global $conn;
if (!isset($_GET["id"])) {
    alert("参数错误");
    echo "<script>window.location.href='user.php';</script>";
    exit;
} else {
    try {
        $sql = $conn->prepare("UPDATE user SET valid=1 WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        echo "<script>window.location.href='user.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
}