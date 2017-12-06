<?php

namespace Home\Controller;

class CartController extends BaseController {

    public function add() {
        $cartModel = D('Admin/Cart');
        $goodsAttrId = I('post.goods_attr_id');
        if ($goodsAttrId) {
            // 把属性ID升序排列，因为后台存属性的存量时是升序的，为了能取出库存量
            sort($goodsAttrId);
            $goodsAttrId = implode(',', $goodsAttrId);
        }
        $cartModel->addToCart(I('post.goods_id'), $goodsAttrId, I('post.amount'));
        redirect(U('lst'));
    }

    public function lst() {
        $cartModel = D('Admin/Cart');
        $data = $cartModel->cartList();

        $this->assign('data', $data);

        $this->setPageInfo('购物车', '购物车', '购物车', 1, array('cart'), array('cart1'));
        $this->display();
    }

    public function ajaxUpdateData() {
        $gid = I('get.gid');
        $gaid = I('get.gaid', '');
        $gn = I('get.gn');
        $cartModel = D('Admin/Cart');
        $data = $cartModel->updateData($gid, $gaid, $gn);
    }

    public function order() {
        /*         * ************ 把用户选择的商品存到了SESSION中，如果会员没有选择过商品就不能进入这个页面 **************** */
        $buythis = I('post.buythis');
        // 先判断表单中是否选择了
        if (!$buythis) {
            $buythis = session('buythis');
            if (!$buythis)
                $this->error('必须先选择商品', U('lst'));
        } else
            session('buythis', $buythis);

        $mid = session('mid');
        if (!$mid) {
            session('returnUrl', U('order'));
            redirect(U('Home/Member/login'));
        }
        //处理表单
        // 如果是下单的表单就处理，勾选了的含有buythis
        if (IS_POST && !isset($_POST['buythis'])) {
            //var_dump($_POST);die;
            /*             * ****下订单先验证***** */
            $orderModel = D('Admin/Order');
            if ($orderModel->create(I('post.'), 1)) {
                if ($id = $orderModel->add()) {
                    $this->success('下单成功', U('order_ok?id=' . $id));
                    exit;
                }
            }
            $this->error($orderModel->getError());
        }
        $cartModel = D('Admin/Cart');
        $data = $cartModel->cartList();
        $this->assign('data', $data);
        $this->setPageInfo('下订单', '下订单', '下订单', 1, array('fillin'), array('cart2'));
        $this->display();
    }

    public function respond() {
        // 执行notify_url.php文件中的代码
        include('./alipay/notify_url.php');
    }

    public function success() {
        echo"支付成功！";
    }

    public function order_ok() {
        $id = I('get.id');
        //查询订单总价
        $orderModel = M('order');
        $tp = $orderModel->field('total_price')->find($id);
        //生成支付宝的按钮
        require_once("./alipay/alipay.config.php");
        require_once("./alipay/lib/alipay_submit.class.php");
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        //接收支付宝发来的消息
        $notify_url = "http://shop34.com/index.php/Home/Cart/respond";
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = "http://shop34.com/index.php/Home/Cart/success";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //卖家支付宝帐户
        $seller_email = 'fortheday@126.com';
        //必填
        //商户订单号-本地订单号
        $out_trade_no = $id;
        //商户网站订单系统中唯一订单号，必填
        //订单名称
        $subject = "shop34网站支付";
        //必填
        //付款金额
        $total_fee = $tp['total_price'];
        //必填
        //订单描述
        $body = 'php34网站定单支付';
        //商品展示地址
        $show_url = "";
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数
        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "seller_email" => $seller_email,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipay_config['input_charset']))
        );
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        // 生成按钮的HTML代码
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "立即跳转到阿里巴巴在线支付");
        $this->assign('btn', $html_text);
        $this->setPageInfo('下单成功', '下单成功', '下单成功', 1, array('success'));
        $this->display();
    }

}
