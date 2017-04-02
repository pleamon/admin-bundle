<?php

namespace P\AdminBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;

use P\AdminBundle\Component\Menu\Event\MenuEvent;
use P\AdminBundle\Entity\AdminMenu as AdminMenu;

class Menu
{
    public const CACHE_REGION = 'p.admin.menu';

    private $container;
    private $router;
    private $em;
    private $excludeScanRoutes;
    private $resultCacheDriver;

    public function __construct($container, $router, $em, $route, $resultCacheDriver)
    {
        $this->container = $container;
        $this->router = $router;
        $this->em = $em;
        $this->excludeScanRoutes = array_merge(array(
            '^_',
            '^adminmenu',
            '^p_admin_config',
            '^fos_user',
            '^fos_oauth',
            ), $route['exclude']
        );
        $this->resultCacheDriver = $resultCacheDriver;
    }

    public function getRootMenus()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $logger = $this->container->get('logger');

        $menus = $this->em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('am')
            ->where('am.parent IS NULL AND am.enabled = 1')
            ->leftJoin('am.roles', 'r')
            ->andWhere('r.name in (:roles) OR r.id IS NULL')
            ->setParameter('roles', array_map(function($role) {return $role->getRole();}, $token->getRoles()))
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            ->useResultCache(true, 86400, self::CACHE_REGION)
            ->getResult()
            ;

        $menuEvent = new MenuEvent($menus);
        $this->container->get('event_dispatcher')->dispatch('p.menu.load', $menuEvent);

        return $menuEvent->getMenus();
    }

    public function clearCache()
    {
        $this->resultCacheDriver->delete(self::CACHE_REGION);
    }

    public function scan()
    {
        $routes = $this->router->getRouteCollection()->all();

        foreach($routes as $name => $route) {
            $options = $route->getOptions();
            if(array_key_exists('default', $options)) {
                continue;
            }
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
                $adminMenu = new AdminMenu();
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
