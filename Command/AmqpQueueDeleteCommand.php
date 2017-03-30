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

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class AmqpQueueDeleteCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:queue:delete')
            ->setDescription('amqp delete queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');
        $queue = $input->getArgument('queue');;

        $amqp->deleteQueue($queue);
    }
}
