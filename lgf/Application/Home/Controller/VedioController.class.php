<?php

namespace Home\Controller;

class VedioController extends BaseController {
    public function vedio(){
        $movieid = I("get.movieid");
        $model=D("Admin/Movie");
        $where['id']=array("eq",$movieid);
        $moviedata=$model->where($where)->find();
        $this->assign('moviedata',$moviedata);
        $this->display();
    }
    
}
