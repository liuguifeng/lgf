<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller 
{
    public function _initialize() {
        
        $catsModel = D('Admin/Category');
        $cats = $catsModel->getNavCatData();
        //var_dump($cats);
        $this->assign('cats',$cats);
        $this->assign('index',false);
    }
    
    
    // 设置页面信息
    public function setPageInfo($title, $keywords, $description, $showNav=0, $css=array(), $js=array())
    {
    	$this->assign('page_keywords', $keywords);
    	$this->assign('page_description', $description);
    	$this->assign('page_title', $title);
    	$this->assign('show_nav', $showNav);
    	$this->assign('page_css', $css);
    	$this->assign('page_js', $js);
    }
}