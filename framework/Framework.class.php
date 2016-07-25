<?php

/**
 * 框架的初始化类
 */
class Framework {
	/**
	 * 总的执行方法
	 */
	public static function run() {
		//依次调用初始化方法
		self::initRequest();
		self::initPath();
		self::loadConfig();
		//初始化错误处理的配置
		self::initErrorHandler();
		//注册自己的自动加载方法
		
		spl_autoload_register(array('Framework', 'itcast_autoload'));
		self::dispatch();
		
	}

	private static function initErrorHandler() {
		if('dev' == $GLOBALS['config']['app']['run_mode']) {
			ini_set('error_reporting', E_ALL | E_STRICT);
			ini_set('display_errors', 1);
			ini_set('log_errors', 0);
		} elseif ('pro' == $GLOBALS['config']['app']['run_mode']) {
			ini_set('display_errors', 0);
			ini_set('error_log', APP_DIR . 'error.log');
			ini_set('log_errors', 1);
		}

	}

	/**
	 * 初始化请求参数
	 */
	private static function initRequest() {
		//将获得的三个参数声明称常量
		//常量没有作用域！
		define('PLATFORM', isset($_GET['p']) ? $_GET['p'] : 'back');
		define('CONTROLLER', isset($_GET['c']) ? $_GET['c'] : 'Admin');
		define('ACTION', isset($_GET['a']) ? $_GET['a'] : 'login');
	}

	/**
	 * 初始化路径常量
	 */
	private static function initPath() {
		define('DS', DIRECTORY_SEPARATOR);//简化目录分隔符名称长度！
		define('ROOT_DIR', dirname(dirname(__FILE__)) . DS);//根
		define('APP_DIR', ROOT_DIR . 'app' . DS);//应用程序
		define('CONT_DIR', APP_DIR . 'controller' . DS);//控制器
		define('CURR_CONT_DIR', CONT_DIR . PLATFORM . DS);//当前控制器
		define('VIEW_DIR', APP_DIR . 'view' . DS);//视图
		define('CURR_VIEW_DIR', VIEW_DIR . PLATFORM . DS);//当前视图
		define('MODEL_DIR', APP_DIR . 'model' . DS);//模型路径
		define('FRAME_DIR', ROOT_DIR . 'framework' . DS);//框架路径
		define('CONFIG_DIR', APP_DIR . 'config' . DS); //配置文件目录
		define('TOOL_DIR', FRAME_DIR . 'tool' . DS);//工具类目录
		define('UPLOAD_DIR', APP_DIR . 'upload' . DS);//上传文件目录
	}

	/**
	 * 自定自动加载方法
	 *
	 * @param $class_name string 需要的类名
	 */
	public static function itcast_autoload($class_name) {
		//特例
		$map = array(
			'MySQLDB' => FRAME_DIR . 'MySQLDB.class.php',
			'Model' => FRAME_DIR . 'Model.class.php',
			'Controller' => FRAME_DIR . 'Controller.class.php',
		);//该数组，将所有的有限的特例，类与类名的映射，完成一个列表
		//判断当前所需要加载的类是否是特例类
		if( isset($map[$class_name]) ) {
			//存在该元素，是特例
			//直接载入
			require $map[$class_name];
		}
		//规律
		elseif (substr($class_name, -10) == 'Controller') {
			//控制器
			require CURR_CONT_DIR . $class_name . '.class.php';
		} elseif (substr($class_name, -5) == 'Model') {
			//模型
			require MODEL_DIR . $class_name . '.class.php';
		}elseif(substr($class_name, -4) == 'Tool') {
			require TOOL_DIR . $class_name . '.class.php';
		}
	}
	
	/**
	 * 请求分发
	 */
	private static function dispatch() {
		//实例化控制器类
		$controller_name = CONTROLLER . 'Controller';
		$controller = new $controller_name;
		//调用相应的方法
		$action_name = ACTION . 'Action';
		$controller->$action_name();
	}
	
	/**
	 * 载入路径常量
	 */
	private static function loadConfig() {
		$GLOBALS['config'] = require CONFIG_DIR . 'app.config.php';
		//var_dump($GLOBALS['config']);
	}
}