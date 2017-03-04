<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        banner添加
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.min.css" rel="stylesheet">
    <!--引入静态文件-->
</head>

    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>类别修改</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="/admin.php/Home/Category/c_update?id=1">
                            <input type="hidden" name="id" value="<?php echo ($category["id"]); ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">类别名称：</label>
                                <div class="col-sm-6">
                                    <input type="text" name="category" class="form-control" placeholder="类别名称" maxlength="10" value="<?php echo ($category["category"]); ?>">
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-primary" type="submit">确认修改</button>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

<script src="/Public/Admin/js/jquery.min.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>

    <!--其他加载的脚本-->

</html>