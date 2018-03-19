<?php
namespace Mtadmin\Controller;
use Think\Controller;
/**
 * 车辆信息管理控制器
 */
class CarInfoController extends AdminController {
	/**
	 * 车辆信息列表
	 */
	public function index(){
		$car = D('php_car_info');
		
		if($city_id){
			$map['city_id']=$city_id;
		}

		$data = $this->lists($car,$map,'id desc');

		$this->assign('data',$data);
		$this->diaplay();
	}
}
?>