<?php
namespace Home\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * 用户预约模型
 */
class PhpMemberOrderLogModel extends Model{
	/* 自动验证规则 */
    protected $_validate = array(
        array('address', '1,99', '地址长度为1-99个字符', self::EXISTS_VALIDATE, 'length'),
        array('mid', '', '你已预约，不可重复提交', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
        array('address', 'require', '地址不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('address', '','该地址已预约，不可重复提交', self::EXISTS_VALIDATE, 'unique'),
    );

	/* 预约模型自动完成 */
    protected $_auto = array(
        array('the_time', NOW_TIME, self::MODEL_BOTH),
        array('mid', UID, self::MODEL_BOTH),
    );

    public function update(){
    	/* 获取数据对象 */
        $data = $this->create($data);
        
        var_dump($data);exit;

        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add($data); //添加基础内容
            if(!$id){
                $this->error = '预约失败！';
                return false;
            }
        }else{
            $id = $this->save($data);
        }

        //内容添加完成
        return $id;
    }

	//统计成功预约次数
	public function success_count(){
		$data = M('php_member_order_log')->count();
		return $data;
	}
}
?>