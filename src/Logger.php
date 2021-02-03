<?php
/**
 * 微信支付v3版
 * 日志类
 */
namespace Xiaoming\Wechatpay;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    private static $instance;

    protected $config = [
        'name' => 'wechat',
        'path' => 'wechat.log',
    ];

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $args
     */
    public static function __callStatic($method, $args): void
    {
        forward_static_call_array([self::getInstance(), $method], $args);
    }

    /**
     * 设置日志类的实例
     * 本实例基于Monolog
     */
    public static function setInstance(MonologLogger $logger) {
        self::$instance = $logger;
    }

    /**
     * getInstance.
     */
    public static function getInstance(): MonologLogger
    {
        if (is_null(self::$instance)) {
            $logger = new MonologLogger(self::$config['name']);
            $logger->pushHandler(new StreamHandler(self::$config['path']), MonologLogger::WARNING);
            self::$instance = $logger;
        }

        return self::$instance;
    }
}