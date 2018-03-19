<?php

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}

//获取数据的状态操作
function show_status($status) {

    switch ($status){
        case 1  : return    '上线';     break;
        case 2  : return    '下线';     break;
        default : return    false;      break;
    }
}

// 获取数据的状态操作
function show_status_op($status) {

    switch ($status){
        case 0  : return    '开启';     break;
        case 1  : return    '关闭';     break;
        default : return    false;      break;
    }
}

// 获取连麦的状态操作
function show_mic_op($status) {

    switch ($status){
        case 0  : return    '开启';     break;
        case 1  : return    '关闭';     break;
        default : return    false;      break;
    }
}

// 获取连麦的状态操作
function show_banner_op($status) {

    switch ($status){
        case 0  : return    '启用';     break;
        case 1  : return    '禁用';     break;
        default : return    false;      break;
    }
}



// 获取封号状态操作
function show_forbid_op($status) {

    switch ($status){
        case 0  : return    '封号';     break;
        case 1  : return    '解封';     break;
        default : return    false;      break;
    }
}

// 获取数据的处理状态操作
function show_status_do($status) {

    switch ($status){
        case 0  : return    '处理';     break;
        case 1  : return    '取消处理';     break;
        default : return    false;      break;
    }
}



/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 */
function get_status_title($status = null){
    if(!isset($status)){
        return false;
    }
    switch ($status){
        case -1 : return    '已删除';   break;
        case 0  : return    '禁用';     break;
        case 1  : return    '正常';     break;
        case 2  : return    '待审核';   break;
        default : return    false;      break;
    }
}

function get_status_order($status = null){
    if(!isset($status)){
        return false;
    }
    switch ($status){
        case -1 : return    '已删除';   break;
        case 1  : return    '预约';     break;
        case 2  : return    '下单';     break;
        case 3  : return    '结单';   break;
		case 4  : return    '已付款';   break;
		case 5  : return    '未付款';   break;
        default : return    false;      break;
    }
}

/**
 * 获取当前文章文档的分类
 * @param int $id
 * @return array 文档类型数组
 */
function get_cate($cate_id = null){
    if(empty($cate_id)){
        return false;
    }
    $cate   =   M('ArticleSort')->where('id='.$cate_id)->getField('name');
    return $cate;
}

/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))) {
    if($data === false || $data === null ){
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row){
        foreach ($map as $col=>$pair){
            if(isset($row[$col]) && isset($pair[$row[$col]])){
                $data[$key][$col.'_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

/**
     * 导出数据为excel表格
    *@param $data    一个二维数组,结构如同从数据库查出来的数组
    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
    *@param $filename 下载的文件名
    *@examlpe 
     $stu = M ('User');
     $arr = $stu -> select();
     exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
 */
function exportexcel($data=array(),$title=array(),$filename='report'){
        
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");  
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    //导出xls 开始
    if (!empty($title)){
        foreach ($title as $k => $v) {
            $title[$k]=iconv("utf-8", "GBK",$v);
        }
        $title= implode("\t", $title);
        echo "$title\n";
    }
    
    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach ($val as $ck => $cv) {
                $data[$key][$ck]=' '.iconv("utf-8", "GBK//IGNORE", $cv);
            }
        $data[$key]=implode("\t", $data[$key]);
        }
        echo implode("\n",$data);
    }
    
    exit;
} 

//秒转化显示(小时分秒)
function secsToStr($secs) {
    if($secs>=86400){
        $days=floor($secs/86400);
        $secs=$secs%86400;
        $r.=$days.'天';
    }
    if($secs>=3600){
        $hours=floor($secs/3600);
        $secs=$secs%3600;
        $r.=$hours.'小时';
    }
    if($secs>=60){
        $minutes=floor($secs/60);
        $secs=$secs%60;
        $r.=$minutes.'分钟';
    }
    $r.=$secs.'秒';
    return $r;
}
?>