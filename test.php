<?php


//一：
mysql_connect('127.0.0.1:3306', 'root', 'root');
$sql = "select * from itcast_shop.it_category where 1 order by sort_order";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)) {
	$list[] = $row;
}
echo '<pre>';
//var_dump($list);


//二，递归查找
/**
 * 
 * @param $arr array 当前所有的可能分类，在该数组内查找子分类
 * @param $p_id int 当前查找的父ID
 *
 * @param $deep int 当前递归调用的深度
 *
 * @return array 排序好的数组列表！
 */
function getTree($arr, $p_id, $deep=0) {
	//利用一个静态局部变量将所有依次找到的元素，都保存
	static $tree = array();
	//遍历所有的可能分类，找到parent_id==$p_id
	foreach($arr as $row) {
		//判断是否为子分类
		if($row['parent_id'] == $p_id) {
			//是子分类
			//记录当前所找到
			$row['deep'] = $deep;
			$tree[] = $row;
			//利用当前查找的分类，找其子分类
			//递归调用
			getTree($arr, $row['cat_id'], $deep+1);
		}
	}

	return $tree;
}

$tree = getTree($list, 0);

//var_dump($tree);

foreach($tree as $row) {
	echo $row['deep'];
	echo str_repeat('&nbsp;&nbsp;', $row['deep']);
	echo $row['cat_name'];
	echo '<br>';
}