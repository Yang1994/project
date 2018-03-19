<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: gavin
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController {

	/* 用户中心首页 */
	public function index(){
		if(UID){
            $id = UID;
            $info = M('php_member')->where('id='.$id)->field('money')->find();
            if($info){
                $data['money']=$info['money'];
            }else{
                $data['money']=0;
            }
            $this->assign('data',$data);
            $this->display();
            
		}else{
			$this->redirect('Public/login');
		}	
	}

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function profile(){
		if ( !is_login() ) {
			$this->error( '您还没有登陆',U('User/login') );
		}
        if ( IS_POST ) {
            //获取参数
            $uid        =   is_login();
            $password   =   I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success('修改密码成功！');
            }else{
                $this->error($res['info']);
            }
        }else{
            $this->display();
        }
    }

    /**
    * 预约首页
    */
    public function bespeak(){
        //判断是否登陆
        if(is_user_login()){
            //判断该用户是否成功预约
            $mid = UID;
            $res = D('php_member')->is_order_success($mid);
            if($res==true){
                $this->redirect('User/index');
            }else{
                $data = D('php_member_order_log')->success_count();
                if($data>=2500){
                    $status=0;  //0表示已完成全部预约
                }else{
                    $status=1;  //1表示未完成全部预约
                }
                $this->assign('status',$status);
                $this->display();  
            }
        }else{
            $data = D('php_member_order_log')->success_count();
            if($data>=2500){
                $status=0;  //0表示已完成全部预约
            }else{
                $status=1;  //1表示未完成全部预约
            }
            $this->assign('status',$status);
            $this->display();    
        }
    }

    /**
    * 判断是否登陆
    */
    public function is_login(){
        if(is_user_login()){
            $res['status']=1;
            $res['result']="已登陆!";
            $this->ajaxReturn($res);
        }else{
            $res['status']=0;
            $res['result']="该用户未登陆!";
            $this->ajaxReturn($res);
        }
    }

    //更换龙网id
    public function changeId(){
        if(IS_POST){
            $id = I('id');
            $mid = UID;
            $address = I('address');
            
            if(!$mid){
                $res['status']=0;
                $res['message']="非法操作!";
                $this->ajaxReturn($res);
            }
            if(!$address){
                $res['status']=0;
                $res['message']="龙网id不能为空!";
                $this->ajaxReturn($res);
            }else{
                $where['address']=$address;
                $info = M('php_member_order_log')->where($where)->find();
                if($info){
                    $res['status']=0;
                    $res['message']="该龙网id已绑定!";
                    $this->ajaxReturn($res);
                }
            }

            $map['id']=$id;
            $data['mid']=$mid;
            $data['address']=$address;
            $result = M('php_member_order_log')->where($map)->save($data);
            
            if($result!==false){
                $res['status']=1;
                $res['address']=$address;
                $res['message']="修改成功!";
                $this->ajaxReturn($res);
            }else{
                $res['status']=0;
                $res['message']="修改失败!";
                $this->ajaxReturn($res);
            }

        }
        
    }

    //加入社区
    public function join(){
        if(UID){
            $id = UID;
            $info = M('php_member')->where('id='.$id)->field('code')->find();
            if($info){
                $data['code']="/code".$info['code'];
            }else{
                $data['code']='';
            }
            $this->assign('data',$data);
            $this->display();
        }else{
            $this->redirect('Public/login');
        }
        
    }

    //邀请朋友
    public function invite(){
        if(UID){
            $id = UID;
            $map['type']=2;
            $map['mid']=$id;
            $money = M('php_member_gain_money_log')->where($map)->sum('money');
			
            //$data['url']="http://q.shangtv.cn/test/hdt_qr2.php?uid=".$id;
			$data['url']="http://q.shangtv.cn/hdt/?uid=".$id;
            if($money){
                $data['total_money']=$money;
            }else{
                $data['total_money']=0;
            }
            $this->assign('data',$data);
            $this->display();
        }else{
            $this->redirect('Public/login');
        }
    }

    //提取码
    public function code(){
       
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display('code');
    }
    //HDT转出
    public function transfer(){
       
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display('transfer');
    }
    public function certification(){
       
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display('certification');
    }
    public function safe_center(){
       
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display("safe_center");
    }
}
?>
