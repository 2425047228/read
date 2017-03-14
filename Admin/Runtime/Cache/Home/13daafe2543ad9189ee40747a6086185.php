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
    <link href="<?php echo C('CSS');?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>animate.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>style.min.css" rel="stylesheet">
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
                            <tbody id="body_content">
                            <?php if(is_array($bookList)): $k = 0; $__LIST__ = $bookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr>
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
                                        <button class="btn btn-warning" type="button" onclick="location.href='/admin.php/Home/Book/b_update?id=<?php echo ($vo["id"]); ?>';">修改</button>
                                        <button class="btn btn-success" type="button" onclick="up_down('<?php echo ($vo["id"]); ?>',this)">
                                            <?php if(($vo['book_state']) == "0"): ?>未上架
                                                <?php else: ?>
                                                已上架<?php endif; ?>
                                        </button>
                                        <!--<button type="button" class="btn btn-danger">删除</button>-->
                                        <?php if(empty($vo['chapter_exists'])): ?><button class="btn btn-info" type="button" onclick="cutChapter('<?php echo ($vo["id"]); ?>',this)" data-id="<?php echo ($k); ?>">断章</button><?php endif; ?>
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
        var bool = true;
        function cutChapter(id,the) {
            var content = '<tr><td colspan="4"><div class="col-sm-12">';
            content += '<iframe id="progress_frame" width="100%" height="100%" src="" frameborder="0" seamless></iframe>';
            content += '</div></td><tr>';
            var number = the.getAttribute('data-id');
            console.log(bool);
            if (bool) {
                $('button[data-id='+number+']').parent().parent().after(content);
                $('#progress_frame').attr('src','/admin.php/Home/Chapter/cut_chapter?bookId='+id);
                bool = false;
            }
            console.log(bool);
        }
    </script>


</html>