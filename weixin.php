<?php
//require './index.php';
define("TOKEN", "zhimayuedu");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->actionWeixin();

class wechatCallbackapiTest
{
	public function actionWeixin(){
        if (isset($_GET['echostr'])) {
            $this->actionValid();
        }else{
            $this->actionResponseMsg();
        }
    }

    public function actionValid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	
	 //响应消息
    public function actionResponseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //error_log(print_r($postStr, true));
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {
                case "event":
                    //error_log(print_r($postObj, true));
                        $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    //error_log(print_r($postObj, true));
                    $result = $this->customService($postObj, $postObj->Content);
                    break;
                case "image":
                   // error_log(print_r($postObj, true));
                    $result = $this->customService_image($postObj);
                    break;
                case "voice":
                   // error_log(print_r($postObj, true));
                    $result = $this->customService_voice($postObj);
                    break;
                default:
                    //error_log(print_r($postObj, true));
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            //error_log(print_r($postObj, true));
            echo '';
            exit;
        }
    }


	
   //自动回复消息
   public function customService($object)
	{
		$content = '您好，请问有什么需要帮助？';
		$textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
	}

    //客服图片消息
    private function customService_image($object)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <MediaId><![CDATA[%s]]></MediaId>
        <MsgId>%s</MsgId>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $object->PicUrl, $object->MediaId, $object->MsgId);
        return $result;
    }

    //客服语音消息
    private function customService_voice($object)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
        <MediaId><![CDATA[%s]]></MediaId>
        <Format><![CDATA[%s]]></Format>
        <MsgId>%s</MsgId>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $object->MediaId, $object->Format, $object->MsgId);
        return $result;
    }



    private function receiveEvent($object)
	{
		$content = "";
		switch ($object->Event)
		{
			case "subscribe":
			    
			    $content[] = array("Title"=>"欢迎关注芝麻阅读",  "Description"=>"", "PicUrl"=>"http://".$_SERVER['HTTP_HOST']."/Public/Home/images/guanzhu.png", "Url" =>"http://".$_SERVER['HTTP_HOST']."/index.php/Home/Index/index");
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
					
                    case "在线客服";
						$content = "您好，请问有什么需要帮助？";
                        exit($this->transmitText($object,$content));
                        break;
                    case "server":
                        $content[] = array("Title"=>"在线客服", "Description"=>"在线客服啊\n还是在线客服", "PicUrl"=>"http://".$_SERVER['HTTP_HOST']."/Public/Home/images/createphoto.jpg", "Url" =>"");
                        break;
					case "制作贺卡":
						$content[] = array("Title"=>"创建我的贺卡", "Description"=>"现在就去创建属于我自己的贺卡", "PicUrl"=>"http://wangzhijia.dev.shuxier.com/Public/Home/images/greetingCard.png", "Url" =>"http://wangzhijia.dev.shuxier.com/index.php/Home/GreetingCard/index?openid=".$object->FromUserName."&weixin_name=".$object->ToUserName);
						break;
					case "创建相册":
						$content[] = array("Title"=>"创建我的相册", "Description"=>"现在就去创建属于我自己的相册", "PicUrl"=>"http://wangzhijia.dev.shuxier.com/Public/Home/images/createphoto.jpg", "Url" =>"http://wangzhijia.dev.shuxier.com/index.php/Home/Photo/do_add?openid=".$object->FromUserName."&weixin_name=".$object->ToUserName);
						break;
					case "已建相册":
						$content[] = array("Title"=>"查看我的相册", "Description"=>"回忆一下我以往的相册", "PicUrl"=>"http://wangzhijia.dev.shuxier.com/Public/Home/images/myphoto.jpg", "Url" =>"http://wangzhijia.dev.shuxier.com/index.php/Home/Photo/index?openid=".$object->FromUserName."&weixin_name=".$object->ToUserName);
						break;
					case "趣味测试":
						$content[] = array("Title"=>"开始趣味测试", "Description"=>"这也太准了吧，马上去测试！", "PicUrl"=>"http://wangzhijia.dev.shuxier.com/Public/Home/images/quiz.png", "Url" =>"http://wangzhijia.dev.shuxier.com/index.php/Home/Quiz/index?openid=".$object->FromUserName."&weixin_name=".$object->ToUserName);
						break;
                }
				$aa = $object->FromUserName;
                break;
		}
			if(is_array($content)){
			$result = $this->transmitNews($object, $content);
			}else{
			$result = $this->transmitText($object, $content);
			}

			return $result;
	}
	
	
	//回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <Content><![CDATA[]]></Content>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }
	
	//回复文本消息
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }


    //生成客服对话
    /*private function customServiceSession()
    {
        $weixin = new \Org\Weixin\jssdk(APPID,APPSECRET);
        $access_token = $weixin->token();    //获取access_token
        $send = new \Org\Weixin\Tijiao();
        $kf_info = $send->refer("https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token={$access_token}");    //获取在线客服信息
        $kf_list = $kf_info['kf_list'];    //将三维数组转换二维
        foreach ($kf_list as $v) {

        }
        // error_log('###############################');
        // error_log($kf_list);
        // error_log($access_token);
        // error_log('###############################');
        // error_log(print_r($kf_list, true));
        // error_log('###############################');

        $url = "https://api.weixin.qq.com/customservice/kfsession/create?access_token={$access_token}";
        /*
        POST数据示例如下：
     {
    "kf_account" : "test1@test",
    "openid" : "OPENID"
  }
    }*/

}