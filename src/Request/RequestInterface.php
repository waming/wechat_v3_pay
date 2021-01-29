<?php

namespace Xiaoming\Wechatpay\Request;

interface RequestInterface {

    /**
     * 获取某个支付的请求参数
     * 详细文档，请查看微信支付相关文档
     * @link https://pay.weixin.qq.com/wiki/doc/apiv3/index.shtml
     */
    public function getRequestData();
}