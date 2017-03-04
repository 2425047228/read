<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        美文列表
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/animate.min.css" rel="stylesheet">
    <link href="/Public/Admin/css/style.min.css" rel="stylesheet">
    
    <link href="/Public/Plugins/js/fancybox/jquery.fancybox.css" rel="stylesheet">
    <style>
        .table-hover>thead>tr>th>span{
            font-weight: normal;
        }
        .cover{
            width:100px;
        }
        .btn_location{
            text-align: center;
            padding: 0;
            margin:0;
        }
    </style>


</head>

    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>美文列表</h5>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>标题</th>
                                <th>作者</th>
                                <th>封面<span>（点击可放大）</span></th>
                                <th>链接</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($beautifulWordsList)): $i = 0; $__LIST__ = $beautifulWordsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($vo["title"]); ?></td>
                                    <td><?php echo ($vo["author"]); ?></td>
                                    <td>
                                        <a class="fancybox" href="<?php echo ($vo["cover"]); ?>" title="封面"><img src="<?php echo ($vo["cover"]); ?>" class="cover"></a>
                                    </td>
                                    <td><?php echo ((isset($vo["url"]) && ($vo["url"] !== ""))?($vo["url"]):'暂无'); ?></td>
                                    <td><?php echo (date('Y-m-d H:i:s', $vo["sent_time"])); ?></td>
                                    <td class="btn_location">
                                        <button class="btn btn-warning" type="button" onclick="delBtn('<?php echo ($vo["id"]); ?>',this)">删除</button>
                                       <!--<button class="btn btn-success" type="button" onclick="up_down('<?php echo ($vo["id"]); ?>',this)"></button>-->
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

<script src="/Public/Admin/js/jquery.min.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>

    <script src="/Public/Plugins/js/fancybox/jquery.fancybox.js"></script>
    <script>
        $(document).ready(function(){$(".fancybox").fancybox({openEffect:"none",closeEffect:"none"})});
        function delBtn(id, the) {
            if (confirm('确认删除吗？')) {
                $.post('/admin.php/Home/Beautifulwords/w_list.html', {'id':id}, function (string) {
                    if (string === 'SUCCESS') {
                        the.parentNode.parentNode.remove();
                    } else {
                        alert('操作失败');
                    }
                });
            }
        }
        function up_down(id, the) {
            var state = the.innerText == '未上架' ? 1 : 0;
            $.post('/admin.php/Home/Beautifulwords/w_list.html', {'id':id,'type':'up_down','state':state}, function (string) {
                if (string === 'SUCCESS') {
                    if (state) {
                        var notice = '已上架';
                    } else {
                        var notice = '未上架';
                    }
                    the.innerText = notice;
                } else {
                    alert('操作失败');
                }
            });
        }

    </script>

</html>