<?php


namespace Omnipay\CCBPay\Requests;


use Omnipay\CCBPay\Common\Signer;
use Omnipay\CCBPay\Responses\DCEPQueryOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class DCEPQueryOrderRequest extends BaseAbstractRequest
{

    protected $endpoint = 'https://ch5.dcep.ccb.com/CCBIS/B2CMainPlat_00_PD_BEPAY';

    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'MERCHANTID',
            'POSID',
            'BRANCHID',
            'Ordr_ID',
            'TXCODE'
        );
    }

    public function getData()
    {
        $this->validateParams();

        $data = $this->parameters->all();
        $str = http_build_query([
            'TXCODE' => $this->getTxCode(),
            'Ordr_ID' => $this->getOrdrId(),
            'SYS_TX_STATUS' => $this->getSysTxStatus(),
        ]);

        $data['ccbParam'] = (new Signer($str))->signWithDES($this->getPublicKey());

        return $data;
    }

    /**
     *
     * @param mixed $data
     * @return \Omnipay\CCBPay\Responses\DCEPQueryOrderResponse
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new DCEPQueryOrderResponse($this, $payload);
    }

    /**
     * @return mixed
     */
    public function getOrdrId()
    {
        return $this->getParameter('Ordr_ID');
    }

    /**
     * 订单号
     *
     * @param mixed $ordrId
     * @return $this
     */
    public function setOrdrId($ordrId)
    {
        return $this->setParameter('Ordr_ID', $ordrId);
    }

    /**
     * @return mixed
     */
    public function getTxCode()
    {
        return $this->getParameter('TXCODE');
    }

    /**
     * 交易码 固定值 PDPCX0
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
    public function getSysTxStatus()
    {
        return $this->getParameter('SYS_TX_STATUS');
    }

    /**
     * 支付结果筛选用，如果不送则不筛选：
     * 00-成功
     * 01-失败
     * 02-不确定
     *
     * @param $value
     * @return $this
     */
    public function setSysTxStatus($value)
    {
        return $this->setParameter('SYS_TX_STATUS', $value);
    }
}
