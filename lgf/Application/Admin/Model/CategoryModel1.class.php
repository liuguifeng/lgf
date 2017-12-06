<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model 
{
	protected $insertFields = array('cat_name','parent_id','listorder');
	protected $updateFields = array('id','cat_name','parent_id','listorder');
	protected $_validate = array(
		array('cat_name', 'require', '分类名称不能为空！', 1, 'regex', 3),
		array('cat_name', '1,30', '分类名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('parent_id', 'number', '上级分类的ID，0：代表顶级必须是一个整数！', 2, 'regex', 3),
                array('listorder', 'number', '上级分类的ID，0：代表顶级必须是一个整数！', 2, 'regex', 3),
	);
	/************************************* 递归相关方法 *************************************/
	public function getTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
                                
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
                
	}
	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
	/************************************ 其他方法 ********************************************/
	public function _before_delete($option)
	{
		// 先找出所有的子分类
		$children = $this->getChildren($option['where']['id']);
		// 如果有子分类都删除掉
		if($children)
		{
			$children = implode(',', $children);
			$this->execute("DELETE FROM php34_category WHERE id IN($children)");
		}
	}
        public function getNavCatData1(){
            $data = array();
            //先取出所有分类
            $allCat=$this->select();
            //再取顶级
            foreach($allCat as $k =>$v){
                if($v['parent_id']==0){
                    //循环这个顶级的2级
                     foreach($allCat as $k1 =>$v1){
                        if($v1['parent_id']==$v['id']){
                            foreach ($allCat as $k2 => $v2){
                                if($v2['parent_id']==$v1['id']){
                                    $v1['children'][]=$v2;
                                }
                            }
                         $v['children'][]=$v1;
                        }
                    }
                   $data[]=$v;
                }
            }
            return $data;
//            //先从缓存取数据
//            $data=S('catData');
//            if($adta)
//                return $data;
//            else{
//                $cat=$this->where('parent_id=0')->select();
//                //var_dump($cat);
//                //循环每个顶级分类取2级
//                foreach($cat as $k => $v){
//                    //var_dump($v);
//                    $cat[$k]['children']=$this->where('parent_id='.$v['id'])->select();
//                    //z再循环取3级
//                    foreach($cat[$k]['children'] as $k1 => $v1){
//                        $cat[$k]['children'][$k1]['children']=$this->where('parent_id='.$v1['id'])->select();
//                    }
//
//                } 
//                S('catData',$cat);
//                //var_dump($a);
//                return $cat;
//
//            }
        }
        //无限级分类
        public function ChildList($arr,$pid=0){
            $res=array();
            foreach($arr as $v){
                if($v['parent_id']==$pid){
                    $v['child']=$this->ChildList($arr,$v['id']);
                    $res[]=$v;
                }
            }
            
            return $res;
        }
        //获取所有分类
        public function getNavCatData(){
            $cats=$this->select();
            $data=$this->ChildList($cats);
            return $data;
        }
        
        public function _before_insert(&$data, $options) {
           $attrId=I('post.attr_id');
           foreach ($attrId as $k => $v){
               if(empty($v)){
                   unset($attrId[$k]);
               }
           }
           if($attrId){
               sort($attrId);
               $data['search_attr_id']= implode(',', $attrId);
           }
        }
         public function _before_update(&$data, $options) {
           $attrId=I('post.attr_id');
           foreach ($attrId as $k => $v){
               if(empty($v)){
                   unset($attrId[$k]);
               }
           }
           if($attrId){
               sort($attrId);
               $data['search_attr_id']= (string)implode(',', $attrId);
           }
        }
        public function getParents($catId){
            $res=array();
            while($catId){
                $cat=$this->where("id=$catId")->find();
                //var_dump($cat);die;
                $res[]=array(
                    'id'=>$cat['id'],
                    'cat_name'=>$cat['cat_name']
                    );
                   //var_dump($res);
                //改变条件
                $catId=$cat['parent_id'];
            }
            return array_reverse($res);
        }
        public function getCategory(){
            $cat=$this->where("parent_id=0")->select();
            return $cat;
        }
}