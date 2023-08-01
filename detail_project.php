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
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE sum = 1 AND project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $in_total = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE sum = 1 AND valid = 0 AND project_id = :project;");
    $sql->execute(["project" => $project_id]);
    $in_not_valid = $sql->fetch()[0];
    echo "<h3>项目支出金额：<b>{$out_total}（含{$out_not_valid}未审核金额）</b></h3>";
    echo "<br>";
    echo "<h3>项目收入金额：<b>{$in_total}（含{$in_not_valid}未审核金额）</b></h3>";
    ?>
    <h2 style="text-align: center">所有支出记录</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">数额</th>
                <th scope="col">费用类型</th>
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
                echo "<td>" . $row["amount"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                $sql->execute(["id" => $row["fee_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
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
                <th scope="col">数额</th>
                <th scope="col">费用类型</th>
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
                echo "<td>" . $row["amount"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                $sql->execute(["id" => $row["fee_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
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