<?php
namespace Mtadmin\Model;
use Think\Model;

/**
 * 用户模型
 */
class SelectShowModel extends Model{

    protected $_validate = array(
        array('show_name', 'require', '节目名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('link_url', 'require', '节目地址不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('show_name', 'checklength', '节目名称超出长度限制', 0, 'callback', 3, array(1, 100)),
    );
    
    /**
    *验证字符长度是否在某个区间，
     *$str : 表单字段接收的内容，
     *$min:最小长度，
     *max:最大长度，
    **/
    function checklength($str, $min, $max) {
        preg_match_all("/./u", $str, $matches);
        $len = count($matches[0]);
        if ($len < $min || $len > $max) {
            return false;
        } else {
            return true;
        }
    }

	/* 自动完成规则 */
    protected $_auto = array(
    	array('make_time',time,self::MODEL_INSERT,'function'),
    	array('show_hot',0,self::MODEL_INSERT)
    );

    //更新&新增商品信息
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