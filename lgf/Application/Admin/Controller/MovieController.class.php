<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class MovieController extends IndexController 
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
    		$model = D('Admin/Movie');
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
     
                // 取出所有的视频分类
                $catModel = D('Category');
                $catData = $catModel->getTree();
                //取出所有所属视频综艺
                $typeModel=D('Type');
                $typedata=$typeModel->order("listorder ASC")->select();
                $this->assign('typedata',$typedata);
                $this->assign('catData', $catData);
		$this->setPageBtn('添加视频', '视频列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');

    	if(IS_POST)
    	{
    		$model = D('Admin/Movie');
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
    	$model = M('Movie');
    	$data = $model->find($id);
        $extcat=$data['ext_category_id'];
        if($extcat){
            $arrcat=explode(',', $extcat);
            $data['ext_category_id']=$arrcat;
        }
        
    	$this->assign('data', $data);
        $this->setPageBtn('修改视频', '视频列表', U('lst?p='.I('get.p')));
        $this->display();
    }
    //回收站列表页
    public function recyclelst(){
        $model = D('Admin/Movie');
    	$data = $model->search(20,1);
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

        $this->setPageBtn('视频回收站', '视频列表', U('lst'));
    	$this->display();
    }

    //还原
    public function restore(){
        $model = M('Movie');
        $model -> where(array(
            'id'=>array('eq',I('get.id')),
        ))->setField('is_delete',0);
        $this->success('操作成功',U('recyclelst',array('p' => I('get.p',1))));
    }
    //放入回收站
    public  function recycle(){
        $model = M('Movie');
        $model -> where(array(
            'id'=>array('eq',I('get.id')),
        ))->setField('is_delete',1);
        $this->success('操作成功',U('lst',array('p' => I('get.p',1))));
    }
    public function delete()
    {
    	$model = D('Admin/Movie');
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
    	$model = D('Admin/Movie');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

        $this->setPageBtn('视频列表', '添加视频', U('add'));
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
            $model = D('Admin/Movie');
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
            $model = D('Admin/Movie');
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
    public function ajaxDelGoodsAttr(){
        $gaid = I('get.gaid');
        $gaModel = M('GoodsAttr');
        $gaModel->delete($gaid);
    }
	public function movieUpload(){
        $rootPath = C('MOV_rootPath');
        $upload = new \Think\Upload(array(
                'rootPath' => $rootPath,
         ));// 实例化上传类
        $upload->maxSize = (int)C('MOV_maxSize') * 1024 * 1024;// 设置附件上传大小
        $upload->exts = C('MOV_exts');// 设置附件上传类型
        //$upload->exts = array('mp4');// 设置附件上传类型
        //$upload->rootPath = $rootPath; // 设置附件上传根目录
        $upload->savePath = 'Admin/'; // 图片二级目录的名称
        // 上传文件 
        //TP中当调用upload方法时，会吧表单中所有的图片全部处理一遍
        //上传时指定一个要上传的图片路径
         //var_dump($upload);exit;
        $info = $upload->upload();//
        //var_dump($info);     
        if(!$info)
        {
           $this->error($upload->getError()); 
        }
        else{
            // 上传成功 
            echo json_encode($info['file']['savepath'].$info['file']['savename'],true);
        }
    }
}