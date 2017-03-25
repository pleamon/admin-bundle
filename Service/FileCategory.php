<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FileCategory
{
    protected $container;
    protected $em;
    public function __construct(ContainerInterface $container, $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function get($name, $keyName, $dir)
    {
        if(empty($category = $this->em->getRepository('PAdminBundle:FileCategory')->findOneByKeyName('rd'))) {
            $category = new FileCategory('需求文档', 'rd', 'rd');
            $this->em->getEntityManager()->persist($category);
            $this->em->getEntityManager()->flush();
        }
        return $category;
    }
}
