<?php

namespace Xiaoming\Wechatpay\Tests;

require_once __DIR__ . '/Config.php';
define("ROOT_PATH", dirname(__DIR__) . "/");

use PHPUnit\Framework\TestCase;
use Xiaoming\Wechatpay\Config;
use Xiaoming\Wechatpay\Logger;
use Xiaoming\Wechatpay\Request\JsPayRequest;
use Xiaoming\Wechatpay\Request\WapPayRequest;
use Xiaoming\Wechatpay\WechatPay;
use Monolog\Logger as MonologLogger;

class TestLog extends TestCase
{
    public function testLog()
    {
        global $config;
        $wechatpay = WechatPay::wapPay($config);
        // $logger = Logger::getInstance();

        Logger::info("测试_2");

        // $this->assertInstanceOf("Monolog\Logger", $logger);
    }
}