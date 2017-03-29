<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

use P\AdminBundle\Entity\AdminMenu as AdminMenuEntity;

class AdminMenu
{
    private $container;
    private $em;
    private $excludeScanRoutes;

    public function __construct(ContainerInterface $container, $em, array $excludeScanRoutes = array())
    {
        $this->container = $container;
        $this->em = $em;
        $this->excludeScanRoutes = array_merge(array(
            '^_',
            '^adminmenu',
            '^p_admin_config',
            '^fos_user',
            '^fos_oauth',
            ), $excludeScanRoutes
        );
    }

    public function getRooeMenus()
    {
        $result = $this->em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('am')
            ->where('am.parent IS NULL')
            ->andWhere('am.enabled = 1')
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            ->getResult()
            ;
        return $result;

    }

    public function scan()
    {
        $router = $this->container->get('router')->getRouteCollection();

        foreach($router->all() as $name => $route) {
            $excluded = false;
            foreach($this->excludeScanRoutes as $exclude) {
                if(preg_match(sprintf("/%s/", $exclude), $name)) {
                    $excluded = true;
                    break;
                }
            }
            if(!$excluded) {
                $adminMenu = $this->em->getRepository('PAdminBundle:AdminMenu')->findOneByRoute($name);
                if($adminMenu) continue;
                $adminMenu = new AdminMenuEntity();
                $adminMenu->setName($name);
                $adminMenu->setText($name);
                $adminMenu->setRoute($name);
                $adminMenu->setEnabled(0);
                $this->em->persist($adminMenu);
            }
        }

        $this->em->flush();
    }
}
