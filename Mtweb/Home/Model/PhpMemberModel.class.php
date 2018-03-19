<?php

namespace Home\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * 文档基础模型
 */
class PhpMemberModel extends Model{

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('login', 0, self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('reg_time', NOW_TIME, self::MODEL_INSERT),
        array('login_ip', 0, self::MODEL_INSERT),
        array('login_time', 0, self::MODEL_INSERT),
        array('status', 1, self::MODEL_INSERT),
    );
		
    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if(!$user){ //未注册
            /* 在当前应用中注册用户 */
            $Api = new UserApi();
            $info = $Api->info($uid);
            //dump($info);
            
            $user['id'] = $info['id'];
            $user['username'] = $info['username'];
        } elseif(1 != $user['status']) {
            $this->error = '用户未激活或已禁用！'; //应用级别禁用
            return false;
        }
        
        /* 登录用户 */
        $this->autoLogin($user);

        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'id'             => $user['id'],
            'login'           => array('exp', '`login`+1'),
            'login_time' => NOW_TIME,
            'login_ip'   => get_client_ip(1),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'             => $user['id'],
            'username' => $user['username'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));  
    }

    /**
     * 判断是否已成功预约
     * @param  integer $uid 用户id
     */
    public function is_order_success($uid){
        
        $res =  M('php_member a')->join('left join php_member_order_log b on a.id = b.mid')
            ->where('a.id ='.$uid.' and b.id <= 2500')->find();
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取用户预约信息
     * @param  integer $uid 用户id
     */
    public function getInfo($uid){
        $res =  M('php_member a')->join('left join php_member_order_log b on a.id = b.mid')
            ->where('a.id ='.$uid.' and b.id <= 2500')->field('a.id as mid,b.id,b.address')->find();
        if($res){
            $data['id']=$res['id'];
            $data['mid']=$uid;
            $data['address']=$res['address'];
            $data['money']=200;
        }else{
            $data['id']=$res['id'];
            $data['mid']=$uid;
            $data['address']='';
            $data['money']=0;
        }
        return $data;
    }
}

?>