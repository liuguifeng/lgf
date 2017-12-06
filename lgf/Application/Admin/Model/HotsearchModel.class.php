<?php
namespace Admin\Model;
use Think\Model;
class HotsearchModel extends Model 
{
	// 表单允许提交的字段
	protected $insertFields = array('word','listorder');
	protected $_validate = array(
		array('word', 'require', '热门搜索词不能为空！', 1, 'regex', 3),
//		array('shr_province', 'require', '收货人所在省不能为空！', 1, 'regex', 3),
//		array('shr_city', 'require', '收货人城市不能为空！', 1, 'regex', 3),
//		array('shr_area', 'require', '收货人地区不能为空！', 1, 'regex', 3),
//		array('shr_address', 'require', '收货人地址不能为空！', 1, 'regex', 3),
//		array('shr_tel', 'require', '收货人电话不能为空！', 1, 'regex', 3),
//		array('pay_method', 'require', '支付方式不能为空！', 1, 'regex', 3),
//		array('post_method', 'require', '发货方式不能为空！', 1, 'regex', 3),
	);
        public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->group('id')->order('listorder asc')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
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
        public function getHotsearch(){
            $data=$this->order("listorder asc")->limit(4)->select();
            $hsdata['hotsearch']=$data;
            return $hsdata;
        }
        public function getdowload(){
            
        }
}