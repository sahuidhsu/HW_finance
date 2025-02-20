<?php
include "header.php";
global $conn, $site_name;
?>
<head>
    <title>明细 - <?php echo $site_name; ?></title>
</head>
<body>
<div class='container' style='margin-top: 2%;'>
    <form action="detail_project.php" method="get">
        <div class="input-group">
        <select class='form-select' name="project_id">
            <option value="" selected disabled>--项目明细--</option>
            <?php
            $sql = $conn->prepare("SELECT * FROM project;");
            $sql->execute();
            $result = $sql->fetchAll();
            foreach ($result as $row) {
                echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">查看</button>
        </div>
    </form>
    <?php
    $sql = $conn->prepare("SELECT SUM(amount) FROM out_fee WHERE sum = 1;");
    $sql->execute();
    $out_total = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM out_fee WHERE sum = 1 AND valid = 0;");
    $sql->execute();
    $out_not_valid = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee;");
    $sql->execute();
    $in_total = $sql->fetch()[0];
    $sql = $conn->prepare("SELECT SUM(amount) FROM in_fee WHERE valid = 0;");
    $sql->execute();
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
    echo "<h3>总计支出金额：<b>{$out_total}（含{$out_not_valid}未审核金额）</b></h3>";
    echo "<br>";
    echo "<h3>总计收入金额：<b>{$in_total}（含{$in_not_valid}未审核金额）</b></h3>";
    ?>
    <h2 style="text-align: center">最近10条支出记录</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">数额</th>
                <th scope="col">费用类型</th>
                <th scope="col">备注</th>
                <th scope="col">所属项目</th>
                <th scope="col">提交人</th>
                <th scope="col">添加时间</th>
                <th scope="col">日期</th>
                <th scope="col">审核状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = $conn->prepare("SELECT * FROM out_fee ORDER BY id DESC LIMIT 10;");
            $sql->execute();
            $result = $sql->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["amount"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                $sql->execute(["id" => $row["fee_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM project WHERE id = :id");
                $sql->execute(["id" => $row["project_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
                $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                $sql->execute(["id" => $row["user_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["username"] . "</td>";
                echo "<td>" . $row["add_time"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
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
    <h2 style="text-align: center">最近10条收入记录</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">数额</th>
                <th scope="col">所属项目</th>
                <th scope="col">备注</th>
                <th scope="col">提交人</th>
                <th scope="col">添加时间</th>
                <th scope="col">日期</th>
                <th scope="col">审核状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = $conn->prepare("SELECT * FROM in_fee ORDER BY id DESC LIMIT 10;");
            $sql->execute();
            $result = $sql->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row["amount"] . "</td>";
                $sql = $conn->prepare("SELECT name FROM project WHERE id = :id");
                $sql->execute(["id" => $row["project_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["name"] . "</td>";
                echo "<td>" . $row["comment"] . "</td>";
                $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                $sql->execute(["id" => $row["user_id"]]);
                $result2 = $sql->fetch();
                echo "<td>" . $result2["username"] . "</td>";
                echo "<td>" . $row["add_time"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
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