<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        类别管理
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo C('CSS');?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>animate.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>style.min.css" rel="stylesheet">
    
    <style>
        .notice{
            color: #1AB394;
            font-weight: bold;
        }
        td,th{
            text-align: center;
        }
    </style>

</head>

    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>类别列表</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link" href="<?php echo U('c_add');?>"><span class="notice">添加类别</span></a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>类别</th>
                                <th>兴趣人数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($vo["category"]); ?></td>
                                    <td><?php echo ($vo["category_personals"]); ?></td>
                                    <td class="btn_location">
                                        <button class="btn btn-warning" type="button" onclick="delBtn('<?php echo ($vo["id"]); ?>',this)">删除</button>
                                        <button class="btn btn-success" type="button" onclick="location.href='/admin.php/Home/Category/c_update?id=<?php echo ($vo["id"]); ?>';">修改</button>
                                    </td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

<script src="<?php echo C('JS');?>jquery.min.js"></script>
<script src="<?php echo C('JS');?>bootstrap.min.js"></script>

    <script>
        function delBtn(id, the) {
            if (confirm('确认删除吗？')) {
                $.post('/admin.php/Home/Category/category.html', {'id':id}, function (string) {
                    if (string === 'SUCCESS') {
                        the.parentNode.parentNode.remove();
                    } else {
                        alert(string);
                    }
                });
            }
        }
    </script>

</html>