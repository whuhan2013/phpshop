<?php


class CaptchaTool {

	/**
	 * 生成验证码的方法
	 */
	public function generate() {
		//随机得到背景图片
		$rand_bg_file = TOOL_DIR . 'captcha/captcha_bg' . mt_rand(1, 5) . '.jpg';
		
		//创建画布
		$img = imagecreatefromjpeg($rand_bg_file);

		//绘制边框
		$white = imagecolorallocate($img, 0xff, 0xff, 0xff);
		//不填充矩形
		imagerectangle($img, 0, 0, 144, 19, $white);

		//生成码值，随机的4个只包含大写字母，和数字的字符串！
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';//32 个字符
		//随机取4个
		$captcha_str = '';
		for($i=1,$strlen=strlen($chars); $i<=4; ++$i) {
			$rand_key = mt_rand(0, $strlen-1);
			$captcha_str .= $chars[$rand_key];
		}
		//保存到session中
		@session_start();
		$_SESSION['captcha_code'] = $captcha_str;

		//先确定颜色，白黑随机！
		$black = imagecolorallocate($img, 0, 0, 0);
		if(mt_rand(0, 1) == 1 ) {
			$str_color = $white;
		} else {
			$str_color = $black;
		}

		//写字符串
		imagestring($img, 5, 60, 3, $captcha_str, $str_color);

		//保存
		//告知浏览器，发送的是jpeg格式的图片
		header('Content-Type: image/jpeg; charset=utf-8');
		imagejpeg($img);//输出到浏览器端！

		//销毁资源
		imagedestroy($img);
	}

	/**
	 * 验证验证码
	 *
	 * @param $code string 用户输入的验证码
	 *
	 * @return bool
	 */
	public function checkCaptcha($code) {
		@session_start();
		return $code == $_SESSION['captcha_code'];
	}

}