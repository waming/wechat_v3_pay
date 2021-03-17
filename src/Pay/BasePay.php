<?php

namespace Xiaoming\Wechatpay\Pay;

use Xiaoming\Wechatpay\Config;
use WechatPay\GuzzleMiddleware\Util\PemUtil;
use WechatPay\GuzzleMiddleware\WechatPayMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Xiaoming\Wechatpay\Exceptions\InvalidPayException;
use Xiaoming\Wechatpay\Request\RequestInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use WechatPay\GuzzleMiddleware\Util\AesUtil;
use Xiaoming\Wechatpay\Logger;
use Xiaoming\Wechatpay\Request\RefundRequest;
use Xiaoming\Wechatpay\Request\RefundInfoRequest;

abstract class BasePay {

    /**
     * 配置文件
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 获取请求client
     */
    protected function getClient()
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
     * 发起请求接口
     */
    protected function request($request)
    {
        if(! $request instanceof RequestInterface) {
            throw new InvalidPayException("ERROR Request! Request must implements RequestInterface");
        }

        $data = $request->getRequestData();

        try {
            $resp = $this->getClient()->request('POST', 
                $request->getRequestUri(), [ // 注意替换为实际URL
                'headers' => [ 'Accept' => 'application/json' ],
                'json' => $data
            ]);
        
            $result = json_decode($resp->getBody(), true);

            return $result;
        
        } catch (RequestException $e) {
           
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * get 请求
     */
    protected function getRequest($request) 
    {
        if(! $request instanceof RequestInterface) {
            throw new InvalidPayException("ERROR Request! Request must implements RequestInterface");
        }

        $data = $request->getRequestData();

        try {
            $resp = $this->getClient()->request('GET', 
                $request->getRequestUri(), [ // 注意替换为实际URL
                'headers' => [ 'Accept' => 'application/json' ],
            ]);
        
            $result = json_decode($resp->getBody(), true);

            return $result;
        
        } catch (RequestException $e) {
           
            throw new \RuntimeException($e->getMessage());
        }

    }

    /**
     * 异步通知
     */
    public function notify()
    {
        $request = Request::createFromGlobals();

        Logger::info(print_r($request->getContent(), true));

        //检查平台证书序列号
        if($request->headers->get("wechatpay-serial") != $this->config->getPlatformSerialNumber()) {
            Logger::error("error, 平台证书检查不通过");
            return [];
        }

        //验证签名
        $signData = [];
        $signData['timestamp'] = $request->headers->get("wechatpay-timestamp");
        $signData['nonce']     = $request->headers->get("wechatpay-nonce");
        $signData['content']   = $request->getContent();

        $sign_str = $signData['timestamp']."\n".$signData['nonce']."\n".$signData['content']."\n"; //验证签名字符串
        
        //获取应答签名
        $signature = base64_decode($request->headers->get("wechatpay-signature"));

        try {

            //获取平台公钥
            $publicKey = openssl_pkey_get_public(file_get_contents($this->config->getPlatformCertPath()));
            $retCode = openssl_verify($sign_str, $signature, $publicKey, OPENSSL_ALGO_SHA256);

            if($retCode != 1) {
                Logger::error("error, 签名错误");
                return [];
            }

            //对商户resource对象解密
            $data = json_decode($signData['content'], true);
            $aesUtil = new AesUtil($this->config->getV3Key());
            $ciphertext = $aesUtil->decryptToString($data['resource']['associated_data'], 
            $data['resource']['nonce'],        
            $data['resource']['ciphertext']);

            return json_decode($ciphertext, true);
        } catch (RequestException $e) {
            throw new \RuntimeException($e->getMessage());
        } 
    }

    /**
     * 申请退款接口
     * @param RefundRequest $refundRequest
     */
    public function refund($refundRequest)
    {
        if(! $refundRequest instanceof RefundRequest) {
            throw new InvalidPayException("ERROR REQUEST, must instaof RefundRequest");
        }

        return $this->request($refundRequest);
    }

    /**
     * 单笔退款查询接口
     */
    public function refundInfo($refundInfoRequest)
    {
        if(! $refundInfoRequest instanceof RefundInfoRequest) {
            throw new InvalidPayException("ERROR REQUEST, must instaof RefundRequest");
        }

        return $this->getRequest($refundInfoRequest);
    }
}