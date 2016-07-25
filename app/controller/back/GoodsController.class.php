<?php

class GoodsController extends BackPlatformController {

	/**
	 * 增加商品表单动作
	 */
	public function addAction() {

		//利用分类模型，得到所有的分类
		$model_category =  new CategoryModel;
		$cat_list = $model_category->getTreeList();

		//载入模板
		require CURR_VIEW_DIR . 'goods_add.html';
	}

	public function insertAction() {
		//收集数据
		$data['goods_name'] = $_POST['goods_name'];
		$data['goods_sn'] = $_POST['goods_sn'];
		$data['cat_id'] = $_POST['cat_id'];
		$data['shop_price'] = $_POST['shop_price'];
		$data['market_price'] = $_POST['market_price'];
		$data['goods_desc'] = $_POST['goods_desc'];
		$data['goods_number'] = $_POST['goods_number'];
		//处理商品状态，精品，新品，热销，位运算
		$is_best = isset($_POST['is_best'])? $_POST['is_best'] : 0;
		$is_new = isset($_POST['is_new'])? $_POST['is_new'] : 0;
		$is_hot = isset($_POST['is_hot'])? $_POST['is_hot'] : 0;
		$data['goods_status'] = 0 | $is_best | $is_new | $is_hot;
		$data['is_on_sale'] = isset($_POST['is_on_sale']) ? $_POST['is_on_sale'] : '0';
		$data['add_time'] = time();
		//上传文件
		$tool_upload = new UploadTool(UPLOAD_DIR, 100000);
		$tool_upload->allow_types = array('image/jpeg', 'image/png', 'image/gif');

		if($result = $tool_upload->upload($_FILES['image_ori'], 'goods_')) {
			//上传成功
			$data['image_ori'] = $result;//记录文件名
			//生成缩略图       
			$tool_image=new ImageTool;
			$tool_image->makeThumb(UPLOAD_DIR.$result,100,100);
		}

		//调用模型增加
		$model_goods = new GoodsModel;
		if ($model_goods->insertGoods($data)) {
			//跳转提示
			die('添加成功');
			$this->jump('index.php?p=back&c=Goods&a=list');
		} else {
			$this->jump('index.php?p=back&c=Goods&a=add', '失败，原因');
		}

	}

	/**
	 * 商品列表
	 */
	public function listAction() {

		//调用模型
		$model_goods = new GoodsModel;
//		$list = $model_goods->getList();//简单商品列表方法
		$page = isset($_GET['page']) ? $_GET['page'] : '1';//获得当前页面，默认为第一页
		$pagesize = isset($_GET['pagesize'])?$_GET['pagesize']:$GLOBALS['config']['back']['goods_list_pagesize'];//获得每页的记录数

		$result = $model_goods->getPageList($page, $pagesize);//获得分页数据,与记录数
		$list = $result['list'];
		$total = $result['total'];
		$total_page = ceil($total/$pagesize);
		
		// $tool_page = new PageTool;
		// $page_html = $tool_page->show($page, $pagesize, $total, 'index.php?p=back&c=Goods&a=list', array('pagesize'=>$pagesize));
		//展示模板
		require CURR_VIEW_DIR . 'goods_list.html';
	}
}