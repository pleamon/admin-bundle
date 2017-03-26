<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminConfig
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
