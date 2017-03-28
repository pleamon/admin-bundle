<?php

namespace P\AdminBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use P\AdminBundle\Event\AMQPEvent;

class AMQPSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'p.amqp.subscribe.example' => array(
                array('onAMQPPre', 1),
                array('onAMQP', 0),
                array('onAMQPPost', -1),
            )
        );
    }

    public function onAMQPPre(AMQPEvent $event)
    {
        $envelope = $event->getEnvelope();
        echo "subscribe amqp pre\t";
        echo sprintf("exchange name: %s\n", $envelope->getExchangeName());
    }

    public function onAMQP(AMQPEvent $event)
    {
        $queue = $event->getQueue();
        echo "subscribe amqp    \t";
        echo sprintf("queue name: %s\n", $queue->getName());
    }

    public function onAMQPPost(AMQPEvent $event)
    {
        $envelope = $event->getEnvelope();
        echo "subscribe amqp post\t";
        echo sprintf("message: %s\n", $envelope->getBody());
    }
}

