<?php

namespace Xiaoming\Wechatpay\Pay;

use Xiaoming\Wechatpay\Exceptions\InvalidPayException;
use Xiaoming\Wechatpay\Request\WapPayRequest;

class WapPay extends BasePay implements PayInterface  {

    /**
     * h5支付统一下单
     * @param WapPayRequest $wapPayRequest
     */
    public function order($wapPayRequest) {

        if(! $wapPayRequest instanceof WapPayRequest) {
            throw new InvalidPayException("ERROR REQUEST, must instaof WapPayRequest");
        }

        return $this->request($wapPayRequest);
    }
}