<?php
/**
 * 模型的基础类
 */
class Model {
	protected $db;//保存MySQLDB类的对象
	protected $prefix;
	protected $fields;//所有的字段
	/**
	 * 构造方法
	 */
	public function __construct() {
		//var_dump($GLOBALS['config']);
		$this->prefix=$GLOBALS['config']['database']['prefix'];
		//连接数据库
		$this->initLink();
		//获得当前表的字段信息
		$this->getFields();
	}

	/**
	 * 
	 */
	public function getFields() {
		//获得描述desc
		$sql = "desc {$this->table()}";
		$fields_rows = $this->db->fetchAll($sql);
		//获得其中的字段部分
		foreach ($fields_rows as $row) {
			$this->fields[] = $row['Field'];
			if($row['Key'] == 'PRI') {
				//primary key
				$this->fields['pk'] = $row['Field'];
			}
		}
	}

		/**
	 * 自动删除
	 *
	 * @param $pk_value string 当前需要处理的主键值
	 *
	 * @return bool
	 */
	public function autoDelete($pk_value) {
		//拼凑delete 的 SQL语句
		//delete from 当前表名 where 主键字段=’主键字段值’
		$sql = "delete from {$this->table()} where `{$this->fields['pk']}`='{$pk_value}'";
		return $this->db->query($sql);
	}

	/**
	 * 自动查询一行
	 *
	 * @param $pk_value string 当前需要处理的主键值
	 *
	 * @return bool
	 */
	public function autoSelectRow($pk_value) {
		//拼凑delete 的 SQL语句
		//select * from 当前表名 where 主键字段=’主键字段值’
		$sql = "select * from {$this->table()} where `{$this->fields['pk']}`='{$pk_value}'";
		return $this->db->fetchRow($sql);
	}

		/**
	 * 自动插入
	 *
	 * @param $data 字段列表
	 */
	public function autoInsert($data) {
//		insert into 表名 (字段1,字段2,字段N) values ('值1','值2','值N')
//		$data = array(
//			'字段1'=>'值1',
//			'字段2'=>'值2',
//			'字段3'=>'值3',
//		);
		//拼凑insert表名
		$sql = "insert into {$this->table()} ";
//		echo $sql, '<br>';
		//拼凑字段列表部分
		$fields = array_keys($data);//取得所有键
		$fields = array_map(function($v){return '`'.$v.'`';}, $fields);//使用反引号包裹字段名
		$fields_str = implode(', ', $fields);//使用逗号连接起来即可
		$sql .= '(' . $fields_str . ')';
//		echo $sql, '<br>';
		//拼凑值列表部分
		$values = array_map(function($v) {return "'".$v."'";}, $data);//获得所有的值，将值增加引号包裹
		$values_str = implode(', ', $values);//再使用逗号连接
		$sql .= ' values (' . $values_str . ')';
//		echo $sql, '<br>';
//		die;
		//执行该insert语句
		return $this->db->query($sql);
	}

	


	/**
	 * 初始化数据库的连接
	 */
	protected function initLink() {
		//require './framework/MySQLDB.class.php';
		// $options = array(
		// 	'host'=>'127.0.0.1',
		// 	'port'=>'3306',
		// 	'user'=>'root',
		// 	'pass'=>'root',
		// 	'charset'=>'utf8',
		// 	'dbname'=>'itcast_shop'
		// );
		$this->db = MySQLDB::getInstance($GLOBALS['config']['database']);
	}

	/**
	 * 拼凑真实表名的方法
	 */
	protected function table() {
		return '`' . $this->prefix . $this->table_name . '`';
	}
}