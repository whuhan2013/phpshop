<?php
/**
* 
*/
class IndexController extends BackPlatformController
{
	
	public function indexAction()
	{
		// session_start();
		// if(isset($_SESSION['is_login']) && $_SESSION['is_login'] =='yes') {
		// 	//继续执行
		// } else {
		// 	//die('没有登陆');
		// 	$this->jump('index.php?p=back&c=Admin&a=login','非法用户',2);
		// }

		require CURR_VIEW_DIR.'index.html';
	}

	public function topAction()
	{
		//echo "top";
		require CURR_VIEW_DIR.'top.html';
	}

	public function menuAction()
	{
		//echo "menu";
		require CURR_VIEW_DIR.'menu.html';
	}

	public function dragAction()
	{
		// echo "drag";
		require CURR_VIEW_DIR.'drag.html';
	}

	public function mainAction()
	{
		// echo "main";
		require CURR_VIEW_DIR.'main.html';
	}
}
?>