<?php

namespace P\AdminBundle\Component\Dashboard;

class Dashboard
{
    public const CACHE_REGION = 'p.dashboard';

    private $container;
    private $templating;
    private $widgets;
    private $user;
    private $widgetConfig;

    public function __construct($container, $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
        $this->widgets = array();
        $this->em = $container->get('doctrine.orm.default_entity_manager');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
        $this->widgetSettings = $this->em->getRepository('PAdminBundle:DashboardWidget')->createQueryBuilder('dw')
            ->join('dw.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $this->user)
            ->getQuery()
            //->useResultCache(true, 86400, self::CACHE_REGION)
            ->getResult()
            ;
    }

    public function register($service, $serviceName, $attributes)
    {
        $widget = null;
        $finds = array_filter($this->widgetSettings, function($_widget) use ($serviceName) {
            return $_widget->getWidgetId() == $serviceName;
        });
        $attribute = $attributes[0];
        $service->setTemplating($this->templating);

        $service->widgetId = $serviceName;
        $service->widgetName = $attribute['widget_name'];
        $service->view = $attribute['view'];
        if(count($finds) > 0) {
            $widget = array_shift($finds);
            $service->enabled = $widget->getEnabled();
            $service->sort = $widget->getSort();
        } else if(array_key_exists('enabled', $attribute)) {
            $service->enabled = $attribute['enabled'];
        } else {
            $service->enabled = true;
        }

        $service->setParameters($attribute);
        $this->widgets[$serviceName] = $service;
    }

    public function getWidgets()
    {
        return $this->widgets;
    }

    public function get($widgetId)
    {
        if(array_key_exists($widgetId, $this->widgets)) {
            return $this->widgets[$widgetId];
        }
        return null;
    }
}
