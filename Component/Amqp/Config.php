<?php

namespace P\AdminBundle\Component\Amqp;

use P\AdminBundle\Entity\AdminConfig;

class Config
{
    public function getConfigs()
    {
        return array(
            new AdminConfig('amqp', 'host', null, null, '', 'amqp server host'),
            new AdminConfig('amqp', 'port', null, null, 5672, 'amqp server port'),
            new AdminConfig('amqp', 'login', null, null, '', 'amqp server user'),
            new AdminConfig('amqp', 'password', null, null, '', 'amqp server password'),

            new AdminConfig('amqp:api', 'host', null, null, '', 'amqp server rest host'),
            new AdminConfig('amqp:api', 'port', null, null, 15672, 'amqp server rest port'),
            new AdminConfig('amqp:api', 'user', null, null, 'guest', 'amqp server rest user'),
            new AdminConfig('amqp:api', 'password', null, null, 'guest', 'amqp server rest password'),
        );
    }
}
