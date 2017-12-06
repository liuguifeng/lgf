<?php
namespace Admin\Model;
use Think\Model;
class AdvertModel extends Model 
{
	protected $insertFields = array('url','advert','status','addtime');
	protected $updateFields = array('id','url','advert','status','addtime');
	protected $_validate = array(
		//array('advert', 'require', '图片不能为空！', 1, 'regex', 1),
//		array('type_name', '1,30', '综艺类型名称的值最长不能超过 30 个字符！', 1, 'length', 3),
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
            $data['data'] = $this->alias('a')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
            return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
            $data['addtime']=time();
            if (isset($_FILES['advert']) && $_FILES['advert']['error'] == 0) {
            $ret = uploadOne('advert', 'Admin');
            if ($ret['ok'] == 1) {
                $data['advert'] = $ret['images'][0];
//                $data['sm_logo'] = $ret['images'][1];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
        }
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
//            $data['update_time']=time();
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
        public function getAdvert(){
           
            $advertModel=D("Admin/Advert");
            $advdata=$advertModel->order("id DESC")->limit(1)->find();
            return $advdata;
        }
        public function getAdverts(){
           
            $advertModel=D("Admin/Advert");
            $advdata=$advertModel->order("id DESC")->limit(1)->select();
            return $advdata;
        }
}