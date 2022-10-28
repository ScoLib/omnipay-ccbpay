<?php


namespace Omnipay\CCBPay\Requests;



use Omnipay\CCBPay\Responses\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends BaseAbstractRequest
{

    public function getData()
    {
        $this->validateParams();

        return $this->getParams();
    }


    public function validateParams()
    {
        $this->validate('params');
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->getParameter('params');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setParams($value)
    {
        return $this->setParameter('params', $value);
    }

    public function sendData($data)
    {
        $params = $data;

        $sign = $params['SIGN'];
        unset($params['SIGN']);

        $match = $this->verify(http_build_query($params), $sign);

        if (! $match) {
            throw new InvalidRequestException('The signature is not match');
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function verify($signStr, $sign)
    {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(base64_encode(hex2bin($this->getPublicKey())), 64)
            . '-----END PUBLIC KEY-----';

        $res = openssl_pkey_get_public($publicKey);

        return openssl_verify($signStr, hex2bin($sign), $res, OPENSSL_ALGO_MD5) == 1;
    }

}
