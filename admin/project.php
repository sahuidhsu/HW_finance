<?php
include "header.php";
global $conn, $site_name;
?>
<head>
    <title>
        项目管理 - <?php echo $site_name; ?>
    </title>
    <script>
        function del() {
            var msg = "你确定要删除这个项目吗？"
            return confirm(msg) === true;
        }
    </script>
</head>
<body>
<div class="container" style="padding-top:70px; margin-bottom: 110px">
    <div class="col-md-10 center-block" style="float: none;">
        <div class="table-responsive">
            <form action="" method="post">
                <input type="text" name="project_name">
                <button type="submit" name="submit" class="btn btn-success">新增一个项目</button>
            </form>
            <form action="" method="post">
                <button type="submit" name="reorder" class="btn btn-info">重置序号</button>
            </form>
            <?php
            if (isset($_POST["submit"])) {
                if ($_POST["project_name"] == null) {
                    echo "<div class='alert alert-danger' role='alert'>项目名称不能为空！</div>";
                } else {
                    try {
                        $sql = "INSERT INTO project (name) VALUES
                                  ('" . htmlspecialchars($name = $_POST['project_name'], ENT_QUOTES) . "');";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        echo "<script>window.location.href='project.php';</script>";
                    }
                    catch (PDOException $e) {
                        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
                    }
                }
            }
            elseif (isset($_POST["reorder"])) {
                try {
                    $sql = "SELECT * FROM project ORDER BY id ASC;";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    $i = 0;
                    foreach ($result as $row) {
                        $i++;
                        $sql = "UPDATE in_fee SET project_id = " . $i . " WHERE project_id = " . $row["id"] . ";";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $sql = "UPDATE out_fee SET project_id = " . $i . " WHERE project_id = " . $row["id"] . ";";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $sql = "UPDATE project SET id = " . $i . " WHERE id = " . $row["id"] . ";";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                    }
                    $sql = "ALTER TABLE project AUTO_INCREMENT = " . $i . ";";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    echo "<script>window.location.href='project.php';</script>";
                }
                catch (PDOException $e) {
                    echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
                }
            }
            ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">项目名</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = $conn->prepare("SELECT * FROM project;");
                $sql->execute();
                $result = $sql->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $row["id"] . "</th>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td><a href='edit_project.php?action=edit&id=" . $row["id"] . "'><button type='button' class='btn btn-primary'>编辑</button></a> <a href='edit_project.php?action=delete&id=" . $row["id"] . "'><button type='button' class='btn btn-danger' onclick='return del()'>删除</button></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>