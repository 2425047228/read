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
    
    <link rel="stylesheet" type="text/css" href="/Public/Plugins/css/markdown/bootstrap-markdown.min.css" />

</head>

    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加作者</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" action="/admin.php/Home/Author/a_update?id=4">
                            <input type="hidden" value="<?php echo ($author["id"]); ?>" name="id">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">作者姓名：</label>
                                <div class="col-sm-9">
                                        <input type="text" name="author" class="form-control" placeholder="作者姓名" maxlength="10" value="<?php echo ($author["author"]); ?>">
                                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">作者简介：</label>
                                <div class="col-sm-9">
                                    <textarea name="author_synopsis" cols="30" rows="10" class="form-control"><?php echo ($author["author_synopsis"]); ?></textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-12 col-sm-offset-3">
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

    <script>
        window.onload = function () {
            if ('' != '<?php echo ($_GET['message']); ?>') {
                alert('<?php echo ($_GET['message']); ?>');
            }
        }
    </script>

</html>