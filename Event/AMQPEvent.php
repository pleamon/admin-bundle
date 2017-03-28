<?php

namespace P\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class AMQPEvent extends Event
{
    private $envelope;
    private $queue;

    public function __construct(\AMQPEnvelope $envelope, \AMQPQueue $queue) {
        $this->envelope = $envelope;
        $this->queue = $queue;
    }

    public function getEnvelope()
    {
        return $this->envelope;
    }

    public function getQueue()
    {
        return $this->queue;
    }
}
