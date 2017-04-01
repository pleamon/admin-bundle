<?php

namespace P\AdminBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;

use P\AdminBundle\Entity\AdminMenu as AdminMenuEntity;

class Menu
{
    public const CACHE_REGION = 'p.admin.menu';

    private $container;
    private $router;
    private $em;
    private $excludeScanRoutes;
    private $metadataCacheDriver;
    private $queryCacheDriver;
    private $resultCacheDriver;

    public function __construct($container, $router, $em, $route, $metadataCacheDriver, $queryCacheDriver, $resultCacheDriver)
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
        $this->metadataCacheDriver = $metadataCacheDriver;
        $this->queryCacheDriver = $queryCacheDriver;
        $this->resultCacheDriver = $resultCacheDriver;
    }

    public function getRootMenus()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $logger = $this->container->get('logger');

        $result = $this->em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('am')
            ->where('am.parent IS NULL AND am.enabled = 1')
            ->leftJoin('am.roles', 'r')
            ->andWhere('r.name in (:roles) OR r.id IS NULL')
            ->setParameter('roles', array_map(function($role) {return $role->getRole();}, $token->getRoles()))
            ->orderBy('am.sort', 'asc')
            ->getQuery()
            //->useResultCache(true, 86400, self::CACHE_REGION)
            ->getResult()
            ;
        return $result;
    }

    public function clearCache()
    {
        $this->resultCacheDriver->delete(self::CACHE_REGION);
        $this->container->get('logger')->info('clear menu cache');
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
