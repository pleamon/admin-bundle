<?php

namespace P\AdminBundle\Component\Core;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Core
{
    private $container;
    private $em;

    public function __construct($container, $em)
    {
        $this->container = $container;
        $this->em = $em;
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
