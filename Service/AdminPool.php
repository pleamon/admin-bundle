<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AdminPool
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($name)
    {
        return $this->container->get($name);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getTitle()
    {
        return $this->container->getParameter('p_admin_title');
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function getMenuParents()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $result = $em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('am')
            ->where('am.parent IS NULL')
            ->orderBy('am.sort', 'desc')
            ->getQuery()
            ->getResult()
            ;
        return $result;
    }
}
