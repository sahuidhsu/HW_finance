<?php
include "header.php";
global $conn, $site_name;
if (isset($_POST["submit"])) {
    try {
        if ($_POST["password"] != "") {
            $sql = "UPDATE user SET username=:username, department_id=:department_id, admin=:admin, password=:password WHERE id=:id;";
            $sql = $conn->prepare($sql);
            $sql->execute(['username' => $_POST["username"], 'department_id' => $_POST["department_id"],
                'admin' => $_POST["admin"],
                'password' => password_hash($_POST["password"], PASSWORD_DEFAULT), 'id' => $_GET["id"]]);
        } else {
            $sql = "UPDATE user SET username=:username, department_id=:department_id, admin=:admin WHERE id=:id;";
            $sql = $conn->prepare($sql);
            $sql->execute(['username' => $_POST["username"], 'department_id' => $_POST["department_id"],
                'admin' => $_POST["admin"], 'id' => $_GET["id"]]);
        }
        echo "<script>window.location.href='user.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit;
}
if (!isset($_GET["action"])) {
    alert("非法调用");
    echo "<script>window.location.href='user.php'</script>";
    exit();
}
if ($_GET["action"] == "delete") {
    try {
        $sql = $conn->prepare("DELETE FROM user WHERE id=:id;");
        $sql->execute(['id' => $_GET["id"]]);
        echo "<script>window.location.href='user.php';</script>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
    exit();
}
if ($_GET["action"] != "edit") {
    alert("调用方法不正确");
    echo "<script>window.location.href='user.php'</script>";
    exit();
}
$sql = $conn->prepare("SELECT * FROM user WHERE id=:id;");
$sql->execute(['id' => $_GET["id"]]);
$result = $sql->fetch();
if ($result == null) {
    alert("用户不存在");
    echo "<script>window.location.href='user.php'</script>";
    exit();
}
echo "
<head>
    <title>
        编辑用户 - {$site_name}
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>编辑用户</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='id'>ID</span>
                <input type='text' class='form-control' name='id' value='{$result['id']}' readonly disabled>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='name'>用户名</span>
                <input type='text' class='form-control' name='username' value='{$result['username']}' required>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='department_id'>所属部门</span>
                <select class='form-select' name='department_id'>";
                $sql = $conn->prepare("SELECT * FROM department;");
                $sql->execute();
                $result2 = $sql->fetchAll();
                foreach ($result2 as $row) {
                    if ($row["id"] == $result["department_id"]) {
                        echo "<option value='" . $row["id"] . "' selected>" . $row["name"] . "</option>";
                    } else {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                }
                echo "
                </select>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='admin'>管理员</span>
                <select class='form-select' name='admin'>";
                if ($result["admin"] == 1) {
                    echo "<option value='1' selected>是</option>
                          <option value='0'>否</option>";
                } else {
                    echo "<option value='1'>是</option>
                          <option value='0' selected>否</option>";
                }
                echo "
                </select>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='password'>密码</span>
                <input type='password' class='form-control' name='password' placeholder='无需修改请留空'>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
</body>
";