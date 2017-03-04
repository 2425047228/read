<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        图书列表
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
                        <h5>图书列表</h5>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>书名</th>
                                <th>字数</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($bookList)): $i = 0; $__LIST__ = $bookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($vo["book_name"]); ?></td>
                                    <td><?php echo ($vo["number_of_words"]); ?></td>
                                    <td>
                                        <?php switch($vo['is_hot']): case "1": ?>最火<?php break;?>
                                            <?php case "2": ?>最新<?php break;?>
                                            <?php case "3": ?>免费<?php break;?>
                                            <?php case "4": ?>更新<?php break;?>
                                            <?php default: ?>暂无<?php endswitch;?>
                                    </td>
                                    <td class="btn_location">
                                        <!--<button class="btn btn-warning" type="button" onclick="delBtn('<?php echo ($vo["id"]); ?>',this)">删除</button>-->
                                        <button class="btn btn-success" type="button" onclick="up_down('<?php echo ($vo["id"]); ?>',this)">
                                            <?php if(($vo['book_state']) == "0"): ?>未上架
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

<script src="/Public/Admin/js/jquery.min.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>

    <script>
        function up_down(id, the) {
            var state = the.innerText == '未上架' ? 1 : 0;
            $.post('/admin.php/Home/Book/b_list.html', {'id':id,'state':state}, function (string) {
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