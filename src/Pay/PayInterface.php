<?php

namespace Xiaoming\Wechatpay\Pay;

use Xiaoming\Wechatpay\Request\RequestInterface;
use Xiaoming\Wechatpay\Request\PayRequest;

/**
 * 支付接口
 */
interface PayInterface {

    /**
     * 统一下单接口
     * @param RequestInterface 请求参数接口
     */
    public function order($requestInterface);

    /**
     * 查询订单接口
     * todo
     */
    
    /**
     * 关闭订单接口
     * todo
    */

    /**
     * 支付结果通知
     * todo
     */
    public function notify();

    /**
     * 申请退款接口
     * todo
     */

    /**
     * 查询单笔退款
     * todo
     */

    /**
     * 退款结果通知
     * todo
    */


    /**
     * 申请交易账单
     * todo
    */

    /**
     * 申请资金账单
     * todo
    */

    /**
     * 下载账单
     * todo
    */
}