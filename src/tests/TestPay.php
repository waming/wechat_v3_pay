<?php

namespace Xiaoming\Wechatpay\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
define("ROOT_PATH", dirname(__DIR__) . "/");

use PHPUnit\Framework\TestCase;
use Xiaoming\Wechatpay\Config;
use Xiaoming\Wechatpay\Request\JsPayRequest;
use Xiaoming\Wechatpay\Request\WapPayRequest;
use Xiaoming\Wechatpay\WechatPay;

class TestPay extends TestCase{
    
    public function testConfig()
    {
        $config = new Config();
        $config->setAppId('xxx') //应用id
            ->setMerchantId('xxx') //商户id
            ->setMerchantSerialNumber('xxx') //商户证书序列号
            ->setV3Key('xxx') //v3版密钥
            ->setPrivateKeyPath(__DIR__.'\apiclient_key_1544477311.pem') //私钥证书文件路径
            ->setPlatformCertPath(__DIR__.'\platform_cert_1544477311.pem') //平台证书文件路径
            ->setPlatformSerialNumber('xxx') //平台证书序列号
            ->setLoggPath(__DIR__.'wechat.log'); //日志保存路径

        $this->assertNotNull($config);
        return $config;
    }

    public function testH5Order()
    {
        $config = $this->testConfig();
        $wechatpay = new WechatPay($config);

        $waprequest = new WapPayRequest();
        $waprequest->setMchid($config->getMerchantId());
        $waprequest->setAppId($config->getAppId());
        $waprequest->setAmount(1);
        $waprequest->setDescirption("测试");
        $waprequest->setOutTradeOn(date('YmdHis'));
        $waprequest->setNotifyUrl("https://test.test.com");
        $waprequest->setIp("127.0.0.1");

        $data = $wechatpay->h5PayOrder($waprequest);
        $this->assertNotNull($data);
        $this->assertNotNull($data['h5_url']);
    }

    public function testJsOrder()
    {
        $config = $this->testConfig();
        $wechatpay = new WechatPay($config);

        $jsrequest = new JsPayRequest();
        $jsrequest->setMchid($config->getMerchantId());
        $jsrequest->setAppId($config->getAppId());
        $jsrequest->setDescirption("测试");
        $jsrequest->setAmount(1);
        $jsrequest->setOutTradeOn(date('YmdHis'));
        $jsrequest->setNotifyUrl("https://test.test.com");
        $jsrequest->setOpenId('12');
        $data = $wechatpay->jsPayOrder($jsrequest);

        $this->assertNotNull($data);
        $this->assertNotNull($data['prepay_id']);
    }
}