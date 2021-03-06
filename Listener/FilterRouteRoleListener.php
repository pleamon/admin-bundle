<?php

namespace P\AdminBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FilterRouteRoleListener
{
    var $container;
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event) {
        $this->checkRouteRole($event);
    }

    public function checkRouteRole(FilterControllerEvent $event) {

        $controllers = $event->getController();
        $controller = $controllers[0];
        if(!$controller instanceof Controller) {
            return;
        }

        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();
        $router = $this->container->get('router')->getRouteCollection();

        $_route = $request->get('_route');
        $route = $router->get($_route);
        $routeOptions = $route->getOptions();
        
        if(array_key_exists('role', $routeOptions)) {
            $roles = $routeOptions['role'];
            $security = $this->container->get('security.context');
            $user = $security->getToken()->getUser();
            if(!array_intersect($user->getRoles(), $roles)) {
                throw new AccessDeniedException();
            }
        }
    }
}
