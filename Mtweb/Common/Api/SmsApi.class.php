<?php
namespace Common\Api;
class SmsApi {
   
     /**
     * 短信控制器初始化
     */
    //短信发送
    public static function smssend($phone,$content){
		$statusStr = array(
			"0" => "短信发送成功",
			"1" => "key无效",
			"2" => "没有分配用户通道",
			"3" => "手机号码无效",
			"4" => "账号余额不足",
			"5" => "余额不足",
			"6" => "内容含有敏感词",
			"7" => "参数不足或参数内容为空",
			"8" => "账号已停用",
			"9" => "短信签名内容有误",
			"21" => "账户不存在",
			"51" => "手机号码不能为空"
		);
		
		if (!$phone){
		   return  51;  
		}
		
		//配置信息
		$uid = 319;
		$key = 'a227930be074d74da1a9c1eae28ef29f';
		
		//构建发送参数
		$timestamp = date('yyyyMMddHHmmss');
		$sign=md5( $uid.$key.$timestamp);

		//验证帐号
		$post_data = array();
		$post_data['uid'] = $uid;
		$post_data['timestamp'] = $timestamp;
		$post_data['sign'] = $sign;
		$post_data['mobile'] = $phone;
		$post_data['text'] = $content;
		$url='http://c.dzd.com/v4/sms/send.do';

		$o='';
		foreach ($post_data as $k=>$v){
			$o.="$k=".urlencode($v).'&';	
		}

		$post_data=substr($o,0,-1);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$result = curl_exec($ch); 
		//释放curl句柄
		curl_close($ch);
        
        return  $result;   
    }
	
	//短信查询
    public static function balance(){
   
   		$smsapi = "http://www.smsbao.com/"; //短信网关
		$user = C("SMS_USER"); //短信平台帐号
		$pass = md5(C("SMS_PASS")); //短信平台密码

		$sendurl = $smsapi."query?u=".$user."&p=".$pass;
		$result = file_get_contents($sendurl);
        return  $result;
		exit;
	}
	
}
?>