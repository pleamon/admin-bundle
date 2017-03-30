<?php

/*
 * This file is part of the PAdminBundle package.
 *
 * (c) P Admin <http://padmin.pleamon.com/>
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
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class AmqpChannelListCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:channel:list')
            ->setDescription('amqp list channels')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');

        $channels = $amqp->listChannel();

        $table = new Table($output);

        $table->setHeaders(array('vhost', 'user', 'name', 'state'));
        foreach($channels as $queue) {
            $table->addRow(array(
                $queue->vhost,
                $queue->user,
                $queue->name,
                $queue->state,
            ));
        }
        $table->render();
    }
}
