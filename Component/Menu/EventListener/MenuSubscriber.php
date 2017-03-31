<?php

namespace P\AdminBundle\Component\Menu\EventListener;

use Doctrine\Common\EventSubscriber;

use P\AdminBundle\Entity\AdminMenu;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MenuSubscriber implements EventSubscriber
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->clearCache($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->clearCache($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->clearCache($args);
    }


    public function clearCache(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof AdminMenu) {
            $this->container->get('p.admin.menu')->clearCache();
        }
    }
}
