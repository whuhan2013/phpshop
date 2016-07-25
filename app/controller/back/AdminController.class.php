<?php
/**
* 
*/
class AdminController extends BackPlatformController
{
	
	public function loginAction()
	{
		//require './app/view/admin/login.html';
		require CURR_VIEW_DIR.'login.html';
		
	}
	public function signinAction()
	{
		//验证验证码
		$tool_captcha = new CaptchaTool;
		if(!$tool_captcha->checkCaptcha($_POST['captcha'])) {
			//没有匹配
			$this->jump('index.php?p=back&c=Admin&a=login', '验证码错误', 2);
		}
		
		//调用模型完成数据库操作
		//利用 表单内的 管理员名 和 密码 完成 查询！
		$model_admin = new AdminModel;
		if ($admin_info = $model_admin->checkByLogin($_POST['username'], $_POST['password'])) {
			//验证通过

			//是否记录cookie登陆信息
			if (isset($_POST['remember']) && $_POST['remember'] == '1') {
				//需要保存
				setcookie('admin_id', $admin_info['admin_id'], PHP_INT_MAX);
				setcookie('admin_pass', md5('itcast'.$admin_info['admin_pass'].'php'), PHP_INT_MAX);

			}

			//设置等登陆凭证
			$_SESSION['is_login'] = 'yes';
			//转到后台首页,立即跳转
			$this->jump('index.php?p=back&c=Index&a=index');
		}else
		{
			//echo "非法用户";
			$this->jump('index.php?p=back&c=Admin&a=login','非法用户',2);
		}
	}

	/**
	 * 生成登陆的验证码
	 */
	public function captchaAction() {
		//得到工具类
		$tool_captcha = new CaptchaTool;
		$tool_captcha->generate();
	}





}
?>