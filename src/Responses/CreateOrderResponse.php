<?php

namespace Omnipay\CCBPay\Responses;

use Omnipay\Common\Message\RedirectResponseInterface;

class CreateOrderResponse extends BaseAbstractResponse implements RedirectResponseInterface
{

    /**
     * @var \Omnipay\CCBPay\Requests\CreateOrderRequest
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }


    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return $this->getData();
    }
}
