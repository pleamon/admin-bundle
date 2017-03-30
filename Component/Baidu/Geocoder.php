<?php

namespace P\AdminBundle\Component\Baidu;

class BaiduGeoder
{
    private $curl;
    private $ak;
    private $sk;

    const GEOCODER_URL = 'http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s&sn=%s';
    const GEOCODER_URI = '/geocoder/v2/';

    public function __construct($curl, $config)
    {
        $this->curl = $curl;
        $this->ak = $config['ak'];
        $this->sk = $config['sk'];
    }

    private function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET')
    {
        if ($method === 'POST'){
            ksort($querystring_arrays); 
        }
        $querystring = http_build_query($querystring_arrays);
        return md5(urlencode($url.'?'.$querystring.$sk));
    }

    public function addressToGeo($address, $output = 'json')
    {
        $querystringArray = array(
            'address' => $address,
            'output' => $output,
            'ak' => $this->ak
        );

        $sn = $this->caculateAKSN($this->ak, $this->sk, self::GEOCODER_URI, $querystringArray);

        $target = sprintf(self::GEOCODER_URL, urlencode($address), $output, $this->ak, $sn);

        $result = json_decode($this->curl->get($target));

        if($result->status) {
            throw new Exception('baidu geocoder error');
        }

        return $result->result;
    }
}
