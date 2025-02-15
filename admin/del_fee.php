<?php
include "header.php";
global $conn, $site_name;
if (!isset($_GET["type"])) {
    alert("非法调用");
    echo "<script>window.location.href='fee.php'</script>";
    exit();
}
try {
    if ($_GET["type"] == "in") {
        $sql = $conn->prepare("SELECT * FROM in_fee WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        $result = $sql->fetch();
        if ($result == null) {
            alert("收入记录不存在");
            echo "<script>window.location.href='review.php'</script>";
            exit();
        }
        $sql = $conn->prepare("DELETE FROM in_fee WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
    }
    elseif ($_GET["type"] == "out") {
        $sql = $conn->prepare("SELECT * FROM out_fee WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        $result = $sql->fetch();
        if ($result == null) {
            alert("支出记录不存在");
            echo "<script>window.location.href='review.php'</script>";
            exit();
        }
        $sql = $conn->prepare("DELETE FROM out_fee WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
    }
    else {
        alert("非法调用");
        echo "<script>window.location.href='review.php'</script>";
        exit();
    }
    alert("删除成功");
    echo "<script>window.location.href='review.php';</script>";
}
catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
}
echo "
<head>
    <title>
        删除费用记录 - {$site_name}
    </title>
</head>";