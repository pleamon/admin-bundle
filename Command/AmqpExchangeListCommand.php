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
class AmqpExchangeListCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:exchange:list')
            ->setDescription('amqp list exchanges')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');

        $exchanges = $amqp->listExchange();

        $table = new Table($output);

        $table->setHeaders(array('vhost', 'name', 'type', 'durable', 'auto_delete', 'internal'));
        foreach($exchanges as $queue) {
            $table->addRow(array(
                $queue->vhost,
                $queue->name,
                $queue->type,
                $queue->durable,
                $queue->auto_delete,
            ));
        }
        $table->render();
    }
}
