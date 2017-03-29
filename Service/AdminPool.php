<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
            ->andWhere('am.enabled = 1')
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            ->getResult()
            ;
        return $result;
    }

    public function entityToJson($source)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);   

        $jsonContent = $serializer->serialize($source, 'json');

        return $jsonContent;
    }
}
