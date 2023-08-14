<?php
include "header.php";
global $conn, $site_name;
if (isset($_POST["submit"])) {
    try {
        $sql = $conn->prepare("UPDATE fee SET name=:name, department_id=:department_id WHERE id=:id;");
        $sql->execute(['name' => $_POST["name"], 'department_id' => $_POST["department_id"], 'id' => $_GET["id"]]);
        echo "<script>window.location.href='fee.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit;
}
if (!isset($_GET["action"])) {
    alert("非法调用");
    echo "<script>window.location.href='fee.php'</script>";
    exit();
}
if ($_GET["action"] == "delete") {
    try {
        $sql = $conn->prepare("DELETE FROM fee WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        echo "<script>window.location.href='fee.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit();
}
if ($_GET["action"] != "edit") {
    alert("调用方法不正确");
    echo "<script>window.location.href='fee.php'</script>";
    exit();
}
$sql = $conn->prepare("SELECT * FROM fee WHERE id=:id;");
$sql->execute(['id' => $_GET["id"]]);
$result = $sql->fetch();
if ($result == null) {
    alert("费用不存在");
    echo "<script>window.location.href='fee.php'</script>";
    exit();
}
echo "
<head>
    <title>
        编辑费用 - {$site_name}
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>编辑费用</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='id'>ID</span>
                <input type='text' class='form-control' name='id' value='{$result['id']}' readonly disabled>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='name'>费用名称</span>
                <input type='text' class='form-control' name='name' value='{$result['name']}' required>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='department_id'>所属部门</span>
                <select class='form-select' name='department_id'>";
                $sql = $conn->prepare("SELECT * FROM department;");
                $sql->execute();
                $result2 = $sql->fetchAll();
                foreach ($result2 as $row) {
                    $sum = $row["sum"] == "1" ? "纳入总计" : "不纳入总计";
                    if ($row["id"] == $result["department_id"]) {
                        echo "<option value='" . $row["id"] . "' selected>" . $row["name"] . "</option>";
                    } else {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                }
                echo "
                </select>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
</body>
";