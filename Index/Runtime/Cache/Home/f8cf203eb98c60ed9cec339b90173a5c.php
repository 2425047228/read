<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>芝麻读书</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    
	<link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>swiper.min.css"/>
	<script src="<?php echo C('JS');?>swiper.min.js" type="text/javascript" charset="utf-8"></script>

</head>

	<body>
	<header class="index_head">
		<div class="swiper-container" style="height:100%;">
			<div class="swiper-wrapper">
				<!--轮播图部分-->
				<?php if(!empty($bannerList)): if(is_array($bannerList)): $i = 0; $__LIST__ = $bannerList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><a href="javascript:void(0);"><img src="<?php echo ($vo["banner_file"]); ?>" alt="" width="100%" height="100%"/></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php else: ?>
					<div class="swiper-slide"><a href="#"><img src="<?php echo C('IMG');?>test_lb.png" alt="" width="100%" height="100%"/></a></div><?php endif; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</header>
	<!-- Initialize Swiper -->
	<script>
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			paginationClickable: true,
			autoplayDisableOnInteraction : false,
			autoplay: 3000,
			loop:true
		});
	</script>
	<article class="index_art">
		<!--搜索框-->
		<h4 class="color_zhu">听说这几本书很火，速来围观...<a href="javascript:void(0);" id="show_btn"></a></h4>
		<section class="novel_list">
			<ul>
				<!--列表-->
				<?php if(is_array($hotBookList)): $i = 0; $__LIST__ = $hotBookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="novel_list_libottom">
						<a href="<?php echo U('Book/b_detail',['b_id'=>$vo['id']]);?>">
							<div class="index_img">
								<img src="<?php echo ($vo["book_banner"]); ?>" class="index_img_cover"/>
								<?php switch($vo['is_hot']): case "1": ?><img src="<?php echo C('IMG');?>hot.png" class="index_img_mark"/><?php break;?>
									<?php case "2": ?><img src="<?php echo C('IMG');?>new.png" class="index_img_mark"/><?php break;?>
									<?php case "3": ?><img src="<?php echo C('IMG');?>gratis.png" class="index_img_mark"/><?php break;?>
									<?php case "4": ?><img src="<?php echo C('IMG');?>updating.png" class="index_img_mark"/><?php break; endswitch;?>
							</div>
							<div class="index_mes">
								<h5 class="color_black"><?php echo ($vo["book_name"]); ?></h5>
								<p>作者：<em><?php echo ($vo["author"]); ?></em></p>
								<p><em><?php echo ($vo["readed_number"]); ?>人已读</em><span><?php echo ($vo["number_of_words"]); ?>字</span></p>
							</div>
						</a>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</section>
	</article>
	<footer>
		<!--底部导航栏-->
		<ul class="novel_btn">
			<li class="novel_btn1"><a href="#" class="color_zhu"><em class="novel_btn_true"></em>首页</a></li>
			<li class="novel_btn2"><a href="#" class="color_hui"><em class="novel_btn_false"></em>书城</a></li>
			<li class="novel_btn3"><a href="#" class="color_hui"><em class="novel_btn_false"></em>美文</a></li>
			<li class="novel_btn4"><a href="#" class="color_hui"><em class="novel_btn_false"></em>书架</a></li>
			<li class="novel_btn5"><a href="#" class="color_hui"><em class="novel_btn_false"></em>我</a></li>
		</ul>
	</footer>
	</body>


<!--其他操作-->

</html>