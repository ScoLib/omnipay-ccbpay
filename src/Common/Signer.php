<?php

namespace Omnipay\CCBPay\Common;

use Exception;

/**
 */
class Signer
{
    protected $ignores = ['SIGN'];

    protected $sort = true;

    protected $filter = true;

    /**
     * @var string
     */
    private $params;

    public function __construct(string $params)
    {
        $this->params = $params;
    }

    public function signWithDES($key)
    {
        $str = $this->params;

        $str .= '&SIGN=' . $this->signMac();

        $str = mb_convert_encoding($str, 'UTF-16BE', 'utf-8');

        $str = hex2bin('FEFF' . bin2hex($str)); // BOM

        return urlencode(
            str_replace(
                '+',
                ',',
                $this->encrypt($str, $this->convertKey($key))
            )
        );
    }

    public function signMac()
    {
        $content = $this->getContentToMac();

        $md5Str = $content . '20120315201809041004';
        return md5($md5Str);
    }

    private function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function encrypt($data, $key)
    {
        $data = $this->pkcs5Pad($data, 8);
        $data = openssl_encrypt(
            $data,
            'DES-ECB',
            $key,
            OPENSSL_RAW_DATA | OPENSSL_NO_PADDING
        );

        return base64_encode($data);
    }

    public function getContentToMac()
    {
        $params = $this->getParamsToMac();

        return http_build_query($params);
    }

    /**
     * @return mixed
     */
    public function getParamsToMac()
    {
        parse_str($this->params, $params);

        $this->unsetKeys($params);

        if ($this->filter) {
            $params = $this->filter($params);
        }

        if ($this->sort) {
            $this->sort($params);
        }

        return $params;
    }


    /**
     * @param $params
     */
    protected function unsetKeys(&$params)
    {
        foreach ($this->getIgnores() as $key) {
            unset($params[$key]);
        }
    }

    /**
     * @return array
     */
    public function getIgnores()
    {
        return $this->ignores;
    }

    /**
     * @param array $ignores
     *
     * @return $this
     */
    public function setIgnores($ignores)
    {
        $this->ignores = $ignores;

        return $this;
    }

    private function filter($params)
    {
        return array_filter($params, 'strlen');
    }

    /**
     * @param $params
     */
    protected function sort(&$params)
    {
        ksort($params);
    }


    /**
     * @param $key
     * @return string
     */
    public function convertKey($key)
    {
        if (strlen($key) >= 30) {
            $key = mb_substr($key, strlen($key) - 30);
        }

        if (strlen($key) >= 8) {
            $key = mb_substr($key, 0, 8);
        }

        return $key;
    }

    /**
     * @param boolean $sort
     *
     * @return Signer
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param boolean $filter
     *
     * @return Signer
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }
}
