<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

global $Sys_config, $conn;
try{
    $conn = new PDO("mysql:host={$Sys_config["db_host"]};dbname={$Sys_config["db_database"]};",
        $Sys_config["db_user"], $Sys_config["db_password"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 禁用prepared statements的模拟效果
    $conn->exec("set names utf8"); //设置编码
} catch (PDOException $e) {
    die("数据库连接失败，错误信息：" . $e->getMessage());
}

if (!isset($_COOKIE['roll_limit'])){
    setcookie("roll_limit","initial",time()+3600);
}

function alert($content)
{
    echo "<script>alert(\"$content\");</script>";
}

function php_self()
{
    return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1);
}

function logout()
{
    session_destroy();
    alert("登出成功");
    echo "<script>setTimeout(\"javascript:location.href='login.php'\", 500);</script>";
}

function get_id_by_username($username): int
{
    global $conn;
    $sql = $conn->prepare("SELECT id FROM user WHERE username=:username;");
    $sql->execute(['username' => $username]);
    if ($sql->rowCount() == 0) {
        return -1;
    } else {
        return $sql->fetch()["id"];
    }
}

function login($username, $password): array
{
    global $conn;
    if (get_id_by_username($username) != -1) {
        $sql = $conn->prepare("SELECT password FROM user WHERE username=:username;");
        $sql->execute(['username' => $username]);
        $password_result = $sql->fetch()["password"];
        if (password_verify($password, $password_result)) {
            return array(true, "登录成功");
        } else {
            return array(false, "密码错误");
        }
    } else {
        return array(false, "用户名不存在");
    }
}

?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>资金周转记录系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="include/bootstrap.min.css" rel="stylesheet">
    <link href="include/fa.css" rel="stylesheet">
    <script src="include/bootstrap.min.js" type="application/javascript"></script>
    <script src="include/fa.js" type="application/javascript"></script>
</head>