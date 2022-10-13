<?php

namespace Omnipay\CCBPay\Common;

use Exception;

/**
 */
class Signer
{
    const ENCODE_POLICY_QUERY = 'QUERY';
    const ENCODE_POLICY_JSON = 'JSON';

    protected $ignores = ['SIGN'];

    protected $sort = true;

    protected $encodePolicy = self::ENCODE_POLICY_QUERY;

    /**
     * @var array
     */
    private $params;


    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function signWithDES($key, $md5Key)
    {
        $content = $this->getContentToSign();

        $md5Str = $content . $md5Key;
        $mac = md5($md5Str);
        $content .= '&SIGN=' . $mac;

        $content = mb_convert_encoding($content, 'UTF-16BE', 'utf-8');

        $content = hex2bin('FEFF' . bin2hex($content)); // BOM

        return urlencode(
            str_replace(
                '+',
                ',',
                $this->encrypt($content, $this->convertKey($key))
            )
        );
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


    public function getContentToSign()
    {
        $params = $this->getParamsToSign();

        if ($this->encodePolicy == self::ENCODE_POLICY_QUERY) {
            return urldecode(http_build_query($params));
        } elseif ($this->encodePolicy == self::ENCODE_POLICY_JSON) {
            return json_encode($params);
        } else {
            return null;
        }
    }


    /**
     * @return mixed
     */
    public function getParamsToSign()
    {
        $params = $this->params;

        $this->unsetKeys($params);

        $params = $this->filter($params);

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
     * @param int $encodePolicy
     *
     * @return Signer
     */
    public function setEncodePolicy($encodePolicy)
    {
        $this->encodePolicy = $encodePolicy;

        return $this;
    }
}
