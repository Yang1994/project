<?php

namespace Mtadmin\Controller;
use User\Api\UserApi;
use User\Api\FtpApi;
/**
 * 工具控制器
 */
class ToolController extends AdminController {
	/**
     * 缓存清理首页
     */
    public function toolCache(){
        $this->meta_title = '清除缓存';
        $this->display();
    }
	
    /**
     * 清除缓存
     */
    public function toolCacheClean(){
        //获取参数
        $type1   =   I('post.type1');
        $type2   =   I('post.type2');
        //html清除
        if($type1==1){
            $diradmin = dirname(__FILE__)."/../../Runtime/Cache/admin";
            $this->deldir($diradmin);
            $dirhome = dirname(__FILE__)."/../../Runtime/Cache/Home";
            $this->deldir($dirhome);
            $dirindex = dirname(__FILE__)."/../../Runtime/Cache/Index";
            $this->deldir($dirindex);
            $dirmtadmin = dirname(__FILE__)."/../../Runtime/Cache/Mtadmin";
            $this->deldir($dirmtadmin);
            $a="html缓存清理成功";
        }
        //DB清除
        if($type2==2){
            $dirdb = dirname(__FILE__)."/../../Runtime/Data/_fields";
            $this->deldir($dirdb);
            $b="数据库缓存清理成功";
        }
        $this->success($a."&".$b);
    }
    public function deldir($dir) { 
        $dh=opendir($dir);
        while ($file=readdir($dh)) { 
            if($file!="." && $file!="..") { 
                $fullpath=$dir."/".$file; 
                if(!is_dir($fullpath)) { 
                    unlink($fullpath); 
                } else { 
                    deldir($fullpath); 
                } 
            } 
        } 
        closedir($dh); 
    } 

    /**
     * 上传apk
     */
    public function ftpUpload(){

    }
}

?>