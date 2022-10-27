<?php


namespace Omnipay\CCBPay\Requests;


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

        $this->setDefaults();

        // 以下data数据 有顺序要求
        // MERCHANTID=123456789&POSID=000000000&BRANCHID=110000000&SUB_MERCHANTID=123456789&ORDERID=19991101234&PAYMENT=0.01&CdtrWltId=050000132&CURCODE=01&TXCODE=HT0000&REMARK1=&REMARK2=&RETURNTYPE=1&TIMEOUT=&PUB=30819d300d06092a864886f70d0108& Mrch_url= https://Ch5.dcep.ccb.com
        $data = [
            'MERCHANTID' => $this->getMerchantId(),
            'POSID' => $this->getPosId(),
            'BRANCHID' => $this->getBranchId(),
        ];

        if ($subMid = $this->getSubMerchantid()) {
            $data['SUB_MERCHANTID'] = $subMid;
        }

        $data['ORDERID'] = $this->getOrderId();
        $data['PAYMENT'] = $this->getPayment();

        if ($CdtrWltId = $this->getCdtrWltId()) {
            $data['CdtrWltId'] = $CdtrWltId;
        }

        $data['CURCODE'] = $this->getCurcode();
        $data['TXCODE'] = $this->getTxCode();
        $data['REMARK1'] = $this->getRemark1();
        $data['REMARK2'] = $this->getRemark2();
        $data['RETURNTYPE'] = $this->getReturntype();
        $data['TIMEOUT'] = $this->getTimeout();
        $data['PUB'] = mb_substr($this->getPublicKey(), -30);

        $data['MAC'] = md5(http_build_query($data));

        if ($mrchUrl = $this->getMrchUrl()) {
            $data['Mrch_url'] = $mrchUrl;
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @return \Omnipay\CCBPay\Responses\CreateOrderResponse|\Omnipay\Common\Message\ResponseInterface
     * @throws \Exception
     */
    public function sendData($data)
    {
        $data = array_merge(['CCB_IBSVersion' => 'V6'], $data);

        return $this->response = new CreateOrderResponse($this, $data);
    }

    protected function setDefaults()
    {
        if (!$this->getRemark1()) {
            $this->setRemark1('');
        }

        if (!$this->getRemark2()) {
            $this->setRemark2('');
        }

        if (!$this->getReturntype()) {
            $this->setReturntype('');
        }
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
     * @return mixed
     */
    public function getReturntype()
    {
        return $this->getParameter('RETURNTYPE');
    }


    /**
     * 1：返回JSON格式【二维码信息串】
     *
     * @param mixed $value
     * @return $this
     */
    public function setReturntype($value)
    {
        return $this->setParameter('RETURNTYPE', $value);
    }

    /**
     * @return mixed
     */
    public function getSubMerchantid()
    {
        return $this->getParameter('SUB_MERCHANTID');
    }


    /**
     * 二级商户代码
     *
     * @param mixed $value
     * @return $this
     */
    public function setSubMerchantid($value)
    {
        return $this->setParameter('SUB_MERCHANTID', $value);
    }

    /**
     * @return mixed
     */
    public function getCdtrWltId()
    {
        return $this->getParameter('CdtrWltId');
    }

    /**
     * 商户结算账号 央行app使用
     *
     * @param mixed $value
     * @return $this
     */
    public function setCdtrWltId($value)
    {
        return $this->setParameter('CdtrWltId', $value);
    }


}
