<?php


namespace Omnipay\CCBPay\Requests;

use Omnipay\CCBPay\Common\Helper;
use Omnipay\CCBPay\Common\Process;
use Omnipay\CCBPay\Common\Signer;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{
    protected $endpoint = 'https://ch5.dcep.ccb.com/CCBIS/ccbMain_XM?CCB_IBSVersion=V6';
    private   $privateKey;

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getData()
    {

    }

    protected function verify($signStr, $sign)
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

        $body = http_build_query($data);

        $response = $this->httpClient->request('POST', $this->getEndpoint(), $headers, $body)->getBody();
        // $payload  = Helper::xml2array($response);

        return $response;
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
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param $privateKey
     *
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

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

}
