<?php
namespace Home\Controller;
use OT\DataDictionary;

class OrderController extends HomeController {
    /*
	* 添加预约信息	
    */
    public function add(){
    	
    	$address = I('address');
    	if(!$address){
    		$this->error('地址不能为空!');
    	}
    	
    	if(is_user_login()){
    		
    		$result = D('php_member_order_log')->success_count();
    		if($result<2500){
    			$Order = D('php_member_order_log');
    			$res = $Order->update();
    			
    			if(false !== $res&&$res<=2500){
			    	$this->success('预约成功!',U('user/index'));
	            } else {
	                $error = $Order->getError();
	                $this->error(empty($error) ? '未知错误！' : $error);
	            }
    		}else{
    			$this->error("预约已结束!");
    		}
    	}else{
    		$this->error("请先登录后提交!",U('Public/login'));
    	}
    }
}
?>