<?php
include "header.php";
global $conn;
$username = $_SESSION["username"];
$sql = $conn->prepare("SELECT * FROM user WHERE username=:username;");
$sql->execute(['username' => $username]);
$result = $sql->fetch();
$department_id = $result["department_id"];
$sql = $conn->prepare("SELECT sum FROM department WHERE id=:id;");
$sql->execute(['id' => $department_id]);
$result2 = $sql->fetch();
$sum = $result2["sum"];
if (isset($_POST["submit"])) {
    $fee_id = $_POST["fee"];
    $sql = $conn->prepare("SELECT department_id FROM fee WHERE id=:id;");
    $sql->execute(['id' => $fee_id]);
    $dep_result = $sql->fetch();
    if ($dep_result["department_id"] != $department_id) {
        echo "<div class='alert alert-danger' role='alert'>您无权添加该费用！</div>";
        exit;
    }
    try {
        $sql = $conn->prepare("INSERT INTO out_fee (amount, fee_id, sum, user_id, add_time) VALUES 
                                                         (:amount, :fee_id, :sum, :user_id, :add_time);");
        $sql->execute(['amount' => $_POST["amount"], 'fee_id' => $_POST["fee"], 'sum' => $sum,
            'user_id' => $result["id"], 'add_time' => date("Y-m-d H:i:s")]);
        echo "<div class='alert alert-success' role='alert'>添加成功！等待管理员审核</div>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
}
?>
<head>
    <title>
        添加支出 - 资金周转管理系统
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>添加支出</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='amount'><i class="fa fa-money-bill" style="margin-right: 5px"></i>数额</span>
                <input type='text' class='form-control' oninput="value=value.replace(/[^\d\.]/g,'')" name='amount' required>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='fee'>费用类型</span>
                <select class='form-select' name='fee'>
                <?php
                    $sql2 = $conn->prepare("SELECT * FROM fee;");
                    $sql2->execute();
                    $result3 = $sql2->fetchAll();
                    foreach ($result3 as $row) {
                        if ($row["department_id"] == $department_id) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                ?>
                </select>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
</body>
