<?php
include "header.php";
global $conn, $site_name;
?>
<head>
    <title>
        费用管理 - <?php echo $site_name; ?>
    </title>
    <script>
        function del(name, dep) {
            var msg = "你确定要删除 " + dep + " 的 " + name + " 吗？"
            return confirm(msg) === true;
        }
    </script>
</head>
<body>
<div class="container" style="padding-top:70px; margin-bottom: 110px">
    <div class="col-md-10 center-block" style="float: none;">
        <div class="table-responsive">
            <form action="" method="post">
                <div class="input-group">
                    <input placeholder="费用名" type="text" name="name">
                    <select name="department_id">
                        <option value="" selected disabled>请选择一个部门</option>
                        <?php
                        $sql = $conn->prepare("SELECT * FROM department;");
                        $sql->execute();
                        $result = $sql->fetchAll();
                        foreach ($result as $row) {
                            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="submit" class="btn btn-success">新增</button>
                </div>
            </form>
            <?php
            if (isset($_POST["submit"])) {
                if ($_POST["department_id"] == "") {
                    echo "<div class='alert alert-danger' role='alert'>请选择一个有效的部门！</div>";
                } else if ($_POST["name"] == "") {
                    echo "<div class='alert alert-danger' role='alert'>费用名不能为空！</div>";
                    }
                else {
                    try {
                        $sql = "INSERT INTO fee (name, department_id) VALUES
                        ('" . $_POST["name"] . "', '" . $_POST["department_id"] . "');";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        echo "<script>window.location.href='fee.php';</script>";
                    }
                    catch (PDOException $e) {
                        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
                    }
                }
            } ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">费用名</th>
                    <th scope="col">所属部门</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = $conn->prepare("SELECT * FROM fee;");
                $sql->execute();
                $result = $sql->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<th scope=\"row\">" . $row["id"] . "</th>";
                    echo "<td>" . $row["name"] . "</td>";
                    $sql2 = $conn->prepare("SELECT * FROM department WHERE id = " . $row["department_id"] . ";");
                    $sql2->execute();
                    $result2 = $sql2->fetch();
                    $sum = $result2["sum"] == "1" ? "纳入总计" : "不纳入总计";
                    echo "<td>" . $result2["name"] . " <b>(" . $sum . ")</b>" . "</td>";
                    echo "<td><a href='edit_fee.php?action=edit&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-primary\">编辑</button></a>
                            <a onclick='return del(\"{$row["name"]}\", \"{$result2["name"]}\")' href='edit_fee.php?action=delete&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-danger\">删除</button></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>