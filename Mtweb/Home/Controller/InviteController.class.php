<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 11:36
 */
namespace Home\Controller;
use Think\Controller;

class InviteController extends Controller{
    /**
     *　结算telegram进群消息
     */
    public function telegram(){

        $map['type']=3;
        $data = M('php_member_gain_money_log')->field('code')->where($map)->order('id desc')->find();

        //获取最近一条已处理的进群奖励发放
        if($data){
            $code = $data['code']+1;
            $url = "https://api.telegram.org/bot518826515:AAHWtR9SZpM1vndE70O8FgN-6OGKllwEOPI/getupdates?offset=".$code;
        }else{
            $url = "https://api.telegram.org/bot518826515:AAHWtR9SZpM1vndE70O8FgN-6OGKllwEOPI/getupdates";
        }
        
        $result = http_curl($url,GET,'');//获取符合条件的telegram群聊天记录
        
        if($result['ok']==true){
            $this->handle_message($result);
        }
    }

    /**
     *　检测数量是否发放完毕
     */
    public function is_send_over(){
        $total_money = 300000;//发放总额
        $map['type']=3;
        
        $data = M('php_member_gain_money_log')->where($map)->sum('money');
        
        if($data<$total_money){
            return true;
        }else{
            return false;
        }
    }

    /**
     *　成功获取聊天群信息，并处理
     */
    public function handle_message($result){
        $res = json_decode($result,true); //解析json字符串
        $arr = $res['result'];
        
        for($i=0;$i<count($arr);$i++){
            $send_status = $this->is_send_over();
            
            //判断是否发放完毕
            if($send_status) {
                $text = $arr[$i]['message']['text'];
                $update_id = $arr[$i]['update_id'];
                
                //判断输入内容是否合法
                if (strlen($text) == 13) {
                    $begin = substr($text, 0, 5);
                    if ($begin == "/code") {
                        $codes = substr($text, 5, 8);
                        $this->add_money_log($codes,$update_id);
                    }
                }else{
                    echo "输入内容不符合条件!";
                }
            }
        }
    }

    /**
     *　发放奖励，并记录日志
     */
    public function add_money_log($codes,$update_id){
        $money = 60;//单次发放数量
        $map['code'] = $codes;
        $info = M('php_member')->where($map)->field('id,money,is_join_telegram')->find();
        if ($info&&$info['is_join_telegram']==0) {  //code有效，及未加群
            M()->startTrans();
            $log['mid'] = $info['id'];
            $log['type'] = 3;
            $log['money'] = $money;
            $log['code'] = $update_id;
            $log['the_time'] = time();
            $log_res = M('php_member_gain_money_log')->add($log);//添加获取日志
            if(!empty($log_res)){
                $minfo['money'] = $info['money']+$money;
                $minfo['is_join_telegram']=1;
                $where['id']=$info['id'];
                $member_res = M('php_member')->where($where)->save($minfo); //更新余额及用户加群状态
            }
            if(!empty($log_res) && !empty($member_res)){
                M()->commit();
                echo "提交成功!";
                //$this->success('事物提交',__ROOT__);
            }else{
                M()->rollback();
                echo "提交失败!";
                //$this->error('事物回滚',__ROOT__);
            }
        }else{
            echo "该用户不存在或用户".$info['id']."你已加群";
        }
    }
}
?>