<?php

namespace Omnipay\CCBPay;

use Omnipay\CCBPay\Requests\CompletePurchaseRequest;
use Omnipay\CCBPay\Requests\CreateOrderRequest;
use Omnipay\CCBPay\Requests\QueryOrderRequest;
use Omnipay\CCBPay\Requests\RefundOrderRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractGateway extends AbstractGateway
{
    protected $endpoints = [
        'production' => 'https://ch5.dcep.ccb.com/CCBIS/ccbMain_XM?CCB_IBSVersion=V6',
        'test'       => 'http://128.196.119.53:8101/CCBIS/ccbMain_XM?CCB_IBSVersion=V6',
    ];

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
    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
    }

    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function production()
    {
        return $this->setEnvironment('production');
    }


    /**
     * @param $value
     *
     * @return $this
     * @throws InvalidRequestException
     */
    public function setEnvironment($value)
    {
        $env = strtolower($value);

        if (!isset($this->endpoints[$env])) {
            throw new InvalidRequestException('The environment is invalid');
        }

        $this->setEndpoint($this->endpoints[$env]);

        return $this;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }


    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function test()
    {
        return $this->setEnvironment('test');
    }

    /**
     * @param array $parameters
     * @return \Omnipay\CCBPay\Requests\CreateOrderRequest
     */
    public function purchase($parameters = array())
    {
        return $this->createRequest(CreateOrderRequest::class, $parameters);
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
     * @return \Omnipay\CCBPay\Requests\QueryOrderRequest
     */
    public function query($parameters = array())
    {
        return $this->createRequest(QueryOrderRequest::class, $parameters);
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
