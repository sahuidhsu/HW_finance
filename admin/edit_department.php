<?php
include "header.php";
global $conn;
if (isset($_POST["submit"])) {
    try {
        $sql = $conn->prepare("UPDATE department SET name=:name, sum=:sum WHERE id=:id;");
        $switch_result = $_POST["sum"] == "on" ? 1 : 0;
        $sql->execute(['name' => $_POST["name"], 'sum' => $switch_result, 'id' => $_GET["id"]]);
        echo "<script>window.location.href='department.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit;
}
if (!isset($_GET["action"])) {
    alert("非法调用");
    echo "<script>window.location.href='department.php'</script>";
    exit();
}
if ($_GET["action"] == "delete") {
    try {
        $sql = $conn->prepare("DELETE FROM department WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        echo "<script>window.location.href='department.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit();
}
if ($_GET["action"] != "edit") {
    alert("调用方法不正确");
    echo "<script>window.location.href='department.php'</script>";
    exit();
}
$sql = $conn->prepare("SELECT * FROM department WHERE id=:id;");
$sql->execute(['id' => $_GET["id"]]);
$result = $sql->fetch();
if ($result == null) {
    alert("部门不存在");
    echo "<script>window.location.href='department.php'</script>";
    exit();
}
echo "
<head>
    <title>
        编辑部门 - 资金周转管理系统
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>编辑部门</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='id'>ID</span>
                <input type='text' class='form-control' name='id' value='{$result['id']}' readonly disabled>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='name'>部门名称</span>
                <input type='text' class='form-control' name='name' value='{$result['name']}' required>
            </div>
            <div class='form-check mb-3 form-switch'>
                <input class='form-check-input' type='checkbox' role='switch' name='sum' id='sum'";
                echo $result["sum"] == 1 ? "checked>" : ">";
                echo "<label class='form-check-label' for='sum'>纳入总计</label>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
</body>
";