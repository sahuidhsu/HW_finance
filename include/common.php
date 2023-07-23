<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config.php';

error_reporting(0);
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
        $sql = $conn->prepare("SELECT password, valid FROM user WHERE username=:username;");
        $sql->execute(['username' => $username]);
        $result = $sql->fetch();
        if ($result[1] == 0) {
            return array(false, "该账号尚未通过管理员审批，暂时无法登录，请耐心等待或联系管理员");
        }
        if (password_verify($password, $result[0])) {
            return array(true, "登录成功");
        } else {
            return array(false, "密码错误");
        }
    } else {
        return array(false, "用户名不存在");
    }
}

function register($username, $password, $department): array
{
    global $conn;
    // 检查部门是否存在
    $sql = $conn->prepare("SELECT id FROM department WHERE id=:id;");
    $sql->execute(['id' => $department]);
    if ($sql->rowCount() == 0) {
        return array(false, "部门不存在，请检查输入");
    }
    if (get_id_by_username($username) == -1) {
        try {
            $sql = $conn->prepare("INSERT INTO user (username, password, department_id) VALUES (:username, :password, :department);");
            $sql->execute(['username' => $username, 'password' => password_hash($password, PASSWORD_DEFAULT), 'department' => $department]);
        }
        catch (PDOException $e){
            return array(false, "注册失败，数据库错误，错误信息：" . $e->getMessage());
        }
        return array(true, "注册成功");
    } else {
        return array(false, "该用户名已存在，请更换用户名");
    }
}

function isAdmin($username): bool
{
    global $conn;
    $stmt = $conn->prepare("SELECT admin FROM user WHERE username=:username;");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()["admin"] == 1) {
        return true;
    } else {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/include/bootstrap.min.css" rel="stylesheet">
    <link href="/include/fa.css" rel="stylesheet">
    <script src="/include/bootstrap.min.js" type="application/javascript"></script>
    <script src="/include/fa.js" type="application/javascript"></script>
</head>