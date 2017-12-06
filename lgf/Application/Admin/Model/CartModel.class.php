<?php

namespace Admin\Model;

use Think\Model;

class CartModel extends Model {

    //加入购物车
    public function addToCart($goods_id, $goods_attr_id, $goods_number = 1) {
        $mid = session('mid');
        if ($mid) {
            $cartModel = M('Cart');
            $has = $cartModel->where(array(
                        'member_id' => array('eq', $mid),
                        'goods_id' => array('eq', $goods_id),
                        'goods_attr_id' => array('eq', $goods_attr_id),
                    ))->find();
            if ($has) {//判断商品是否存在
                $cartModel->where('id=' . $has['id'])->setInc('goods_number', $goods_number);
            } else {
                $cartModel->add(array(
                    'goods_id' => $goods_id,
                    'goods_attr_id' => $goods_attr_id,
                    'goods_number' => $goods_number,
                    'member_id' => $mid,
                ));
            }
        } else {
            //先从购物车中取出数组
            $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
            //吧商品加入到这个数组中
            $key = $goods_id . '-' . $goods_attr_id;
            //先判断数组中哟没有这件商品
            if (isset($cart[$key]))
                $cart[$key] +=$goods_number;
            else
                $cart[$key] = $goods_number;
            //吧这个数组存回到cookie
            $aMonth = 30 * 86400;
            setcookie('cart', serialize($cart), time() + $aMonth, '/', 'shop34.com');
        }
    }

    public function cartList() {
        $mid = session('mid');
        if ($mid) {
            $cartModel = M('Cart');
            $_cart = $cartModel->where(array('member_id' => array('eq', $mid)))->select();
        } else {
            $_cart_ = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

            //转换这个数组的结构，变成2维数组
            $_cart = array();
            foreach ($_cart_ as $k => $v) {
                //从下标中解析出商品id
                $_k = explode('-', $k);
                $_cart[] = array(
                    'goods_id' => $_k[0],
                    'goods_attr_id' => $_k[1],
                    'goods_number' => $v,
                    'member_id' => 0,
                );
            }
        }
        /*         * **************循环购物车中的每件商品*根据id取出商品详细信息******* */
        $goodsModel = D('Admin/Goods');
        foreach ($_cart as $k => $v) {//取出显示面板要显示的再加到$_cart这个数组中
            $ginfo = $goodsModel->field('sm_logo,goods_name')->find($v['goods_id']);
            $_cart[$k]['goods_name'] = $ginfo['goods_name'];
            $_cart[$k]['sm_logo'] = $ginfo['sm_logo'];
            // 计算会员价格
            $_cart[$k]['price'] = $goodsModel->getMemberPrice($v['goods_id']);
            // 把商品属性ID转化成商品属性字符串	
            $_cart[$k]['goods_attr_str'] = $goodsModel->convertGoodsAttrIdToGoodsAttrStr($v['goods_attr_id']);
        }
        return $_cart;
    }

    public function moveDataToDb() {
        $mid = session('mid');
        if ($mid) {
            //从cookie取出购物车的数据
            $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
            if ($cart) {
                foreach ($cart as $k => $v) {
                    // 从下标出解析出商品ID，和商品属性ID
                    $_k = explode('-', $k);
                    $this->addToCart($_k[0], $_k[1], $v);
                }
                // 清空COOKIE中的数据
                setcookie('cart', '', time() - 1, '/', 'shop34.com');
            }
        }
    }

    public function updateData($gid, $gaid, $gn) {
        $mid = session('mid');
        if ($mid) {
            $cartModel = M('Cart');
            if ($gn == 0)
                $cartModel->where(array(
                    'goods_id' => array('eq', $gid),
                    'goods_attr_id' => array('eq', $gaid),
                    'member_id' => array('eq', $mid),
                ))->delete();
            else
                $cartModel->where(array(
                    'goods_id' => array('eq', $gid),
                    'goods_attr_id' => array('eq', $gaid),
                    'member_id' => array('eq', $mid),
                ))->setField('goods_number', $gn);
        }
        else {
            // 先从COOKIE中取出购物车的数组
            $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
            $key = $gid . '-' . $gaid;
            if ($gn == 0)
                unset($arr[$key]);
            else
                $arr[$key] = $gn;
            // 把这个数组存回到cookie
            $aMonth = 30 * 86400;
            setcookie('cart', serialize($cart), time() + $aMonth, '/', 'shop34.com');
        }
    }

    public function clearDb() {
        $mid = session('mid');
        if ($mid) {
            // 取出勾选的商品
            $buythis = session('buythis');
            $cartModel = M('Cart');
            // 循环勾选 的商品进行删除
            foreach ($buythis as $k => $v) {
                // 从字符串 解析出商品ID和商品属性ID
                $_v = explode('-', $v);
                $cartModel->where(array('member_id' => array('eq', $mid), 'goods_id' => array('eq', $_v[0]), 'goods_attr_id' => array('eq', $_v[1])))->delete();
            }
        }
    }

}
