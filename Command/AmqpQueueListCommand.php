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
use Symfony\Component\Console\Helper\Table;

/**
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class AmqpQueueListCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:queue:list')
            ->setDescription('amqp list queues')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');

        $queues = $amqp->listQueue();

        $table = new Table($output);

        $table->setHeaders(array('vhost', 'name', 'durable', 'ready', 'total'));
        foreach($queues as $queue) {
            $table->addRow(array(
                $queue->vhost,
                $queue->name,
                $queue->durable ? 'true' : 'false',
                $queue->messages_ready_ram,
                $queue->messages_ram,
            ));
        }
        $table->render();
    }
}
