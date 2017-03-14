<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>个人中心</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <!--js,css等静态文件使用区-->
</head>

	<body>
		<header class="me_head">
			<a href="#">
				<em class="me_set"></em>
				<div class="me_main">
					<div class="me_tou"><img src="/Public/Index/images/0.jpeg" alt=""/></div>
					<h5 class="color_black">老师</h5>
					<p class="color_black"><img src="/Public/Index/images/me_nv.png"/>女</p>
				</div>
				<ul class="color_white">
					<li id="me_li1">古装言情</li>
					<li id="me_li2">古装言情</li>
					<li id="me_li3">古装言情</li>
					<li id="me_li4">古装言情</li>
					<li id="me_li5">古装言情</li>
				</ul>
			</a>
		</header>
		<article class="me_art">
			<a href="/index.php/Home/User/feedback"><em class="me_art_em1"></em>意见反馈</a>
			<span class="span1"></span>
			<a href="#"><em class="me_art_em2"></em>上传书籍</a>
			<span class="span2"></span>
		</article>
		<footer class="">
			<div class="novel_foot me_foot">
				<a href="#" id="novel_out">退出登录</a>
			</div>
			<!--底部导航栏-->
        		<ul class="novel_btn">
        			<li class="novel_btn1"><a href="/index.php/Home/Index/index" class="color_hui"><em class="novel_btn_false"></em>首页</a></li>
        			<li class="novel_btn2"><a href="<?php echo U('Book/library');?>" class="color_hui"><em class="novel_btn_false"></em>书城</a></li>
        			<li class="novel_btn3"><a href="#" class="color_hui"><em class="novel_btn_false"></em>美文</a></li>
        			<li class="novel_btn4"><a href="<?php echo U('Book/bookshelf');?>" class="color_hui"><em class="novel_btn_false"></em>书架</a></li>
        			<li class="novel_btn5"><a href="/index.php/Home/User/me" class="color_zhu"><em class="novel_btn_true"></em>我</a></li>
        		</ul>
		</footer>
	</body>
	<!-------
	
	
	
	https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa2b618fb1705e8bb&redirect_uri=http://read.wzj.dev.shuxier.com/index.php/Home/User/userinfo&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
	
	
	
	
	
	----------->


<!--其他操作-->

</html>