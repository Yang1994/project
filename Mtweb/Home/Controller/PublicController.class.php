<?php

namespace Home\Controller;
use User\Api\UserApi;
/**
 * 前台首页控制器
 */
class PublicController extends \Think\Controller {
	/*注册页面*/
//	public function register(){
//		//$this->error("当前未开放注册功能!",U('index/index'));
//		if(IS_POST){
//			//短信取值
//			$mobileCode = I('mobileCode');
//			$password = I('password');
//			$pwdRepeat = I('pwdRepeat');
//			$mobile = I('username');
//
//			if($password!=$pwdRepeat){
//				$this->error('确认密码与密码不一致!');
//			}
//			
//		    $phone_name="SMS".trim($mobile);
//			$rand=S($phone_name);
//			
//			//判断输入的验证码是否正确
//			if($rand == $mobileCode){
//				/* 调用注册接口注册用户 */
//	            $User = new UserApi;
//				$uid = $User->register($mobile, $password);
//				if(100000 < $uid){ //注册成功
//					//TODO: 发送验证邮件
//					$this->success('注册成功！',U('public/login'));
//				} else { //注册失败，显示错误信息
//					$this->error($this->showRegError($uid));
//				}
//
//			}else{
//			    $this->error('验证码不正确');
//			}
//		}else{
//			$this->display("register");
//		}
//	}

	/* 登录页面 */
	// public function login($username = '', $password = '', $verify = ''){
	// 	if(IS_POST){ //登录验证
	// 		/* 检测验证码 */
	// 		if(!check_verify($verify)){
	// 			$this->error('验证码输入错误！');
	// 		}

	// 		/* 调用UC登录接口登录 */
	// 		$user = new UserApi;
	// 		$uid = $user->login($username, $password);
			
	// 		if(100000 <= $uid){ //UC登录成功
	// 			/* 登录用户 */
	// 			$Member = D('php_member');
	// 			if($Member->login($uid)){ //登录用户
				// TODO:跳转到登录前页面
	// 				$this->success('登录成功！',U('user/bespeak'));
	// 			} else {
	// 				$this->error($Member->getError());
	// 			}

	// 		} else { //登录失败 
	// 			switch($uid) {
	// 				case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
	// 				case -2: $error = '密码错误！'; break;
	// 				default: $error = '未知错误！'; break; 
	// 			}
	// 			$this->error($error);
	// 		}

	// 	} else { //显示登录表单
	// 		$this->display('login');
	// 	}
	// }

	/* 退出登录 */
	public function logout(){
		D('php_member')->logout();
		$this->redirect('index/index');
		//$this->success('退出成功！', U('index/index'));
	}

	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在16个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '用户名被占用！'; break;
			case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
			case -5:  $error = '邮箱格式不正确！'; break;
			case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
			case -7:  $error = '邮箱被禁止注册！'; break;
			case -8:  $error = '邮箱被占用！'; break;
			case -9:  $error = '手机格式不正确！'; break;
			case -10: $error = '手机被禁止注册！'; break;
			case -11: $error = '手机号被占用！'; break;
			default:  $error = '未知错误';
		}
		return $error;
	}

	//用户登陆
	public function login(){
		if(IS_POST){

			$username = I('username');
			
			if($username){
				if(preg_match("/^1[345678]\d{9}$/", $username)){
					$info = M('php_member')->where("username=".$username)->field('id')->find();
					$this->initCookie();
					cookie('User_Mobile',$username);
					cookie('Mid',$info['id']);
					
					if($info){    //判断用户是否存在,存在为登陆,不存在为注册
						$this->success('验证通过',U('Public/password'));
					}else{
						$this->success('成功',U('Public/identify_code'));
					}
				}else{
					$this->error("手机号不合法!");
				}
			}else{
				$this->error("手机号码不能为空!");
			}
			
		}else{
			$id = I('id');
			if($id){
				cookie('Invite_User_Id',$id);
			}
			$lang = Cookie('lang');
			$this->assign('lang',$lang);
			$this->display('login');
		}
	}

	//初始化cookie
	public function initCookie($mobile){
		cookie('User_Mobile','');
		cookie('Mid','');	
		//$status = "Status".$mobile;
		//cookie($status,'');
	}

	//校验密码
	public function password(){
		if(IS_POST){
			$password = I('password');
			$username = cookie('User_Mobile');

			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password);
			
			if(100000 <= $uid){ //UC登录成功
				/* 登录用户 */
				$Member = D('php_member');
				if($Member->login($uid)){ //登录用户
					//TODO:跳转到登录前页面
					cookie('Mid','');
					$this->success('登录成功！',U('user/index'));
				} else {
					$this->error($Member->getError());
				}

			} else { //登录失败 
				switch($uid) {
					case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
					case -2: $error = '密码错误！'; break;
					default: $error = '未知错误！'; break; 
				}
				$this->error($error);
			}

		}else{
			$mid = cookie('Mid');
			if($mid){
				$lang = Cookie('lang');
				$this->assign('lang',$lang);
				$this->display();
			}else{
				$this->redirect('Public/login');
			}
		}
	}

	//校验验证码
	public function identify_code(){
		// By beard, 2018/3/2
		$invite_id = cookie('Invite_User_Id');
		if($invite_id){
			$invite_num = M('php_member')->where("inviter_id=".$invite_id)->count("id");
			if($invite_num >= 200) {
				$this->error("该用户的邀请人数已达到上限!");
			}
		}
						
		$mobile = cookie('User_Mobile');
		if(IS_POST){
			$mobileCode = I('mobileCode');
			$phone_name="SMS".trim($mobile);
			$rand=S($phone_name);
			//判断输入的验证码是否正确
			if($rand == $mobileCode){
				$codeStatus = "Status".trim($mobile);
				//cookie($codeStatus,1);
				$_SESSION[$codeStatus] = 1; // Fixed user register without identify_code issue, by beard 2018/2/28.
				$this->success('校验成功',U('Public/set_password'));
			}else{
				$this->error('验证码输入错误!');
			}
		}else{
			
			if($mobile){
				A('register')->sendMobileCode($mobile);
				$lang = Cookie('lang');
				$this->assign('lang',$lang);
				$this->display();
			}else{
				$this->redirect('Public/login');
			}
		}
	}

	//设置密码
	public function set_password(){
		if(IS_POST){
			$password = I('pwd');
			$pwdRepeat = I('pwdRepeat');
			$mobile = cookie('User_Mobile');

			if($password!=$pwdRepeat){
				$this->error('确认密码与密码不一致!');
			}
			
			if($mobile){
				$mid = cookie('Mid');
			}else{
				$this->error('登录超时',U('Public/login'));
			}
			
			// Fixed user register without identify_code issue, by beard 2018/2/28.
			$codeStatus = "Status".trim($mobile);
			if(empty($_SESSION[$codeStatus]) ) {
				$this->error('登录超时?');
			}

			//判断用户是否存在，不存在为注册，存在为重置密码
			if(!$mid){
				/* 调用注册接口注册用户 */
	            $User = new UserApi;
				// $uid = $User->register($mobile, $password); // by beard
				$inviter_id = cookie('Invite_User_Id'); // by beard
				$uid = $User->register($mobile, $password, $inviter_id);  // by beard
				if(100000 < $uid){ //注册成功
					
					$money1 = 20;
					$money2 = 20;
					//发放注册奖励
					D('php_member_gain_money_log')->add($uid,1,$money1,'');
					//发放邀请奖励
					$invite_id = cookie('Invite_User_Id');
					
					if($invite_id){
						D('php_member_gain_money_log')->add($invite_id,2,$money2,$uid);
					}
					
					unset($_SESSION[$codeStatus]); // Fixed user register without identify_code issue, by beard 2018/2/28.
					
					$this->initCookie($mobile);
					cookie('Invite_User_Id','');
					//新增领取记录
					/* 登录用户 */
					$Member = D('php_member');
					if($Member->login($uid)){ //登录用户
						//TODO:跳转到登录前页面
						cookie('Mid','');
						$this->success('登录成功!',U('user/index'));
					} else {
						$this->error($Member->getError());
					}
				} else { //注册失败，显示错误信息
					$this->error($this->showRegError($uid));
				}
			}else{
				$codeStatus = "Status".trim($mobile);
				$res = $_SESSION[$codeStatus];  
			    //判断是否短信授权
				if($res){
					$data['password'] = $password;
					$data['id'] = $mid;
					
					/* 调用修改密码接口 */
					$Api = new UserApi();
            		$result = $Api->updatePassword($mid,$data);
					
            		if($result['status']){
		                unset($_SESSION[$codeStatus]); // Fixed user register without identify_code issue, by beard 2018/2/28.
		                $this->success('修改密码成功！',U('index/index'));
		            }else{
		                $this->error($this->showRegError($result['info']));
		            }
				}else{
					$this->error('验证码验证失效!',U('Public/login'));
				}
			}
			
		}else{
			$mobile = cookie('User_Mobile');
			if($mobile){
				$codeStatus = "Status".trim($mobile);
				$res = $_SESSION[$codeStatus];
				if($res){
					$lang = Cookie('lang');
					$this->assign('lang',$lang);
					$this->display('set_password');
				}else{
					$this->redirect('Public/login');
				}	
			}else{
				$this->redirect('Public/login');
			}
		}
	}

}
?>