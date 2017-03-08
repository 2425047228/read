<?php
	namespace Org\Weixin;
	
	//用于php 向微信 提交数据，获取返回值
	//其中常用数据 APPID APPSECRET 在入口文件index.php 中定义为常量，需要修改时 往入口文件处修改。
	
	class Tijiao{
		
		/****
		$data 提交时需要传递的数据
		$url 提交数据地址
		此方法使用curl 的post 提交。需要环境支持curl扩展
		
		****/
		public function refer($url,$data){
			$data = $data ? $data : "";
			if($url==""){
				return false;
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
			//curl_setopt($ch, CURLOPT_HEADER, 1);
			//curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$tmpInfo = curl_exec($ch);
			if (curl_errno($ch)) {
			  return curl_error($ch);
			}
			curl_close($ch);
			return  json_decode($tmpInfo, true);
			
		}
	}
?>