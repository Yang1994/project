<?php

namespace Home\Controller;
use User\Api\UserApi;
/**
 * 前台首页控制器
 */
class QueryController extends \Think\Controller {
	private $ONE_DAY_SECONDS;

	public function simple() {
		$ONE_DAY_SECONDS = 24 * 3600;
		$start_date = mktime(0, 0, 0, 2, 9, 2018);
		$end_date = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
		$day_count = ($end_date - $start_date)/$ONE_DAY_SECONDS + 1;
		if($day_count <= 0) $day_count = 1;
		
		echo "<html><head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
		echo "<style type=\"text/css\">";
		echo "body {margin:0px; padding:0px; width:100%;}";
		echo "td {font-family:arial; font-size:14px; line-height:24px; text-align:center;}";
		echo "</style></head><body><table style=\"font:arial 14px/36px;\" border=\"1\" colspan=\"7\">";
		echo "<tr><td>&nbsp;&nbsp;日 期&nbsp;&nbsp;</td><td>新增注册人数</td><td>通过邀请注册</td><td>发币数量</td><td>注册奖励</td><td>邀请奖励</td><td>加群奖励</td></tr>";
		
		$total_reg_count = 0;
		$total_inv_count = 0;
		$total_grant_coin = 0;
		$total_reg_grant_coin = 0;
		$total_inv_grant_coin = 0;
		$total_telegram_grant_coin = 0;
		$Member = M("php_member_gain_money_log");
		for($i=0; $i<$day_count; ++$i) {
			$day_start = mktime(0, 0, 0, date("m")  , date("d") - $i, date("Y"));
			$day_end = $day_start + $ONE_DAY_SECONDS;
			
			$condition = "the_time >= " . $day_start . " AND the_time < " . $day_end;
			$reg_count = $Member->where($condition . " AND type = 1")->count("id");
			$inv_count = $Member->where($condition . " AND type = 2")->count("id");
			$grant_coin = $Member->where($condition)->sum("money");
			if (empty($grant_coin) ) $grant_coin = 0;
			$reg_grant_coin = $Member->where($condition . " AND type = 1")->sum("money");
			if (empty($reg_grant_coin) ) $reg_grant_coin = 0;
			$inv_grant_coin = $Member->where($condition . " AND type = 2")->sum("money");
			if (empty($inv_grant_coin) ) $inv_grant_coin = 0;
			$telegram_grant_coin = $Member->where($condition . " AND type = 3")->sum("money");
			if (empty($telegram_grant_coin) ) $telegram_grant_coin = 0;
			echo "<tr><td>" . date("m-d", $day_start) . "</td><td>" . $reg_count . "</td><td>" . $inv_count . "</td><td>" . $grant_coin . "</td><td>" . $reg_grant_coin . "</td><td>" . $inv_grant_coin . "</td><td>" . $telegram_grant_coin . "</td></tr>";
			
			$total_reg_count += $reg_count;
			$total_inv_count += $inv_count;
			$total_grant_coin += $grant_coin;
			$total_reg_grant_coin += $reg_grant_coin;
			$total_inv_grant_coin += $inv_grant_coin;
			$total_telegram_grant_coin += $telegram_grant_coin;
		}
		
		echo "<tr><td>&nbsp;&nbsp;合 计&nbsp;&nbsp;</td><td>" . $total_reg_count . "</td><td>" . $total_inv_count . "</td><td>" . $total_grant_coin . "</td><td>" . $total_reg_grant_coin . "</td><td>" . $total_inv_grant_coin . "</td><td>" . $total_telegram_grant_coin . "</td></tr>";
		echo "</table></body></html>";
	}
	
	private $mobile;
	public function identify_code(){
		$mobile = "18971472511";
		$codeStatus = "Status".trim($mobile);
		
		cookie($codeStatus,1);
		$_SESSION[$codeStatus] = 1;
	}
	
	public function set_password(){
		$mobile = "18971472511";
		$codeStatus = "Status".trim($mobile);
		echo $codeStatus . " - " . cookie($codeStatus) . "<br />";
		echo $codeStatus . " - " . $_SESSION[$codeStatus] . "<br />";
		var_dump($_COOKIE);
		unset($_COOKIE[$codeStatus]);
		unset($_SESSION[$codeStatus]);
		$_COOKIE = array();
		var_dump($_COOKIE);
	}
	
	public function md5() {
		$username = I('username');
		$password = I('password');
		
		echo $username . "," . $password;
		/* 调用UC登录接口登录 */
		$user = new UserApi;
		$uid = $user->login($username, $password);
		echo $uid;
	}
	
	public function invite() {
		$invite_id = cookie('Invite_User_Id');
		if($invite_id){
			$invite_num = M('php_member')->where("inviter_id=".$invite_id)->count("id");
			echo $invite_num . ",,,,,,,,<br />";
			if($invite_num >= 10) {
				$this->error("该用户的邀请人数已达到上限!");
			}
		}
	}
}
?>