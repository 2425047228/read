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

	<body>
	<header class="search_head">
		<p><input type="text" name="" id="search_input" value="" placeholder="请输入作者或书名"/><em class="color_black" id="search">搜索</em></p>
	</header>
	<article class="search_art">
		<section class="search_history">
			<?php if(!empty($allSearch)): ?><div id="" class="search_history_list">
					<h3>大家都在搜</h3>
					<ul>
						<?php if(is_array($allSearch)): $i = 0; $__LIST__ = $allSearch;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('b_detail',['b_id'=>$vo['id']]);?>">
							<?php if(($vo['is_hot']) == ""): ?><li>
									<?php else: ?>
								<li class="search_history_li1"><?php endif; ?>
								<?php echo ($vo["book_name"]); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div><?php endif; ?>
			<?php if(!empty($searchRecord)): ?><div id="search_history2" class="search_history_list">
					<p></p>
					<h3 class="search_history_h3_blue">搜索历史<em id="search_del"></em></h3>
					<ul>
						<?php if(is_array($searchRecord)): $i = 0; $__LIST__ = $searchRecord;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:void(0);" class="record_search"><li><?php echo ($vo); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div><?php endif; ?>

		</section>
		<section class="novel_list">
			<ul>
				<!--列表-->

			</ul>
		</section>
		<!--无内容是显示-->
		<section class="novel_secnull">无搜索结果</section>
	</article>

	</body>


	<script type="text/javascript">
		function searched(id) {
			$.post('/index.php/Home/Book/b_search.html',{'id':id},function () {
				location.href = '/index.php/Home/Book/b_detail?b_id='+id;
			});
		}
		$(function(){
			//清空历史
			$('#search_del').click(function(){
				//请求后台后执行
				$.post('/index.php/Home/Book/b_search.html',{'type':'removeSearchRecord'}, function (string) {
					console.log(string);
				});
				$('#search_history2').remove();
			});
			//搜索
			$(".record_search").click(function () {
				console.log($(this).children(0).text());
				startSearch($(this).children(0).text());
			});
			function startSearch(data) {
				$.post('/index.php/Home/Book/b_search.html',{'search':data},function (json) {
					//请求后台后执行
					$('.search_history').hide();
					$('.novel_secnull').hide();
					$('.novel_list ul li').remove();
					var data = eval('('+json+')');
					var len = data.length;
					if (len > 0) {
						var content = '';
						var hot = '';
						for (var i=0; i < len; ++i) {
							switch (data[i].is_hot)
							{
								case 1:hot = '<img src="<?php echo C('IMG');?>hot.png" class="index_img_mark"/>';break;
								case 2:hot = '<img src="<?php echo C('IMG');?>new.png" class="index_img_mark"/>';break;
								case 3:hot = '<img src="<?php echo C('IMG');?>gratis.png" class="index_img_mark"/>';break;
								case 4:hot = '<img src="<?php echo C('IMG');?>updating.png" class="index_img_mark"/>';break;
								default:hot = '';break;
							}
							content += '<li class="novel_list_litop"><a href="javascript:searched('+data[i].id+');"><div class="index_img">';
							content += '<img src="'+data[i].book_banner+'" class="index_img_cover"/>'+hot;
							content += '</div><div class="index_mes"><h5 class="color_black">'+data[i].book_name+'</h5><p>作者：<em>'+data[i].author+'</em>';
							content += '</p><p><em>'+data[i].readed_number+'人已读</em><span>'+data[i].number_of_words+'字</span></p></div></a></li>';
						}
						$('.novel_list ul').append(content);
					} else {
						//如果搜索无结果,显示novel_secnull
						$('.novel_secnull').show();
					}
				});
			}
			$('#search').click(function(){
				if($('#search_input').val().length==0){
					return false;
				}
				startSearch($('#search_input').val());
			});
		});
	</script>

</html>