<?php
//配置文件

return array(
	'database' => array(
		'host'=>'127.0.0.1',
		'port'=>'3306',
		'user'=>'root',
		'pass'=>'root',
		'charset'=>'utf8',
		'dbname'=>'itcast_shop',
		'prefix' => 'it_',
	),//数据库组
	'app' => array(
		'run_mode' => 'dev',//运行模式 dev|pro
	),//应用程序项目组
	'back' => array(
		'goods_list_pagesize' => 2,
	),//后台
	'front' => array(),
);
