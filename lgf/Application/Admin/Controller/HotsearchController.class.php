<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class HotsearchController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Hotsearch');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}

		$this->setPageBtn('添加热门搜索词', '热门搜索词列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Hotsearch');
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
    	$model = M('Hotsearch');
    	$data = $model->find($id);
    	$this->assign('data', $data);
        $this->setPageBtn('修改热门搜索词', '热门搜索词列表', U('lst?p='.I('get.p')));
        $this->display();
    }
    public function delete()
    {
    	$model = D('Admin/Hotsearch');
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
    	$model = D('Admin/Hotsearch');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

        $this->setPageBtn('热门搜索词列表', '添加热门搜索词', U('add'));
    	$this->display();
    }
}