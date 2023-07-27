<?php
include "header.php";
global $conn;
$username = $_SESSION["username"];
?>
<head>
    <title>
        修改密码 - 资金周转管理系统
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>修改密码</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='old'><i class="fa fa-lock-open" style="margin-right: 5px"></i>原密码</span>
                <input type='password' class='form-control' name='old_password' required>
            </div>
            <div class='input-group mb-3'>
                <span class="input-group-text" id="new"><i class="fa fa-lock" style="margin-right: 5px"></i>新密码</span>
                <input type="password" class="form-control" name="new_password" required>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
    <?php
    if (isset($_POST["submit"])) {
        $sql = "SELECT * FROM user WHERE username=:username;";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();
        if ($result == null) {
            echo "<div class='alert alert-danger' role='alert'>用户不存在！请尝试重新登录</div>";
        } else
            if (password_verify($_POST["old_password"], $result["password"])) {
                try {
                    $sql = "UPDATE user SET password=:password WHERE username=:username;";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['password' => password_hash($_POST["new_password"], PASSWORD_DEFAULT), 'username' => $username]);
                    echo "<div class='alert alert-success' role='alert'>密码修改成功！</div>";
                }
                catch (PDOException $e) {
                    echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>原密码错误！请检查输入</div>";
            }
    }
    ?>
</div>
</body>
