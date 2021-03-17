<?php
/**
 * 单笔退款查询接口
 */
namespace Xiaoming\Wechatpay\Request;

class RefundInfoRequest implements RequestInterface
{
    /**
     * 单笔退款商户单号
     */
    private $out_refund_no = '';

    public function setOutRefundNo($out_refund_no = '') {
        $this->out_refund_no = $out_refund_no;
    }

    public function getRequestUri()
    {
        return sprintf("https://api.mch.weixin.qq.com/v3/refund/domestic/refunds/%s", $this->out_refund_no);
    }

    /**
     * 获取支付请求参数
     */
    public function getRequestData()
    {
        return [
            'out_refund_no' => $this->out_refund_no
        ];
    }
}