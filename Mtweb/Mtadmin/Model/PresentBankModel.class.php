<?php
namespace Mtadmin\Model;
use Think\Model;

/**
 * 提现方式模型
 */
class PresentBankModel extends Model{
	protected $_validate = array(
    	array('bank_name', 'require', '方式名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );

	/**
     * 更新提现方式
     */
	public function update(){
		$data = $this->create();

        if(!$data){ //数据对象创建错误
            return false;
        }
        /* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add($data);
        }else{
            $res = $this->save($data);
        }

        return $res;
	}
}
?>