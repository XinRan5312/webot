<?php
require 'config.php';
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

require 'ImagePoster.php';
$wechatObj->forwardToWeibo();

class wechatCallbackapiTest
{
  var $poster;

	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        }
    }
    
    public function forwardToWeibo() {
		$this->poster = new ImagePoster();
	  //get post data, May be due to the different environments
	  $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    //extract post data
	  if (!empty($postStr)){                
              $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
              $msgType = $postObj->MsgType;
          
              $fromUsername = $postObj->FromUserName;
              $key = $fromUsername . 'key';
              $toUsername = $postObj->ToUserName;
              if ( $msgType == 'image' ) {
                 $url = $postObj->PicUrl;
                 $this->poster->addImage($key, trim($url));
                 $this->prompt($fromUsername, $toUsername, 'Please send your photo description!');
              } else if ( $msgType == 'text' ) {
                 if ( $this->poster->hasUser($key) ) {
                     $keyword = trim($postObj->Content);  	        
                     $this->poster->addText($key, $keyword);
                     $this->prompt($fromUsername, $toUsername, 'Photo is forwarded to techyizu weibo, thanks!');
                 } else {
                     $this->prompt($fromUsername, $toUsername, 'Please upload your photo first, and then send the photo text!');
                 }
              }
          }
    }
		
    private function prompt($username, $techyizu, $msg) {
        $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";  
            
        $time = time();
        $resultStr = sprintf($textTpl, $username, $techyizu, time(), $msg);
        echo $resultStr;
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>