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

    public function getMenus()
    {
        return $this->container->getParameter('p_admin.menus');
    }

    public function getTitle()
    {
        return $this->container->getParameter('p_admin_title');
    }

    public function getRoute($route)
    {
        return $this->container->get('router')->getRouteCollection()->get($route);
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

}
