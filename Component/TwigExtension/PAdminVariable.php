<?php

namespace P\AdminBundle\Component\TwigExtension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PAdminVariable
{
    private $container;
    private $em;
    private $favicon;
    private $title;
    private $copyright;
    private $description;
    private $search;
    private $template;

    public function __construct(ContainerInterface $container, $em, $favicon, $title, $description, $copyright, $search, $template)
    {
        $this->container = $container;
        $this->favicon = $favicon;
        $this->title = $title;
        $this->description = $description;
        $this->copyright = $copyright;
        $this->search = $search;
        $this->template;
    }

    public function get($name)
    {
        return $this->container->get($name);
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    public function getFavicon()
    {
        return $this->favicon;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCopyright()
    {
        return $this->copyright;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getMenuParents()
    {
        $result = $this->em->getRepository('PAdminBundle:AdminMenu')->getRoots();
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
