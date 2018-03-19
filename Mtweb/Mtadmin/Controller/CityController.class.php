<?php
namespace Mtadmin\Controller;
use Think\Controller;
/**
 * 城市管理控制器
 */
class CityController extends AdminController {
	/**
	* 城市列表
	*/
	public function index(){
		$prefix   = C('DB_PREFIX');
        $u_table  = $prefix.('php_city');
		$r_table  = $prefix.('php_store');
		$field = "a.*,GROUP_CONCAT(b.store_name) as store";
		$model = M()->table($u_table.' a')
				   ->join (" LEFT JOIN ".$r_table." b ON a.city_id = b.city_id");
		$map = "";
		$data = $this->lists($model,$map,'a.city_id desc','',$field);
		$this->assign('data',$data);
		$this->meta_title = "城市列表";
		$this->display();
	}

	/**
	*  新增编辑城市及门店信息
	*/
	public function edit(){
		$id = I('id');

		$City = D('php_city');
		
		if(IS_POST){ //提交表单   

		    if(false !== $City->update()){
		    	if($id){
			    	$this->success('编辑成功！', U('index'));
			    }else{
			    	$this->success('新增成功！', U('index'));
                }
            } else {
                $error = $City->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        }else{
        	
        	if($id){
        		//获取城市信息
        		$info = M('php_city')->where('city_id = '.$id)->find();
        		//获取相关门店信息
        		$data = M('php_store')->where('city_id = '.$id)->select();
        		$this->assign('info',$info); 
        		$this->assign('data',$data); 
		    	$this->meta_title = '编辑城市';
		    }else{
		    	$this->meta_title = '新增城市';
		    }

        	$this->display('');
        }
	}

	/**
	*  删除城市及门店信息
	*/
	public function del(){
		$id = I('id');
		
		if($id){
			$map['city_id'] = $id;
			$res = M('php_city')->where($map)->delete();
		}
		
		if($res){
			//删除城市相关门店信息
			M('php_store')->where($map)->delete();
			$this->success('删除成功',U('SelectShow/index'));
		}else{
			$this->error('删除失败',U('SelectShow/index'));
		}
	}
}
?>