<?php

namespace P\AdminBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FilterRouteRoleListener
{
    private $container;
    private $em;
    private $authorizationChecker;
    private $request;
    public function __construct(ContainerInterface $container, $em, $authorizationChecker, $requestStack) {
        $this->container = $container;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->request = $requestStack->getCurrentRequest();
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

        $route = $this->request->get('_route');
        $menu = $this->em->getRepository('PAdminBundle:AdminMenu')->findOneByRoute($route);

        if(empty($menu)) {
            return;
        }

        $roles = array();
        foreach($menu->getRoles() as $role) {
            array_push($roles, $role->getName());
        }

        if (false === $this->authorizationChecker->isGranted($roles)) {
            throw new AccessDeniedException();
        }
    }
}
