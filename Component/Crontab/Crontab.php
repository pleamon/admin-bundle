<?php

namespace P\AdminBundle\Component\Crontab;

class Crontab
{
    private $container;
    private $em;

    public function __construct($container, $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getSchedules()
    {
        return $this->em->getRepository('PAdminBundle:Crontab')->findByStatus(true);
    }
}
