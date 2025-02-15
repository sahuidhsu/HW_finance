<?php
include "header.php";
global $conn, $site_name;
if (isset($_POST["submit"])) {
    try {
        $sql = $conn->prepare("UPDATE project SET name=:name WHERE id=:id;");
        $sql->execute(['name' => $_POST["name"], 'id' => $_GET["id"]]);
        echo "<script>window.location.href='project.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit;
}
if (!isset($_GET["action"])) {
    alert("非法调用");
    echo "<script>window.location.href='project.php'</script>";
    exit();
}
if ($_GET["action"] == "delete") {
    try {
        $sql = $conn->prepare("DELETE FROM in_fee WHERE project_id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        $sql = $conn->prepare("DELETE FROM out_fee WHERE project_id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        $sql = $conn->prepare("DELETE FROM project WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        echo "<script>window.location.href='project.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit();
}
if ($_GET["action"] != "edit") {
    alert("调用方法不正确");
    echo "<script>window.location.href='project.php'</script>";
    exit();
}
$sql = $conn->prepare("SELECT * FROM project WHERE id=:id;");
$sql->execute(['id' => $_GET["id"]]);
$result = $sql->fetch();
if ($result == null) {
    alert("项目不存在");
    echo "<script>window.location.href='project.php'</script>";
    exit();
}
echo "
<head>
    <title>
        编辑项目 - {$site_name}
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>编辑项目</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='id'>ID</span>
                <input type='text' class='form-control' name='id' value='{$result['id']}' readonly disabled>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='name'>项目名称</span>
                <input type='text' class='form-control' name='name' value='{$result['name']}' required>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
</body>
";