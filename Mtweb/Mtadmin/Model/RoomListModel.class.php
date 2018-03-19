<?php
namespace Mtadmin\Model;
use Think\Model;

/**
 * 主播直播详情模型
 */
class RoomListModel extends Model{
	public function lists($map = array(),$number,$pagesize,$field = true,$order){
	    //获取主播基本信息
	    $prefix   = C('DB_PREFIX');
        $u_table  = $prefix.('user');
		$r_table  = $prefix.('room_list');
		$info = M()->table($u_table.' a')
        		   ->distinct(true)
				   ->join (" LEFT JOIN ".$r_table." b ON a.uid=b.owner_id" )
			       ->field($field)->limit($number,$pagesize)
			       ->where($map)->order($order)->select();
		return $info;      
	}

	//时间段内直播时长(分钟)获取
	public function getHour($start,$end,$uid){
		$lmap['create_time'] = array(array('egt',$start),array('elt',$end));
		$lmap['owner_id'] = $uid;
		$lmap['statue'] = array('in','3,4');	
			
		//minute 直播时长(分钟)			
		$list = M('room_list')->where($lmap)->field('create_time,finish_time')->select();

		$time = 0;
		foreach ($list as &$value) {
			# code...
			$finish = strtotime($value['finish_time']);
			$create = strtotime($value['create_time']);
			if($finish>=$create){
				$second = $finish-$create;
			}else{
				$second = 3600; 
			}
			$time += $second;
		}

		$minute = floor($time/60);
		return $minute;
	}

	//获取指定点的直播分钟输
	public function hourList($timestart,$timeend,$startHour,$endHour,$uid){
		$lmap['create_time'] = array(array('egt',$timestart),array('elt',$timeend));
		$lmap["HOUR(create_time)"] = array(array('egt',$startHour),array('elt',$endHour));
		$lmap['owner_id'] = $uid;
		$lmap['statue'] = array('in','3,4');

		//minute 直播时长(分钟)			
		$list = M('room_list')->where($lmap)->field('create_time,finish_time')->select();

		$time = 0;
		foreach ($list as &$value) {
			# code...
			$finish = strtotime($value['finish_time']);
			$create = strtotime($value['create_time']);
			if($finish>=$create){
				$second = $finish-$create;
			}else{
				$second = 3600;
			}
			$time += $second;
		}

		$minute = floor($time/60);
		return $minute;
	}


}
?>