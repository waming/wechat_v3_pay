# wechat_v3_pay
微信支付v3版

# 关于项目起源
由于目前很多的项目都是基于v2版本的微信支付，所以自己从新造了一个轮子。 致力于v3版的微信开发

# 实现功能

1.h5统一下单

2.js统下单

3.接受异步回调

## 支持的支付方法
- 手机网站支付
- jspay支付
- 获取平台证书

|  method   |   描述       |
| :-------: | :-------:   |
|  wapPay  | 手机网站支付 |
|  jsPay   | jspay支付   |
|  getCertFile | 获取平台证书 |
|  notify | 异步通知 |

# 使用方法

    composer require xming/wechat_v3_pay

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
    $wechatpay = WechatPay::wapPay($config);

    //h5统一下单
    $waprequest = new WapPayRequest();
    $waprequest->setMchid($config->getMerchantId());
    $waprequest->setAppId($config->getAppId());
    $waprequest->setAmount(1);
    $waprequest->setDescirption("测试");
    $waprequest->setOutTradeOn(date('YmdHis'));
    $waprequest->setNotifyUrl("https://test.com/notify/wechat");
    $waprequest->setIp("127.0.0.1");
    $data = $wechatpay->order($waprequest);

    //js统一下单
    $jsrequest = new JsPayRequest();
    $jsrequest->setMchid($config->getMerchantId());
    $jsrequest->setAppId($config->getAppId());
    $jsrequest->setDescirption("测试");
    $jsrequest->setAmount(1);
    $jsrequest->setOutTradeOn(date('YmdHis'));
    $jsrequest->setNotifyUrl("https://test.com/notify/wechat");
    $jsrequest->setOpenId('xxx'); //openid
    $data = $wechatpay->order($jsrequest);

```
# 注意
第一次使用时，建议先获取平台证书，并保存到某个文件中。微信平台不建议硬编码使用。并需要定时更新

``` php
   //获取平台证书. 仅返回第一个平台证书
   $wechatpay = WechatPay::getPlatformCert($config);
   echo 'xxx';  //平台证书序列号
   echo 'xxx';  //平台证书，请保存某个文件xx.pem
```

# 问题
1.若何获取平台证书？

https://pay.weixin.qq.com/wiki/doc/apiv3/wechatpay/wechatpay5_1.shtml
或者使用系统封装方法获取


# 感谢

微信官方提供请求中间件
欢迎大家提issue和pr
