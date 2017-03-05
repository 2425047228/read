<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh_ch">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">

    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->

    <title>
        图书断章
    </title>
    <!--公用静态文件-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo C('CSS');?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>animate.min.css" rel="stylesheet">
    <link href="<?php echo C('CSS');?>style.min.css" rel="stylesheet">
    
    <style>
        /**{
            padding: 0;
            margin: 0;
        }*/
    </style>

</head>

    <body>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <h5>正在获取图书内容并断章，请不要关闭页面！</h5>
                <div class="progress progress-striped">
                    <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" id="progress" class="progress-bar progress-bar-warning">
                        断章中...<span id="show_progress">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

<script src="<?php echo C('JS');?>jquery.min.js"></script>
<script src="<?php echo C('JS');?>bootstrap.min.js"></script>

    <script>
        function progress(progressData) {
            document.getElementById('progress').style.width = progressData+'%';
            document.getElementById('show_progress').innerText = progressData+'%';
        }
    </script>

</html>