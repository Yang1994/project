<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
	*  官网首页
    public function index(){   
    	$lang = I('lang');
    	$data = M('php_article')->field('id,title,the_time')->limit(6)->cache(true,60)->select();
        $this->assign('data',$data);
        if($lang=='ch'){
            $this->display('index');
        }else{
            $this->display('index_en');
        }
    }*/

    /**
	*  资讯详情页
    */
    public function news(){   
    	$id = I('id');
    	$data = M('php_article')->where('id='.$id)->cache(true)->find();
        $this->assign('data',$data);
        $this->display('news');
    }

    public function index(){
        $lang = I('lang');
        
        if($lang=='cn'){
            Cookie('lang',1);
            $this->display('index_cn');
        }else{
            Cookie('lang',0);
            $this->display('index');
        }
    }

    /*
    * 关于我们
    */
    public function about(){
        $this->display('about');
    }

    /*
    * 资讯中心
    */
    public function information(){
        $map = "";
        $article = D('php_article');
        $list = $this->lists($article,$map,'id desc');
        foreach ($list as &$value) {
            if ($value['the_time']) {
                $value['day']=date("d",strtotime($value['the_time']));
                $value['month']=date("Y-m",strtotime($value['the_time']));
            }
        }
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display('information');
    }
    public function information_detail(){
       
        
         
        //渠道类别值
        $this->assign('list', $list);
        $this->display('information_detail');
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$base = array('status'=>array('egt',0)),$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 2;
        }
    
        $page = new \Think\Page($total, $listRows, $REQUEST);
        
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;

        $model->setProperty('options',$options);
        
        return $model->field($field)->select();
    }
}
?>