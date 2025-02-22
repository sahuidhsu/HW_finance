<?php
include "header.php";
global $conn, $site_name;
if (isset($_POST["submit"])) {
    try {
        if ($_POST["department_id"] == "") {
            die("<div class='alert alert-danger' role='alert'>请选择部门</div>");
        }
        $table = "in_fee";
        $sql = $conn->prepare("UPDATE $table SET date = :date, project_id = :project_id, department_id = :department_id, amount = :amount, comment = :comment, valid = :valid WHERE id = :id;");
        $sql->execute([
            "date" => $_POST["date"],
            "project_id" => $_POST["project_id"],
            "department_id" => $_POST["department_id"],
            "amount" => $_POST["amount"],
            "comment" => $_POST["comment"],
            "valid" => $_POST["valid"],
            "id" => $_GET["id"]
        ]);
        echo "<div class='alert alert-success' role='alert'>修改成功！</div>";
    }
    catch (PDOException $e) {
        alert("数据库错误，错误信息：" . $e->getMessage());
        exit();
    }
}
if (!isset($_GET["id"])) {
    alert("非法调用");
    echo "<script>window.location.href='review.php'</script>";
    exit();
}

$sql = $conn->prepare("SELECT * FROM in_fee WHERE id = :id;");
$sql->execute(['id' => $_GET["id"]]);
$result = $sql->fetch();
if ($result == null) {
    alert("费用不存在");
    echo "<script>window.location.href='review.php'</script>";
    exit();
}
?>
<head>
    <title>
        编辑收入费用 - <?php echo $site_name; ?>
    </title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <div class='card border-dark'>
        <h4 class='card-header bg-primary text-white text-center'>编辑费用 - 收入</h4>
        <form action='' method='post' style='margin: 20px;'>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='id'>ID</span>
                <input type='text' class='form-control' name='id' value='<?php echo $result['id'] ?>' readonly disabled>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='date'>日期</span>
                <input type='date' class='form-control' name='date' value='<?php echo $result['date'] ?>' required>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='project'>所属项目</span>
                <select class='form-select' name='project_id'>
                    <?php
                    $sql2 = $conn->prepare("SELECT * FROM project;");
                    $sql2->execute();
                    $result3 = $sql2->fetchAll();
                    if ($result3 == null) {
                        die("<script>alert('目前暂无项目，请管理员前往 管理面板-项目管理 添加至少一个项目！');
                            window.location.href='index.php';</script>");
                    }
                    foreach ($result3 as $row) {
                        echo "<option value='" . $row["id"] . "' " . ($row["id"] == $result["project_id"] ? "selected" : "") . ">" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='department'>部门</span>
                <select class='form-select' name='department_id'>
                    <?php
                    $sql = $conn->prepare("SELECT * FROM department;");
                    $sql->execute();
                    $result2 = $sql->fetchAll();
                    foreach ($result2 as $row) {
                        echo "<option value='" . $row["id"] . "' " . ($row["id"] == $result["department_id"] ? "selected" : "") . ">" . $row["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='amount'>数额</span>
                <input type='text' class='form-control' oninput="value=value.replace(/[^\d\.]/g,'')" name='amount' value='<?php echo $result['amount'] ?>' required>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='comment'>备注</span>
                <input type='text' class='form-control' name='comment' value='<?php echo $result['comment'] ?>'>
            </div>
            <div class='input-group mb-3'>
                <span class='input-group-text' id='valid'>状态</span>
                <select class='form-select' name='valid'>
                    <option value='0' <?php echo $result['valid'] == 0 ? "selected" : "" ?>>未审核</option>
                    <option value='1' <?php echo $result['valid'] == 1 ? "selected" : "" ?>>已审核</option>
                </select>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-block' value='保存'>
        </form>
    </div>
</div>
