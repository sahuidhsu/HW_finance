<?php
include "header.php";
global $conn;
$username = $_SESSION["username"];
$sql = $conn->prepare("SELECT * FROM user WHERE username=:username;");
$sql->execute(['username' => $username]);
$result = $sql->fetch();
$department_id = $result["department_id"];
if (isset($_POST["submit"])) {
    try {
        $sql = $conn->prepare("INSERT INTO in_fee (amount, user_id, add_time, project_id, comment) VALUES 
                                                         (:amount, :user_id, :add_time, :project, :comment);");
        $sql->execute(['amount' => $_POST["amount"], 'user_id' => $result["id"],
            'add_time' => date("Y-m-d H:i:s"), 'project' => $_POST["project"], 'comment' => $_POST["comment"]]);
        echo "<div class='alert alert-success' role='alert'>添加成功！等待管理员审核</div>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
}
?>
<head>
    <title>
        添加收入 - 资金周转管理系统
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>添加收入</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='project'><i class="fa fa-diagram-project" style="margin-right: 5px"></i>所属项目</span>
                <select class='form-select' name='project'>
                    <?php
                    $sql2 = $conn->prepare("SELECT * FROM project;");
                    $sql2->execute();
                    $result3 = $sql2->fetchAll();
                    foreach ($result3 as $row) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='amount'><i class="fa fa-money-bill" style="margin-right: 5px"></i>数额</span>
                <input type='text' class='form-control' oninput="value=value.replace(/[^\d\.]/g,'')" name='amount' required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="comment"><i class="fa fa-file-lines" style="margin-right: 5px"></i>备注</span>
                <input type="text" class="form-control" name="comment">
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
    <?php
    $user_id = $result["id"];
    $sql = $conn->prepare("SELECT * FROM in_fee WHERE user_id=:user_id ORDER BY id DESC LIMIT 10;");
    $sql->execute(['user_id' => $user_id]);
    $out_result = $sql->fetchAll();
    ?>
    <br>
    <h2 style="text-align: center;">我提交的最近10条收入历史</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">数额</th>
                <th scope="col">所属项目</th>
                <th scope="col">备注</th>
                <th scope="col">添加时间</th>
                <th scope="col">审核状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($out_result as $row) {
                $status_result = $row["valid"];
                $sql = $conn->prepare("SELECT name FROM project WHERE id=:id;");
                $sql->execute(['id' => $row["project_id"]]);
                $project_result = $sql->fetch();
                $project = $project_result["name"];
                if ($status_result == 0) {
                    $status = "<span class='badge bg-warning text-dark'>待审核</span>";
                }
                else if ($status_result == 1) {
                    $status = "<span class='badge bg-success'>已通过</span>";
                }
                else {
                    $status = "<span class='badge bg-danger'>未知状态</span>";
                }
                echo "<tr><th scope='row'>" . $row["id"] . "</th><td>" . $row["amount"] . "</td><td>" . $project .
                    "</td><td>" . $row["comment"] . "</td><td>" . $row["add_time"] . "</td><td>" . $status . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
