<?php

namespace Mtadmin\Controller;
use Common\Api\SmsApi;

/**
 * 后台配置控制器
 */
class ConfigController extends AdminController {

    /**
     * 配置管理
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
        $map  = array('status' => 1);
        if(isset($_GET['group'])){
            $map['group']   =   I('group',0);
        }
        if(isset($_GET['name'])){
            $map['name']    =   array('like', '%'.(string)I('name').'%');
        }

        $list = $this->lists('PhpConfig', $map,'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('group',C('CONFIG_GROUP_LIST'));
        $this->assign('group_id',I('get.group',0));
        $this->assign('list', $list);
        $this->meta_title = '配置管理';
        $this->display();
    }

   
    /**
     * 批量保存配置
     */
    public function save($config){
        if($config && is_array($config)){
            $Config = M('PhpConfig');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
        S('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

   
    // 获取某个标签的配置参数
    public function group() {
        $id     =   I('get.id',1);
       // $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("PhpConfig")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
	
        if($list) {
            $this->assign('list',$list);
        }
		//短信查询
		$sendsms=new SmsApi;
		$ren=$sendsms->balance();
	
		$renarray=explode(chr(10),$ren);
		$renarray=explode(",",$renarray[1]);
		$this->assign('renarray',$renarray);	
        $this->assign('id',$id);
        $this->meta_title = '系统基本设置';
        $this->display();
    }

  
}

?>