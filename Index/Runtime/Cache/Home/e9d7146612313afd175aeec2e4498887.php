<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>书架</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    

</head>

	<body class="rack_body">
	<?php if(!empty($recordBookInfo)): ?><header class="rack_head">
			<h3>足迹</h3>
			<a href="<?php echo U('b_read',['b_id'=>$recordBookInfo['id']]);?>">
				<div class="rack_head_top">
					<div class="rack_head_top_left">
						<img src="<?php echo ($recordBookInfo["book_banner"]); ?>" alt=""/>
					</div>
					<div class="rack_head_top_right">
						<h4><?php echo ($recordBookInfo["book_name"]); ?></h4>
						<p class="color_hui">作者：<em><?php echo ($recordBookInfo["author"]); ?></em></p>
						<p class="color_hui">阅读时间：<em><?php echo (date('Y.m.d',cookie('readRecordTime'))); ?></em></p>
					</div>
				</div>
			</a>
		</header><?php endif; ?>

	<article class="rack_art">
		<h3>我的书架<em class="color_zhu" id="rack_manager">管理</em></h3>
		<div class="rack_art_edit"><a href="<?php echo U('library');?>" class="color_black">增加</a><a href="javascript:;" class="color_black" id="edit_delete">删除</a></div>
		<!--书架有内容显示内容-->
		<section class="novel_list">
				<ul>
					<?php if(is_array($bookshelf)): $i = 0; $__LIST__ = $bookshelf;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="novel_list_litop">
							<a href="<?php echo U('b_read',['b_id'=>$vo['id']]);?>">
								<div class="index_img">
									<img src="<?php echo ($vo["book_banner"]); ?>" class="index_img_cover"/>
									<?php switch($vo['is_hot']): case "1": ?><img src="<?php echo C('IMG');?>hot.png" class="index_img_mark"/><?php break;?>
										<?php case "2": ?><img src="<?php echo C('IMG');?>new.png" class="index_img_mark"/><?php break;?>
										<?php case "3": ?><img src="<?php echo C('IMG');?>gratis.png" class="index_img_mark"/><?php break;?>
										<?php case "4": ?><img src="<?php echo C('IMG');?>updating.png" class="index_img_mark"/><?php break; endswitch;?>
								</div>
								<div class="index_mes"><h5 class="color_black"><?php echo ($vo["book_name"]); ?></h5><p>作者：<em><?php echo ($vo["author"]); ?></em></p><p><em><?php echo ($vo["readed_number"]); ?>人已读</em><span><?php echo ($vo["number_of_words"]); ?>字</span></p></div>
							</a>
							<em class="novel_list_del" data-id="<?php echo ($vo["id"]); ?>"></em>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>

				</ul>
			</section>
		<!--书架无内容是显示-->
		<section class="novel_secnull">暂无相关数据</section>
	</article>
	<footer>
		<ul class="novel_btn">
			<li class="novel_btn1"><a href="<?php echo U('Index/index');?>" class="color_hui"><em class="novel_btn_false"></em>首页</a></li>
			<li class="novel_btn2"><a href="<?php echo U('library');?>" class="color_hui"><em class="novel_btn_false"></em>书城</a></li>
			<li class="novel_btn3"><a href="<?php echo U('BeautifulWords/beautifulwords');?>" class="color_hui"><em class="novel_btn_false"></em>美文</a></li>
			<li class="novel_btn4"><a href="javascript:void(0);" class="color_zhu"><em class="novel_btn_true"></em>书架</a></li>
			<li class="novel_btn5"><a href="<?php echo U('User/me');?>" class="color_hui"><em class="novel_btn_false"></em>我</a></li>
		</ul>
	</footer>
	</body>


	<script type="text/javascript">
		$(function(){
			var bool=true;
			//点击管理
			$('#rack_manager').click(function(){
				if(bool==true){
					$(this).addClass('rack_art_em1');
					$('.rack_art_edit').show();
					bool=false;
				}else{
					$(this).removeClass('rack_art_em1');
					$('.rack_art_edit').hide();
					bool=true;
				}
			});
			//点击删除
			$('#edit_delete').click(function(){
				if ($(this).text() == '删除') {
					$(this).text('取消');
					$('.novel_list_del').show();
				} else {
					$(this).text('删除');
					$('.novel_list_del').hide();
				}
				bool = true;
				$('#rack_manager').removeClass('rack_art_em1');
				$('.rack_art_edit').hide();
			});
			//删除事件
			$('.novel_list_del').click(function(){
				//请求后台删除书架内容,返回后执行下面语句
				if (confirm('您是否确定移出书架？')) {
					$.post('/index.php/Home/Book/bookshelf.html', {'id':$(this).attr('data-id')},function (string) {console.log(string)});
					$(this).parent().remove();
					//$('.novel_list_del').hide();
					if($('.novel_list ul li').length==0){
						$('.novel_secnull').show();
					}
				}
			});
			if($('.novel_list ul li').length==0){
				$('.novel_secnull').show();
			}
		});
	</script>

</html>