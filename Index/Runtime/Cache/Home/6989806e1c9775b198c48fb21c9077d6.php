<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>目录</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0" name="viewport" />
    <link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>common.css"/>
    <script src="<?php echo C('JS');?>common.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo C('JS');?>jquery.min.js" type="text/javascript" charset="utf-8"></script>
    
	<link rel="stylesheet" type="text/css" href="<?php echo C('CSS');?>dropload.css"/>
	<style type="text/css">
		.dropload-up,.dropload-down{
			position: relative;
			height: 0;
			bottom:0;
			overflow: hidden;
			font-size: 0.4rem;
			/* 开启硬件加速 */
			-webkit-transform:translateZ(0);
			transform:translateZ(0);
		}
		.dropload-down{
			height: 1.2rem;
		}
		.dropload-refresh,.dropload-update,.dropload-load,.dropload-noData{
			height: 1.2rem;
			line-height: 1.2rem;
			text-align: center;
			color: rgb(153,153,153);
		}
	</style>

</head>

	<body>
	<article class="dir_art">
		<h4><?php echo ($bookName); ?></h4>
		<ul>
			<!--<a href="#"><li>第一章第一章第一章第一章第一章第一章第一章第一章第一章第一章第一章</li></a>
			<ol>
				<a href="#"><li>小标题第一章第一章第一章第一章第一章第一章第一章第一章第一章第一章第一章</li></a>
				<a href="#"><li>小标题</li></a>
				<a href="#"><li>小标题</li></a>
				<a href="#"><li>小标题</li></a>
			</ol>
			<a href="#"><li>第一章</li></a>
			<ol>
				<a href="#"><li>小标题</li></a>
				<a href="#"><li>小标题</li></a>
				<a href="#"><li>小标题</li></a>
				<a href="#"><li>小标题</li></a>
			</ol>-->
		</ul>
	</article>
	</body>


	<script src="<?php echo C('JS');?>dropload.min.js"></script>
	<script>
		$(function(){
			var itemIndex = 0;
			var bool=[false,false,false,false];
			/********************************************************************************************************/
			var pageInfo = 1;    //记录数据分页信息
			var bookId = '<?php echo ($bookId); ?>';
			getData(pageInfo);
			pageInfo++;
			function getData(page,execStart,execResult) {
				if (typeof execStart == "function") {
					execStart();
				}
				$.post('/index.php/Home/Book/catalogue/b_id/21.html',{'b_id':bookId,'page':page},function (json) {
					var jsonData = eval('('+json+')');
					var len = jsonData.length;
					if (len == 0) {
						execResult('noData');
					}

					var content = '';
					for (var i = 0; i < len; ++i) {
						content +='<a href="/index.php/Home/Book/b_read?b_id=<?php echo ($bookId); ?>&chapter='+jsonData[i].chapter_sort+'"><li>'+jsonData[i].chapter+'</li></a>';
					}
					$('.dir_art ul').append(content);
					if (typeof execResult == "function") {
						execResult();
					}
				});
			}
			/********************************************************************************************************/

			// dropload
			var dropload = $('.dir_art').dropload({
				scrollArea : window,
				domDown : {
					domClass   : 'dropload-down',
					domRefresh : '<div class="dropload-refresh">↑上拉加载更多</div>',
					domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中</div>',
					domNoData  : '<div class="dropload-noData">到底啦</div>'
				},
				loadDownFn : function(me){
					getData(
							pageInfo,
							function () {
								me.lock();
							},
							function () {
								if (arguments[0] == 'noData') {
									console.log(arguments[0]);
									bool[itemIndex] = true;
									me.lock();
									me.noData();
								} else {
									me.unlock();
									me.resetload();
								}
							}
					);
					pageInfo++;
					/*var result = '';
					for(var i = 0; i < 4; i++){
						result +='<a href="#"><li>第一章</li></a><ol><a href="#"><li>小标题</li></a><a href="#"><li>小标题</li></a><a href="#"><li>小标题</li></a><a href="#"><li>小标题</li></a></ol>';
						if(i== 2){
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
						$('.dir_art ul').append(result);
						// 每次数据加载完，必须重置
						me.resetload();
					},1000);
*/
				}
			});
		});
	</script>

</html>