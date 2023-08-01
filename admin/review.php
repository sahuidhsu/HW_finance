<?php
include "header.php";
global $conn;
if (isset($_GET["action"])) {
    if ($_GET["action"] == "out") {
        try {
            $sql = $conn->prepare("UPDATE out_fee SET valid = 1 WHERE id = :id;");
            $sql->execute(["id" => $_GET["id"]]);
            echo "<script>window.location.href='review.php';</script>";
        }
        catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
        }
        exit;
    }
    elseif ($_GET["action"] == "in") {
        try {
            $sql = $conn->prepare("UPDATE in_fee SET valid = 1 WHERE id = :id;");
            $sql->execute(["id" => $_GET["id"]]);
            echo "<script>window.location.href='review.php';</script>";
        }
        catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
        }
        exit;
    }
}
?>
<head>
    <title>
        审核 - 资金周转管理系统
    </title>
    <script>
        function out() {
            var msg = "你确定要通过此支出记录吗？"
            return confirm(msg) === true;
        }

        function in_() {
            var msg = "你确定要通过此收入记录吗？"
            return confirm(msg) === true;
        }
    </script>
</head>
<body>
<div class="container" style="padding-top:70px; margin-bottom: 110px">
    <div class="col-md-10 center-block" style="float: none;">
        <h2 style="text-align: center">待审核支出记录</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">数额</th>
                    <th scope="col">费用类型</th>
                    <th scope="col">所属项目</th>
                    <th scope="col">提交人</th>
                    <th scope="col">添加时间</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = $conn->prepare("SELECT * FROM out_fee WHERE valid = 0;");
                $sql->execute();
                $result = $sql->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<th scope=\"row\">" . $row["id"] . "</th>";
                    echo "<td>" . $row["amount"] . "</td>";
                    $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                    $sql->execute(["id" => $row["fee_id"]]);
                    $result2 = $sql->fetch();
                    echo "<td>" . $result2["name"] . "</td>";
                    $sql = $conn->prepare("SELECT name FROM project WHERE id=:id;");
                    $sql->execute(['id' => $row["project_id"]]);
                    $project_result = $sql->fetch();
                    echo "<td>" . $project_result["name"] . "</td>";
                    $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                    $sql->execute(["id" => $row["user_id"]]);
                    $result2 = $sql->fetch();
                    echo "<td>" . $result2["username"] . "</td>";
                    echo "<td>" . $row["add_time"] . "</td>";
                    echo "<td><a onclick='return out()' href='review.php?action=out&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-primary\">通过</button></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <h2 style="text-align: center">待审核收入记录</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">数额</th>
                        <th scope="col">费用类型</th>
                        <th scope="col">所属项目</th>
                        <th scope="col">提交人</th>
                        <th scope="col">添加时间</th>
                        <th scope="col">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = $conn->prepare("SELECT * FROM in_fee WHERE valid = 0;");
                    $sql->execute();
                    $result = $sql->fetchAll();
                    foreach ($result as $row) {
                        echo "<tr>";
                        echo "<th scope=\"row\">" . $row["id"] . "</th>";
                        echo "<td>" . $row["amount"] . "</td>";
                        $sql = $conn->prepare("SELECT name FROM fee WHERE id = :id;");
                        $sql->execute(["id" => $row["fee_id"]]);
                        $result2 = $sql->fetch();
                        echo "<td>" . $result2["name"] . "</td>";
                        $sql = $conn->prepare("SELECT name FROM project WHERE id=:id;");
                        $sql->execute(['id' => $row["project_id"]]);
                        $project_result = $sql->fetch();
                        echo "<td>" . $project_result["name"] . "</td>";
                        $sql = $conn->prepare("SELECT username FROM user WHERE id = :id;");
                        $sql->execute(["id" => $row["user_id"]]);
                        $result2 = $sql->fetch();
                        echo "<td>" . $result2["username"] . "</td>";
                        echo "<td>" . $row["add_time"] . "</td>";
                        echo "<td><a onclick='return in_()' href='review.php?action=in&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-primary\">通过</button></a></td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
</body>