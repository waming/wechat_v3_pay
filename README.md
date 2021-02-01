# wechat_v3_pay
微信支付v3版

# 关于项目起源
由于目前很多的项目都是基于v2版本的微信支付，所以自己从新造了一个轮子。 致力于v3版的微信开发

# 实现功能
1.h5统一下单
2.js统下单
3.接受异步回调

# 使用方法

    composer require xiaoming/wechat_v3_pay

```php
    use Xiaoming\Wechatpay\Config;
    use Xiaoming\Wechatpay\Request\JsPayRequest;
    use Xiaoming\Wechatpay\Request\WapPayRequest;
    use Xiaoming\Wechatpay\WechatPay;

    $config = new Config();
    $config->setAppId('xxx') //应用id
            ->setMerchantId('xxx') //商户id
            ->setMerchantSerialNumber('xxx') //商户证书序列号
            ->setV3Key('xxx') //v3版密钥
            ->setPrivateKeyPath(__DIR__.'\apiclient_key_1544477311.pem') //私钥证书文件路径
            ->setPlatformCertPath(__DIR__.'\platform_cert_1544477311.pem') //平台证书文件路径
            ->setPlatformSerialNumber('xxx') //平台证书序列号
            ->setLoggPath(__DIR__.'wechat.log'); //日志保存路径
    $wechatpay = new WechatPay($config);

    //h5支付
    $waprequest = new WapPayRequest();
    $waprequest->setMchid($config->getMerchantId());
    $waprequest->setAppId($config->getAppId());
    $waprequest->setAmount(1);
    $waprequest->setDescirption("测试");
    $waprequest->setOutTradeOn(date('YmdHis'));
    $waprequest->setNotifyUrl("https://test.com/notify/wechat");
    $waprequest->setIp("127.0.0.1");
    $data = $wechatpay->h5PayOrder($waprequest);

    //js支付
    $jsrequest = new JsPayRequest();
    $jsrequest->setMchid($config->getMerchantId());
    $jsrequest->setAppId($config->getAppId());
    $jsrequest->setDescirption("测试");
    $jsrequest->setAmount(1);
    $jsrequest->setOutTradeOn(date('YmdHis'));
    $jsrequest->setNotifyUrl("https://test.com/notify/wechat");
    $jsrequest->setOpenId('xxx'); //openid
    $data = $wechatpay->jsPayOrder($jsrequest);

```

# 感谢
欢迎大家提issue和pr