<?php

class Controller {

	/**
	 * @param $url string 目标url
	 * @param $message string 提示信息
	 * @param $time int 提示停留的秒数，几秒后跳转
	 */
	protected function jump($url, $message='', $time=3) {
		if ($message == '') {
			//立即
			header('Location: ' . $url);
		} else {
			//提示跳转
			//判断是否有用户定义的跳转模板
			if (file_exists(CURR_VIEW_DIR . 'jump.html')) {
				//使用用户定义的
				require CURR_VIEW_DIR . 'jump.html';
			} else {
				//没有，使用默认的
				echo <<<HTML
<HTML>
 <HEAD>
  <TITLE> 提示：$message </TITLE>
  <META HTTP-EQUIV="Content-Type" CONTENT="text/html ;charset=utf-8">
  <META HTTP-EQUIV="Refresh" CONTENT="$time; url=$url">
 </HEAD>
 <BODY>
	默认的：$message
 </BODY>
</HTML>
HTML;
			}
		}

		die;//强制停止
	}
}