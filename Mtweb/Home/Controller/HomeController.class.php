<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}
    
	/**
	* 通过IP获取对应城市信息(该功能基于淘宝第三方IP库接口)
	* @param $ip IP地址,如果不填写，则为当前客户端IP
	* @return  如果成功，则返回数组信息，否则返回false
	*/
	function getIpInfo($ip){
	    if(empty($ip)) $ip=get_client_ip();  //get_client_ip()为tp自带函数
	    $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
	    $result = @file_get_contents($url);
	    $result = json_decode($result,true);
		
	    if($result['code']!==0 || !is_array($result['data'])) return false;
	    return $result['data'];
	}

	/**
     * 前台控制器初始化
     */
    protected function _initialize(){
        define('UID',is_user_login());

        /* 读取数据库中的配置 */
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); //添加配置
        /* if(!C('WEB_SITE_CLOSE')){
           $this->error('站点已经关闭，请稍后访问~');
        }*/
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_user_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}

}
