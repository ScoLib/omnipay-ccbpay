<?php

namespace Omnipay\CCBPay;

use Omnipay\CCBPay\Requests\CompletePurchaseRequest;
use Omnipay\CCBPay\Requests\DCEPCreateOrderRequest;
use Omnipay\CCBPay\Requests\DCEPQueryOrderRequest;
use Omnipay\CCBPay\Requests\RefundOrderRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractGateway extends AbstractGateway
{

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return mixed
     */
    public function getPosId()
    {
        return $this->getParameter('posId');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setPosId($value)
    {
        return $this->setParameter('posId', $value);
    }

    /**
     * @return mixed
     */
    public function getBranchId()
    {
        return $this->getParameter('branchId');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setBranchId($value)
    {
        return $this->setParameter('branchId', $value);
    }


    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->getParameter('public_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('public_key', $value);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\CCBPay\Requests\DCEPCreateOrderRequest
     */
    public function purchase($parameters = array())
    {
        return $this->createRequest(DCEPCreateOrderRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\CCBPay\Requests\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     *
     * @param array $parameters
     *
     * @return \Omnipay\CCBPay\Requests\DCEPQueryOrderRequest
     */
    public function query($parameters = array())
    {
        return $this->createRequest(DCEPQueryOrderRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\CCBPay\Requests\RefundOrderRequest
     */
    public function refund($parameters = array())
    {
        return $this->createRequest(RefundOrderRequest::class, $parameters);
    }
}
