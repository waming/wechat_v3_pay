<?php

namespace Xiaoming\Wechatpay\Request;

abstract class PayRequest {

    /**
     * 请求应用appid
     */
    private $appId;

    /**
     * 请求商户id
     */
    private $mchid;

    /**
     * 订单描述
     */
    private $description;

    /**
     * 商户订单号，请保证唯一
     */
    private $out_trade_no;
    
    /**
     * 请求结果异步返回通知url
     */
    private $notify_url;

    /**
     * 支付金额
     */
    private $amount;

    /**
     * 扩展参数，用户自定义
     */
    private $extendData = [];

    /**
     * 设置其他参数，用于扩展
     */
    public function setExtendData($data = []) {
        $this->extendData = $data;
    }

    public function setAppId($appId = '') {
        $this->appId  = $appId;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function setMchid($mchid = '') {
        $this->mchid  = $mchid;
    }

    public function getMchid() {
        return $this->mchid;
    }

    public function setDescirption($description = '') {
        $this->description = $description;
    }

    public function getDescirption() {
        return $this->description;
    }


    public function setOutTradeOn($out_trade_no = '') {
        $this->out_trade_no = $out_trade_no;
    }

    public function getOutTradeOn() {
        return $this->out_trade_no;
    }

    public function setNotifyUrl($notify_url = '') {
        $this->notify_url = $notify_url;
    }

    public function getNotifyUrl() {
        return $this->notify_url;
    }


    public function setAmount($amount = 1 ) {
        $this->amount = $amount;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getExtendData() {
        return $this->extendData;
    }
}