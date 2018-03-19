<?php
namespace Mtadmin\Model;
use Think\Model;

/**
 * 用户信息模型
 */
class UserModel extends Model{
	//删除指定用户
	public function mdelete($id){
		$user = M('user')->where('uid ='.$id)->delete();
		return $user;
	}

	//查询有上架商品的用户s
	public function lists($map = array(),$number,$pagesize,$field = true,$order){
		$prefix   = C('DB_PREFIX');
    	$u_table  = $prefix.('user');
		$r_table  = $prefix.('user_sale_goods');
		$info = M()->table($u_table.' a')
    		   ->distinct(true)
			   ->join (" LEFT JOIN ".$r_table." b ON a.uid = b.uid" )
		       ->field($field)->limit($number,$pagesize)
		       ->where($map)->order($order)->select();
		return $info;
	}
}
?> 