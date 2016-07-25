<?php

class UploadTool {

	private $upload_dir;//上传目录
	private $max_size;
	private $allow_types;

	private $error_info;

	public function __construct($dir='', $size=2000000, $types=array()) {
		$this->upload_dir = $dir;
		$this->max_size = $size;
		$this->allow_types = empty($types)?array('image/jpeg', 'image/png'):$types;
		date_default_timezone_set('PRC');
	}

	public function __set($p_name, $p_value) {
		if (in_array($p_name, array('upload_dir', 'max_size', 'allow_types'))) {
			$this->$p_name = $p_value;
		}
	}
	public function __get($p_name) {
		if ($p_name  == 'error_info') {
			return $this->$p_name;
		}
	}

	/**
	 * 拿到一个上传文件的信息
	 * 判断其合理和合法性，移动到指定目标
	 *
	 * @param $file array 包含了5个上传文件信息的数组
	 * @param $prefix string 生成文件的前缀
	 *
	 * @return 成功，目标文件名；失败：false
	 */
	function upload($file, $prefix='upload_') {
		//判断是否有错误
		if($file['error'] != 0 ) {
			//文件上传错误
			switch ($file['error']) {
				case 1:
					$this->error_info = '文件太大，超出了php.ini的限制';
				break;
				case 2:
					$this->error_info = '文件太大，超出了表单内的MAX_FILE_SIZE的限制';
				break;
				case 3:
					$this->error_info = '文件没有上传完';
				break;
				case 4:
					$this->error_info = '没有上传文件';
				break;
				case 6:
				case 7:
					$this->error_info = '临时文件夹错误';
				break;

			}
			return false;
		}
		//判断类型
		if(!in_array($file['type'], $this->allow_types)) {
			$this->error_info = '类型不对';
			return false;
		}
		//判断大小
		if($file['size'] > $this->max_size) {
			$this->error_info = '文件过大';
			return false;
		}

		
		//处于安全性的考虑，判断是否是一个真正的上传文件：
		if( !is_uploaded_file($file['tmp_name'])) {
			$this->error_info = '上传文件可疑';
			return false;
		}

		//移动
		//通常都会为目标文件重启名字
		//原则是：不重名，没有特殊字符，有一定的意义！
		$dst_file = uniqid($prefix) . strrchr($file['name'], '.');
		//形成子目录
		$sub_dir = date('YmdH');
		//判断是否存在
		if(! is_dir($this->upload_dir . $sub_dir)) {
			//不存在则创建
			mkdir ($this->upload_dir . $sub_dir);
		}
		if (move_uploaded_file($file['tmp_name'], $this->upload_dir .$sub_dir . DS. $dst_file)) {
			//移动成功，上传完毕！
			return $dst_file;
		} else {
			//失败
			$this->error_info = '移动失败';
			return false;
		}
	}

}