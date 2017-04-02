<?php

namespace P\AdminBundle\Component\Notification;

class Notification
{
    private $em;
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getCount($level = null, $nkey = null)
    {
        $count = $this->em->getRepository('PAdminBundle:Notification')->getCount($level, $nkey);
        return $count;
    }

    public function getMessages($level = null, $nkey = null)
    {
        return $this->em->getRepository('PAdminBundle:Notification')->getMessages($level, $nkey);
    }
}
