<?php

namespace P\AdminBundle\Component\Baidu;

use P\AdminBundle\Entity\AdminConfig;

class Config
{
    public function registerConfig()
    {
        return array(
            new AdminConfig('baidu', 'ak'),
            new AdminConfig('baidu', 'sk'),
        );
    }
}
