<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($bookInfo["book_name"]); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    
</head>

	<body id="read_body">
	<header class="read_head">
		<a href="<?php echo (cookie('goBack')); ?>"><em class="read_head_em color_zhu">返回</em><span><?php echo ($bookInfo["book_name"]); ?></span></a>
	</header>
	<input type="hidden" id="book_id" value="<?php echo ($bookId); ?>">
	<article class="read_art">
		<p class="read_art_word"><?php echo ($chapterInfo["chapter"]); echo ($chapterInfo["chapter_content"]); ?></p>
		<div>
			<a href="<?php echo U('b_read',['b_id'=>$bookId,'chapter'=>($chapterInfo['chapter_sort']+1)]);?>" class="color_zhu" id="read_next"><?php echo ($nextChapter); ?></a>
			<p>请点击章节到下一章</p>
			<em></em>
		</div>

	</article>
	<footer>
		<div id="read_set" class="read_sets">
			<div id="read_set1" class="read_sets1">
				<p>
					<a href="<?php echo U('b_read',['b_id'=>$bookId,'chapter'=>($chapterInfo['chapter_sort']-1)]);?>"><em class="read_sets1_em1">上一章</em></a>
					<strong><?php echo ($chapterInfo["chapter"]); ?></strong>
					<a href="<?php echo U('b_read',['b_id'=>$bookId,'chapter'=>($chapterInfo['chapter_sort']+1)]);?>"><em class="read_sets1_em2">下一章</em></a>
				</p>
				<ul>
					<a href="<?php echo U('catalogue',['b_id'=>$bookId]);?>"><li class="read_sets1_li1">目录</li></a>
					<li class="read_sets1_li2" id="read_fontsize">字体大小</li>
					<li class="read_sets1_li3" id="read_bookrack">加入书架</li>
				</ul>
			</div>
			<div id="read_set2" class="read_sets2">
				<p class="read_sets2_p1">字号大小<em value="32" class="read_sets2_p1_em1">小</em><em value="36">中</em><em value="40">大</em><em value="48">特大</em></p>
				<p class="read_sets2_p2">背景<em class="read_sets2_p2_em1 read_sets2_p2_emchange" value="url(<?php echo C('IMG');?>read_bg.png)"></em><em class="read_sets2_p2_em2" value="#ffffff"></em><em class="read_sets2_p2_em3" value="#080808"></em></p>
			</div>
		</div>
	</footer>
	<div id="bookback_alert"></div>
	<div id="bookback_firstalert">
		<div></div>
	</div>
	</body>


	<script type="text/javascript">
		//取消默认事件
		var event_f = function(e){e.preventDefault();}
		function alertnone(){
			document.body.removeEventListener('touchmove', event_f, false);
			$('#bookback_alert').hide();
		};
		$(function(){
			var bool=true;
			//首次进入有引导
			if ('<?php echo (cookie('isFirst')); ?>' == 'isFirst') {
				$('#bookback_firstalert').hide();
			} else {
				$('#bookback_firstalert').show();
				document.body.addEventListener('touchmove', event_f, false); //禁止页面滚动
				$('#bookback_firstalert').click(function(){
					document.body.removeEventListener('touchmove', event_f, false);
					$('#bookback_firstalert').hide();
					$('#read_set1').show();
					setting({'isFirst':'isFirst'});
					bool=false;
				});
			}

			//下一章
			$('#read_next').click(function(e){
				e.stopPropagation();
				window.location.href="http://www.baidu.com";
			});


			//列表弹出
			$('.read_art').click(function(){
				if(bool==true){
					$('#read_set1').show();
					bool=false;
				}else{
					$('#read_set1').hide();
					$('#read_set2').hide();
					bool=true;
				}
			});

			//字体大小调整
			$('#read_fontsize').click(function(){
				$('#read_set1').hide();
				$('#read_set2').show();
			});

			//加入书架
			var add_bookrack=false;
			if ('<?php echo ($isExistsBookshelf); ?>' != '') {
				add_bookrack=true;
				$('#read_bookrack').text('已加入书架');
			}
			$('#read_bookrack').click(function(){
				if(add_bookrack==true){
					return false;
				}
				var div=$('#bookback_alert');
				var w=$(window).width();
				//请求加入书架,下方为请求成功后再执行
				var scroll_top=document.body.scrollTop;//网页被卷去的高
				var toph=7*w/15+scroll_top;
				add_bookrack=true;
				$(this).text('已加入书架');
				setting({'bookId':$('#book_id').val()});
				div.css({'display':'block','top':toph});
				document.body.addEventListener('touchmove', event_f, false); //禁止页面滚动
				setTimeout('alertnone()',1000);
			});

			//字号调整
			$('.read_sets2_p1 em').click(setFontSize);

			//背景调整
			$('.read_sets2_p2 em').click(setBackgroundColor);

			function setBackgroundColor(){
				if (arguments.length > 0 && typeof(arguments[0]) != "object") {
					var color = arguments[0];
					var $this = $('em[value="'+color+'"]');
				} else {    //用户主动设置
					var $this=$(this);
					var color=$this.attr('value');
					setting({'backgroundColor':color});
				}

				$this.addClass('read_sets2_p2_emchange').siblings('.read_sets2_p2 em').removeClass('read_sets2_p2_emchange');
				$('#read_body').css('background',color);
				$('#read_body').css('background-size','contain');
			}

			function setFontSize(){
				if (arguments.length > 0 && typeof(arguments[0]) != "object") {
					var size = arguments[0];
					var $this = $('em[value="'+size+'"]');
				} else {    //用户主动设置
					var $this=$(this);
					var size=$this.attr('value');
					setting({'fontSize':size});
				}
				var h=parseInt(size)+20;
				$this.addClass('read_sets2_p1_em1').siblings('.read_sets2_p1 em').removeClass('read_sets2_p1_em1');
				$('.read_art_word').css('font-size',size/75+'rem');
				$('.read_art_word').css('line-height',h/75+'rem');
			}

			function setting(setInfo) {
				$.post("<?php echo U('setting');?>",setInfo, function (string) {console.log(string)});
			}
			if ('<?php echo (cookie('fontSize')); ?>' != '') {
				setFontSize('<?php echo (cookie('fontSize')); ?>');

			}
			if ('<?php echo (cookie('backgroundColor')); ?>' != '') {
				setBackgroundColor('<?php echo (cookie('backgroundColor')); ?>');
			}


		});
	</script>

</html>