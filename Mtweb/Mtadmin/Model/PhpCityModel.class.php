<?php

namespace Mtadmin\Model;
use Think\Model;
/**
 * 城市模型
 */
class PhpCityModel extends Model {
	protected $_validate = array(
        array('city_name', 'require', '城市名不能为空！', self::EXISTS_VALIDATE, 'regex'),
        array('country', 'require', '国家不能为空', self::EXISTS_VALIDATE, 'regex'), //用户名被占用
    );

	/**
 	* 更新城市门店数据
 	*/
    public function update(){
    	$data = $this->create();
		
        if(!$data){ //数据对象创建错误
            return false;
        }

    	/* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add($data);
			$data['id']=$res;
        }else{
            $res = $this->save($data);    
        }
        
        //添加城市门店信息
        if ($res !== false) {
        	$store = explode(",",I('store'));
        	$lat   = explode(",",I('lat'));
        	$lng   = explode(",",I('lng'));
        	M('php_store')->where('city_id = '.$res)->delete();
        	for($i=0;$i<count($store);$i++){
        		$data1['store_name']=$store[$i];
        		$data1['geo_lat']=$lat[$i];
        		$data1['geo_lng']=$lng[$i];
        		$data1['city_id']=$res;
        		$info[] = $data1;
        	}
        	M('php_store')->addAll($info);
        }

        return $res;
    }
}
?>