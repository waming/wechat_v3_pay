<?php
/**
 * 微信支付v3版
 * 配置类
 */
namespace Xiaoming\Wechatpay;

class Config {

    /**
     * appid应用id
     */
    private $appId;

    /**
     * 商户id
     */
    private $merchantId;

    /**
     * 商户证书序列号
     */
    private $merchantSerialNumber;

    /**
     * v3密钥
     */
    private $V3key;

    /**
     * 证书保存路径，确认可读权限
     */
    private $privateKeyPath;

    /**
     * 平台证书保存路径，确认可读权限
     */
    private $platformCertPath;

    /**
     * 平台证书序列号
     */
    private $plateformCertSerialNumber;

    public function setAppId($appId = '') {
        $this->appId = $appId;
        return $this;
    }

    public function setMerchantId($merchantId = '') {
        $this->merchantId = $merchantId;
        return $this;
    }

    public function setMerchantSerialNumber($merchantSerialNumber = '') {
        $this->merchantSerialNumber = $merchantSerialNumber;
        return $this;
    }

    public function setV3Key($V3key = '') {
        $this->V3key = $V3key;
        return $this;
    }

    public function setPrivateKeyPath($privateKeyPath = '') {
        $this->privateKeyPath = $privateKeyPath;
        return $this;
    }

    public function setPlatformCertPath($platformCertPath = '') {
        $this->platformCertPath = $platformCertPath;
        return $this;
    }

    public function setPlatformSerialNumber($plateformCertSerialNumber = '') {
        $this->plateformCertSerialNumber = $plateformCertSerialNumber;
        return $this;
    }

    public function getAppId(){
        return $this->appId;
    }

    public function getMerchantId(){
        return $this->merchantId;
    }

    public function getMerchantSerialNumber() {
        return $this->merchantSerialNumber;
    }

    public function getV3Key() {
        return $this->V3key;
    }

    public function getPrivateKeyPath(){
        return  $this->privateKeyPath;
    }

    public function getPlatformCertPath() {
        return $this->platformCertPath;
    }

    public function getPlatformSerialNumber(){
        return $this->plateformCertSerialNumber;
    }
}