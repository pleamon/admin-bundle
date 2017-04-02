<?php

namespace P\AdminBundle\Component\Core\Listener;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class FilterRouteRoleListener
{
    private $container;
    private $em;
    private $authorizationChecker;
    private $request;
    public function __construct($container, $em, $authorizationChecker, $requestStack) {
        $this->container = $container;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $token = $this->container->get('security.token_storage')->getToken();
        if($token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->checkRouteRole($event);
        }
    }

    public function checkRouteRole(GetResponseEvent $event) {
        $route = $this->request->get('_route');
        $menu = $this->em->getRepository('PAdminBundle:AdminMenu')->findOneByRoute($route);

        if(empty($menu)) {
            return;
        }

        if(empty(count($menu->getRoles()))) {
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
