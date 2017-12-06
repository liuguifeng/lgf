<?php

namespace Admin\Model;

use Think\Model;

class MovieModel extends Model {

   
    protected $insertFields = array('movie_name', 'category_id', 'ext_category_id','description','dianzan','views','status','is_hot', 'is_new', 'is_best', 'seo_keyword', 'seo_description','listorder','status','is_delete','actor');
    protected $updateFields = array('id', 'movie_name', 'category_id', 'ext_category_id','description','dianzan','views','status','is_hot', 'is_new', 'is_best', 'seo_keyword', 'seo_description','create_time','update_time','listorder','status','is_delete','actor');
    protected $_validate = array(
        array('movie_name', 'require', '视频名称不能为空！', 1, 'regex', 3),
        array('movie_name', '1,50', '视频名称的值最长不能超过 50 个字符！', 1, 'length', 3),
        array('seo_keyword', 'require', '送选机构不能为空！', 1, 'regex', 3),
        array('seo_keyword', '1,30', '送选机构的值最长不能超过 30 个字符！', 1, 'length', 3),
        array('actor', 'require', '演员不能为空！', 1, 'regex', 3),
        array('actor', '1,40', '演员名称最长不能超过 40 个字符！', 1, 'length', 3),
        array('seo_description', '1,200', '主题的值最长不能超过 200 个字符！', 2, 'length', 3),
        array('category_id', 'require', '主分类的id不能为空！', 1, 'regex', 3),
        array('category_id', 'number', '主分类的id必须是一个整数！', 1, 'regex', 3),
        array('is_hot', 'number', '是否最热必须是一个整数！', 2, 'regex', 3),
        array('is_new', 'number', '是否最新必须是一个整数！', 2, 'regex', 3),
        array('is_best', 'number', '是否推荐必须是一个整数！', 2, 'regex', 3),
        array('listorder', 'number', '排序数字必须是一个整数！', 2, 'regex', 3),
        array('status', 'number', '是否发布数字必须是一个整数！', 2, 'regex', 3),
        array('dianzan', 'number', '点赞数必须是一个整数！', 2, 'regex', 3),
        array('views', 'number', '浏览量必须是一个整数！', 2, 'regex', 3),
//        array('is_delete', 'number', '是否删除必须是一个整数！', 2, 'regex', 3),
      
    );

    //后台搜索
    public function search($pageSize = 10, $isDelete = 0) {
        /*         * ************************************** 搜索 *************************************** */
        //只取出未放到回收站的数据
        $where['is_delete'] = array('eq', $isDelete);
        if ($movie_name = I('get.movie_name'))
            $where['movie_name'] = array('like', "%$movie_name%");
        if ($category_id = I('get.category_id'))
            $where['category_id'] = array('eq', $cat_id);
        if ($brand_id = I('get.brand_id'))
            $where['brand_id'] = array('eq', $brand_id);
        $is_hot = I('get.is_hot');
        if ($is_hot != '' && $is_hot != '-1')
            $where['is_hot'] = array('eq', $is_hot);
        $is_new = I('get.is_new');
        if ($is_new != '' && $is_new != '-1')
            $where['is_new'] = array('eq', $is_new);
        $is_best = I('get.is_best');
        if ($is_best != '' && $is_best != '-1')
            $where['is_best'] = array('eq', $is_best);
        $addtimefrom = I('get.addtimefrom');
        $addtimeto = I('get.addtimeto');
        if ($addtimefrom && $addtimeto)
            $where['addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
        elseif ($addtimefrom)
            $where['addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
        elseif ($addtimeto)
            $where['addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
        /*         * *********************************** 翻页 *************************************** */
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /*         * ************************************ 取数据 ***************************************** */
        $data['data'] = $this->where($where)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }
    //APP搜索
    public function appsearch($pageSize = 10, $isDelete = 0){
        $page=I('post.page');
        if(!$page){
            $page=1;
        }
        $search_name = I('post.search_name');
        if(!$search_name){
            $this->error=array(
                4018=>"请输入搜索关键词"
            );
            return false;
        }
        $startCount=($page-1)*$pageSize;
        $where['movie_name']  = array('like', "%$search_name%");
        $where['actor']  = array('like',"%$search_name%");
		$where['seo_description']  = array('like',"%$search_name%");
        $where['seo_keyword']  = array('like',"%$search_name%");
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $map['is_delete']  = array('eq',0);
        $count = $this->where($map)->count();
        if(!$count){
            $this->error=array(
                4017=>"搜索结果不存在"
            );
            return false;
        }
        $totalPage = ceil($count/$pageSize);
        if($page > $totalPage){
            $this->error=array(
                4016=>"已经没有更多数据"
            );
            return false;
        }
        $data=$this->where($map)->order('listorder asc')->limit($startCount, $pageSize)->select();
        return $data;       
    }

    // 添加前
    protected function _before_insert(&$data, $option) {
        //获取当前时间
        $data['create_time'] = time();
        $data['update_time'] = time();
		$data['mic'] = stripslashes($_POST['mic']);
        /* 处理扩展分类 */
        $eci = I('post.ext_category_id');
        //var_dump($eci);
        if($eci){
            $streci=implode(',', $eci);
            //var_dump($streci);exit;
            $data['ext_category_id']=$streci;
        }
        //var_dump($ret);exit;
//        if (isset($_FILES['mic']) && $_FILES['mic']['error'] == 0) {
 //           $ret = uploadMovie('mic', 'Admin');
            //var_dump($ret['movies']);
//            if ($ret['ok'] == 1) {
 //               $data['mic'] = $ret['movies'];
 //           } else {
  //              $this->error = $ret['error'];
 //               return FALSE;
//            }
 //       }
        if (isset($_FILES['pic']) && $_FILES['pic']['error'] == 0) {
            
            $ret = uploadOne('pic', 'Admin');
            if ($ret['ok'] == 1) {
                $data['pic'] = $ret['images'][0];
//                $data['sm_logo'] = $ret['images'][1];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
        }

    }

    //视频基本信息插入之后
    protected function _after_insert($data, $option) {

        
    }

    // 修改前
    protected function _before_update(&$data, $option) {
        $data['update_time'] = time();
        if (isset($_FILES['mic']) && $_FILES['mic']['error'] == 0) {
            $ret = uploadMovie('mic', 'Admin');
            //var_dump($ret['movies']);
            if ($ret['ok'] == 1) {
                $data['mic'] = $ret['movies'];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
            deleteMovie(array(
                I('post.old_mic'),
            ));
        }
        if (isset($_FILES['pic']) && $_FILES['pic']['error'] == 0) {
            $ret = uploadOne('pic', 'Admin');
            if ($ret['ok'] == 1) {
                $data['pic'] = $ret['images'][0];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
//            $pic=I('post.old_pic');
//            var_dump($pic);exit;
            deleteImage(array(
                I('post.old_pic'),
            ));
        }
    }

    //商品基本信息处理完之后
    protected function _after_update($data, $option) {
        /* 处理扩展分类 */
        $eci = I('post.ext_cat_id');
        $gcModel = M('GoodsCat');
        //先清除原来的扩展分类
        $gcModel->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
        if ($eci) {

            //如果有再添加一遍
            foreach ($eci as $v) {
                if (empty($v))//如果为空跳过处理下一个
                    continue;
                $gcModel->add(array(
                    'goods_id' => $option['where']['id'],
                    'cat_id' => $v,
                ));
            }
        }
    }

    // 删除前
    protected function _before_delete($option) {
        if (is_array($option['where']['id'])) {
            $this->error = '不支持批量删除';
            return FALSE;
        }
        $images = $this->field('pic')->find($option['where']['id']);
        deleteImage($images);
        $movies=$this->field('mic')->find($option['where']['id']);
        deleteMovie($movies);
    }

    /*     * ********************************** 其他方法 ******************************************* */
    public function getHot() {
     
        return $this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_hot' => array('eq', 1),
                ))->order('listorder asc')->select();
    }

    public function getNew() {

        return $this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_new' => array('eq', 1),
                ))->order('listorder asc')->select();
    }
    //方法写的有点重，后续在优化
    public function getBest() {
  
        return $this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_best' => array('eq', 1),
                ))->order('listorder asc')->select();
    }
    public function getHots() {
        
        $banner=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_hot' => array('eq', 1),
                ))->order('listorder asc')->limit(5)->select();
        $ishot=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_hot' => array('eq', 1),
                ))->order('listorder asc')->select();
        $data['banner']=$banner;
        $data['ishot']=$ishot;
        return $data;
    }

    public function getNews() {
        
         $banner=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_new' => array('eq', 1),
                ))->order('listorder asc')->limit(5)->select();
        $isnew=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_new' => array('eq', 1),
                ))->order('listorder asc')->select();
        $data['banner']=$banner;
        $data['isnew']=$isnew;
        return $data;
    }

    public function getBests() {
         $banner=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_best' => array('eq', 1),
                ))->order('listorder asc')->limit(5)->select();
        $isbest=$this->where(array(
                    'status' => array('eq', 1),
                    'is_delete' => array('eq', 0),
                    'is_best' => array('eq', 1),
                ))->order('listorder desc')->select();
        $data['banner']=$banner;
        $data['isbest']=$isbest;
        return $data;
    }
    public function getCats($catname){
        if(!$catname){
            return false;
        }
        $catname= trim($catname);
        $where['cat_name']=array("eq",$catname);
        $catModel=D("Admin/Category");
        $catdata=$catModel->field("id")->where($where)->select();
        var_dump($catdata);die;
        //去取出所有$catname下的分类和其子分类
        foreach($catdata as $kc =>$vc){
            $res[]=$catModel->getChildren($vc['id']);
            $res[$kc][]=$vc['id'];
        }
        foreach($res as $kd =>$vd){
            foreach ($vd as $ke =>$ve){
                $ree[]=$ve;
            }
        }
        //var_dump($ree);
//        if($res){
//            $res=implode(',', $res);
//        }
        $movModel=M("Movie");
		$where2['status']=array("eq",1);
		$where2['is_delete']=array("eq",0);
        $extdata=$movModel->where($where2)->select();
        //$extdata1=array();
        foreach ($extdata as $k =>$v){
            if($v['ext_category_id']){
                $v['ext_category_id']= explode(',',$v['ext_category_id']);
            }
            //循环刚刚的数组，查找扩展分类中的数据
            foreach($ree as $kf =>$cf){
                if(in_array($cf,$v['ext_category_id'])){
                    $v['ext_category_id']= implode(',', $v['ext_category_id']);
                    $extdata1[]=$v;
                }
            }
//            if(in_array($catdata['id'],$v['ext_category_id'])){
//                $v['ext_category_id']= implode(',', $v['ext_category_id']);
//                $extdata1[]=$v;
//            }
            $extdata=$extdata1;
        }
        //var_dump($extdata1);
        $where1['category_id']=array('in',$ree);
		$where1['status']=array("eq",1);
		$where1['is_delete']=array("eq",0);
        $movdata=$movModel->where($where1)->select();
        //var_dump($movdata);die;
        if($extdata&&$movdata){
            $alldata1= array_merge($extdata,$movdata);
            //var_dump($alldata1);die;
            usort($alldata1, function($a, $b){
                if ($a['listorder'] == $b['listorder'])
                return 0;
                return ($a['listorder'] < $b['listorder']) ? -1 : 1;
            });

            foreach ($alldata1 as $k=> $v){
                $alldata1[$k]=serialize($v);   
            }
            $alldata1= array_unique($alldata1);
            //var_dump($alldata);exit;
            foreach ($alldata1 as $k=> $v){
                $alldata1[$k]=unserialize($v);
            }
            $alldata=$alldata1;
            //var_dump($alldata);die;
        }
        elseif(!$extdata){
            $alldata=$movdata;
        } 
        elseif(!$movdata) {
            $alldata=$extdata;
        }else{
            $alldata=array();
        }
        //$arr=array();
        if(!empty($alldata)){
                foreach ($alldata as $k => $v){
                    if($v['is_best']==1){
                            $arr['is_best'][]=$v;
                            $arr['is_banner'][]=$v;
                    }
                    if($v['is_new']==1){
                            $arr['is_new'][]=$v;
                    }
                    if($v['is_hot']==1){
                            $arr['is_hot'][]=$v;
                    }
                }
        }else{
            $arr=array();
        }
        $advertModel=D("Admin/Advert");
        $advdata=$advertModel->getAdvert();
        $arr['advert'][]= $advdata;
        //var_dump($arr);exit;
        return $arr;
    }
    //获取一级分类下所有商品
    public function getFirst($catname){
        if(!$catname){
            return false;
        }
        $catname= trim($catname);
        $where['cat_name']=array("eq",$catname);
        $catModel=D("Admin/Category");
        $catdata=$catModel->field("id")->where($where)->find();
        //var_dump($catdata);die;
        //取出所有$catname下的分类和其子分类
        $res=$catModel->getChildren($catdata['id']);
        $res[]=$catdata['id'];
        //var_dump($res);die;
        $where1['category_id']=array('in',$res);
		$where1['status']=array("eq",1);
		$where1['is_delete']=array("eq",0);
        $movModel=D("Admin/Movie");
        $movdata=$movModel->where($where1)->order("listorder ASC ")->select();
        //var_dump($movdata);die;
        foreach($movdata as $k =>$v){
            if($v['is_hot']==1){
                $arr["is_hot"][]=$v;
            }
            if($v['is_best']==1){
                $arr["is_best"][]=$v;
                $arr["is_banner"][]=$v;
            }
            if($v['is_new']==1){
                $arr["is_new"][]=$v;
            }
        }
        $advertModel=D("Admin/Advert");
        $advdata=$advertModel->getAdvert();
        $arr['advert'][]= $advdata;
        return $arr;
    }

}
