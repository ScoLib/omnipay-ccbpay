<?php


namespace Omnipay\CCBPay\Requests;

use Omnipay\CCBPay\Common\Helper;
use Omnipay\CCBPay\Common\Process;
use Omnipay\CCBPay\Common\Signer;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{
    protected $endpoint;
    private   $publicKey;

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getData()
    {

    }

    /**
     * @param mixed $data
     * @return array|mixed|\Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $data = array_merge(['CCB_IBSVersion' => 'V6'], $data);

        $body = http_build_query($data);

        $response = $this->httpClient->request('POST', $this->getEndpoint(), $headers, $body)->getBody();

        return json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        $this->endpoint = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param $publicKey
     *
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('MERCHANTID');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('MERCHANTID', $value);
    }

    /**
     * @return mixed
     */
    public function getPosId()
    {
        return $this->getParameter('POSID');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setPosId($value)
    {
        return $this->setParameter('POSID', $value);
    }

    /**
     * @return mixed
     */
    public function getBranchId()
    {
        return $this->getParameter('BRANCHID');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setBranchId($value)
    {
        return $this->setParameter('BRANCHID', $value);
    }
}
