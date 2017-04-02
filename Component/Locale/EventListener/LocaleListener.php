<?php

namespace P\AdminBundle\Component\Locale\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener
{
    private $container;
    private $languages;
    public function __construct($container, $languages)
    {
        $this->container = $container;
        $this->languages = $languages;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $logger = $this->container->get('logger');

        $locale = $request->query->get('_locale');
        if(array_key_exists($locale, $this->languages)) {
            $request->setLocale($locale);
            $session->set('_locale', $locale);
        } else {
            $locale = $session->get('_locale');
            if($locale) {
                $request->setLocale($locale);
            }
        }
    }
}
