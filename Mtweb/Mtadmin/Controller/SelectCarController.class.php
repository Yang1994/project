<?php
namespace Mtadmin\Controller;
use Think\Controller;
/**
 * 精选汽车管理控制器
 */
class SelectCarController extends AdminController {
	//精选汽车管理列表
	public function index(){
		$car = D('php_home_select_car');
		$data = $this->lists($car,$map,'weight desc');

		$this->meta_title = "精选车辆管理";
		$this->assign('data',$data);
		$this->display(''); 
	} 

	//编辑精选车辆
	public function edit(){
		$id = I('id');
		
		if(IS_POST){
			$title = I('title');
			$img = I('img');
			$multimage = I('multimage');
			$detail = I('detail');
			if($title){
				$data['title']=$title;
			}else{
				$this->error('车辆名称不能为空');
			}
			$data['img']=$img;
			$data['multimage']=$multimage;
			$data['detail']=$detail;
			if($id){
				$where['id']=$id;
				$res = M('php_home_select_car')->where($where)->save($data);
				if($res!==false){
					$this->success('编辑成功!');
				}else{
					$this->error('编辑失败!');
				}
			}
		}else{
			$info = M('php_home_select_car')->where('id ='.$id)->find();
		}
		$this->meta_title = "编辑车辆信息";
		$this->assign('info',$info);
		$this->display('');
	}

	//设置精选车辆权重
	public function setWeight(){
		$id = I('id');
		$weight = I('weight');

		if($id){
    		$map['id'] = $id;
    	}else{
    		$this->error('参数错误');
    	}
    	
    	if($weight){
    		$info['weight'] = $weight;
    	}else{
    		$info['weight'] = 0;
    	}

    	$res = M('php_home_select_car')->where($map)->save($info);

    	if($res){
    		$this->success('设置成功');
    	}else{
    		$this->error('设置失败');
    	}
	}
}
?>