<?php
namespace Home\Controller;
use OT\DataDictionary;
use Common\Api\SmsApi;
use User\Api\UserApi;

class RegisterController extends HomeController {
	
	//用户注册
	public function sendMobileCode($phone){
	    //发送注册用户
		$ren=$this->validation($phone);
		return $ren;
		exit;
	}

    //短信验证
    public function validation($phone){
		$content=C("PHONE_VERIFICATION");
		$rands=str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
		$phone_name="SMS".$phone;
		$session = S($phone_name);
		if($session){
			return true;
			exit;
		}else{
			S($phone_name,$rands,60);	
		}
		
		$content=str_replace("XXXXXX",$rands,$content);
			
		if (C("SMS_ALLOW")){  //是否开启
		    $sendsms=new SmsApi;
		    $ren=$sendsms->smssend($phone,$content);
		}

		return $ren;
		exit;	
   	}
   
}

?>