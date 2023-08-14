<?php
include("header.php");
global $conn, $site_name;
if (isset($_POST['submit'])) {
    try {
        foreach ($_POST['setting_name'] as $key => $value) {
            $value = htmlspecialchars($value);
            $sql = $conn->prepare("UPDATE setting SET value=:value WHERE name=:name");
            $sql->execute(["value" => $value, "name" => $key]);
        }
        echo "<div class='alert alert-success' role='alert'>保存成功！</div>";
    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>数据库错误，错误信息：" . $e->getMessage() . "</div>";
    }
}
?>
<head>
    <title>
        网站设置 - <?php echo $site_name; ?>
    </title>
</head>
<div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>名称</th>
                    <th>内容</th>
                    <th>备注</th>
                </tr>
                </thead>
                <form action='' method='post'>
                    <?php
                    $sql = $conn->prepare("SELECT * FROM setting");
                    $sql->execute();
                    $result = $sql->fetchAll();
                    foreach ($result as $row) {
                        $content = htmlspecialchars_decode($row['value']);
                        echo "<tr><th>{$row['id']}</th><td>{$row['name']}</td>
                              <td><input type='text' oninput='value=value.replace(/[^\d\.]/g,\"\")'
                              class='form-control' name='setting_name[{$row['name']}]' value='{$content}'></td>
                              <td>{$row["comment"]}</td>
                              </tr>";
                    }
                    ?>
                    <input type='submit' class='btn btn-primary' name='submit' value='保存'>
                </form>
            </table>
        </div>
    </div>
</div>