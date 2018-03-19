<?php
namespace Mtadmin\Controller;
use Think\Upload;

/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class FileController extends AdminController {

    /* 文件上传 */
    public function upload(){
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
		/* 调用文件上传组件上传文件 */
		$File = D('File');
		$file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
		$info = $File->upload(
			$_FILES,
			C('DOWNLOAD_UPLOAD'),
			C('DOWNLOAD_UPLOAD_DRIVER'),
			C("UPLOAD_{$file_driver}_CONFIG")
		);

        /* 记录附件信息 */
        if($info){
            $return['data'] = think_encrypt(json_encode($info['download']));
            $return['info'] = $info['download']['name'];
        } else {
            $return['status'] = 0;
            $return['info']   = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            $this->error('参数错误！');
        }

        $logic = D('Download', 'Logic');
        if(!$logic->download($id)){
            $this->error($logic->getError());
        }

    }

    /**
     * 上传图片
       */
    public function uploadPicture(){
        //TODO: 用户登录检测
		
		   /* 返回标准数据 */
         $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
		
         $picconfig=C('PICTURE_UPLOAD');
		 $Upload = new Upload($picconfig);
         $info   = $Upload->upload($_FILES);
 
         if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
              
                /* 记录文件信息 */
                $value['path'] = "upload/".$value['savepath'].$value['savename'];	//在模板里的url路径
               
            }
           // return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
		
		/* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return = array_merge($info['download'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
		/* 返回JSON数据 */
        $this->ajaxReturn($return);	
    }

    /**
     * 上传头像
       */
    public function uploadImage(){
        //TODO: 用户登录检测
        
        /* 返回标准数据 */
         $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        
         $picconfig=C('PICTURE_UPLOAD');
         $Upload = new Upload($picconfig);
         $info   = $Upload->upload($_FILES);
 
         if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
              
                /* 记录文件信息 */
                $value['path'] = "http://h5.17playlive.com/upload/".$value['savepath'].$value['savename'];   //在模板里的url路径
               
            }
           // return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
        
        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return = array_merge($info['download'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    public function uploadShowImg(){
        /* 返回标准数据 */
         $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        
         $picconfig=C('PICTURE_UPLOAD');
         $Upload = new Upload($picconfig);
         $info   = $Upload->upload($_FILES);
 
         if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
              
                /* 记录文件信息 */
                $value['path'] = "upload/".$value['savepath'].$value['savename'];   //在模板里的url路径
               
            }
           // return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
        
        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return = array_merge($info, $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    } 
}

?>