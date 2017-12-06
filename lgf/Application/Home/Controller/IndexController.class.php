<?php
namespace Home\Controller;
class IndexController extends BaseController 
{
    public function index()
    {	
    	$this->assign(array(
                'index'   =>true,
    	));
    	
    	// 设置页面的
    	// 参数1 ： 标题
    	// 参数2：设置meta标签中的关键字
    	// 参数3：设置meta标签中的描述
    	// 参数4: 分类树是否展开、1:展开 0：折叠
    	// 参数5：设置页面中需要包含的CSS文件
    	// 参数6：设置页面中需要包含的JS文件
    	$this->setPageInfo('首页', '关键字', '描述', 1, array('index'), array('index'));
    	$this->display();
    }
    public function test(){
//        $testModel=D('Admin/Admin');
//        $data=$testModel->select();
//        //var_dump($data);
//        echo json_encode($data);
        $json=new \api\app\ResponseJson;
        $arr=array(
            'id'=>1,
            'name'=>'lgf'
        );
        $json->json(200,'数据返回成功',$arr);
    }
    public function test2(){
        
        $xml=new \Lib\api\ResponseJson;
        $arr=array(
            'id'=>1,
            'name'=>'lgf',
            'type'=>array(7,8,9)
        );
        $xml->xmljson(200,'success',$arr,'json');
    }

}




















