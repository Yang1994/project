<?php

namespace Mtadmin\Controller;
use User\Api\UserApi;
use Api\EmojiApi;


/**
 * 后台用户控制器
 */
class UserController extends AdminController {

    /**
     * 用户管理首页
     */
    public function index(){
        $nickname       =   I('nickname');
        $map['status']  =   array('egt',0);
        $map['id'] = array('not in','1,2');
        if(is_numeric($nickname)){
            $map['id|username'] = array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }else{
            $map['username'] = array('like', '%'.(string)$nickname.'%');
        }

        $list   = $this->lists('php_admin', $map);
        int_to_string($list);
        foreach ($list as &$value) {
            $info = M()->table("php_auth_group a ,php_auth_group_access b,php_admin c")->where('c.id = b.uid and a.id= b.group_id and c.id='.$value['id'])->field('a.title,a.id')->find();
            $value['group_id']=$info['id'];
            $value['group']=$info['title'];
        }
	    $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }


  //   /**
  //    * 修改昵称初始化
  //    */
  //   public function updateNickname(){
  //       $nickname = M('Member')->getFieldByUid(UID, 'nickname');
  //       $this->assign('nickname', $nickname);
  //       $this->meta_title = '修改昵称';
  //       $this->display();
  //   }

  //   /**
  //    * 修改昵称提交
  //    */
  //   public function submitNickname(){
  //       //获取参数
  //       $nickname = I('post.nickname');
  //       $password = I('post.password');
  //       empty($nickname) && $this->error('请输入昵称');
  //       empty($password) && $this->error('请输入密码');

  //       //密码验证
  //       $User   =   new UserApi();
  //       $uid    =   $User->login(UID, $password, 4);
  //       ($uid == -2) && $this->error('密码不正确');

  //       $Member =   D('Member');
  //       $data   =   $Member->create(array('nickname'=>$nickname));
  //       if(!$data){
  //           $this->error($Member->getError());
  //       }

  //       $res = $Member->where(array('uid'=>$uid))->save($data);

  //       if($res){
  //           $user               =   session('user_auth');
  //           $user['username']   =   $data['nickname'];
  //           session('user_auth', $user);
  //           session('user_auth_sign', data_auth_sign($user));
  //           $this->success('修改昵称成功！');
  //       }else{
  //           $this->error('修改昵称失败！');
  //       }
  //   }
   
  //   /**
  //    * 查看管理员（代理）报表
  //    */
  //   public function reportAdmin(){
  //       $this->meta_title = '查看管理员（代理）报表';
		// $year=I("year");
		// $month=I("month");
		// $uid=I("uid");
		// if ($year and $month){
		//     if ($month<10){
		//         $month="0".$month;
		//     }
		//   $date=$year."-".$month."-01";
		// }else{
		//     $year=date("Y");
		//     $month=date("m");
		//     $date=date("Y-m")."-01";
		// }
		
		// $firstday = strtotime($date);
		
  //       $lastday =(strtotime("$date +1 month ")-1);

		// if ($uid){  
		//     $map['adminid']=$uid;
		// }else{
		//     $map['adminid']=UID;
		// }
		// $map['status']=3;
		// $map['playtime'] = array(array('gt',$firstday),array('lt',$lastday)) ;
		// $hour=(int)M("order")->where($map)->sum("hour");
		// $price=M("order")->where($map)->sum("price");
		// if ($uid){
		//   $linfo=M("admin")->field("id,percent,contact")->where("id=".$uid)->find();
		// }else{
		//   $linfo=M("admin")->field("id,percent,contact")->where("id=".UID)->find();
		// }		
	 //    $lprice=$price*(100-$linfo['percent'])/100; 
		// $repdate="(".$linfo['contact']." ) ".$year." 年".$month." 月，游戏约玩报表";
		
		// $data = M('order')->where($map)->group('uid')->field('uid,sum(price) as money,sum(hour) as hour')->select();
		// foreach($data as &$value){
		//    if($value['uid']){
		//         $minfo = M('member')->where('id ='.$value['uid'])->field('nickname')->find();
		//         $value['nickname']=$minfo['nickname'];
		//         $value['price']=$value['money']*(100-$linfo['percent'])/100;
	 //       }
		// }
		
		// $this->assign('data',$data);
		// $this->assign('hour', $hour);
		// $this->assign('price', $price);
		// $this->assign('lprice', $lprice);
		// $this->assign('repdate', $repdate);
		// $this->assign('uid', $uid);
  //       $this->assign('info', $linfo);
				
  //       $this->display();
  //   }
	
	 
    /** 
     * 修改管理员资料 
     */ 
    public function updateAdmin(){
        $this->meta_title = '修改管理员资料';
		$uid=I("uid");
		if ($uid){
		 $map['id']=$uid;
		}else{
		  $map['id']=UID;
		}
		$linfo=M("php_admin")->where($map)->find();
		
		//获取省份
        $Area = M("zone")->field('zone_id as fid,zone_name as name')->where('rank=0')->order('zone_id')->select();
	
		//获取城市
		if (isset($linfo['proid'])){
		    $proid=$linfo['proid'];
		}else{
		     $proid=0;
		}
        
		$City = M("zone")->field('zone_id as fid,zone_name as name')->where('affiliation='.$proid)->order('zone_id')->select();
		
		$this->assign('Area', $Area);
		$this->assign('City', $City);
        $this->assign('info', $linfo);
		$this->assign('uid', $uid);	
        $this->display();
    }
    /**
     * 修改管理员资料提交

     */
    public function submitAdmin(){
        //获取参数
        $data['proid']   =   I('post.proid');
        $data['cityid']   =   I('post.cityid');
	    $data['percent']   =   I('post.percent');
	    $data['telephone']   =   I('post.telephone');
	    $data['address']   =   I('post.address');
	    $data['cellphone']   =   I('post.cellphone');
	    $data['contact']   =   I('post.contact');
		$uid   =   I('post.uid');  
	    if ($uid){
		  $map['id']=$uid;
		}else{
		  $map['id']=UID;
		}
		
		$res=M("php_admin")->where($map)->data($data)->save();
		
        if($res){
            $this->success('资料修改成功！');
        }else{
            $this->error("资料修改失败");
        }
    }
	
	/**
     * 修改密码初始化
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display();
    }

    /*
    *重置密码初始化
    */
    public function resetPassword(){
        $id = I('id');
        
        if(IS_POST){
            $data['password'] = md5(I('post.password'));
            empty($data['password']) && $this->error('请输入新密码');
            $repassword = md5(I('post.repassword'));
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }

            $res = M('php_admin')->where('id='.$id)->save($data);
            
            if($res){
                $this->success('重置密码成功！',U('User/index'));
            }else{
                $this->error('重置密码失败！');
            }
        }else{
            $data = M('php_admin')->where('id='.$id)->field('id,username')->find();
            $this->assign('data',$data);
            $this->meta_title = '重置密码';
            $this->display();
        }
        
    }
	
    /**
     * 修改密码提交

     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api    =   new UserApi();
        $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表

     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }

        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
      
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('php_admin', $map );
                break;
            case 'resumeuser':
                $this->resume('php_admin', $map );
                break;
            case 'deleteuser':
                $this->delete('php_admin', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add($username = '', $password = '', $repassword = '',$email='',$mobile = '', $group_id = '', $user_id = ''){
        if(IS_POST){
            $cid = I('request.channel_id');
            $gift_type = I('request.gift_type');
            $anchor_percent = I('request.anchor_percent');
            $system_percent = I('request.system_percent');
            $leader_percent = I('request.leader_percent');
            $guard_type = I('request.guard_type');
            $anchor_guard = I('request.anchor_guard');
            $system_guard = I('request.system_guard');
            $leader_guard = I('request.leader_guard');
            $channel_id = implode(",",$cid);
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            if($group_id == 8){
                if(!$channel_id){
                    $this->error('选择为渠道方时，渠道id不能为空');
                }  
            }

            if($group_id == 9 || $group_id == 10){
                if(!$user_id){
                    $this->error('选择为主播运营者时，用户id不能为空');
                } 

                $user=M('go_user')->where('uid='.$user_id)->field('auth_real_info,admin_id')->find();
                if($user['auth_real_info']!=1){
                    $this->error('该用户id未实名认证，请先实名认证后绑定');
                }
                
                $link = M('php_user_admin_link')->where('user_id='.$user_id)->field('admin_id')->find();
                if($link['admin_id']!=$id){
                    $this->error('该用户id已绑定家族');
                }

                if($user['admin_id']!=2){     //2为官方主播
                    $this->error('该用户id不是官方主播');
                }

                for ($i=0; $i <count($gift_type) ; $i++) {
                    $total_percent = $anchor_percent[$i]+$system_percent[$i]+$leader_percent[$i];
                    
                    if($total_percent!=100){
                        $this->error('三者分成比之和必需为100%');
                    }
                }

                for ($j=0; $j <count($gaurd_type) ; $j++) {
                    $total_percent = $anchor_guard[$j]+$system_guard[$j]+$leader_guard[$j];
                    
                    if($total_percent!=100){
                        $this->error('三者分成比之和必需为100%');
                    }
                }
            }

            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password,$email,$mobile,$channel_id);
            if(0 < $uid){ //注册成功
                /* 将用户添加对应用户组id */
                $gid = $group_id;
                $AuthGroup = D('PhpAuthGroup');
                $AuthGroup->addToGroup($uid,$gid);

                //身份为家族长和运营长，创建对应家族
                if($group_id==9||$group_id==10){
                    $data['name']=$username;
                    $data['type']=$group_id;
                    $data['admin_id']=$uid;
                    $data['create_time']=time();
                    $res = M('php_family')->add($data);
                    //echo $res;
                    //dump($gift_type);dump($anchor_percent);
                    //exit;
                    //创建家族对应分成比
                    $family_id=$res;
                    for ($i=0; $i <count($gift_type) ; $i++) {
                        $percent['family_id']=$family_id;
                        $percent['gift_type']=$gift_type[$i];
                        $percent['anchor_percent']=round($anchor_percent[$i]/100,2);
                        $percent['system_percent']=round($system_percent[$i]/100,2);
                        $percent['leader_percent']=round($leader_percent[$i]/100,2);
                        $percent['the_time']=date("Y-m-d H:i:s",time());
                        M('php_family_gift_percent')->add($percent);
                    }

                    //添加守护分成
                    for($j=0;$j<count($guard_type);$j++){
                        $guard['family_id']=$family_id;
                        $guard['guard_type']=$guard_type[$j];
                        $guard['anchor_percent']=round($anchor_guard[$j]/100,2);
                        $guard['system_percent']=round($system_guard[$j]/100,2);
                        $guard['leader_percent']=round($leader_guard[$j]/100,2);
                        $guard['the_time']=date("Y-m-d H:i:s",time());
                        M('php_family_guard_percent')->add($guard);
                    }
                }

                //绑定对应的APP端的用户id
                if($user_id){
                    $linfo['admin_id']=$uid;
                    $linfo['user_id']=$user_id;
                    M('php_user_admin_link')->add($linfo);
                }

                $this->success('用户添加成功！',U('index'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $data = M('php_gift_category')->select();
            $info = M('php_guard')->select();
            $Group = M('PhpAuthGroup')->where('status <> -1')->field('id,title')->select();
            $Channel = M('php_channel')->select();

            $this->assign('info',$info);
            $this->assign('data',$data);
            $this->assign('Channel',$Channel);
            $this->assign('Group',$Group);
            $this->meta_title = '新增用户';
            $this->display();
        }
    }

    //编辑后台账号信息
    public function edit(){
        $id = I('id');

        if(IS_POST){
            $family_id = I('family_id');
            $group_id = I('group_id');
            $user_id = I('user_id');
            $cid = I('request.channel_id');
            $channel_id = implode(",",$cid);
            $gift_type = I('request.gift_type');
            $anchor_percent = I('request.anchor_percent');
            $system_percent = I('request.system_percent');
            $leader_percent = I('request.leader_percent');
            $guard_type = I('request.guard_type');
            $anchor_guard = I('request.anchor_guard');
            $system_guard = I('request.system_guard');
            $leader_guard = I('request.leader_guard');

            if($group_id == 8){
                if(!$channel_id){
                    $this->error('选择为渠道方时，渠道id不能为空');
                }  
            }

            if($group_id == 9 || $group_id == 10){
                if(!$user_id){
                    $this->error('选择为主播运营者时，用户id不能为空');
                }
                $user=M('go_user')->where('uid='.$user_id)->field('auth_real_info,admin_id')->find();
                if($user['auth_real_info']!=1){
                    $this->error('该用户id未实名认证，请先实名认证后绑定');
                }
                
                $link = M('php_user_admin_link')->where('user_id='.$user_id)->field('admin_id')->find();
                if($link['admin_id']!=null){
                    if($link['admin_id']!=$id){
                        $this->error('该用户id已绑定家族');
                    }
                }

                if($user['admin_id']!=2){     //2为官方主播
                    $this->error('该用户id不是官方主播');
                }

                for ($i=0; $i <count($gift_type) ; $i++) {
                    $total_percent = $anchor_percent[$i]+$system_percent[$i]+$leader_percent[$i];
                    
                    if($total_percent!=100){
                        $this->error('三者分成比之和必需为100%');
                    }
                }

                for ($j=0; $j <count($gaurd_type) ; $j++) {
                    $total_percent = $anchor_guard[$j]+$system_guard[$j]+$leader_guard[$j];
                    
                    if($total_percent!=100){
                        $this->error('三者分成比之和必需为100%');
                    }
                }
            }
            
            if($group_id){
                M('php_auth_group_access')->where('uid='.$id)->delete();
                $AuthGroup = D('PhpAuthGroup');
                $res = $AuthGroup->addToGroup($id,$group_id);
                if($res){
                    if($user_id){
                        M('php_user_admin_link')->where('admin_id='.$id)->delete();
                        $linfo['admin_id']=$id;
                        $linfo['user_id']=$user_id;
                        M('php_user_admin_link')->add($linfo);
                    }

                    if($group_id==8){    //渠道方
                        $data['channel_id']=$channel_id;
                        M('php_admin')->where("id=".$id)->save($data);
                    }

                    //身份为家族长和运营长，创建对应家族
                    if(!$family_id){
                        if($group_id==9||$group_id==10){
                            $data['name']=$username;
                            $data['type']=$group_id;
                            $data['admin_id']=$uid;
                            $data['create_time']=time();
                            $res = M('php_family')->add($data);
                            $family_id=$res;
                            //创建家族对应分成比
                            for ($i=0; $i <count($gift_type) ; $i++) {
                                $percent['family_id']=$family_id;
                                $percent['gift_type']=$gift_type[$i];
                                $percent['anchor_percent']=round($anchor_percent[$i]/100,2);
                                $percent['system_percent']=round($system_percent[$i]/100,2);
                                $percent['leader_percent']=round($leader_percent[$i]/100,2);
                                $percent['the_time']=date("Y-m-d H:i:s",time());
                                M('php_family_gift_percent')->add($percent);
                            }

                            //添加守护分成
                            for($j=0;$j<count($guard_type);$j++){

                                $guard['family_id']=$family_id;
                                $guard['guard_type']=$guard_type[$j];
                                $guard['anchor_percent']=round($anchor_guard[$j]/100,2);
                                $guard['system_percent']=round($system_guard[$j]/100,2);
                                $guard['leader_percent']=round($leader_guard[$j]/100,2);
                                $guard['the_time']=date("Y-m-d H:i:s",time());
                                M('php_family_guard_percent')->add($guard);
                            }
                        }    
                    }else{
                        if($group_id==9||$group_id==10){
                            //修改家族礼物分成比及礼物分成比
                            for ($i=0; $i <count($gift_type) ; $i++) {
                                $where['family_id']=$family_id;
                                $where['gift_type']=$gift_type[$i];
                                $percent['anchor_percent']=round($anchor_percent[$i]/100,2);
                                $percent['system_percent']=round($system_percent[$i]/100,2);
                                $percent['leader_percent']=round($leader_percent[$i]/100,2);
                                $percent['the_time']=date("Y-m-d H:i:s",time());
                                //修改家族礼物分成
                                M('php_family_gift_percent')->where($where)->save($percent);
                                
                                $GiftType=$gift_type[$i];
                                $Opercent=round($anchor_percent[$i]/100,2);
                                $Spercent=round($system_percent[$i]/100,2);
                                $Lpercent=round($leader_percent[$i]/100,2);
                                $time = date("Y-m-d H:i:s",time());
                                $UserPercent = M('php_user_gift_percent a')
                                    ->join(" left join go_user b on a.user_id = b.uid");
                                //修改家族旗下主播守护分成
                                $sql = "UPDATE php_user_gift_percent a LEFT JOIN go_user b 
                                        on a.user_id = b.uid set a.owner_percent =".$Opercent.",a.system_percent =".$Spercent.",a.leader_percent =".$Lpercent.",a.the_time = '".$time."' where a.gift_type = ".$GiftType." and b.admin_id =".$id;
                                M()->execute($sql);
                                //$UserPercent->where($map)->save($percent1);
                            }

                            //修改家族守护分成及主播守护分成
                            for($j=0;$j<count($guard_type);$j++){
                                $where['family_id']=$family_id;
                                $where['guard_type']=$guard_type[$j];
                                $guard['anchor_percent']=round($anchor_guard[$j]/100,2);
                                $guard['system_percent']=round($system_guard[$j]/100,2);
                                $guard['leader_percent']=round($leader_guard[$j]/100,2);
                                $guard['the_time']=date("Y-m-d H:i:s",time());
                                //修改家族守护分成
                                M('php_family_guard_percent')->where($where)->save($guard);

                                $map1['a.guard_type']=$guard_type[$j];
                                $map1['b.admin_id']=$id;
                                $guard1['a.owner_percent']=round($anchor_guard[$j]/100,2);
                                $guard1['a.system_percent']=round($system_guard[$j]/100,2);
                                $guard1['a.leader_percent']=round($leader_guard[$j]/100,2);
                                $guard1['a.the_time']=date("Y-m-d H:i:s",time());
                                $model = M('php_user_guard_percent a')
                                    ->join(" left join go_user b on a.user_id = b.uid");
                                //修改家族旗下主播守护分成
                                $model->where($map1)->save($guard1);
                            }
                        }
                    }

                    $this->success('修改信息成功');
                }else{
                    $this->success('修改信息失败');
                }
                
            }

        }else{
            $info = M('php_admin')->where('id='.$id)->find();
            $family = M('php_family')->where('admin_id='.$id)->field('id')->find();
            if($family){
                $info['family_id']=$family['id'];
            }
            $Group = M('php_auth_group')->select();
            $data = M('php_auth_group_access')->where('uid='.$id)->field('group_id')->find();
            
            if($data){
                $info['group_id']=$data['group_id'];
                if($info['group_id']==10||$info['group_id']==9){
                    $link = M('php_user_admin_link')->where('admin_id='.$info['id'])->find();
                    if($link){
                        $info['user_id']=$link['user_id'];
                    }
                }
                if($data['group_id']){
                    $auth = M('php_auth_group')->where('id='.$data['group_id'])->find();
                    if($auth){
                        $info['title']=$auth['title'];
                    }   
                }
            }
            
            if($info['channel_id']){
                $arr_channel = explode(',',$info['channel_id']);
            }

            //获取渠道方渠道信息
            $Channel = M('php_channel')->select();    
            foreach ($Channel as &$value) {
                if($value['channel_id']){
                    for($i=0;$i<count($arr_channel);$i++){
                        if($arr_channel[$i]==$value['channel_id']){
                            $value['select']=1;
                        }
                    }
                }
                if(!$value['select']){
                    $value['select']=0;
                }   
            }

            //获取礼物及对应分成比
            $gift = M('php_gift_category')->select();
            foreach ($gift as &$value) {
                if($value['category_id']!=null){
                    $map['gift_type']=$value['category_id'];
                    $map['family_id']=$family['id'];
                    $percent = M('php_family_gift_percent')->where($map)->find();
                    if($percent){
                        $value['anchor_percent']=$percent['anchor_percent']*100;
                        $value['system_percent']=$percent['system_percent']*100;
                        $value['leader_percent']=$percent['leader_percent']*100;
                    }
                }
            }

            //获取守护类型及对应分成比
            $guard = M('php_guard')->select();
            foreach ($guard as &$value) {
                if($value['id']!=null){
                    $map['gift_type']=$value['id'];
                    $map['family_id']=$family['id'];
                    $percent = M('php_family_guard_percent')->where($map)->find();
                    if($percent){
                        $value['anchor_percent']=$percent['anchor_percent']*100;
                        $value['system_percent']=$percent['system_percent']*100;
                        $value['leader_percent']=$percent['leader_percent']*100;
                    }
                }
            }

            $this->assign('guard',$guard);
            $this->assign('gift',$gift);    
            $this->assign('Channel',$Channel);
            $this->assign('Group',$Group);
            $this->assign('info',$info);
            $this->meta_title = '编辑用户';
            $this->display('edit');
        }   
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

    //用户信息查询
    public function user(){
        $uid = I('uid');
        $nickname = I('nickname');
        $forbid = I('forbid');

        if($uid){
            $map['uid'] = $uid;
        }

        if($nickname){
            $map['nick_name']=array('like','%'.$nickname.'%');
        } 

        if($forbid){
            $map['forbid_powers'] = $forbid;
        } 

        $User = D('go_user');
        $data = $this->lists($User,$map,'uid desc');
        foreach ($data as &$value) {
            if($value['register_time']){
                $value['register_time']=date('Y-m-d H:i:s',$value['register_time']);
            }else{
                $value['register_time']="";
            }
            if($value['register_channel']){
                $register_channel = $value['register_channel'];
                $channel = M('php_channel')->where("channel_id = '$register_channel'")->field('channel_name')->cache(true)->find();
                $value['channel_name']=$channel['channel_name'];
            }
        }
        $this->meta_title = '主播查询';
        if($forbid){
            $this->assign('Forbid',$forbid);
        }
        $this->assign('data',$data);
        $this->display('user');
    }

    //删除用户
    public function del(){
        $id     =   I('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }
        
         /*删除指定用户账号*/
        $User = D('go_user');
        $ren = $User->mdelete($id);
        if ($ren){
          $this->success('删除成功',U('index'));
        }else{
          $this->error(empty($error) ? '未知错误！' : $error);
        }
    }

    //后台手动充值
    public function recharge(){
        
        if(IS_POST){
            $uid = I('uid');
            $money = I('money');
            $user = M('go_user');
            
            if(!$money){
                $this->error('充值金额不能为空', U('User/recharge'));
            }else{
                $minfo['diamond'] = array('exp','diamond+'.$money);// 用户金币充值
            }
            
            if(!$uid){
                $this->error('用户id不能为空', U('User/recharge'));
            }
            
            $data = $user->where('uid ='.$uid)->find();
            
            if(!$data){
                $this->error('你输入的用户id不存在', U('User/recharge'));
            }else{
                $res = $user->where('uid ='.$uid)->save($minfo);
            }
            
            if($res !== false){
                $this->success('充值成功', U('User/recharge'));      
            }else{
                $this->error('充值失败', U('User/recharge'));
            }
        }else{
            $this->meta_title = '金币充值';
            $this->display('recharge');
        }
    }

    //编辑用户信息
    public function setForbid(){
        $uid = I('uid');
        $forbid_powers = I('forbid_powers');

        if(!$uid){
           $this->error('参数错误，没有用户id', U('User/user')); 
        }

        if($forbid_powers!=null){
            $info['forbid_powers']=$forbid_powers;
        }

        if($uid){
            $res = M('go_user')->where('uid='.$uid)->save($info);
            if($res){
                //后台操作结束，向用户发送系统消息
                $this->success('操作成功！');
            }else{
                $this->error('操作失败！');
            }
        }
    }

    //优秀主播列表
    public function fineUser(){
        $map['a.group_id']=1;
        $data = M('go_user a')
                ->join(" left join php_admin b on a.admin_id = b.id")
                ->field('a.uid,a.nick_name,b.username')
                ->where($map)->select();

        $this->assign('data',$data);
        $this->meta_title = '优秀主播列表';
        $this->display('fineUser');
    }

    //优秀主播设置（绑定/解除）
    public function setFine(){
        $group_id = I('group_id');
        $uid = I('uid');

        $info['group_id']=$group_id;
        
        $res = M('go_user')->where('uid='.$uid)->save($info);
        if($res){
            $where['owner_id']=$uid;
            $where['statue']=1;
            $result = M('go_room_list')->where($where)->field('owner_id,weight')->find();
            
            if($result){
                $group = M('go_group_weight')->where("group_id='$group_id'")->field('weight')->find();
                $data['weight']=$group['weight'];
                M('go_room_list')->where($where)->save($data);
            }
            
            $this->success('操作成功',U('user/fineUser'));
        }else{
            $this->error('操作失败',U('user/fineUser'));
        }
    } 

    //主播搜索
    public function findUser(){
        
        $uid = I('uid');
        
        if($uid){
            $data = M('go_user')->where('uid ='.$uid)->select();
            $this->assign('data',$data);
        }

        $this->meta_title = '主播查询';
        $this->display('findUser');     
    }

    //curl方式改变用户账号状态
    public function forbidChange(){
        $oid = I('oid');
        $forbid = I('forbid');
        $url = "http://shangtv.cn:3003/admin/forbid_user_power";
        $data['oid'] = $oid;
        $data['forbid'] = $forbid;
        /*
        $data['oid'] = 286;
        $data['forbid'] = 1;
        $data = array ('oid' => '286','forbid' => '1');
        */
        $result = $this->httpPost($url,$data);
        
        if($forbid==1){
            $this->success('封号成功');
        }else if($forbid==0){
            $this->success('解封成功');
        }            
    }

    //模拟提交数据函数
    public function httpPost($url,$data){   
        
        $curl = curl_init();
        
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //使用自动跳转
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //执行命令
        $tmpInfo = curl_exec($curl);
        
        //关闭URL请求
        curl_close($curl);

        return $tmpInfo;
    }

    //渠道数据查询
    public function channel(){
        $channel_id = I('channel_id');
        $timestart = I('timestart');
        $timeend = I('timeend');
        $starttime = strtotime($timestart." 00:00:00");
        $endtime = strtotime($timeend." 23:59:59");

        if($channel_id){
            $map['register_channel'] = $channel_id;
            $cmap['channel_id']=$channel_id;
        }else{
            $cmap = "";
        }

        if($timestart && $timeend){
            $map['register_time'] = array(array('egt',$starttime),array('elt',$endtime));
            $rmap['register_time'] = array(array('egt',$starttime),array('elt',$endtime));
            $tmap['purchase_data'] = array(array('egt',$starttime),array('elt',$endtime));
            $lmap['operation_time'] = array(array('egt',$starttime),array('elt',$endtime));
        }else{
            //php获取今日开始时间戳beginToday
            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
            //结束时间戳endToday
            $nowTime=time();
            $map['register_time'] = array(array('egt',$beginToday),array('elt',$nowTime));
            $tmap['purchase_data'] = array(array('egt',$beginToday),array('elt',$nowTime));
            $tmap['operation_time'] = array(array('egt',$beginToday),array('elt',$nowTime));
        }

        $channel = M('php_channel');
        
        $info = $channel->where($cmap)->select();

        foreach ($info as $key => $value) {
            if($timestart && $timeend){
                $map1 = "register_time > ".$starttime." and register_time < ".$endtime." and register_channel = '".$value['channel_id']."'";
            }else{
                $map1 = "register_time > ".$beginToday." and register_time < ".$nowTime." and register_channel = '".$value['channel_id']."'";
            }
            
            $register = M('go_user')->where($map1)->count();

            $userinfo = M('go_user')->where($map1)->field('uid')->select();
            //echo $register;
            //dump($userinfo);

            //register 新增注册数,channel_id渠道id
            $data1['register'] = $register;
            $data1['channel_id'] = $value['channel_id'];
            
            $minfo = M('go_user')->where($map1)->field('uid')->select();
            
            //pmid复合条件的注册用户id
            if($minfo){
                $pmid =array_column($minfo,'uid');
                $tmap['uid'] = array('in',$pmid);
                $tmap['status'] = 2;

                $lmap['uid'] = array('in',$pmid);
                $login = M('go_daily_record')->where($lmap)->count();
                $data1['login'] = $login;
                $logininfo = M('go_daily_record')->where($lmap)->select();
                //dump($logininfo);
                //dump($lmap);exit;

                $trade = M('go_trade')->where($tmap)->sum('money'); 
    
                //trade充值总金额,users用户数量,conversion付费转化率
                if($trade){
                    $data1['trade'] = $trade/100;
                }else{
                    $data1['trade'] = 0;
                }
                
                $tdata = M('go_trade')->where($tmap)->distinct(true)->field('uid')->count();
                $data1['users'] = $tdata;
                $data1['conversion'] = $data1['users']/$data1['register']*100;

                $money = $data1['trade']/$data1['register'];
                //trade_money人均充值金额
                $data1['trade_money'] = round($money,2);
            }else{
                $data1['login'] = 0;
                $data1['trade'] = 0;
                $data1['users'] = 0;
                $data1['conversion'] = 0;
                $data1['trade_money'] = 0;
            }
            $data[]=$data1;
            
        }

        $this->assign('data',$data);
        $this->meta_title = '渠道数据查询';
        $this->display('channel');
    }

    #添加测试号
    public function addTest(){
        $uid = I('uid');

        if(IS_POST){
            $res = M('go_user')->where('uid ='.$uid)->field('uid')->find();
        
            if($res){
                $info['account_type'] = 1;
                $result = M('go_user')->where('uid ='.$uid)->save($info);
                if($result){
                    $this->success('添加成功',U('User/addTest'));
                }else{
                    $this->error('添加失败',U('User/addTest'));
                }
            }else{
                $this->error('用户ID不存在',U('User/addTest'));
            }
        }else{
            $this->meta_title = "添加测试号";
            $this->display('addTest');
        }
    }

    #主播列表(连麦管理)
    public function userList(){
        $status = I('status');
        $uid = I('uid');

        if($uid){
            $map['uid'] = $uid;
        }else{
            $map['auth_real_info'] = 1;
        }

        if($status!=null){
            $map['can_link_mic'] = $status;
            $Status = $status;
        }else{
            $Status = 2;
        }
        
        //$map['robot'] = array('neq',1);

        $User = D('go_user');
        $data = $this->lists($User,$map,' uid desc');

        foreach ($data as &$value) {
            # 获取用户认证信息
            if($value['uid']){
                $field = "identification,identify_type,name";
                $uinfo = M('go_auth_real_info')->where('uid ='.$value['uid'].' and statues = 1')->field($field)->find();
                if($uinfo){
                    $value['identification'] = $uinfo['identification'];
                    $value['identify_type']  = $uinfo['identify_type'];
                    $value['name'] = $uinfo['name'];
                }else{
                    $value['identification'] = "";
                    $value['identify_type']  = "";
                    $value['name'] = "";       
                }
            }
        }
        
        $this->meta_title = "连麦管理";
        $this->assign('Status',$Status);
        $this->assign('data',$data);
        $this->display('userList');
    }

    #主播连麦状态修改
    public function change_mic(){
        $mic = I('mic');
        $uid = I('uid');
        
        if($uid){
            $map['uid'] = $uid;
        }else{
            $this->error('操作用户不存在',U('User/userList'));
        }

        if($mic!=null){
            $data['can_link_mic'] = $mic;   
        }

        $res = M('go_user')->where($map)->save($data);
        if($res){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }        
    }

    /*
    * 管理者主播管理列表  
    */
    public function manageUser(){
        $uid = I('uid');
        $nick_name = I('nick_name');
        $username = I('username');
        
        if($uid){
            $map['m.uid']=$uid;
        }
        if($username){
            $map['a.username']=array('like', '%'.(string)$username.'%');
        }
        if($nick_name){
            $map['m.nick_name']=array('like', '%'.(string)$nick_name.'%');
        }
        
        $map['m.auth_real_info'] = 1;
        $field = "m.uid,m.nick_name,a.username,a.id";

        $prefix   = C('DB_PREFIX');
        $l_table  = $prefix.('go_user');
        $r_table  = $prefix.('php_admin');
        $model    = M()->table( $l_table.' m' )->join ( $r_table.' a ON m.admin_id=a.id' );
        $list = $this->lists($model,$map,'m.uid asc',null,$field);
        foreach ($list as &$value) {
            if($value['uid']){
                $link = M('php_user_admin_link')->where('user_id='.$value['uid'])->find();
                if($link){
                    $admin_id = $link['admin_id'];
                    $where = "a.group_id = b.id and a.uid=".$admin_id;
                    $group = M()->table('php_auth_group_access a,php_auth_group b')->where($where)->find();
                    if($group){
                        $value['group_id']=$group['group_id'];
                        $value['group_name']=$group['title'];
                    }
                }  
            }
        }

        $this->assign('data',$list);
        $this->meta_title="管理者主播管理";
        $this->display();
    }

    /*
    * 查看/编辑主播管理者
    */
    public function editManage(){
        $uid = I('uid');
        $admin_id = I('admin_id');
        $image = I('image');
        if(IS_POST){
            $data['admin_id'] = $admin_id;
            $data['image'] = $image;
            $res= M('go_user')->where('uid='.$uid)->save($data);
            if($res){
                if($admin_id){
                    //修改主播表的家族管理者
                    $anchor_info['admin_id']=$admin_id;
                    M('php_anchor')->where('uid='.$uid)->save($anchor_info);

                    $result = M('php_user_gift_percent')->where('user_id='.$uid)->find();
                    if($result){
                        M('php_user_gift_percent')->where('user_id='.$uid)->delete();
                        M('php_user_guard_percent')->where('user_id='.$uid)->delete();
                    }
                    
                    $prefix   = C('DB_PREFIX');
                    $l_table  = $prefix.('php_family_gift_percent');
                    $r_table  = $prefix.('php_family');
                    $model    = M()->table( $l_table.' m' )->join ( $r_table.' a ON m.family_id=a.id' );
                    $map['a.admin_id']=$admin_id;
                    $field="m.gift_type,m.anchor_percent,m.system_percent,m.leader_percent";
                    $data = $this->lists($model,$map,'m.gift_type asc',null,$field);
                    
                    //添加主播游戏，礼物分成
                    if($data){
                        foreach ($data as $key => $value) {
                            $data1['user_id']=$uid;
                            $data1['gift_type']=$value['gift_type'];
                            $data1['owner_percent']=$value['anchor_percent'];
                            $data1['system_percent']=$value['system_percent'];
                            $data1['leader_percent']=$value['leader_percent'];
                            $data1['the_time']=date('Y-m-d H:i:s',time());
                            $percent[]=$data1;
                        }
                        //批量插入礼物分成
                        M('php_user_gift_percent')->addAll($percent);
                    }

                    $GuardPercent = M('php_family_guard_percent b')
                                ->join(' left join php_family a on b.family_id = a.id');
                    $field1="b.guard_type,b.anchor_percent,b.system_percent,b.leader_percent";
                    $info= $GuardPercent->where($map)->field($field1)
                            ->order('b.guard_type asc')->select();
                    if($info){
                        foreach ($info as $key => $value) {
                            $info1['user_id']=$uid;
                            $info1['guard_type']=$value['guard_type'];
                            $info1['owner_percent']=$value['anchor_percent'];
                            $info1['system_percent']=$value['system_percent'];
                            $info1['leader_percent']=$value['leader_percent'];
                            $info1['the_time']=date('Y-m-d H:i:s',time());
                            $guard[]=$info1;
                        }
                        //批量插入守护分成
                        M('php_user_guard_percent')->addAll($guard);
                    }
                    
                }
                $this->success('编辑成功',U('user/manageUser'));
            }else{
                $this->error('编辑失败',U('user/manageUser'));
            }
        }else{
            //获取用户基本信息
            $data = M('go_user')->where('uid='.$uid)->field('uid,admin_id,image,nick_name')->find();
            if($data['admin_id']){
                $l_info = M('php_user_admin_link')->where('admin_id='.$data['admin_id'])->field('user_id')->find();
                if($l_info){
                    $ainfo = M('go_user')->where('uid='.$l_info['user_id'])->field('uid,nick_name')->find();
                    $data['id']=$ainfo['uid'];
                    $data['username'] = $ainfo['nick_name'];
                }
                
                $group = M('php_auth_group_access')->where('uid='.$data['admin_id'])->field('group_id')->find();
                $data['commossion_id']=$group['group_id'];
            }

            //获取用户绑定的管理者id
            $map['b.group_id'] = array('in','9,10,11');
            $field='a.id,a.username';
            $prefix   = C('DB_PREFIX');
            $l_table  = $prefix.('php_admin');
            $r_table  = $prefix.('php_auth_group_access');
            $model    = M()->table( $l_table.' a' )->join ( $r_table.' b ON a.id=b.uid' );
            $list = $model->where($map)->order('a.id asc')->field($field)->select();

            foreach ($list as &$value) {
                if($data){
                    if($value['id']==$data['admin_id']){
                        $value['select']=1;
                    }else{
                        $value['select']=0;
                    }
                }else{
                    $value['select']=0;
                }
            }

            $this->assign('list',$list);
            $this->assign('data',$data);
            $this->meta_title="编辑主播管理者";
            $this->display('editManage');
        }
    } 
}

?>