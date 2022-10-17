<?php


namespace Omnipay\CCBPay\Responses;


class CompletePurchaseResponse extends BaseAbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        $data = $this->getData();

        return $data && $data['SUCCESS'] == 'Y';
    }
}
