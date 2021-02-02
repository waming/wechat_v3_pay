<?php

namespace Xiaoming\Wechatpay\Pay;

use Xiaoming\Wechatpay\Exceptions\InvalidPayException;
use Xiaoming\Wechatpay\Request\JsPayRequest;

class JsPay extends BasePay implements PayInterface  {

    /**
     * js支付统一下单
     * @param JsPayRequest $wapPayRequest
     */
    public function order($jsPayRequest) {

        if(! $jsPayRequest instanceof JsPayRequest) {
            throw new InvalidPayException("ERROR REQUEST, must instaof JsPayRequest");
        }

        return $this->request($jsPayRequest);
    }
}