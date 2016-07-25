<?php
/**
* 
*/
class CategoryController extends BackPlatformController
{
	
	public function listAction()
	{
		$model_category=new CategoryModel;
		$list=$model_category->getTreeList();

		require CURR_VIEW_DIR.'category_list.html';
	}

	/**
	 * 删除分类的动作
	 */
	public function deleteAction() {
		//调用模型，利用当前的分类ID，删除
		$model_category = new CategoryModel;
		
		if ($model_category->delById($_GET['id'])) {
			//删除处理成功
			$this->jump('index.php?p=back&c=Category&a=list');
		} else {
			//删除失败
			$this->jump('index.php?p=back&c=Category&a=list', '失败：' . $model_category->error_info);
		}

	}

	/**
	 * 添加表单
	 *
	 */
	public function addAction() {
		//利用模型获得所有分类数据
		$model_category = new CategoryModel;
		$cat_list = $model_category->getTreeList();

		//展示视图
		require CURR_VIEW_DIR . 'category_add.html';
	}

	/**
	 * 处理添加数据
	 */
	public function insertAction() {
		//得到表单数据
		$data['cat_name']  = $_POST['cat_name'];
		$data['parent_id'] = $_POST['parent_id'];
		$data['sort_order'] = $_POST['sort_order'];
		//利用模型插入到it_category表
		$model_category = new CategoryModel;
		if ($model_category->insertCat($data) ) {
		//处理添加结果
			$this->jump('index.php?p=back&c=Category&a=list');
		} else {
			$this->jump('index.php?p=back&c=Category&a=add', "添加失败：$model_category->error_info");
		}
	}

	/**
	 * 编辑表单
	 */
	public function editAction() {
		//模型
		$model_category = new CategoryModel;
		//获得当前编辑的分类信息
		$curr_cat = $model_category->getById($_GET['id']);
		//分类列表
		$cat_list = $model_category->getTreeList();

		//视图
		require CURR_VIEW_DIR . 'category_edit.html';
	}

	/**
	 * 处理编辑数据
	 *
	 */
	public function updateAction() {
		//得到表单数据
		$data['cat_id'] = $_POST['cat_id'];
		$data['cat_name']  = $_POST['cat_name'];
		$data['parent_id'] = $_POST['parent_id'];
		$data['sort_order'] = $_POST['sort_order'];

		//
		$model_category = new CategoryModel;
		if($model_category->updateCat($data) ) {
			$this->jump('index.php?p=back&c=Category&a=list');
		} else {
			$this->jump('index.php?p=back&c=Category&a=edit&id='.$data['cat_id'], '原因:'.$model_category->error_info);
		}
	}

}
?>