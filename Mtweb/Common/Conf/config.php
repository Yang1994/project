
<?php
return array(
	//'配置项'=>'配置值'
	
	/* URL配置 */
	'WEB_URL'=>"http://hudongtoken.com",
    'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
	
	/*调试配置*/
    'SHOW_PAGE_TRACE' => false,
	
	'USER_ADMINISTRATOR' => 1, //管理员用户ID
	'USER_ADMINGUEST' => 2, //管理员客服用户ID
	
    'DEFAULT_MODULE'     => 'Mtadmin', //默认模块
    'SESSION_AUTO_START' => true, //是否开启session
    
	 /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '47.75.16.43', // 服务器地址
    'DB_NAME'   => 'HDT', // 数据库名
    'DB_USER'   => 'hdtuser', // 用户名
    'DB_PWD'    => 'Db52P#ssWd',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    
	//加载配置档
    'LOAD_EXT_CONFIG'=>'configsms',
);

?>