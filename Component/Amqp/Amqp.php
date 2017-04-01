<?php

namespace P\AdminBundle\Component\Amqp;

use Symfony\Component\DependencyInjection\ContainerInterface;

use P\AdminBundle\Event\AMQPEvent;

use P\AdminBundle\Component\Amqp\Connection;
use P\AdminBundle\Component\Amqp\Exchange;
use P\AdminBundle\Component\Amqp\Channel;
use P\AdminBundle\Component\Amqp\Queue;

class Amqp
{
    private $container;
    private $curl;
    private $eventDispatcher;

    private $credentials;
    private $conn;
    private $channel;
    private $type;
    private $flags;

    public function __construct(ContainerInterface $container, $curl, $event_dispatcher)
    {
        $this->container = $container;
        $this->curl = $curl;
        $this->eventDispatcher = $event_dispatcher;
        $this->credentials = $container->getParameter('p.amqp.credentials');
    }

    public function listChannel()
    {
        $result = $this->curl->get(sprintf("http://%s:%s/api/channels", $this->credentials['host'], $this->credentials['api_port']), array(
            CURLOPT_USERPWD => 'guest:guest',
        ));
        return json_decode($result);
    }

    public function getChannel()
    {
        if(empty($credentials)) {
            $credentials = $this->credentials;
        }
        if(empty($credentials) || empty($credentials['host'])) {
            throw new \Exception('please set amqp host');
        }

        $conn = new \AMQPConnection($credentials);
        $conn->connect();

        $channel = new \AMQPChannel($conn);
    }

    public function listExchange()
    {
        $result = $this->curl->get(sprintf("http://%s:%s/api/exchanges", $this->credentials['host'], $this->credentials['api_port']), array(
            CURLOPT_USERPWD => sprintf("%s:%s", $this->credentials['api_user'], $this->credentials['api_password']),
        ));
        return json_decode($result);
    }

    public function declareExchange($exchangeName, $type = AMQP_EX_TYPE_DIRECT, $flags = AMQP_DURABLE)
    {
        $channel = Channel::new($this->credentials);

        $exchange = new Exchange($channel, $exchangeName, $type, $flags);
        $exchange->declareExchange();

        $channel->close();
    }

    public function deleteExchange($exchangeName)
    {
        $channel = Channel::new($this->credentials);

        $exchange = new Exchange($channel);
        $exchange->delete($exchangeName);

        $channel->close();
    }

    public function listQueue()
    {
        $result = $this->curl->get(sprintf("http://%s:%s/api/queues", $this->credentials['host'], $this->credentials['api_port']), array(
            CURLOPT_USERPWD => sprintf("%s:%s", $this->credentials['api_user'], $this->credentials['api_password']),
        ));
        return json_decode($result);
    }

    public function declareQueue($queueName, $flags = AMQP_DURABLE)
    {
        $channel = Channel::new($this->credentials);

        $queue = new Queue($channel, $queueName, $flags);
        $queue->declareQueue();

        $channel->close();
    }

    public function deleteQueue($queueName)
    {
        $channel = Channel::new($this->credentials);

        $queue = new \AMQPQueue($channel);
        $queue->setName($queueName);
        $queue->delete();

        $channel->close();
    }

    public function bindings()
    {
        $result = $this->curl->get(sprintf("http://%s:%s/api/bindings", $this->credentials['host'], $this->credentials['api_port']), array(
            CURLOPT_USERPWD => sprintf("%s:%s", $this->credentials['api_user'], $this->credentials['api_password']),
        ));
        return json_decode($result);

    }

    public function bind($exchangeName, $queueName, $routing)
    {
        $channel = Channel::new($this->credentials);

        $queue = new Queue($channel, $queueName, AMQP_DURABLE);
        $queue->bind($exchangeName, $routing);

        $channel->close();
    }

    public function unbind($exchangeName, $queueName, $routing)
    {
        $channel = Channel::new($this->credentials);

        $queue = new Queue($channel, $queueName, AMQP_DURABLE);
        $queue->unbind($exchangeName, $routing);

        $channel->close();
    }


    public function publish($exchangeName, $queueName, $routing, $message)
    {
        $channel = Channel::new($this->credentials);

        $exchange = new Exchange($channel, $exchangeName, AMQP_EX_TYPE_DIRECT, AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new Queue($channel, $queueName, AMQP_DURABLE);
        $queue->declareQueue();

        $exchange->bind($exchangeName, $routing);

        $exchange->publish($message, $routing);

        $channel->close();
    }

    public function subscribe($exchangeName, $queueName, $routing, $callback = null, $autoack = true)
    {
        $channel = Channel::new($this->credentials);

        $exchange = new Exchange($channel, $exchangeName, AMQP_EX_TYPE_DIRECT, AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new Queue($channel, $queueName, AMQP_DURABLE);
        $queue->declareQueue();

        $queue->bind($exchangeName, $routing);

        if(empty($callback)) {
            $callback = array($this, 'processMessage');
        }
        $queue->consume($callback, $autoack ? AMQP_AUTOACK : AMQP_NOPARAM);

        $channel->close();
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    public function processMessage($envelope, $queue)
    {
        $amqpEvent = new AMQPEvent($envelope, $queue);
        $this->eventDispatcher->dispatch('p.amqp.event', $amqpEvent);
    }
}
