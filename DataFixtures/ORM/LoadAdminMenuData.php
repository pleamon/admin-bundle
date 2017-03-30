<?php

namespace P\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use P\AdminBundle\Entity\AdminMenu;

class LoadAdminMenuData implements FixtureInterface, ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $icons = array();
        $datas = $this->getData();
        foreach($datas as $type => $data) {
            foreach($data as $name) {
                $icon = new Icon($type, $name);
                $manager->persist($icon);
            }
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

    public function getData()
    {
        return array(
            
        );
    }
}
