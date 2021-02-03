<?php

namespace Xiaoming\Wechatpay\Pay;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WechatPay\GuzzleMiddleware\Util\PemUtil;
use WechatPay\GuzzleMiddleware\Util\AesUtil;
use WechatPay\GuzzleMiddleware\WechatPayMiddleware;
use Xiaoming\Wechatpay\Config;
use Xiaoming\Wechatpay\Exceptions\InvalidPayException;
use Xiaoming\Wechatpay\NoopValidator;

class GetPlatformCert extends BasePay implements PayInterface {

    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->getCertFile();
    }

    public function order($request) {
        throw new InvalidPayException("not use order method");
    }

    /**
     * 下载证书，此证书非商户平台部署的证书。而是请求接口时的证书
     * 下载后请保存证书到某个文件中以供调用
     * 此方法直接打印出来
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
        } 
    }
}