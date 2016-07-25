<?php

class SessionDBTool {
	private $db ;//MySQLDB类的对象

	public function __construct() {
		ini_set('session.save_handler', 'user');
		//设置处理器方法
		session_set_save_handler(
			array($this, 'sess_open'),
			array($this, 'sess_close'),
			array($this, 'sess_read'),
			array($this, 'sess_write'),
			array($this, 'sess_destroy'),
			array($this, 'sess_gc')
		);
		//开启session
		session_start();
	}

	public function sess_open() {
		$this->db = MySQLDB::getInstance($GLOBALS['config']['database']);
	}

	public function sess_close() {
		return true;
	}

	public function sess_read($sess_id) {
		$sql = "select sess_data from `it_session` where sess_id='$sess_id'";
		return (string) $this->db->fetchColumn($sql);
	}

	public function sess_write($sess_id, $sess_data) {
		$expire = time();
		$sql = "insert into it_session values ('$sess_id', '$sess_data', $expire) on duplicate key update sess_data='$sess_data', expire=$expire";
		return $this->db->query($sql);
	}

	public function sess_destroy($sess_id) {
		$sql = "delete from it_session where sess_id='$sess_id'";
		return $this->db->query($sql);
	}

	public function sess_gc($ttl) {
		$last_time = time()-$ttl;
		$sql = "delete from it_session where expire < $last_time";
		return $this->db->query($sql);
	}
}