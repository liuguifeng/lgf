<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class AdvertController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{   
            // 设置这个脚本可以执行的时间，单位：秒，0：代表一直执行到结束，默认30秒
    		set_time_limit(0);//tp自带
//                echo "<pre>";
//                var_dump($_FILES);
//                echo "</pre>";
    		$model = D('Admin/Advert');
                //$data=I('post.');
                //print_r($data);exit;
                //var_dump($FILES['pic']);exit;
    		if($model->create(I('post.'), 1))
    		{
                        
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
                //echo M()->getLastSql();exit;
    		$this->error($model->getError());
    	}       
		$this->setPageBtn('添加广告', '广告列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');

    	if(IS_POST)
    	{
    		$model = D('Admin/Advert');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
        
    	// 取出所有的视频分类
    	$catModel = D('Category');
    	$catData = $catModel->getTree();
        //取出所有所属视频综艺
        $typeModel=D('Type');
        $typedata=$typeModel->order("listorder ASC")->select();
        $this->assign('typedata',$typedata);
    	$this->assign('catData', $catData);
    	// 取出要修改的视频的基本信息
    	$model = M('Advert');
    	$data = $model->find($id);
        $extcat=$data['ext_category_id'];
        if($extcat){
            $arrcat=explode(',', $extcat);
            $data['ext_category_id']=$arrcat;
        }
        
    	$this->assign('data', $data);
        $this->setPageBtn('修改广告', '广告列表', U('lst?p='.I('get.p')));
        $this->display();
    }

    public function delete()
    {
    	$model = D('Admin/Advert');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Advert');
    	$data = $model->search();
        //var_dump($data);
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

        $this->setPageBtn('广告列表', '添加广告', U('add'));
    	$this->display();
    }
    public function status(){
        $id=I('get.id');
        $where['id']= array('eq',$id);
        //var_dump($where);exit;
        $status=I('get.status');
        if($status==0){
            $data['status'] = $status;
            //var_dump($data);exit;
            $model = D('Admin/Advert');
            if($model->where($where)->setField($data) !== FALSE)
            {
                $this->success('取消发布成功！', U('lst', array('p' => I('get.p', 1))));
                exit;
            }

            $this->error($model->getError());
        }
        elseif($status==1){
            $data['status'] = $status;
            //var_dump($data);exit;
            $model = D('Admin/Advert');
            if($model->where($where)->setField($data) !== FALSE)
            {
                $this->success('发布成功！', U('lst', array('p' => I('get.p', 1))));
                exit;
            }

            $this->error($model->getError());
        }else{
            return false;
        }

    	
    }

    //AJAX获取属性根据类型id
    public function ajaxGetAttr(){
        $typeId = I('get.type_id');
    	$attrModel = M('Attribute');
    	// 根据类型ID取属性
    	$attrData = $attrModel->where(array('type_id'=>array('eq', $typeId)))->order('id ASC')->select();
        echo json_encode($attrData);
    }
    public function ajaxDelImg(){
        $picId = I('get.pic_id');
        $gpModel = M('GoodsPics');
        $pic = $gpModel->field('pic,sm_pic')->find($picId);
        //把图片从硬盘上删掉
        deleteImage($pic);
        //再从数据库删掉
        $gpModel->delete($picId);
    }

}