<?php

namespace P\AdminBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;

use P\AdminBundle\Entity\AdminMenu as AdminMenuEntity;

class Menu
{
    private $router;
    private $em;
    private $excludeScanRoutes;

    public function __construct($router, $em, array $excludeScanRoutes = array())
    {
        $this->router = $router;
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

    public function getRootMenus()
    {
        $result = $this->em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('am')
            ->where('am.parent IS NULL')
            ->andWhere('am.enabled = 1')
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            ->useResultCache(true, 86400, 'admin_menus')
            ->getResult()
            ;
        return $result;

    }

    public function scan()
    {
        $routes = $this->router->getRouteCollection()->all();

        foreach($routes as $name => $route) {
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
