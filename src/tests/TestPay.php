<?php

namespace Xiaoming\Wechatpay\Tests;

require_once __DIR__ . '/Config.php';

use PHPUnit\Framework\TestCase;
use Xiaoming\Wechatpay\Config;
use Xiaoming\Wechatpay\Request\JsPayRequest;
use Xiaoming\Wechatpay\Request\RefundInfoRequest;
use Xiaoming\Wechatpay\Request\RefundRequest;
use Xiaoming\Wechatpay\Request\WapPayRequest;
use Xiaoming\Wechatpay\WechatPay;

class TestPay extends TestCase{

    public function testWechatObj()
    {
        global $config;
        $wechatpay = WechatPay::wapPay($config);
        $this->assertNotNull($wechatpay);
    }

    public function testH5Order()
    {
        global $config;
        $wechatpay = WechatPay::wapPay($config);

        $waprequest = new WapPayRequest();
        $waprequest->setMchid($config->getMerchantId());
        $waprequest->setAppId($config->getAppId());
        $waprequest->setAmount(1);
        $waprequest->setDescirption("测试");
        $waprequest->setOutTradeOn(date('YmdHis'));
        $waprequest->setNotifyUrl("https://test.test.com");
        $waprequest->setIp("127.0.0.1");

        $data = $wechatpay->order($waprequest);
        $this->assertNotNull($data);
        $this->assertNotNull($data['h5_url']);
    }

    public function testJsOrder()
    {
        global $config;
        $wechatpay = WechatPay::jsPay($config);

        $jsrequest = new JsPayRequest();
        $jsrequest->setMchid($config->getMerchantId());
        $jsrequest->setAppId($config->getAppId());
        $jsrequest->setDescirption("测试");
        $jsrequest->setAmount(1);
        $jsrequest->setOutTradeOn(date('YmdHis'));
        $jsrequest->setNotifyUrl("https://test.test.com");
        $jsrequest->setOpenId('oitcF1fiDnx980nNj0iOrhO4Xvx0');
        $data = $wechatpay->order($jsrequest);

        $this->assertNotNull($data);
        $this->assertNotNull($data['prepay_id']);
    }

    public function testCert()
    {
        global $config;
        WechatPay::getPlatformCert($config);
    }

    public function testRefund()
    {
        global $config;
        $pay = WechatPay::wapPay($config);

        $outrefundno = 'REFUND'.date('YmdHis');
        $refundRequest = new RefundRequest();
        $refundRequest->setTransactionId('4200000998202103171886095327');
        // $refundRequest->setOutTradeNo($outtrandeno);
        $refundRequest->setOutRefundNo($outrefundno);
        $refundRequest->setReason('测试退款');
        $refundRequest->setNotifyUrl('https://channel.test.com/notify/refund');
        $refundRequest->setRefundFee(1);
        $refundRequest->setTotalFee(1);


        $data = $refundRequest->getRequestData();

        $this->assertNotNull($data['transaction_id']);
        $this->assertEquals($data['transaction_id'], '4200000998202103171886095327', 'okokok');

        $data = $pay->refund($refundRequest);

        $this->assertNotNull($data);
        $this->assertNotNull($data['refund_id']);
        $this->assertEquals($outrefundno, $data['out_refund_no']);
        $this->assertNotNull($data['channel']);
        $this->assertNotNull($data['status']);
    }

    public function testRefundInfo()
    {
        global $config;
        $pay = WechatPay::wapPay($config);

        $request = new RefundInfoRequest();
        $request->setOutRefundNo('REFUND'.date('YmdHis'));
        $data = $pay->refundInfo($request);
        $this->assertNotNull($data);
    }
}