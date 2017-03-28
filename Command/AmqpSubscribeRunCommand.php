<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use P\AdminBundle\DataFixtures\ORM\LoadIconData;

use P\AdminBundle\Event\AMQPEvent;

/**
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class AmqpSubscribeRunCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:subscribe:run')
            ->setDescription('amqp subscribe output to console')
            ->addArgument('exchange', InputArgument::REQUIRED, 'exchange')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue')
            ->addArgument('routing', InputArgument::REQUIRED, 'routing')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $amqp = $this->getContainer()->get('p.amqp');
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $exchange = $input->getArgument('exchange');;
        $queue = $input->getArgument('queue');;
        $routing = $input->getArgument('routing');;

        $amqp->subscribe($exchange, $queue, $routing, function($envelope, $queue) use ($output, $eventDispatcher) {
            $amqpEvent = new AMQPEvent($envelope, $queue);
            $eventDispatcher->dispatch('p.amqp.subscribe.example', $amqpEvent);
        });
    }
}
