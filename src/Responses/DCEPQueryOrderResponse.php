<?php

namespace Omnipay\CCBPay\Responses;

class DCEPQueryOrderResponse extends BaseAbstractResponse
{

    /**
     * @var CreateOrderRequest
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        $data = $this->getData();
        //{
        //     "SUCCESS": "true",
        //     "Detail_Grp": [
        //         {
        //             "STATUSCODE": "00",
        //             "ORDERID": "xxxx",
        //             "AMOUNT": "1",
        //             "ORDERDATE": "xxxx",
        //             "ACCNAME": "xx**",
        //             "Pref_Amt": "0.00",
        //             "CmAvy_Cntnt": ""
        //         }
        //     ]
        // }
        return $data && $data['SUCCESS'] == true && !empty($data['Detail_Grp']);
    }

    public function isPaid()
    {
        $data = $this->getData();
        if ($data) {
            return !!count(array_filter($data['Detail_Grp'], function ($i) {
                return $i['STATUSCODE'] == '00'; // 成功
            }));
        }
    }

    public function getOrderInfo()
    {
        $data = $this->getData();

        return $data['Detail_Grp'];
    }
}
