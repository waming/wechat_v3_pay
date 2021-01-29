<?php
/**
 * 微信支付v3版
 */
namespace Xiaoming\Wechatpay;

use WechatPay\GuzzleMiddleware\Util\PemUtil;
use WechatPay\GuzzleMiddleware\WechatPayMiddleware;
use Xiaoming\Wechatpay\Request\JsPayRequest;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Xiaoming\Wechatpay\Request\WapPayRequest;
use GuzzleHttp\Exception\RequestException;
use NoopValidator;
use WechatPay\GuzzleMiddleware\Util\AesUtil;

class WechatPay {

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;        
    }

    /**
     * 获取请求client
     */
    private function getClient()
    {
        $merchantPrivateKey = PemUtil::loadPrivateKey($this->config->getPrivateKeyPath()); // 商户私钥

        // 微信支付平台证书
        $wechatpayCertificate = file_get_contents($this->config->getPlatformCertPath());

        $wechatpayMiddleware = WechatPayMiddleware::builder()
            ->withMerchant($this->config->getMerchantId(), $this->config->getMerchantSerialNumber(), $merchantPrivateKey) // 传入商户相关配置
            ->withWechatPay([ $wechatpayCertificate ]) // 可传入多个微信支付平台证书，参数类型为array
            ->build();

        // 将WechatPayMiddleware添加到Guzzle的HandlerStack中
        $stack = HandlerStack::create();
        $stack->push($wechatpayMiddleware, 'wechatpay');

        return new Client(['handler' => $stack]);
    }

    /**
     * h5 统一下单接口
     * @link https://pay.weixin.qq.com/wiki/doc/apiv3/open/pay/chapter2_6_3.shtml
     */
    public function h5PayOrder(WapPayRequest $wapPayRequest)
    {
        $data = $wapPayRequest->getRequestData();

        try {
            $resp = $this->getClient()->request('POST', 
                'https://api.mch.weixin.qq.com/v3/pay/transactions/h5', [ // 注意替换为实际URL
                'headers' => [ 'Accept' => 'application/json' ],
                'json' => $data
            ]);
        
            $result = json_decode($resp->getBody(), true);

            return $result;
        
        } catch (RequestException $e) {
            // 进行错误处理
            echo $e->getMessage()."\n";
            if ($e->hasResponse()) {
                echo $e->getResponse()->getStatusCode().' '.$e->getResponse()->getReasonPhrase()."\n";
                echo $e->getResponse()->getBody();
            }
            // throw new \RuntimeException($e->getMessage());
            // return;
        }
    }


    /**
     * jspay 统一下单接口
     * @link https://pay.weixin.qq.com/wiki/doc/apiv3/open/pay/chapter2_4.shtml
    */
    public function jsPayOrder(JsPayRequest $jsPayRequest)
    {
        $data = $jsPayRequest->getRequestData();

        try {
            $resp = $this->getClient()->request('POST', 
                'https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi', [ // 注意替换为实际URL
                'headers' => [ 'Accept' => 'application/json' ],
                'json' => $data
            ]);
        
            $result = json_decode($resp->getBody(), true);

            return $result;
        
        } catch (RequestException $e) {
            throw new \RuntimeException($e->getMessage());
            // 进行错误处理
            // echo $e->getMessage()."\n";
            // if ($e->hasResponse()) {
            //     echo $e->getResponse()->getStatusCode().' '.$e->getResponse()->getReasonPhrase()."\n";
            //     echo $e->getResponse()->getBody();
            // }
            // return;
        }
    }

    /**
     * 下载证书，此证书非商户平台部署的证书。而是请求接口时的证书
     * 下载后请保存证书到某个文件中以供调用
     */
    public function getCertFile() {
        $merchantPrivateKey = PemUtil::loadPrivateKey($this->config->getPrivateKeyPath());
        $wechatpayMiddleware = WechatPayMiddleware::builder()
            ->withMerchant($this->config->getMerchantId(), $this->config->getMerchantSerialNumber(), $merchantPrivateKey)
            ->withValidator(new NoopValidator())
            ->build();

        $stack = HandlerStack::create();
        $stack->push($wechatpayMiddleware, 'wechatpay');

        $client = new Client(['handler' => $stack]);

        $url = "https://api.mch.weixin.qq.com/v3/certificates";

        try {
            $resp = $client->request('get', $url, 
            [ 
                'headers' => [ 
                    'Accept' => 'application/json',
                    'User-Agent' => 'https://zh.wikipedia.org/wiki/User_agent'
                ]
            ]);
            $data = json_decode($resp->getBody(), true);

            //商户序列号
            $result = $data['data'][0];
            $serlizeNo = $result['serial_no'];

            //序列号
            echo $serlizeNo."\n";
            $encrypt_certificate = $result['encrypt_certificate'];
            
            //签名后的参数
            $aesUtil = new AesUtil($this->config->getV3Key());
            $ciphertext = $aesUtil->decryptToString($encrypt_certificate['associated_data'], 
            $encrypt_certificate['nonce'],        
            $encrypt_certificate['ciphertext']);
            
            //平台证书字符串
            echo $ciphertext;
        } catch (RequestException $e) {
            throw new \RuntimeException($e->getMessage());
            // // 进行错误处理
            // echo $e->getMessage()."\n";
            // if ($e->hasResponse()) {
            //     echo $e->getResponse()->getStatusCode().' '.$e->getResponse()->getReasonPhrase()."\n";
            //     echo $e->getResponse()->getBody();
            // }
            // return;
        } 
    }
}