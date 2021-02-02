<?php

namespace Xiaoming\Wechatpay\Request;

/**
 * 微信支付jspay请求类
 */
class JsPayRequest extends PayRequest implements RequestInterface {

    /**
     * openId
     */
    private $openId;

    public function setOpenId($openId = '') {
        $this->openId = $openId;
    }

    public function getOpenId() {
        return $this->openId;
    }

    public function getRequestUri()
    {
        return "https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi";
    }

    /**
     * 获取支付请求参数
     */
    public function getRequestData()
    {
        $data = [];
        $data['appid'] = $this->getAppId();
        $data['mchid'] = $this->getMchid();
        $data['description']  = $this->getDescirption();
        $data['out_trade_no'] = $this->getOutTradeOn();
        $data['notify_url']   = $this->getNotifyUrl();
        $data['amount'] = [
            'total' => $this->getAmount()
        ];

        $data['payer'] = [
            'openid' => $this->getOpenId()
        ];

        return array_merge($data, $this->getExtendData());
    }
}