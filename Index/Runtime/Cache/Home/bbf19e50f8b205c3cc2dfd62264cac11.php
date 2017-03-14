<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>书籍详情</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    
	<script type="text/javascript">
		var html = document.getElementsByTagName('html')[0];
		var pageWidth = html.getBoundingClientRect().width;
		html.style.fontSize = pageWidth / 10 + 'px';
		$(function(){
			var ua = navigator.userAgent.toLowerCase();
			if (/iphone|ipad|ipod/.test(ua)) {
				// alert("iphone");
				$('.xiadan-foot').removeClass("xiadan-foot1");
				$('.xiadan-foot').addClass("xiadan-foot2");
			} else if (/android/.test(ua)) {
				//alert("android");
				$('.detail_head_top_right div').css('padding-top','1px');
				$('.detail_head_bottom a').css('padding-top','1px');
			}
		})
	</script>

</head>

	<body>
	<header class="detail_head">
		<div class="detail_head_top">
			<div class="detail_head_top_left">
				<img src="<?php echo ($bookInfo["book_cover"]); ?>" alt=""/>
			</div>
			<div class="detail_head_top_right">
				<h4><?php echo ($bookInfo["book_name"]); ?></h4>
				<?php if(is_array($categories)): $i = 0; $__LIST__ = $categories;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="color_zhu"><?php echo ($v); ?></div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

				<p class="color_hui">作者：<em><?php echo ($bookInfo["author"]); ?></em></p>
				<p class="color_hui"><em class="novel_left"><?php echo (date('Y.m.d',$bookInfo["shelves_time"])); ?>上架</em><em class="novel_right"><?php echo ($bookInfo["number_of_words"]); ?>字</em></p>
			</div>
		</div>
		<div class="detail_head_bottom">
			<a href="<?php echo U('b_read',['b_id'=>$bookInfo['id']]);?>" class="detail_head_bottom_a1 bgcolor_zhu novel_left">立即阅读</a>
			<!--加入书架/已加入书架-->
			<a href="#" class="detail_head_bottom_a2 novel_right" id="add_bookrack">加入书架</a>
		</div>
		<a href="<?php echo U('catalogue',['b_id'=>$bookInfo['id']]);?>" class="detail_head_middle">目录</a>
	</header>
	<input type="hidden" id="book_id" value="<?php echo ($bookInfo['id']); ?>">
	<article class="detail_art">
		<nav>
			<ul id="detail_nav">
				<li class="novel_left detail_art_nav_li1">本书简介</li>
				<li class="novel_right">作者简介</li>
			</ul>
		</nav>
		<section class="detail_sec">
			<!--本书简介-->
			<p><?php echo ($bookInfo["book_synopsis"]); ?></p>
		</section>
		<section class="detail_sec">
			<!--作者简介-->
			<p><?php echo ($bookInfo["author_synopsis"]); ?></p>
		</section>
	</article>
	<!--二维码部分(背景和img都要换路径)-->
	<div class="detail_share">
		<div class="detail_share_left" style="background-image: url(<?php echo C('IMG');?>2.jpg);"></div>
		<div class="color_black detail_share_right">
			<p>长按二维码关注[芝麻阅读]</p>
			<p>看更多免费好书......</p>
		</div>
		<img src="<?php echo C('IMG');?>2.jpg" alt=""/>
	</div>
	<div id="bookback_alert"></div>
	<script type="text/javascript">
		$(function(){
			//初始化
			$('.detail_sec').eq(0).show().siblings('.detail_sec').hide();
			//列表切换
			$('#detail_nav li').click(function(){
				var $this=$(this);
				var itemIndex = $this.index();
				$this.addClass('detail_art_nav_li1').siblings('#detail_nav li').removeClass('detail_art_nav_li1');
				$('.detail_sec').eq(itemIndex).show().siblings('.detail_sec').hide();
			});
			//加入书架
			if ('<?php echo ($isExistsBookshelf); ?>' != '') {    //判断是否已在书架
				$('#add_bookrack').text('已加入书架');
			}
			$('#add_bookrack').click(function(){
				if($('#add_bookrack').text()=="已加入书架"){
					return false;
				}
				var x=$('#bookback_alert');
				//请求加入书架,下方为请求成功后再执行
				$.post("<?php echo U('setting');?>",{'bookId':$('#book_id').val()}, function (string) {console.log(string)});
				x.css('display','block');
				$('#add_bookrack').text('已加入书架');
				setTimeout(function alertnone(){x.css("display","none");},2000);
			});
		})
	</script>
	</body>


<!--其他操作-->

</html>