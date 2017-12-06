<?php

namespace Admin\Controller;

use \Admin\Controller\IndexController;

class CategoryController extends IndexController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Category');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $parentModel = D('Admin/Category');
        $parentData = $parentModel->getTree();
        $this->assign('parentData', $parentData);
        //取出所有的视频分类制作下拉框
        $typeModel=M('Type');
        $typeData=$typeModel->select();
        $this->assign('typeData',$typeData);
        $this->setPageBtn('添加视频分类', '视频分类列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function edit() {
        $id = I('get.id');
        if (IS_POST) {
            $model = D('Admin/Category');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $model = M('Category');
        $data = $model->find($id);
        $this->assign('data', $data);
        $parentModel = D('Admin/Category');
        $parentData = $parentModel->getTree();
        $children = $parentModel->getChildren($id);
        //var_dump($children);
        $this->assign(array(
            'parentData' => $parentData,
            'children' => $children,
        ));
        //取出所有的视频分类制作下拉框
        $typeModel=M('Type');
        $typeData=$typeModel->select();
        $this->assign('typeData',$typeData);
        //取出当前这个分类设置过的属性
        if($data['search_attr_id']){
            //把属性id转换成属性的名字
            $attrModel=M('Attribute');
            $searchAttrData=$attrModel->field('id,attr_name,type_id')->where(array('id'=>array('in',$data['search_attr_id'])))->select();
            $this->assign('searchAttrData',$searchAttrData);
            //var_dump($searchAttrData);
        }
        $this->setPageBtn('修改视频分类', '视频分类列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Category');
        if ($model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Category');
        $data = $model->getTree();
        echo "<pre>";
        //var_dump($data);
        echo "</pre>";
        $this->assign(array(
            'data' => $data,
        ));

        $this->setPageBtn('视频分类列表', '添加视频分类', U('add'));
        $this->display();
    }

}
