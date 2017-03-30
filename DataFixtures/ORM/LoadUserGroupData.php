<?php

namespace P\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use P\UserBundle\Entity\Group;

class LoadUserGroupData implements FixtureInterface, ContainerAwareInterface
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
        foreach($datas as $name => $roles) {
            $group = $manager->getRepository('PUserBundle:Group')->findOneByName($name);
            if($group) continue;
            $group = new Group();
            $group->setName($name);

            foreach($roles as $role) {
                $role = $manager->getRepository('PUserBundle:Role')->findOneByName($role);
                $group->addRole($role);
            }
            $manager->persist($group);
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
            '超级管理员' => array(
                'ROLE_SUPERADMIN',
            ),
            '管理员' => array(
                'ROLE_ADMIN'
            ),
            '后台用户' => array(
                'ROLE_MEMBER',
            ),
            '普通用户' => array(
                'ROLE_USER',
            )
        );
    }
}
