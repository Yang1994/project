<?php

namespace Home\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class PhpMemberGainMoneyLogModel extends Model{
    public function add($mid,$type,$money,$update_id){
        if($type==1){ //发放总额
            $total_money = 200000;
        }else if($type==2){
            $total_money = 200000;
        }else if($type==3){
            $total_money = 300000;
        }else{
            $total_money = 0;
        }
        $map['type']=$type;
        $data = M('php_member_gain_money_log')->where($map)->sum('money');
        if($data>=$total_money){
            return false;
            exit;
        }else{
            M()->startTrans();
            $log['mid'] = $mid;
            $log['type'] = $type;
            $log['money'] = $money;
            $log['code'] = $update_id;
            $log['the_time'] = time();
            $log_res = M('php_member_gain_money_log')->add($log);//添加获取日志
            if(!empty($log_res)){
                $where['id']=$mid;
                $member_res = M('php_member')->where($where)->setInc('money',$money);; //更新余额及用户加群状态
            }
            if(!empty($log_res) && !empty($member_res)){
                M()->commit();
                return true;
                //$this->success('事物提交',__ROOT__);
            }else{
                M()->rollback();
                return false;
                //$this->error('事物回滚',__ROOT__);
            }
        }

    }

}
?>