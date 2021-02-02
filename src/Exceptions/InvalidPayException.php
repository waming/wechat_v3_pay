<?php

namespace Xiaoming\Wechatpay\Exceptions;

class InvalidPayException extends \Exception {

    public function __construct($message)
    {
        parent::__construct($message, '10000');
    }
}