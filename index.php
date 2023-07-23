<?php
include "header.php";
?>
<head>
    <title>
        首页 - 资金周转管理系统
    </title>
</head>
<body>
<div class="container">
    <h1>用户<?php
        global $conn;
        echo $_SESSION["username"]
        ?>您好，欢迎使用本系统</h1>
    <h2><?php
        $sql = $conn->prepare("SELECT department_id FROM user WHERE username=:username;");
        $sql->execute(['username' => $_SESSION["username"]]);
        $result = $sql->fetch();
        $sql = $conn->prepare("SELECT name FROM department WHERE id=:id;");
        $sql->execute(['id' => $result["department_id"]]);
        $result = $sql->fetch();
        echo "您所在的部门是：" . $result["name"];
        ?>
    </h2>
    <h2>请点击上方导航栏选择您需要的功能</h2>
</div>
</body>