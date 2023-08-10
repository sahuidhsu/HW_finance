<?php
include "header.php";
global $conn;
if (!isset($_GET["project_id"]) or $_GET["project_id"] == "") {
    die("<script>alert('未选择项目');window.location.href='detail.php';</script>");
}
$project_id = $_GET["project_id"];
$sql = $conn->prepare("SELECT name FROM project WHERE id = :id;");
$sql->execute(["id" => $project_id]);
if ($sql->rowCount() == 0) {
    die("<script>alert('项目不存在');window.location.href='detail.php';</script>");
}
$project_name = $sql->fetch()[0];
?>
<head>
    <title>项目明细 - 资金周转管理系统</title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <a href="detail.php"><button class="btn btn-primary">返回</button></a>
    <h2>项目名称：<?php echo $project_name ?></h2>
    <?php
    $sql = $conn->prepare("SELECT SUM(amount) FROM out_fee WHERE sum = 1 AND project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $out_total = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM out_fee WHERE sum = 1 AND valid = 0 AND project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $out_not_valid = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $in_total = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE valid = 0 AND project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $in_not_valid = $sql->fetch()[0];
    if ($out_not_valid == null) {
        $out_not_valid = 0;
    }
    if ($in_not_valid == null) {
        $in_not_valid = 0;
    }
    if ($in_total == null) {
        $in_total = 0;
    }
    if ($out_total == null) {
        $out_total = 0;
    }
    echo "<h3>项目支出金额：<b>{$out_total}（含{$out_not_valid}未审核金额）</b></h3>";
    echo "<br>";
    echo "<h3>项目收入金额：<b>{$in_total}（含{$in_not_valid}未审核金额）</b></h3>";
    ?>
    <h2 style="text-align: center; margin-top: 15px">资金成本</h2>
    <form action="" method="post">
        <div class="input-group">
            <span class="input-group-text">开始日期</span>
            <input type="date" class="form-control" name="start_date" <?php if (isset($_POST["start_date"])) echo "value=\"{$_POST["start_date"]}\"" ?> required>
            <span class="input-group-text">结束日期</span>
            <input type="date" class="form-control" name="end_date" <?php if (isset($_POST["end_date"])) echo "value=\"{$_POST["end_date"]}\"" ?> required>
            <button class="btn btn-primary" name="interest" type="submit">计算</button>
        </div>
    </form>
    <?php
    if (isset($_POST["interest"])) {
        if ($_POST["start_date"] == null or $_POST["end_date"] == null) {
            die("<script>alert('日期不能为空');window.location.href='detail_project.php?project_id={$project_id}';</script>");
        } else if ($_POST["start_date"] > $_POST["end_date"]) {
            die("<script>alert('开始日期不能大于结束日期');window.location.href='detail_project.php?project_id={$project_id}';</script>");
        }
        else {
            $start_time = $_POST["start_date"] . " 00:00:00";
            $end_time = $_POST["end_date"] . " 23:59:59";
            $sql = $conn->prepare("SELECT SUM(amount) FROM out_fee WHERE sum = 1 AND valid = 1 AND project_id = :project AND add_time BETWEEN :start AND :end;");
            $sql->execute(["project" => $project_id, "start" => $start_time, "end" => $end_time]);
            $out_total = $sql->fetch()[0];
            if ($out_total == null) $out_total = 0;
            $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE valid = 1 AND project_id = :project AND add_time BETWEEN :start AND :end;");
            $sql->execute(["project" => $project_id, "start" => $start_time, "end" => $end_time]);
            $in_total = $sql->fetch()[0];
            if ($in_total == null) $in_total = 0;
            $interest = ($out_total - $in_total) * 0.8;
            echo "<h3>资金成本：<b>{$interest}</b><br>计算公式：(<b>已审核</b>支出-<b>已审核</b>收入) * 0.8 (支出：{$out_total} 收入：{$in_total})</h3>";
        }
    }
    ?>
    <h2 style="text-align: center; margin-top: 15px">所有支出记录</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">数额</th>
                <th scope="col">费用类型</th>
                <th scope="col">备注</th>
                <th scope="col">提交人</th>
                <th scope="col">添加时间</th>
                <th scope="col">审核状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = $conn->prepare("SELECT * FROM out_fee WHERE project_id = :project ORDER BY id DESC;");
            $sql->execute(["project" => $project_id]);
            $result = $sql->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["amount"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                $sql->execute(["id" => $row["fee_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                $sql->execute(["id" => $row["user_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["username"] . "</td>";
                echo "<td>" . $row["add_time"] . "</td>";
                if ($row["valid"] == 0) {
                    echo "<td><span class=\"badge bg-warning\">未审核</span></td>";
                } else {
                    echo "<td><span class=\"badge bg-success\">已审核</span></td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <h2 style="text-align: center">所有收入记录</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">数额</th>
                <th scope="col">备注</th>
                <th scope="col">提交人</th>
                <th scope="col">添加时间</th>
                <th scope="col">审核状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = $conn->prepare("SELECT * FROM in_fee WHERE project_id = :project ORDER BY id DESC;");
            $sql->execute(["project" => $project_id]);
            $result = $sql->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["amount"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                $sql->execute(["id" => $row["user_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["username"] . "</td>";
                echo "<td>" . $row["add_time"] . "</td>";
                if ($row["valid"] == 0) {
                    echo "<td><span class=\"badge bg-warning\">未审核</span></td>";
                } else {
                    echo "<td><span class=\"badge bg-success\">已审核</span></td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>