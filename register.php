<?php
include "header.php";
if (isset($_SESSION['isLogin']) && $_SESSION['isLogin']) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
if (isset($_POST["submit"])) {
    $result = register($_POST["username"], $_POST["password"], $_POST["department"]);
    if ($result[0]) {
        alert("注册成功，请等待管理员审批");
        echo "<script>window.location.href='register.php';</script>";
        exit;
    } else {
        alert($result[1]);
    }
}
?>
<body class=" d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">用户注册</h2>
                <p style="text-align: center; color: red">请注意：账号注册后需要管理员审批才可以登录</p>
                <form method="post" action="register.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="username">
                            <i class="fa fa-user" style="margin-right: 5px"></i>用户名</span>
                        <input type="text" name="username" class="form-control" aria-describedby="username">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="password">
                            <i class="fa fa-lock" style="margin-right: 5px"></i>密码</span>
                        <input type="password" name="password" class="form-control" aria-describedby="password">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="department">
                            <i class="fa fa-building-user" style="margin-right: 5px"></i>部门</span>
                        <select class="form-control" name="department" aria-describedby="department">
                            <option value="999" selected disabled>请选择部门</option>
                            <?php
                            global $conn;
                            $sql = $conn->prepare("SELECT * FROM department;");
                            $sql->execute();
                            $result = $sql->fetchAll();
                            foreach ($result as $row) {
                                echo "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-footer">
                        <button class="btn btn-primary w-100 login-button" name="submit" type="submit">注册申请</button>
                    </div>
                </form>
                <div class="text-center text-muted mt-3">
                    已经有账号了？<a href="login.php" tabindex="-1">登录</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>