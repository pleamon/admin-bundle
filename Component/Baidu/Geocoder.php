<?php

namespace P\Component\Baidu;

use P\Component\Http\HttpClient;

class Geocoder
{
    private $ak;
    private $sk;

    const GEOCODER_URL = 'http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s&sn=%s';
    const GEOCODER_URL_V2 = 'http://api.map.baidu.com/geocoder/v2/';
    const GEOCODER_URI = '/geocoder/v2/';

    public function __construct($config)
    {
        $this->ak = $config->get('baidu', 'ak');
        $this->sk = $config->get('baidu', 'sk');
    }

    private function caculateAKSN($ak, $sk, $url, $parameters, $method = 'GET')
    {
        if ($method === 'POST'){
            ksort($parameters); 
        }
        $querystring = http_build_query($parameters);
        return md5(urlencode($url.'?'.$querystring.$sk));
    }

    public function addressToGeo($address, $output = 'json')
    {
        $parameters = array(
            'address' => $address,
            'output' => $output,
            'ak' => $this->ak
        );

        $sn = $this->caculateAKSN($this->ak, $this->sk, self::GEOCODER_URI, $parameters);
        $parameters['sn'] = $sn;

        $rs = HttpClient::post(self::GEOCODER_URL_V2, $parameters);
        if($rs->getErrno()) {
            throw new \Exception($rs->getErrno(), $rs->getErrMsg());
        }
        return $rs->getJson();
    }
}
