<?php
include "header.php";
global $site_name;
?>
<head>
    <title>
        部门管理 - <?php echo $site_name; ?>
    </title>
    <script>
        function del() {
            var msg = "你确定要删除这个部门吗？"
            return confirm(msg) === true;
        }
    </script>
</head>
<body>
<div class="container" style="padding-top:70px; margin-bottom: 110px">
    <div class="col-md-10 center-block" style="float: none;">
        <div class="table-responsive">
            <form action="" method="post">
                <input type="text" name="department_name">
                <button type="submit" name="submit" class="btn btn-success">新增一个部门</button>
            </form>
            <?php
            global $conn, $site_name;
            if (isset($_POST["submit"])) {
                if ($_POST["department_name"] == null) {
                    echo "<div class='alert alert-danger' role='alert'>部门名称不能为空！</div>";
                } else {
                    try {
                        $sql = "INSERT INTO department (name) VALUES
                                  ('" . htmlspecialchars($name = $_POST['department_name'], ENT_QUOTES) . "');";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        echo "<script>window.location.href='department.php';</script>";
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
                    <th scope="col">部门名</th>
                    <th scope="col">纳入总计</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = $conn->prepare("SELECT * FROM department;");
                $sql->execute();
                $result = $sql->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<th scope=\"row\">" . $row["id"] . "</th>";
                    echo "<td>" . $row["name"] . "</td>";
                    if ($row["sum"] == 1) echo "<td style='color: blue'>是</td>";
                        else echo "<td style='color: red;'>否</td>";
                    echo "<td><a href='edit_department.php?action=edit&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-primary\">编辑</button></a>
                            <a onclick='return del()' href='edit_department.php?action=delete&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-danger\">删除</button></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>