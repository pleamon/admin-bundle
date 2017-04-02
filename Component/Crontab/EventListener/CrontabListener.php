<?php

namespace P\AdminBundle\Component\Crontab\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

use Symfony\Component\Process\Process;

use P\AdminBundle\Component\Filesystem\Filesystem;

class CrontabListener
{
    private $container;
    private $crontabDir;
    private $process;
    private $logger;

    public function __construct($container)
    {
        $this->container = $container;
        $this->crontabDir = $container->getParameter('kernel.root_dir') . '/../var/crontab';

        $fs = new Filesystem();
        try {
            $fs->mkdir($this->crontabDir);
        } catch(Exception $e) {
            return;
        }

        $stream = new StreamHandler($this->crontabDir . '/crontab.log', Logger::INFO);
        $logger = new Logger('crontab');
        $logger->pushHandler($stream);

        $this->logger = $logger->withName('crontab');
    }
    public function onKernelRequest(GetResponseEvent $event)
    {
        
    }
}
