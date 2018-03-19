<?php
namespace Mtadmin\Model;
use Think\Model;

/**
 * 版本控制信息模型
 */
class VersionManagerModel extends Model{
	protected $_validate = array(
        array('version', 'require', '版本号不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('min_version', 'require', '版本号不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_INSERT),
        array('url', 'require', '下载地址不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );
    
	/* 自动完成规则 */
    protected $_auto = array(
    	array('pub_time',time,self::MODEL_INSERT,'function'),
    	array('plat_form',1,self::MODEL_INSERT), 
    );

	//更新&新增版本控制信息
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

        return $res;
    }
}
?>
