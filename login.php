<?php
include "header.php";
?>
<body class=" d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">用户登录</h2>
                <form method="post" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="fa fa-user"></i> 用户名</label>
                        <input autocomplete="off" class="form-control" id="username" name="username"
                               placeholder="请输入您的用户名" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fa fa-lock"></i> 密码</label>
                        <input class="form-control" id="password" name="password" placeholder="请输入您的密码"
                               type="password">
                    </div>
                    <div class="form-footer">
                        <button class="btn btn-primary w-100 login-button" type="submit">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>