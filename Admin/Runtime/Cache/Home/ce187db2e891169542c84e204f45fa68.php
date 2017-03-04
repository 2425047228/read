<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        登陆
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.min.css" rel="stylesheet">
    
    <link href="/Public/Admin/css/login.min.css" rel="stylesheet">
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>
    <style>
        .verify{
            position: relative;
            width: 100%;
            height: 40px;
            box-sizing: border-box;
            padding:0;
            margin: 0;
        }
        .verify>input{
            width: 60%;
            height: 40px;
            font-size: 14px;
            color: black;
            padding-left: 1em;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        .verify>img{
            position: absolute;
            right: 0;
            top: 0;
            width: 36%;
            height: 40px;
        }
        #notice{
            color: red;
        }
    </style>

</head>

    <body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                        <h1>您好！</h1>
                    </div>
                    <div class="m-b"></div>
                    <h3><strong>欢迎登陆芝麻读书后台</strong></h3>
                    <ul class="m-b">
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势一</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势二</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势三</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势四</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势五</li>
                    </ul>
                    <strong>还没有账号？</strong>
                </div>
            </div>
            <div class="col-sm-5">
                <form method="post" action="/admin.php/Home/Login/login" name="login">
                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md" id="notice"><?php echo ($_GET['message']); ?></p>
                    <input type="text" class="form-control uname" placeholder="用户名" name="account" id="account" value="<?php echo (cookie('account')); ?>"/>
                    <input type="password" class="form-control pword m-b" placeholder="密码" name="password" id="password"/>
                    <section class="verify">
                        <input type="text" name="verify" placeholder="验证码" maxlength="4" id="verify">
                        <img src="<?php echo U('verification_code');?>" title="点击切换" onclick="this.src='/admin.php/Home/Login/verification_code?token='+(Math.random()*1000)">
                    </section>

                    <button class="btn btn-success btn-block">登录</button>
                </form>
            </div>
        </div>
    </div>
    </body>

<script src="/Public/Admin/js/jquery.min.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>

    <script>
        window.onload = function () {
            document.login.onsubmit = function () {
                var account = document.getElementById('account').value;
                var password = document.getElementById('password').value;
                var verify = document.getElementById('verify').value;
                var notice = document.getElementById('notice');
                if (account == '') {
                    notice.innerHTML = '用户名不能为空';
                    return false;
                } else if (password == '') {
                    notice.innerHTML = '密码不能为空';
                    return false;
                } else if (verify == '') {
                    notice.innerHTML = '验证码不能为空';
                    return false;
                }
            }
        }

    </script>

</html>