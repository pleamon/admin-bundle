<?php

namespace P\AdminBundle\Component\Gravatar;

class Gravatar
{
    private $gravatarUrl = 'https://www.gravatar.com';
    private $email;
    private $default;
    private $size;

    public function setInfo($email, $default = '', $size = 50)
    {
        if($email) {
            $this->email = $email;
        }

        if($default) {
            $this->default = $default;
        }

        if($size) {
            $this->size = $size;
        }
    }

    public function generateUrl($email, $default = '', $size = 50)
    {
        $this->setInfo($email, $default, $size);
        $url = sprintf("%s/avatar/%s?d=%s&s=%d", $this->gravatarUrl, md5(strtolower(trim($this->email))), $this->default, $this->size);
        return $url;
    }
}
