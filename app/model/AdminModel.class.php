<?php

//require_once './framework/Model.class.php';
/**
 * admin表模型
 */
class AdminModel extends Model {

	protected $table_name='admin';

	/**
	 * 利用登陆时的名称和密码进行查询
	 *
	 * @param $admin_name 用户名
	 * @param $admin_pass 密码
	 *
	 * @return bool true 合法，false非法
	 */
	public function checkByLogin($admin_name, $admin_pass) {
		//形成SQL
		$sql = "select * from {$this->table()} where admin_name='$admin_name' and admin_pass=md5('$admin_pass')";
		//执行
		$row = $this->db->fetchRow($sql);
		return (bool) $row;//将数据转成布尔值即可
	}

	/**
	 * 利用cookie查询
	 */
	public function checkByCookie() {
		//判断是否有cookie
		if(!isset($_COOKIE['admin_id'])  || !isset($_COOKIE['admin_pass'])) {
			return false;
		}

		//是否合法
		$sql = "select * from {$this->table()} where admin_id='{$_COOKIE['admin_id']}' and md5(concat('itcast',admin_pass,'php')) = '{$_COOKIE['admin_pass']}'";
		return $this->db->fetchRow($sql);
	}

	
}