<?php

namespace P\AdminBundle\Component\Amqp;

class Channel extends \AMQPChannel
{
    private $connection;
    public function __construct(Connection $connection)
    {
        parent::__construct($connection);
        $this->connection = $connection;
    }

    public static function new($credentials)
    {
        $connection = new Connection($credentials);
        $connection->connect();
        return new Channel($connection);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function close()
    {
        $this->connection->disconnect();
    }
}
