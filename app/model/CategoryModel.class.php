<?php

class CategoryModel extends Model {
	protected $table_name='category';

	public function getList() {
		$sql = "select * from {$this->table()} where 1 order by sort_order";
		return $this->db->fetchAll($sql);
	}

	/**
	 * 获得树状列表
	 */
	public function getTreeList($p_id=0) {
		//先获得所有的数据
		$list = $this->getList();

		//再排序，缩进
		return $this->getTree($list, $p_id, 0);
	}

	public function getTree($arr, $p_id=0, $deep=0) {
		static $tree = array();

		foreach($arr as $row) {
			if ($row['parent_id'] == $p_id) {
				$row['deep'] = $deep;
				$tree[] = $row;

				$this->getTree($arr, $row['cat_id'], $deep+1);
			}
		}
		return $tree;
	}

	/**
	 * 利用ID，删除分类
	 *
	 * @param $cat_id int 
	 *
	 * @return bool
	 */
	public function delById($cat_id) {
		//判断是否是叶子分类
		if(!$this->isLeaf($cat_id)) {
			//记录错误信息
			$this->error_info = '分类不是末级分类';
			return false;
		}
		// $sql = "delete from it_category where cat_id='$cat_id'";
		// return $this->db->query($sql);
		return $this->autoDelete($cat_id);
	}
	
	/**
	 * 判断当前分类是否是叶子分类
	 *
	 * @param $cat_id
	 *
	 * @return bool 是返回true
	 */
	public function isLeaf($cat_id) {
		$sql = "select count(*) from it_category where parent_id='$cat_id'";
		$child_count = $this->db->fetchColumn($sql);
		return $child_count == 0;
	}

	/**
	 * 将数据形成insert语句，插入到Category表
	 *
	 * @param $data array 关联数组，当前需要插入的字段信息
	 *
	 * @param bool 成功：true，失败：false（并同时记录错误原因)
	 */
	public function insertCat($data) {
		//判断是否合法和合理的数据
		//判断分类名不能为空字符串
		if($data['cat_name'] == '') {
			$this->error_info = '分类名不能为空';
			return false;
		}

		//不能重名
		$sql = "select count(*) from it_category where parent_id={$data['parent_id']} and cat_name='{$data['cat_name']}'";
		$cat_count = $this->db->fetchColumn($sql);
		if($cat_count > 0 ) {
			$this->error_info = '分类已经存在，请确定';
			return false;
		}
		

		// $sql = "insert into it_category values (null, '{$data['cat_name']}', '{$data['sort_order']}', '{$data['parent_id']}')";
		// return $this->db->query($sql);
		return $this->autoInsert($data);
	}

	/**
	 * 利用id获得当前分类信息
	 *
	 * @param $cat_id
	 *
	 * @return array 当前记录字段数组
	 */
	public function getById($cat_id) {
		// $sql = "select * from it_category where cat_id='$cat_id'";
		// return $this->db->fetchRow($sql);
		return $this->autoSelectRow($cat_id);
	}

	


	/**
	 * 更新数据
	 *
	 * @param $data 
	 */
	public function updateCat($data) {
		//判断$data['parent_id'] 不是自己，或者是后代的ID
		//利用getTreeList所有后代的分类
		$child_list = $this->getTreeList($data['cat_id']);
		$ids = array($data['cat_id']);//所有不行的ID，自己
		foreach($child_list as $row) {//后代的！
			$ids[] = $row['cat_id'];
		}
		//当前更新的父分类id不在ids内即可
		if(in_array($data['parent_id'], $ids)) {
			$this->error_info = '不能为自己或者后代分类的子分类';
			return false;
		}

		$sql = "update it_category set cat_name='{$data['cat_name']}', sort_order='{$data['sort_order']}', parent_id='{$data['parent_id']}' where cat_id='{$data['cat_id']}'";
		return $this->db->query($sql);
	}

}