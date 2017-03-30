<?php

namespace P\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use P\UserBundle\Entity\Role;

class LoadUserRoleData implements FixtureInterface, ContainerAwareInterface
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
            $role = $manager->getRepository('PUserBundle:Role')->findOneByName($data[1]);
            if($role) continue;
            $role = new Role();
            $role->setName($data[1]);
            if($data[0]) {
                $parent = $manager->getRepository('PUserBundle:Role')->findOneByName($data[0]);
                $role->setParent($parent);
            }
            $manager->persist($role);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    public function getData()
    {
        return array(
            array(null, 'ROLE_SUPERADMIN'),
            array('ROLE_SUPERADMIN', 'ROLE_ADMIN'),
            array('ROLE_ADMIN', 'ROLE_MEMBER'),
            array('ROLE_MEMBER', 'ROLE_USER'),
        );
    }
}
