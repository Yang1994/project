<?php
namespace Mtadmin\Controller;
use Think\Upload;

/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class PictureController extends AdminController {
    public function index(){
	  
		if(IS_POST){
		    $mid =  I('mid');
			$pcid=I('pcid');
			$data=array();
		     $data['img1'] = I('img1');
			 $data['img2'] = I('img2');
			 $data['img3'] = I('img3');
			 $data['img4'] = I('img4');
			 if(empty($pcid)){
			      $data['mid'] = $mid;
			      $result = D('MemberImg')->imgAdd($data);
				 
			 }else{
			     $result = M('MemberImg')->where("mid=$pcid")->save($data); 
			 }
		     if(false !== $result){
                $this->success('保存成功！',U('Member/index'));
              } else {
                $error = D('MemberImg')->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
		 }else{
		   $id =  I('get.id');
	       $info = M('MemberImg')->where("mid=$id")->find(); 
		   $this->assign('mid', $id);
		   $this->assign('info', $info);
	       $this->display('add_picture');
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
                /* 已经存在文件记录 */
               // if(isset($value['id']) && is_numeric($value['id'])){
                //    continue;
              //  }

                /* 记录文件信息 */
                $value['path'] = "upload/".$value['savepath'].$value['savename'];	//在模板里的url路径
               // if($this->create($value) && ($id = $this->add())){
                //    $value['id'] = $id;
               // } else {
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
               //     unset($info[$key]);
               // }
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
	 
}


?>