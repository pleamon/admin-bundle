<?php

namespace P\AdminBundle\Component\Amqp;

class Queue extends \AMQPQueue
{
    private $channel;
    public function __construct($channel, $queueName = null, $flags = null)
    {
        parent::__construct($channel);
        $this->channel = $channel;

        if($queueName)
        {
            $this->setName($queueName);
        }

        if($flags)
        {
            $this->setFlags($flags);
        }
    }

    public function getChannel()
    {
        return $this->channel;
    }
}
