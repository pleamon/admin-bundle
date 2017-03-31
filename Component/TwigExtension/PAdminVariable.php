<?php

namespace P\AdminBundle\Component\TwigExtension;

use Symfony\Component\DependencyInjection\ContainerInterface;


class PAdminVariable
{
    private $container;
    private $em;
    private $search;
    private $template;
    private $fs;

    public function __construct(ContainerInterface $container, $search, $template)
    {
        $this->container = $container;
        $this->search = $search;
        $this->template = $template;
        $this->fs = $container->get('p.admin.filesystem');
    }

    public function get($name)
    {
        return $this->container->get($name);
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function getConfig($name, $group)
    {
        return $this->container->get('p.admin.core')->getConfig($name, $group);
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getDiskTotalSpace()
    {
        return $this->fs->bytesToSize(disk_total_space('/'));
    }

    public function getDiskFreeSpace()
    {
        return $this->fs->bytesToSize(disk_free_space('/'));
    }

    public function getColors()
    {
        return array(
            'navy',
            'danger',
            'primary',
            'info',
            'warning',
        );
    }
}
