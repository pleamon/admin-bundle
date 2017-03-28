<?php

namespace P\AdminBundle\Component\Amqp;

class Exchange extends \AMQPExchange
{
    private $channel;
    public function __construct(Channel $channel, $name = null, $type = null, $flags = null)
    {
        parent::__construct($channel);
        $this->channel = $channel;

        if($name) {
            $this->setName($name);
        }

        if($type) {
            $this->setType($type);
        }

        if($flags) {
            $this->setFlags($flags);
        }
    }

    public function getChannel()
    {
        return $this->channel;
    }
}
