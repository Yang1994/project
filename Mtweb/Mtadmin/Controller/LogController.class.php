<?php
namespace Mtadmin\Controller;
use Think\Controller;
/**
 * 日志审计和敏感词过滤控制器
 */
class LogController extends AdminController {
	//审计日志列表
	public function index(){
		$this->meta_title = "审计日志";    
		$this->display('index');
	}

	//敏感词列表
	public function keyword(){
		$this->meta_title = "关键词过滤";
		$this->display('keyword');
	}

	//访问日志
	public function visit(){
	    $this->meta_title = "访问日志";  
		$this->display('visit');
	}

}
?>