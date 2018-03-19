<?php

namespace Mtadmin\Controller;
use User\Api\UserApi;

/**
 * 数据管理控制器
 */
class DataController extends AdminController {
	//数据补偿查询列表
	public function user_list(){
		$uid = I('uid');
		$nick_name = I('nick_name');
		
		if($uid){
			$map['uid']=$uid;
		}
		
		if($nick_name){
			$map['nick_name']=array('like', '%'.(string)$nick_name.'%');
		}

		if($uid||$nick_name){
			$User = D('go_user');
			$field = "uid,nick_name,diamond,coupons,score,user_level,anchor_level,moon";
			$data = $this->lists($User,$map,'uid desc',null,$field);
			$this->assign('data',$data);
		}
		
		$this->meta_title = "数据查询";
		$this->display('user_list');
	}

	//数据补偿操作
	public function dataCompensate(){
		$type = I('type');
		$uid = I('uid');
		
		if(IS_POST){
			
			$Compensate = D('php_data_compensate_log');
			if(false !== $Compensate->update()){
			    $this->success('操作成功！', U('Data/dataLog'));
            } else {
                $error = $Compensate->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }

		}else{
			$info = D('go_user')->get_nick_name($uid);
			$info['uid']=$uid;
			$this->assign('Type',$type);
			$this->assign('data',$info);
			if($type==1){
				$this->meta_title = "经济补偿";
				$this->display('dataEconomy');
			}else if($type==2){
				$this->meta_title = "等级补偿";
				$this->display('dataLevel');
			}
		}
	}

	//数据补偿记录列表
	public function dataLog(){
		$username = I('username');
		$select = I('select');
		$timestart = I('timestart');
		$timeend = I('timeend');
		$starttime = $timestart." 00:00:00";
		$endtime = $timeend." 23:59:59";
		$uid = I('uid');

		if($username){
			$where['username']=array('like', '%'.(string)$username.'%');
			$ainfo = M('php_admin')->where($where)->field('id')->select();
			$ids = array_column($ainfo,'id');
			$map['admin_id']=array('in',$ids);
		}
		if($timestart && $timeend){
			$map['time'] = array(array('egt',strtotime($starttime)),array('elt',strtotime($endtime)));
		}
		if($select){
			$map['action_type']=$select;
		}
		if($uid){
			$map['uid']=$uid;
		}
		
		$Compensate = D('php_data_compensate_log');
		$data = $this->lists($Compensate,$map,'id desc',null,$field);
		foreach ($data as &$value) {
			if($value['uid']){
				$user = D('go_user')->get_nick_name($value['uid']);
				$value['nick_name']=$user['nick_name'];
			}
			if($value['admin_id']){
				$info = M('php_admin')->where('id='.$value['admin_id'])->field('username')->find();
				$value['admin'] = $info['username'];
			}
			if($value['time']){
				$value['time']=date('Y-m-d H:i:s',$value['time']);
			}else{
				$value['time']='';
			}
		}
		$this->assign('Select',$select);
		$this->assign('data',$data);
		$this->meta_title = "数据查询";
		$this->display('dataLog');
	}

	//游戏币数据查询
	public function coinSearch(){
		$timestart  = I('timestart');
		$timeend = I('timeend');	

		if($timestart&&$timeend){
			
		}

		if($map){
			
		}else{
			$data="";
		}

		$this->meta_title = "游戏币数据查询";
		$this->assign('data',$data);
		$this->display('coinSearch');
	}
}
?>