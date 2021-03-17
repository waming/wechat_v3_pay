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
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->setLogService();
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

    /**
     * 配置日志相关
     */
    protected function setLogService()
    {
        $logger = new Logger('wechat');
        $logger->pushHandler(new StreamHandler($this->config->getLoggerPath()), Logger::WARNING);
        \Xiaoming\Wechatpay\Logger::setInstance($logger);
    }
}
