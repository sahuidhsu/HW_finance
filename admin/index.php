<?php
include "header.php";
global $conn, $site_name;
?>
<head>
    <title>
        管理面板 - <?php echo $site_name; ?>
    </title>
</head>
<body>
<div class="container">
    <h1>管理员<?php
        echo $_SESSION["username"]
        ?>您好，欢迎使用本系统</h1>
    <h2>请点击上方导航栏选择您需要的功能</h2>
    <div class="card border-dark">
        <h3 class="card-header">服务器信息</h3>
        <ul class="list-group">
            <li class="list-group-item">
                <b>PHP版本:</b> <?php echo phpversion() ?>
                <?php if (ini_get('safe_mode')) {
                    echo '线程安全';
                } else {
                    echo '非线程安全';
                } ?>
            </li>
            <li class="list-group-item">
                <b>MySQL版本:</b> <?php echo $conn->getAttribute(PDO::ATTR_SERVER_VERSION) ?>
            </li>
            <li class="list-group-item">
                <b>网页服务器:</b> <?php echo $_SERVER['SERVER_SOFTWARE'] ?>
            </li>
            <li class="list-group-item">
                <b>服务器系统:</b> <?php echo php_uname('a') ?>
            </li>
            <li class="list-group-item">
                <b>最大运行时间:</b> <?php echo ini_get('max_execution_time') ?>s
            </li>
            <li class="list-group-item">
                <b>POST大小限制:</b> <?php echo ini_get('post_max_size'); ?>
            </li>
        </ul>
    </div>
</div>
</body>