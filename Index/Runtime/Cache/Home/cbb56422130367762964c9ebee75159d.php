<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>美文</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    
<link rel="stylesheet" type="text/css" href="/Public/Index/css/dropload.css"/>
<meta name="format-detection" content="telephone=no, email=no"/>

</head>

	<body>
		<header class="art_head">
			<p><input type="text" name="" id="search_input" value="" placeholder="请输入作者或标题"/><em class="color_black" id="search">搜索</em></p>
		</header>
		<article class="art_art">
			<ul>
			
				<!----------
				<li class="color_black">
					<div class="art_li_left"><img src="/Public/Index/images/test_bg.png" alt=""/></div>
					<div class="art_li_right">
						<p class="art_li_p1">作者<em class="color_hui">2014.09.09</em></p>
						<h4>标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</h4>
						<p class="art_li_p2 color_hui"><span class="art_li_p2_1"><em></em>152</span><span class="art_li_p2_2"><em></em>152</span><span class="art_li_p2_3"><em></em>152</span></p>
					</div>
				</li>
				------------->
				<?php if(is_array($list)): foreach($list as $key=>$be): ?><li id="<?php echo ($be["id"]); ?>">
						<!------
						<div class="beautiful" style="display:none"><?php echo ($be["id"]); ?></div>
						<div class="editor" style="display:none"><?php echo ($be["editor_way"]); ?></div>
						<div class="url" style="display:none"><?php echo ($be["url"]); ?></div>
						-------->
							<div class="art_li_left"><img src="<?php echo ($be["cover"]); ?>" alt=""/></div>
							<div class="art_li_right">
								<p class="art_li_p1"><?php echo ($be["author"]); ?><em class="color_hui"><?php echo (date("Y.m.d",$be["sent_time"])); ?></em></p>
								<h4><?php echo ($be["title"]); ?></h4>
								<p class="art_li_p2 color_hui"><span class="art_li_p2_1"><em></em>152</span><span class="art_li_p2_2"><em></em>152</span><span class="art_li_p2_3" be="<?php echo ($be["id"]); ?>"><em></em>152</span></p>
							</div>
					</li><?php endforeach; endif; ?>
			</ul>
        		
        		<!--无内容是显示-->
        		<section class="novel_secnull">无搜索结果</section>
		</article>
		<footer>
        		<!--底部导航栏-->
        		<ul class="novel_btn">
        			<li class="novel_btn1"><a href="<?php echo U('Index/index');?>" class="color_hui"><em class="novel_btn_false"></em>首页</a></li>
        			<li class="novel_btn2"><a href="<?php echo U('Book/library');?>" class="color_hui"><em class="novel_btn_false"></em>书城</a></li>
        			<li class="novel_btn3"><a href="javascript:void(0);" class="color_zhu"><em class="novel_btn_true"></em>美文</a></li>
        			<li class="novel_btn4"><a href="<?php echo U('Book/bookshelf');?>" class="color_hui"><em class="novel_btn_false"></em>书架</a></li>
					<li class="novel_btn5"><a href="<?php echo U('User/me');?>" class="color_hui"><em class="novel_btn_false"></em>我</a></li>
        		</ul>
        </footer>
		<script type="text/javascript">
			$(function(){
				//点赞

				$('.art_art').on('click','.art_li_p2_3',function(e){
					e.stopPropagation();
					var id = $(this).attr("be");
					alert(id);
					if($(this).find('em').hasClass('art_li_p2_3_em1')){
						$(this).find('em').removeClass('art_li_p2_3_em1');
					}else{
						$(this).find('em').addClass('art_li_p2_3_em1');
					}
				});
				
				$('.art_art').on('click','li',function(e){
					e.stopPropagation();
					var id = $(this).attr("id");
					window.location.href="/index.php/Home/BeautifulWords/detail?id="+id;
				});
				

				//搜索
				$('#search').click(function(){
					if($('#search_input').val().length==0){
						return false;
					}
					//请求后台后执行
					
					$('.art_art ul li').remove();
					
					var html='<li id="5"><div class="art_li_left"><img src="/Public/Index/images/test_bg.png" alt=""/></div><div class="art_li_right"><p class="art_li_p1">作者<em class="color_hui">2014.09.09</em></p><h4>标题</h4><p class="art_li_p2 color_hui"><span class="art_li_p2_1"><em></em>152</span><span class="art_li_p2_2"><em></em>152</span><span class="art_li_p2_3"><em></em>152</span></p></div></li>';
					$('.art_art ul').append(html);
					//如果搜索无结果,显示novel_secnull
				});
			});
		</script>
		        <script src="/Public/Index/js/dropload.min.js"></script>
		<script>
		$(function(){
		    var itemIndex = 0;
		    var bool=[false,false,false,false];
		
		
		    // dropload
		    var dropload = $('.art_art').dropload({
		        scrollArea : window,
		        domDown : {
		            domClass   : 'dropload-down',
		            domRefresh : '<div class="dropload-refresh">↑上拉加载更多</div>',
		            domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中</div>',
		            domNoData  : '<div class="dropload-noData">到底啦</div>'
		        },
		        loadDownFn : function(me){
		        		
		        		var result = '';
                    for(var i = 0; i < 4; i++){
                        result +='<li id="'+(i+10)+'"><div class="art_li_left"><img src="/Public/Index/images/test_bg.png" alt=""/></div><div class="art_li_right"><p class="art_li_p1">作者<em class="color_hui">2014.09.09</em></p><h4>标题</h4><p class="art_li_p2 color_hui"><span class="art_li_p2_1"><em></em>152</span><span class="art_li_p2_2"><em></em>152</span><span class="art_li_p2_3"><em></em>152</span></p></div></li>';
                        if(i== 3){
                            // 数据加载完
                            bool[itemIndex] = true;
                            console.log(bool);
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                            break;
                        }
                    }
                    // 为了测试，延迟1秒加载
                    setTimeout(function(){
                        $('.art_art ul').append(result);
                        // 每次数据加载完，必须重置
                        me.resetload();
                    },1000);
		        		
		        }
		    });
		});
		</script>
	</body>


<!--其他操作-->

</html>