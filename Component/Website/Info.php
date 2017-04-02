<?php

namespace P\AdminBundle\Component\Website;

use P\AdminBundle\Entity\AdminConfig;

class Info
{
    public function registerConfig()
    {
        return array(
            new AdminConfig('Website', 'title', null, null, 'P Admin', '浏览器标签名称'),
            new AdminConfig('Website', 'name', null, null, 'P Admin System', '系统名称'),
            new AdminConfig('Website', 'description', null, null, 'P Admin System Description', '项目描述'),
            new AdminConfig('Website', 'copyright', null, null, 'PAdmin we app framework base on Symfony 3 © 2017', 'Copyright'),
        );
    }
}
