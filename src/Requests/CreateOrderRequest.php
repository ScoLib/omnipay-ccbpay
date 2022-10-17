<?php


namespace Omnipay\CCBPay\Requests;


use Omnipay\CCBPay\Common\Signer;
use Omnipay\CCBPay\Responses\CreateOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CreateOrderRequest extends BaseAbstractRequest
{

    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'MERCHANTID',
            'POSID',
            'BRANCHID',
            'ORDERID',
            'PAYMENT',
            'CURCODE',
            'TXCODE'
        );
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getData()
    {
        $this->validateParams();

        $data = $this->parameters->all();

        $data['MAC'] = (new Signer(http_build_query($data)))->setIgnores(['Mrch_url'])->signMac();

        return $data;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getParameter('ORDERID');
    }

    /**
     * 由商户提供，最长30位
     *
     * @param mixed $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setParameter('ORDERID', $orderId);
    }

    /**
     * @return mixed
     */
    public function getMrchUrl()
    {
        return $this->getParameter('Mrch_url');
    }

    /**
     * 退出支付流程时返回商户URL
     *
     * @param mixed $mrchUrl
     * @return $this
     */
    public function setMrchUrl($mrchUrl)
    {
        return $this->setParameter('Mrch_url', $mrchUrl);
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->getParameter('PAYMENT');
    }

    /**
     * 付款金额
     *
     * @param mixed $payment
     * @return $this
     */
    public function setPayment($payment)
    {
        return $this->setParameter('PAYMENT', $payment);
    }

    /**
     * @return mixed
     */
    public function getCurcode()
    {
        return $this->getParameter('CURCODE');
    }

    /**
     * 缺省为01－人民币（只支持人民币支付）
     *
     * @param mixed $curCode
     * @return $this
     */
    public function setCurCode($curCode)
    {
        return $this->setParameter('CURCODE', $curCode);
    }

    /**
     * @return mixed
     */
    public function getTxCode()
    {
        return $this->getParameter('TXCODE');
    }


    /**
     * 交易码 HT0000
     *
     * @param $txCode
     * @return $this
     */
    public function setTxCode($txCode)
    {
        return $this->setParameter('TXCODE', $txCode);
    }

    /**
     * @return mixed
     */
    public function getRemark1()
    {
        return $this->getParameter('REMARK1');
    }


    /**
     * 备注1
     *
     * @param mixed $remark1
     * @return $this
     */
    public function setRemark1($remark1)
    {
        return $this->setParameter('REMARK1', $remark1);
    }

    /**
     * @return mixed
     */
    public function getRemark2()
    {
        return $this->getParameter('REMARK2');
    }


    /**
     * 备注2
     *
     * @param mixed $remark2
     * @return $this
     */
    public function setRemark2($remark2)
    {
        return $this->setParameter('REMARK2', $remark2);
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->getParameter('TIMEOUT');
    }


    /**
     * 订单超时时间
     *
     * 格式： YYYYMMDDHHMMSS
     * 如：20120214143005
     *
     * @param mixed $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        return $this->setParameter('TIMEOUT', $timeout);
    }


    /**
     * @param mixed $data
     * @return array|mixed|\Omnipay\CCBPay\Responses\CreateOrderResponse|\Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new CreateOrderResponse($this, $payload);
    }
}
