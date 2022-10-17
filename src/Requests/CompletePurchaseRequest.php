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

        // $match = $this->verify($signStr, $data['signData']);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }


}
