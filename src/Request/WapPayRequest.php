<?php

namespace Xiaoming\Wechatpay\Request;

/**
 * 微信支付h5请求类
 */
class WapPayRequest extends BaseRequest implements RequestInterface {

    /**
     * 请求ip
     */
    private $ip;

    public function setIp($ip = '') {
        $this->ip = $ip;
    }

    public function getIp() {
        return $this->ip;
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
        $data['scene_info'] = [

            'payer_client_ip' => $this->getIp(),
            'h5_info' => [
                'type' => 'Wap',
            ]
        ];

        return $data;
    }
}