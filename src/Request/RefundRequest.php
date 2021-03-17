<?php
/**
 * 退款请求
 */
namespace Xiaoming\Wechatpay\Request;

class RefundRequest implements RequestInterface
{
    /**
     * 微信支付订单号
     */
    private $transactionId = '';

    public function setTransactionId($transactionId = '') {
        $this->transactionId = $transactionId;
    }

    /**
     * 商户订单号
     */
    private $outTradeNo = '';

    public function setOutTradeNo($outTradeNo = '') {
        $this->outTradeNo = $outTradeNo;
    }

    /**
     * 商户退款单号
     */
    private $outRefundNo = '';

    public function setOutRefundNo($outRefundNo = '') {
        $this->outRefundNo = $outRefundNo;
    }

    /**
     * 退款原因
     */
    private $reason = '';

    public function setReason($reason = '') {
        $this->reason = $reason;
    }

    /**
     * 退款结果回调url
     */
    private $notifyUrl = '';

    public function setNotifyUrl($notifyUrl = '') {
        $this->notifyUrl = $notifyUrl;
    }

    /**
     * 金额信息,退款金额
     */
    private $refund = 0;

    public function setRefundFee($refund = '') {
        $this->refund = $refund;
    }

    /**
     * 金额信息,原订单金额
     */
    private $total = 0;

    public function setTotalFee($total = '') {
        $this->total = $total;
    }

    /**
     * 退款商品信息
     */
    private $goodsDetail = [];

    public function getRequestUri()
    {
        return "https://api.mch.weixin.qq.com/v3/refund/domestic/refunds";
    }

    /**
     * 获取支付请求参数
     */
    public function getRequestData()
    {
        $data = [];

        if(!empty($this->transactionId)) {
            $data['transaction_id'] = $this->transactionId;
        }

        if(!empty($this->outTradeNo)) {
            $data['out_trade_no'] = $this->outTradeNo;
        }

        $data['out_refund_no'] = $this->outRefundNo;
        $data['reason']        = $this->reason;
        $data['notify_url']    = $this->notifyUrl;
        $data['amount'] = [

            'refund' => $this->refund,
            'total'  => $this->total,
            'currency' => 'CNY',
        ];

        return $data;
    }
}