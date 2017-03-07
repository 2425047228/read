<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        修改图书信息
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo C('CSS');?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>animate.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>style.min.css" rel="stylesheet">
    
    <link href="<?php echo C('PLUGINS_CSS');?>iCheck/custom.css" rel="stylesheet">
    <style>
        .radio_inline_block{
            display: inline-block;
        }

        .block_hidden{
            display: none;
        }
    </style>

</head>

    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>修改图书信息</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" class="form-horizontal" enctype="multipart/form-data" action="/admin.php/Home/Book/b_update?id=16" id="book_info">
                            <input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
                            <div class="form-group">
                                    <label class="col-sm-3 control-label">书籍名称：</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="book_name" class="form-control" placeholder="书籍名称" maxlength="30" required value="<?php echo ($bookInfo["book_name"]); ?>">
                                    </div>
                                </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">作者：</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="author_id">
                                        <?php if(is_array($authorList)): $i = 0; $__LIST__ = $authorList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo['id']) == $bookInfo['author_id']): ?>selected<?php endif; ?>><?php echo ($vo["author"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">字数：</label>
                                <div class="col-sm-9">
                                    <input type="text" name="number_of_words" class="form-control" placeholder="书籍字数" required value="<?php echo ($bookInfo["number_of_words"]); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">最火：</label>
                                <div class="col-sm-9">
                                    <div class="radio i-checks radio_inline_block" id="is_not_hot">
                                        <label><input type="radio" value="0" name="is_hot_validate" <?php if(($bookInfo['is_hot']) == "0"): ?>checked="checked"<?php endif; ?> >&nbsp;<i></i>否</label>
                                    </div>
                                    <div class="radio i-checks radio_inline_block" id="is_hot">
                                        <label><input type="radio" value="1" name="is_hot_validate" id="is_hot_validate" <?php if(($bookInfo['is_hot']) > "0"): ?>checked="checked"<?php endif; ?> >&nbsp;<i></i>是</label>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed block_hidden"></div>
                            <div class="form-group block_hidden" id="is_hot_area">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <div class="radio i-checks radio_inline_block">
                                        <label><input type="radio" value="1" name="is_hot"  <?php if(($bookInfo['is_hot']) == "1"): ?>checked<?php endif; ?> >&nbsp;<i></i>最火</label>
                                    </div>
                                    <div class="radio i-checks radio_inline_block">
                                        <label><input type="radio" value="2" name="is_hot"  <?php if(($bookInfo['is_hot']) == "2"): ?>checked<?php endif; ?> >&nbsp;<i></i>最新</label>
                                    </div>
                                    <div class="radio i-checks radio_inline_block">
                                        <label><input type="radio" value="3" name="is_hot"  <?php if(($bookInfo['is_hot']) == "3"): ?>checked<?php endif; ?> >&nbsp;<i></i>免费</label>
                                    </div>
                                    <div class="radio i-checks radio_inline_block">
                                        <label><input type="radio" value="4" name="is_hot" <?php if(($bookInfo['is_hot']) == "4"): ?>checked<?php endif; ?> >&nbsp;<i></i>更新</label>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed block_hidden"></div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label">类型标签：</label>
                                <div class="col-sm-9">
                                    <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="checkbox-inline i-checks">
                                            <input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="category_ids[]" <?php if(in_array(($vo['id']), is_array($bookCategories)?$bookCategories:explode(',',$bookCategories))): ?>checked<?php endif; ?> >&nbsp;<?php echo ($vo["category"]); ?>
                                        </label><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">本书简介：</label>
                                <div class="col-sm-9">
                                    <textarea name="book_synopsis" cols="30" rows="10" class="form-control"  required><?php echo ($bookInfo["book_synopsis"]); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">图书封面：</label>
                                <div class="col-sm-6">
                                    <input type="file" name="book_cover" class="form-control">
                                    <span class="help-block m-b-none" id="book_cover">请上传gif,jpg,jpeg,png格式的文件</span>
                                </div>
                                <div class="col-sm-3">
                                    <img src="<?php echo ($bookInfo["book_cover"]); ?>" style="width: 100px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">图书横幅：</label>
                                <div class="col-sm-6">
                                    <input type="file" name="book_banner" class="form-control">
                                    <span class="help-block m-b-none" id="book_banner">请上传gif,jpg,jpeg,png格式的文件</span>
                                </div>
                                <div class="col-sm-3">
                                    <img src="<?php echo ($bookInfo["book_banner"]); ?>" style="width: 100px;">
                                </div>
                            </div>

                            <div class="progress progress-striped active block_hidden" id="progress_bar_area">
                                <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar progress-bar-danger" id="progress_bar">
                                    正在上传...<span id="progress">0%</span>
                                </div>
                            </div>
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

<script src="<?php echo C('JS');?>jquery.min.js"></script>
<script src="<?php echo C('JS');?>bootstrap.min.js"></script>

    <script src="<?php echo C('PLUGINS_JS');?>iCheck/icheck.min.js"></script>
    <script src="<?php echo C('JS');?>AjaxProgressBar.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green"})});

        window.onload = function () {
            //选项卡隐藏显示效果
            document.getElementById('is_hot').addEventListener('click', show, true);
            document.getElementById('is_not_hot').addEventListener('click', hidden, true);
            function show() {
                var isHot = document.getElementById('is_hot_validate');
                if (!isHot.checked) {
                    $('#is_hot_area').removeClass('block_hidden');
                }
            }
            function hidden() {
                var isHot = document.getElementById('is_hot_validate');
                if (isHot.checked) {
                    $('#is_hot_area').addClass('block_hidden');
                }
            }
            (function (){
                var isHot = document.getElementById('is_hot_validate');
                if (isHot.checked) {
                    $('#is_hot_area').removeClass('block_hidden');
                }
            })();

            //表单校验
            document.getElementById('book_info').onsubmit = function (evt) {
                var categorys = document.getElementsByName('category_ids[]');
                var len = categorys.length;
                var validateNumber = 0;
                for (var i = 0; i < len; ++i) {
                    if (categorys[i].checked) {
                        ++validateNumber;
                    }
                }
                if (validateNumber == 0) {
                    return false;
                }
                progressBarData('/admin.php/Home/Book/b_update?id=16', 'post', progressBarShow,cutChapter,evt);
            }

            function cutChapter(bookId) {
                location.href = "<?php echo U('b_list');?>";
            }

            function progressBarShow(evt) {
                if ($('#progress_bar_area').hasClass('block_hidden')) {
                    $('#progress_bar_area').removeClass('block_hidden');
                }
                var progressData = Math.floor((evt.loaded/evt.total) * 100)+'%';
                $('#progress_bar').width(progressData);
                $('#progress').text(progressData);
                if (progressData == '100%') {
                    window.setTimeout(function () {
                        $('#progress_bar_area').addClass('block_hidden');
                    }, 1000);
                }
            }
        };
    </script>

</html>