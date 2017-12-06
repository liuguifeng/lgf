<?php
namespace Admin\Model;
use Think\Model;
class FavouriteModel extends Model 
{
	// 表单允许提交的字段
	protected $insertFields = array();
	protected $_validate = array(
//		array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
//		array('shr_province', 'require', '收货人所在省不能为空！', 1, 'regex', 3),
//		array('shr_city', 'require', '收货人城市不能为空！', 1, 'regex', 3),
//		array('shr_area', 'require', '收货人地区不能为空！', 1, 'regex', 3),
//		array('shr_address', 'require', '收货人地址不能为空！', 1, 'regex', 3),
//		array('shr_tel', 'require', '收货人电话不能为空！', 1, 'regex', 3),
//		array('pay_method', 'require', '支付方式不能为空！', 1, 'regex', 3),
//		array('post_method', 'require', '发货方式不能为空！', 1, 'regex', 3),
	);
	protected function _before_insert(&$data, $option)
	{
//		// 判断购物车中是否有商品
//		$cartModel = D('Admin/Cart');
//		$cartData = $cartModel->cartList();
//		if(count($cartData) == 0)
//		{
//			$this->error = '必须先购买商品才能下单';
//			return FALSE;
//		}
//		// 循环购物车中每件商品检查库存量够不够，并且计算总价
//		// 加锁-> 高并发下单时，库存量会出现混乱的问题，加锁来解决
//		$this->fp = fopen('./order.lock', 'r');
//		flock($this->fp, LOCK_EX);
//		$tp = 0; // 总价
//		$gnModel = M('GoodsNumber');
//		// 循环购物车中所有的商品
//		$buythis = session('buythis');
//		foreach ($cartData as $k => $v)
//		{
//			// 判断这件商品有没有被选择
//			if(!in_array($v['goods_id'].'-'.$v['goods_attr_id'], $buythis))
//				continue;
//			// 取出这件商品的库存量
//			$gn = $gnModel->field('goods_number')->where(array(
//				'goods_id' => array('eq', $v['goods_id']),
//				'goods_attr_id' => array('eq', $v['goods_attr_id']),
//			))->find();
//			if($gn['goods_number'] < $v['goods_number'])
//			{
//				$this->error = '商品库存量不足无法下单';
//				return FALSE;
//			}
//			// 计算总价
//			$tp += $v['price'] * $v['goods_number'];
//		}
//		// 下单前把定单的其他信息补就即可
//		$data['member_id'] = session('mid');
//		$data['addtime'] = time();
//		$data['total_price'] = $tp;
//		// 启用事务
//		mysql_query('START TRANSACTION');
	}
	protected function _after_insert($data, $option)
	{
		
	}
	
	public function setFavourite()
	{
            $token=I('post.token');
            $data1['member_id']=I('post.user_id');
            $data1['movie_id']=I('post.video_id');
            if(!$data1['member_id']||!$token||!$data1['movie_id']){
                $this->error=array(4012=>"获取信息失败，请重新登录");
                return false;
            }
            $model=D('Admin/Member');
            $data=$model->where(array('id'=>array('eq',$data1['member_id'])))->find();
            if($data['token']==$token){
                $scModel=D("Admin/Favourite");
                $favdata=$scModel->field('movie_id')->where(array('member_id'=>array('eq',$data1['member_id'])))->select();
                       
                foreach($favdata as $k => $v){
                    $arr[]=$v['movie_id'];
                }
                if(in_array($data1['movie_id'],$arr)){
                    $this->error=array(4014=>"你已经点过赞了");
                    return false;
                }else{
                    $movModel=D("Admin/Movie");
                    $movModel->where(array('id'=>array('eq',$data1['movie_id'])))->setInc('dianzan',1);
                    $favdata=$movModel->field('dianzan')->where(array('id'=>array('eq',$data1['movie_id'])))->find();
                    if($scModel->add($data1)){
                        return $favdata;
                    }
                }
            }else{
                $this->error=array(4012=>"获取信息失败，请重新登录");
                return false;
            }
	}
}