<?php
include "header.php";
?>
<head>
    <title>
        用户管理 - 资金周转管理系统
    </title>
    <script>
        function del() {
            var msg = "你确定要删除这个用户吗？"
            return confirm(msg) === true;
        }
        function valid(username) {
            var msg = "你确定要通过用户" + username + "的审核吗？这将允许这个用户登录本系统"
            return confirm(msg) === true;
        }
    </script>
</head>
<body>
<div class="container" style="padding-top:70px; margin-bottom: 110px">
    <div class="col-md-10 center-block" style="float: none;">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">用户名</th>
                    <th scope="col">所属部门</th>
                    <th scope="col">管理员</th>
                    <th scope="col">审核</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                global $conn;
                $sql = $conn->prepare("SELECT * FROM user;");
                $sql->execute();
                $result = $sql->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<th scope=\"row\">" . $row["id"] . "</th>";
                    echo "<td>" . $row["username"] . "</td>";
                    $sql = $conn->prepare("SELECT name FROM department WHERE id=:id;");
                    $sql->execute(['id' => $row["department_id"]]);
                    $result2 = $sql->fetch();
                    echo "<td>" . $result2["name"] . "</td>";
                    if ($row["admin"] == 1) {
                        echo "<td>是</td>";
                    } else {
                        echo "<td>否</td>";
                    }
                    if ($row["valid"] == 1) {
                        echo "<td>已通过</td>";
                        echo "<td>";
                    } else {
                        echo "<td style='color: red'>未通过</td>";
                        echo "<td><a onclick='return valid(\"{$row["username"]}\")' style='margin-right: 5px' href='valid.php?id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-success\">通过</button></a>";
                    }
                    echo "<a href='edit_user.php?action=edit&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-primary\">编辑</button></a>
                    <a onclick='return del()' href='edit_user.php?action=delete&id=" . $row["id"] . "'><button type=\"button\" class=\"btn btn-danger\">删除</button></a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>