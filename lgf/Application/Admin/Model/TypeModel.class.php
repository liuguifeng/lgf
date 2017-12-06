<?php
namespace Admin\Model;
use Think\Model;
class TypeModel extends Model 
{
	protected $insertFields = array('type_name','listorder','status','create_time','update_time');
	protected $updateFields = array('type_id','type_name','listorder','status','create_time','update_time');
	protected $_validate = array(
		array('type_name', 'require', '主题名称不能为空！', 1, 'regex', 3),
		array('type_name', '1,30', '主题名称的值最长不能超过 30 个字符！', 1, 'length', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.type_id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
            $data['create_time']=time();
            $data['update_time']=time();
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
            $data['update_time']=time();
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
	}
	/************************************ 其他方法 ********************************************/

}