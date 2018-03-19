<?php

namespace Mtadmin\Controller;
use Mtadmin\Model\AuthGroupModel;
use Think\Page;

/**
 * 后台内容控制器

 */
class ArticleController extends AdminController {
	
	/**
	*  文章列表管理
	*/
	public function index(){
		$nickname = I('nickname');
		
		if($nickname){
		    if(is_numeric($nickname)){
                $map['id|title']=   array('like','%'.$nickname.'%');
            }else{
                $map['title']  =  array('like', '%'.(string)$nickname.'%');
            }
		}

		$article = D('php_article');
	    $list = $this->lists($article,$map,'id desc');
		 
		//渠道类别值
		$this->assign('list',   $list);
	    $this->meta_title = '文章列表管理';
	    $this->display();
	}

	/**
     * 文档新增页面初始化
     */
    public function add(){
       	$this->meta_title = '新增文章';
        $this->display();
    }

    /**
     * 文档编辑页面初始化  
     */
    public function edit(){
        
        $id     =   I('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        /*获取一条记录的详细数据*/
        $Article = D('php_article');
        $data = $Article->detail($id);
        if(!$data){
            $this->error($Article->getError());
        }
		
        $this->assign('data', $data);
        $this->meta_title   =   '编辑文章';
        $this->display();
    }

    /**
     * 更新一条数据
     */
    public function update(){
        $res = D('php_article')->update();
        if(!$res){
            $this->error(D('php_article')->getError());
        }else{
            $this->success($res['id']?'更新成功':'新增成功',U('Article/index'));
        }
    }

    /**
     * 文档编辑页面初始化
     */
    public function adel(){
	 
	    $id     =   I('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }
		
		 /*获取一条记录的详细数据*/
        $Article = M('php_article');
		$Article->delete($id);
		$this->success('删除成功',U('Article/index'));	
	}
	
}
?>