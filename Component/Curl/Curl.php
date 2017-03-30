<?php

namespace P\AdminBundle\Component\Curl;

class Curl
{
    private $ch = null;

    public function init()
    {
        $this->ch = curl_init();
    }

    public function setOpt($key, $value)
    {
        curl_setopt($this->ch, $key, $value);
    }

    public function setOpts($opts)
    {
        curl_setopt_array($this->ch, $opts);
    }

    public function exec()
    {
        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }

    public function get($url, $options = array())
    {
        $this->init();
        $this->setOpts($options);
        $this->setOpt(CURLOPT_RETURNTRANSFER, 1);
        $this->setOpt(CURLOPT_URL, $url);

        return $this->exec();
    }

    public function post($url, $data = array(), $options = array())
    {
        $this->init();
        $this->setOpts(array_merge(array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => $data,
        ), $options));

        return $this->exec();
    }
}
