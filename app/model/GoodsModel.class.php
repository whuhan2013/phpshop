<?php

class GoodsModel extends Model {
	protected $table_name = 'goods';//逻辑表名

	public function insertGoods($data) {
		//先做业务逻辑判断
		//货号不冲突，名称是否合法等等...

		//再插入
		return $this->autoInsert($data);
	}

	public function getList() {
		$sql = "select * from {$this->table()} where 1 ";
		return $this->db->fetchAll($sql);
	}

	/**
	 * @param $page 当前页数
	 * @param $pagesize 每页显示的记录数
	 *
	 * @return array 当前页面的数据，和符合当前条件的记录数 array('list'=>数据, 'total'=>记录数);
	 */
	public function getPageList($page, $pagesize) {

			
		//先计算偏移量
		$offset = ($page-1) * $pagesize;
		$where_str = " where 1 " ;//统一数据与记录数的查询条件
		$sql = "select * from {$this->table()} $where_str limit $offset, $pagesize";
		$data['list'] = $this->db->fetchAll($sql);//记录下来数据

		//查询记录数
		$sql = "select count(*) from  {$this->table()} $where_str";
		$data['total'] = $this->db->fetchColumn($sql);

		return $data;
	}
}
