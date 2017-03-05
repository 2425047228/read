<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        banner列表
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo C('CSS');?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>animate.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>style.min.css" rel="stylesheet">
    
    <link href="<?php echo C('PLUGINS_JS');?>fancybox/jquery.fancybox.css" rel="stylesheet">
    <style>
        .table-hover>thead>tr>th>span{
            font-weight: normal;
        }
        .banner{
            width:150px;
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
                        <h5>banner列表</h5>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>banner图片<span>（点击可放大）</span></th>
                                <th>跳转到的书籍</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($bannerList)): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td>
                                        <a class="fancybox" href="<?php echo ($vo["banner_file"]); ?>" title="banner">
                                            <img src="<?php echo ($vo["banner_file"]); ?>" class="banner">
                                        </a>
                                    </td>
                                    <td><?php echo ((isset($vo["book_name"]) && ($vo["book_name"] !== ""))?($vo["book_name"]):'暂无跳转'); ?></td>
                                    <td class="btn_location">
                                        <button class="btn btn-warning" type="button" onclick="delBtn('<?php echo ($vo["id"]); ?>',this)">删除</button>
                                        <button class="btn btn-success" type="button" onclick="up_down('<?php echo ($vo["id"]); ?>',this)">
                                            <?php if(($vo['banner_state']) == "0"): ?>未上架
                                                <?php else: ?>
                                                已上架<?php endif; ?>
                                        </button>
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

    <script src="<?php echo C('PLUGINS_JS');?>fancybox/jquery.fancybox.js"></script>
    <script>
        $(document).ready(function(){$(".fancybox").fancybox({openEffect:"none",closeEffect:"none"})});
        function delBtn(id, the) {
            if (confirm('确认删除吗？')) {
                $.post('/admin.php/Home/Banner/b_list.html', {'id':id,'type':'del'}, function (string) {
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
            $.post('/admin.php/Home/Banner/b_list.html', {'id':id,'type':'up_down','state':state}, function (string) {
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