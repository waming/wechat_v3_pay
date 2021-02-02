<?php
/**
 * 微信支付v3版
 */
namespace Xiaoming\Wechatpay;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Xiaoming\Wechatpay\Exceptions\InvalidPayException;
use Xiaoming\Wechatpay\Pay\PayInterface;

class WechatPay {

    private $config;
    
    private $logger;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->logger = new Logger('wechat');
        $this->logger->pushHandler(new StreamHandler($config->getLoggerPath()), Logger::WARNING);
    }

    /**
     * 静态方法调用
     */
    public static function __callStatic($method, $arguments) : PayInterface
    {
        $wehchatPay = new self(...$arguments);
        return $wehchatPay->make($method);
    }

    /**
     * 生成实例
     */
    protected function make($method) : PayInterface
    {
        $gateway = __NAMESPACE__.'\\Pay\\'.ucwords($method);

        if (!class_exists($gateway)) {
            throw new InvalidPayException("{$gateway} not exists");
        }

        $app = new $gateway($this->config);

        if ($app instanceof PayInterface) {
            return $app;
        }

        throw new InvalidPayException("{$method} Must Be An Instance Of PayInterface");
    }
}