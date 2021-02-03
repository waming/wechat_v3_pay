<?php

namespace Xiaoming\Wechatpay;

use Psr\Http\Message\ResponseInterface;
use WechatPay\GuzzleMiddleware\Validator;

class NoopValidator implements Validator {

    /**
     * 第一次验证直接返回true
     */
    public function validate(ResponseInterface $response)
    {
        return true;
    }
}